<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\Category;
use Illuminate\Support\Str;

class BalanceService
{
    protected $repositoryBalance;
    protected $repositoryCategory;

    public function __construct(
        Balance $repositoryBalance,
        Category $repositoryCategory
        )
    {
        $this->repositoryBalance = $repositoryBalance;
        $this->repositoryCategory = $repositoryCategory;
    }

    public function getBalanceUser($userId)
    {
        $transactions = $this->repositoryBalance->getAllBalanceByUser($userId);
        $incomeTotal = 0;
        $expenseTotal = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->type === 'INCOME') {
                $incomeTotal += $transaction->amount;
            } elseif ($transaction->type === 'EXPENSE') {
                $expenseTotal += $transaction->amount;
            }
        }
        $amountByCategory = $this->getTransactionsCategoryByUser($userId);

        $balance = $incomeTotal - $expenseTotal;

        // $amountBalance = $balanceUser ? $balanceUser : 0;
        return [
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'balance' => $balance,
            'transactions' => $transactions,
            'amountByCategory' => $amountByCategory
        ];
    }

    public function listCategories()
    {
        $responseCategories = $this->repositoryCategory->getAll();

        return $responseCategories;
    }

    public function create($dataTransaction)
    {
        $dataTransaction['id'] = Str::uuid();

        $this->repositoryBalance->create($dataTransaction);
    }

    public function getTransactionsCategoryByUser($userId)
    {
        $transactionsByCategory = $this->repositoryBalance->getTransactionsCategoryByUser($userId);

        $expenseTotalsByCategory = [];
        $incomeTotal = 0;

        foreach ($transactionsByCategory as $categoryName => $transactions) {

            foreach ($transactions as $transaction) {
                if ($transaction->type === 'INCOME') {
                    $incomeTotal += $transaction->amount;
                } elseif ($transaction->type === 'EXPENSE') {
                    if (!isset($expenseTotalsByCategory[$categoryName])) {
                        $expenseTotalsByCategory[$categoryName] = 0;
                    }
                    $expenseTotalsByCategory[$categoryName] += $transaction->amount;
                }
            }
        }

        $expensePercentagesByCategory = [];
        foreach ($expenseTotalsByCategory as $categoryName => $expenseTotal) {
            $expensePercentage = ($expenseTotal / $incomeTotal) * 100;
            $expensePercentagesByCategory[$categoryName] = $expensePercentage;
        }

        $mergeArray = [];

        foreach ($expensePercentagesByCategory as $categoryName => $value) {
            $mergeArray[] = [
                'categoryName' => $categoryName,
                'percentage' => $value,
                'total' => $expenseTotalsByCategory[$categoryName] ?? 0,
            ];
        }

        return $mergeArray;
    }
}
