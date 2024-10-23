<?php
namespace App\Services;


use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportService extends BaseService
{

    public function __construct(
        private readonly WalletService $walletService,
        private readonly TransactionService $transactionService
    )
    {
        parent::__construct();
    }

    public function model(): string
    {
        return Transaction::class;
    }

    public function getTransactionDashBoard(): array
    {
        $transactionCalculator = $this->transactionService->getTotalIncomeAndExpense();

        $transactionByCategory = $this->transactionService->getExpenseByCategory(date('m'));

        return [
            'wallet' => $this->walletService->findAll(),
            'overall' => $transactionCalculator,
            'transaction_by_category' => $transactionByCategory,
            'recent_transaction' => $this->transactionService->getRecentTransaction(),
            'compare_month' => [
                'last_month' => $this->transactionService->getTotalIncomeAndExpense(date('m', strtotime('-1 month'))),
                'this_month' => $this->transactionService->getTotalIncomeAndExpense(date('m')),
            ]
        ];
    }
}
