<?php


namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class BaseService
{
    const TIME_STAMP = ['created_at', 'updated_at', 'deleted_at'];

    /** @var $model Model */
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public abstract function model();

    /**
     * Set Eloquent Model to instantiate
     *
     * @return void
     */
    private function setModel(): void
    {
        $newModel = App::make($this->model());

        if (!$newModel instanceof Model)
            throw new \RuntimeException("Class {$newModel} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        $this->model = $newModel;
    }

    /**
     * @param bool $withDefaultFilter
     * @return Builder
     */
    private function query(bool $withDefaultFilter = false): Builder
    {
        return $this->buildBasicQuery(null, [], false, $withDefaultFilter);
    }

    /**
     * @param array|null $params
     * @param array $relations
     * @param bool $withTrashed
     * @param bool $withDefaultFilter
     * @return Builder
     */
    protected function buildBasicQuery(array $params = null, array $relations = [], bool $withTrashed = false, bool $withDefaultFilter = true): Builder
    {
        $query = $this->model->query();
        $params = $params ?: request()->toArray();

        if ($relations && count($relations)) {
            $query->with($relations);
        }
        if ($withTrashed && in_array(SoftDeletes::class, class_uses($this->model)) && method_exists($query, 'withTrashed')) {
            $query->withTrashed();
        }
        if (method_exists($this, 'addFilter')) {
            $this->addFilter($query, $params);
        }
        if ($withDefaultFilter) {
            $this->addDefaultFilter($query, $params);
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param $params
     * @return Builder
     */
    protected function addDefaultFilter(Builder $query, $params = null): Builder
    {
        $params = $params ?: request()->toArray();
        if (isset($params['filter']) && $params['filter']) {
            $filters = json_decode($params['filter'], true);
            foreach ($filters as $key => $filter) {
                $this->basicFilter($query, $key, $filter);
            }
        }
        if (isset($params['sort']) && $params['sort']) {
            $sort = explode('|', $params['sort']);
            if ($sort && count($sort) == 2) {
                $query->orderBy($sort[0], $sort[1]);
            }
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param $key
     * @param $filter
     * @return void
     */
    protected function basicFilter(Builder $query, $key, $filter): void
    {
        if (is_array($filter)) {
            if ($key == 'equal') {
                foreach ($filter as $index => $value) {
                    if ($this->checkParamFilter($value)) {
                        $query->where($index, $value);
                    }
                }
            } else if ($key == 'like') {
                foreach ($filter as $index => $value) {
                    if ($this->checkParamFilter($value)) {
                        $query->where($index, 'LIKE', '%' . $value . '%');
                    }
                }
            } else if ($key == 'range') {
                foreach ($filter as $index => $value) {
                    if ($this->checkParamFilter($value)) {
                        if (is_array($value) && count($value) == 2 && in_array($index, static::TIME_STAMP)) {
                            $query->whereBetween($index, $value);
                        }
                    }
                }
            } else if ($key == 'in') {
                foreach ($filter as $index => $value) {
                    if ($this->checkParamFilter($value)) {
                        if (is_array($value)) {
                            $query->whereIn($index, $value);
                        }
                    }
                }
            } else if ($key == 'relation') {
                foreach ($filter as $relation => $relationFilters) {
                    if (is_array($relationFilters) && count($relationFilters)) {
                        foreach ($relationFilters as $index => $value) {
                            if ($value && count($value)) {
                                $query->whereHas($relation, function ($builder) use ($index, $value) {
                                    $this->basicFilter($builder, $index, $value);
                                });
                            }
                        }
                    }
                }
            } else {
                if (count($filter)) {
                    $query->whereIn($key, $filter);
                }
            }
        } else {
            $query->where($key, 'LIKE', '%' . $filter . '%');
        }
    }

    /**
     * @param $value
     * @return bool
     */
    protected function checkParamFilter($value): bool
    {
        return $value != '' && $value != null;
    }

    /**
     * @param array $columns
     * @return Builder[]|Collection
     */
    public function findAll(array $columns = ['*'], $relations = []): Collection
    {
        return $this->query()->with($relations)->get(is_array($columns) ? $columns : func_get_args());
    }

    /**
     * Retrieve the specified resource.
     *
     * @param int $id
     * @param array $relations
     * @param array $appends
     * @param array $hidden
     * @param bool $withTrashed
     * @return Model
     */
    public function show(int $id, array $relations = [], array $appends = [], array $hidden = [], bool $withTrashed = false): Model
    {
        $query = $this->query();
        if ($withTrashed) {
            $query->withTrashed();
        }
        return $query->with($relations)->findOrFail($id)->setAppends($appends)->makeHidden($hidden);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return Model|bool
     * @throws Exception
     */
    public function store(array $attributes)
    {
        $parent = $this->query()->create($attributes);
        $relations = [];

        foreach (array_filter($attributes, [$this, 'isRelation']) as $key => $models) {
            if (method_exists($parent, $relation = Str::camel($key))) {
                $relations[] = $relation;
                $this->syncRelations($parent->$relation(), $models, false);
            }
        }
        if (count($relations)) {
            $parent->load($relations);
        }

        return $parent->push() ? $parent : false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int|Model $parent
     * @param array $attributes
     * @return Model|bool
     *
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function update(Model|int $parent, array $attributes): Model|bool|int
    {
        if (is_integer($parent)) {
            $parent = $this->query()->findOrFail($parent);
        }
        $parent->fill($attributes);
        $relations = [];

        foreach (array_filter($attributes, [$this, 'isRelation']) as $key => $models) {
            if (method_exists($parent, $relation = Str::camel($key))) {
                $relations[] = $relation;
                $this->syncRelations($parent->$relation(), $models);
            }
        }
        if (count($relations)) {
            $parent->load($relations);
        }

        return $parent->push() ? $parent : false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Model|int $item
     * @param bool $force
     * @return bool
     *
     */
    public function destroy($item, bool $force = false): bool
    {
        if (is_integer($item)) {
            $item = $this->query()->findOrFail($item);
        }
        return $item->{$force ? 'forceDelete' : 'delete'}();
    }

    /**
     * @param $id
     * @return bool
     */
    public function restore($id): bool
    {
        return $this->query()->withTrashed()->findOrFail($id)->restore();
    }

    /**
     * @param array $attrs
     * @return Builder|Model|null|object
     */
    public function findBy(array $attrs)
    {
        return $this->query()->where($attrs)->first();
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Builder|Model
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->query()->firstOrCreate($attributes, $values);
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Builder|Model
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->query()->updateOrCreate($attributes, $values);
    }

    /**
     * @param array|null $params
     * @param array $relations
     * @param bool $withTrashed
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function paginate(array $params = null, array $relations = [], bool $withTrashed = false): LengthAwarePaginator
    {
        $params = $params ?: request()->toArray();
        $limit = Arr::pull($params, 'limit', 20);
        $page = Arr::pull($params, 'page');

        $query = $this->buildBasicQuery($params, $relations, $withTrashed);
        return $query->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * @param $value
     * @return bool
     */
    private function isRelation($value): bool
    {
        return is_array($value) || $value instanceof Model;
    }

    /**
     * @param Relation $relation
     * @param array | Model $conditions
     * @param bool $detaching
     * @return void
     * @throws Exception
     */
    private function syncRelations(Relation $relation, $conditions, bool $detaching = true): void
    {
        $conditions = is_array($conditions) ? $conditions : [$conditions];
        $relatedModels = [];
        foreach ($conditions as $condition) {
            if ($condition instanceof Model) {
                $relatedModels[] = $condition;
            } else if (is_array($condition)) {
                $relatedModels[] = $relation->firstOrCreate($condition);
            }
        }

        if ($relation instanceof BelongsToMany && method_exists($relation, 'sync')) {
            $relation->sync($this->parseIds($relatedModels), $detaching);
        } else if ($relation instanceof HasMany | $relation instanceof HasOne) {
            $relation->saveMany($relatedModels);
        } else {
            throw new Exception('Relation not supported');
        }
    }

    /**
     * @param array $models
     * @return array
     */
    private function parseIds(array $models): array
    {
        $ids = [];
        foreach ($models as $model) {
            $ids[] = $model instanceof Model ? $model->getKey() : $model;
        }

        return $ids;
    }
}
