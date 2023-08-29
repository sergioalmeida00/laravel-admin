<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BalanceService
{

    public function getBalanceUser($userId)
    {
        $transactions = DB::table('transactions')
            ->where('user_id', '=', $userId)
            ->get();

        $incomeTotal = 0;
        $expenseTotal = 0;

        foreach($transactions as $transaction){
            if($transaction->type === 'INCOME'){
                $incomeTotal += $transaction->amount;
            }elseif($transaction->type === 'EXPENSE'){
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

    public function listCategories(){
        $categories = DB::table('category')
            ->select('id','name')
            ->get();

        return $categories;
    }

    public function create($transactionData){
        DB::table('transactions')
            ->insert([
                'id' => Str::uuid(),
                'user_id' => $transactionData['user_id'],
                'name' => $transactionData['name'],
                'amount' => $transactionData['amount'],
                'type' => $transactionData['type'],
                'category_id' => $transactionData['category_id'],
            ]);
    }

    public function getTransactionsCategoryByUser($userId){
        $transactionsByCategory = DB::table('transactions')
            ->join('category', 'transactions.category_id', '=', 'category.id')
            ->select('transactions.*', 'category.name as category_name')
            ->where('transactions.user_id','=',$userId)
            ->get()
            ->groupBy('category_name');

        $expenseTotalsByCategory = [];
        $incomeTotal = 0;

        foreach ($transactionsByCategory as $categoryName => $transactions) {

            foreach ($transactions as $transaction) {
                if($transaction->type === 'INCOME'){
                    $incomeTotal += $transaction->amount;
                }elseif ($transaction->type === 'EXPENSE') {
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
