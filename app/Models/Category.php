<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id'; // Apenas se não estiver definido como 'id'
    public $incrementing = false; // Define que a chave primária não é auto incremento
    protected $keyType = 'string'; // Define o tipo da chave primária como string (UUID)

    protected $fillable = [
        'name'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid(); // Gere um UUID para a coluna 'id'
        });
    }

    public function createCategory($categoryData)
    {
        DB::table($this->table)
            ->insert([
                'id' => $categoryData['id'],
                'name' => $categoryData['name']
            ]);
    }

    public function findOne($idCategory)
    {
        $responseCategory = DB::table($this->table)
            ->where('id', '=', $idCategory)
            ->first();

        return $responseCategory;
    }

    public function getAll()
    {
        $responseCategories = DB::table($this->table)
            ->get();

        return  $responseCategories;
    }

    public function updateCategory($dataCategory, $idCategory)
    {
        return DB::table($this->table)
            ->where('id', '=', $idCategory)
            ->update([
                'name' => $dataCategory['name']
            ]);
    }

    public function deleteCategory($idCategory)
    {
        $responseCategoryRow = DB::table($this->table)
            ->where('id', '=', $idCategory)
            ->delete();

        return $responseCategoryRow;
    }
}
