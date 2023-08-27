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

        $balance = $expenseTotal - $incomeTotal;

        // $amountBalance = $balanceUser ? $balanceUser : 0;
        return [
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'balance' => $balance,
            'transactions' => $transactions
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
}
