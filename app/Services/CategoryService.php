<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryService{
    public function getCategories(){
        $categories = DB::table('category')
            ->get();

        return $categories;
    }

    public function create($categoryData){
        DB::table('category')
            ->insert([
                'id' => Str::uuid(),
                'name' => strtoupper($categoryData['name'])
            ]);
    }
}
