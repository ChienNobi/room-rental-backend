<?php
namespace App\Services;


use App\Constants\Common;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService extends BaseService
{

    public function __construct(private readonly WalletService $walletService)
    {
        parent::__construct();
    }

    public function model(): string
    {
        return Transaction::class;
    }

    public function store(array $attributes): Transaction
    {
        DB::beginTransaction();
        try {
            $attributes['user_id'] = auth()->id();
            $category = DB::table('categories')->where('id', $attributes['category_id'])->first(['type']);

            $attributes['transaction_type'] = $this->getTransactionTypeByCategory($category->type);
            $result = $this->model->query()->create($attributes);

            $this->walletService->updateBalance($category, $attributes['wallet_id'], $attributes['amount']);

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTransactionTypeByCategory($categoryType): ?string
    {
        return match ($categoryType) {
            Common::CATEGORY_TYPE['DEBT'],
            Common::CATEGORY_TYPE['EXPENSE'] => Common::TRANSACTION_TYPE['EXPENSE'],
            Common::CATEGORY_TYPE['INCOME'],
            Common::CATEGORY_TYPE['SALARY'] => Common::TRANSACTION_TYPE['INCOME'],
            default => null
        };
    }

    public function updateTransaction($id, array $attributes): \Illuminate\Database\Eloquent\Builder|array|\Illuminate\Database\Eloquent\Collection|Model
    {
        DB::beginTransaction();
        try {
            $transaction = $this->model->query()->find($id);
            $category = DB::table('categories')->where('id', $attributes['category_id'])->first(['type']);

            $attributes['transaction_type'] = $this->getTransactionTypeByCategory($category->type);
            $this->walletService->updateBalance($transaction, $transaction->wallet_id, $transaction->amount, true);
            $transaction->fill($attributes)->update();
            $this->walletService->updateBalance($transaction, $attributes['wallet_id'], $attributes['amount']);

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id, bool $force = false): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->model->query()->find($id);
            $this->walletService->updateBalance($transaction->category_id, $transaction->wallet_id, $transaction->amount, true);
            return $transaction->delete();
        });
    }

    public function addFilter($query, $params): void
    {
       $query->when(!empty($params['start_date']), function ($builder) use ($params) {
          $builder->where('action_time', '>=', $params['start_date']);
       })->when(!empty($params['end_date']), function ($builder) use ($params) {
            $builder->where('action_time', '<=', $params['end_date']);
        })->when(!empty($params['category_id']), function ($builder) use ($params) {
            $builder->where('category_id', $params['category_id']);
        })->when(!empty($params['wallet_id']), function ($builder) use ($params) {
            $builder->where('wallet_id', $params['wallet_id']);
        });
    }

    public function getTotalIncomeAndExpense($month = null)
    {
        return $this->model->query()
            ->selectRaw("SUM(CASE WHEN transaction_type = '" . Common::TRANSACTION_TYPE['INCOME'] . "' THEN amount ELSE 0 END) as total_income")
            ->selectRaw("SUM(CASE WHEN transaction_type = '" . Common::TRANSACTION_TYPE['EXPENSE'] . "' THEN amount ELSE 0 END) as total_expense")
            ->when($month, function ($builder) use ($month) {
                $builder->whereMonth('action_time', $month);
            })
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    public function getListTransactionByMonth($month)
    {
        return $this->model->query()
            ->whereMonth('action_time', $month)
            ->with('category')
            ->where('user_id', auth()->id())
            ->get();
    }

    public function getExpenseByCategory($month = null): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->model->query()
            ->selectRaw('categories.name as category_name, SUM(amount) as total')
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->where('transaction_type', Common::TRANSACTION_TYPE['EXPENSE'])
            ->when($month, function ($builder) use ($month) {
                $builder->whereMonth('action_time', $month);
            })
            ->where('transactions.user_id', auth()->id())
            ->groupBy('category_id')
            ->get();
    }

    public function getRecentTransaction(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->query()
            ->with(['wallet:id,name', 'category:id,name'])
            ->orderBy('action_time', 'desc')
            ->limit(10)
            ->where('user_id', auth()->id())
            ->get();
    }
}
