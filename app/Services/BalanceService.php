<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BalanceService
{
    protected $repositoryBalance;
    protected $repositoryCategory;

    public function __construct(
        Balance $repositoryBalance,
        Category $repositoryCategory
    ) {
        $this->repositoryBalance = $repositoryBalance;
        $this->repositoryCategory = $repositoryCategory;
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

    public function getBalanceUser($userId, $dateStart = '', $dateFim = '')
    {
        if (empty($dateStart) && empty($dateFim)) {
            $startMonth = Carbon::now()->startOfMonth();
            $endMonth = Carbon::now()->endOfMonth();

            $dateStart = $startMonth->format('Y-m-d');
            $dateFim = $endMonth->format('Y-m-d');
        }

        $transactionsByCategory = $this->repositoryBalance->getAllBalanceByUser($userId, $dateStart, $dateFim, true);

        $expenseByCategoryAndBalance = $this->calculateExpenseTotalsByCategory($transactionsByCategory);
        $percentagesByCategory = $this->calculateExpensePercentagesByCategory(
            $expenseByCategoryAndBalance['expenseTotalsByCategory'],
            $expenseByCategoryAndBalance['incomeTotal']
        );

        $mergeArray = $this->combineData($percentagesByCategory,$expenseByCategoryAndBalance['expenseTotalsByCategory'] );

        return [
            'incomeTotal' => $expenseByCategoryAndBalance['incomeTotal'],
            'expenseTotal' => $expenseByCategoryAndBalance['expenseTotal'],
            'balance' => $expenseByCategoryAndBalance['balance'],
            'transactions' => $expenseByCategoryAndBalance['transactions'],
            'amountByCategory' => $mergeArray
        ];
    }


    private function calculateExpenseTotalsByCategory($transactionsByCategory)
    {
        $expenseTotalsByCategory = [];
        $incomeTotal = 0;
        $expenseTotal = 0;
        $transactionsNew = [];

        foreach ($transactionsByCategory as $categoryName => $transactions) {

            foreach ($transactions as $transaction) {
                $transactionsNew[] = $transaction;
                if ($transaction->type === 'INCOME') {
                    $incomeTotal += $transaction->amount;
                } elseif ($transaction->type === 'EXPENSE') {
                    if (!isset($expenseTotalsByCategory[$categoryName])) {
                        $expenseTotalsByCategory[$categoryName] = 0;
                    }
                    $expenseTotalsByCategory[$categoryName] += $transaction->amount;
                    $expenseTotal += $transaction->amount;
                }
            }
        }
        $balance = $incomeTotal - $expenseTotal;

        return [
            'expenseTotalsByCategory' => $expenseTotalsByCategory,
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'balance' => $balance,
            'transactions' => $transactionsNew
        ];
    }

    private function calculateExpensePercentagesByCategory($expenseTotalsByCategory, $incomeTotal)
    {

        $expensePercentagesByCategory = [];

        foreach ($expenseTotalsByCategory as $categoryName => $expenseTotal) {
            if ($incomeTotal > 0) {
                $expensePercentage = ($expenseTotal / $incomeTotal) * 100;
            } else {
                $expensePercentage = 0;
            }
            $expensePercentagesByCategory[$categoryName] = $expensePercentage;
        }

        return $expensePercentagesByCategory;
    }

    private function combineData($expensePercentagesByCategory, $expenseTotalsByCategory)
    {
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
