<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BalanceService
{

    public function listBalanceUser($userId)
    {
        $balanceUser = DB::table('transactions')
            ->where('user_id', '=', $userId)
            ->first();
        $amountBalance = $balanceUser ? $balanceUser->amount : 0;
        return $amountBalance;
    }

    public function listCategories(){
        $categories = DB::table('category')
            ->select('id','name')
            ->get();

        return $categories;
    }

    public function create($transactionData){
        DB::table('transaction')
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
