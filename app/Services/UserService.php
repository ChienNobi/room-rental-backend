<?php
namespace App\Services;


use App\Constants\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService extends BaseService
{

    public function model(): string
    {
        return User::class;
    }

    public function addFilter($query, $params): void
    {
        $query->when(isset($params['name']), function ($query) use ($params) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        })->when(isset($params['is_active']), function ($query) use ($params) {
            $query->where('is_active', $params['is_active']);
        });
    }

    public function updateUserBalance($userId, $amount, $paymentType = Payment::PAYMENT_TYPE['DEPOSIT']): bool
    {
        $user = $this->model->query()->findOrFail($userId);

        switch ($paymentType) {
            case Payment::PAYMENT_TYPE['DEPOSIT']:
                $user->balance += $amount;
                break;
            case Payment::PAYMENT_TYPE['CHARGE']:
            case Payment::PAYMENT_TYPE['REFUND']:
                if($user->balance < $amount) {
                    return false;
                }
                $user->balance -= $amount;
                break;
        }

        $user->save();
        return true;
    }
}
