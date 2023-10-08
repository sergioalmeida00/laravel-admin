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
                'user_id' => $dataTransaction['userId'],
                'name' => $dataTransaction['name'],
                'amount' => $dataTransaction['amount'],
                'type' => $dataTransaction['type'],
                'created' => $dataTransaction['date'],
                'category_id' => $dataTransaction['category_id'],
            ]);

        return $responseTransactionRow;
    }

    public function getAllBalanceByUser($idUser, $dateStart, $dateFim, $groupByCategory = false,$perPage = 6)
    {
        $query = DB::table('transactions')
            ->select('transactions.*', 'category.name as category_name')
            ->join('category', 'transactions.category_id', '=', 'category.id')
            ->where('transactions.user_id', '=', $idUser)
            ->where('transactions.created', '>=', $dateStart)
            ->where('transactions.created', '<=', $dateFim);

        if ($groupByCategory) {
            $result = $query->get();
            return $result->groupBy('category_name');
        } else {
            return $query->paginate($perPage);
        }
    }

    public function getTransactionsCategoryByUser($idUser, $dateStart, $dateFim)
    {
        $responseTransactionsCategoryByUser = DB::table($this->table)
            ->join('category', 'transactions.category_id', '=', 'category.id')
            ->select('transactions.*', 'category.name as category_name')
            ->where('transactions.user_id', '=', $idUser)
            ->where('transactions.created', '>=', $dateStart)
            ->where('transactions.created', '<=', $dateFim)
            ->get()
            ->groupBy('category_name');

        return $responseTransactionsCategoryByUser;
    }
}
