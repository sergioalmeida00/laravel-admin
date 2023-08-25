<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
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
}
