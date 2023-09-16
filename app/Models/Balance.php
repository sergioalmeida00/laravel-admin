<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Balance extends Model
{
    protected $table = 'transactions';

    public function create($dataTransaction)
    {
        $responseTransactionRow = DB::table($this->table)
            ->insert([
                'id' => $dataTransaction['id'],
                'user_id' => $dataTransaction['user_id'],
                'name' => $dataTransaction['name'],
                'amount' => $dataTransaction['amount'],
                'type' => $dataTransaction['type'],
                'category_id' => $dataTransaction['category_id'],
            ]);

        return $responseTransactionRow;
    }

    public function getAllBalanceByUser($idUser)
    {
        $responseBalanceUser = DB::table($this->table)
            ->where('user_id', '=', $idUser)
            ->get();

        return $responseBalanceUser;
    }

    public function getTransactionsCategoryByUser($idUser)
    {
        $responseTransactionsCategoryByUser = DB::table($this->table)
            ->join('category', 'transactions.category_id', '=', 'category.id')
            ->select('transactions.*', 'category.name as category_name')
            ->where('transactions.user_id', '=', $idUser)
            ->get()
            ->groupBy('category_name');

        return $responseTransactionsCategoryByUser;
    }
}
