<?php
namespace App\Services;


use App\Models\Task;

class TaskService extends BaseService
{

    public function model(): string
    {
        return Task::class;
    }
}
