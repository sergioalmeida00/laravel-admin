<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryService{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategories(){
        $categories = DB::table('category')
            ->get();

        return $categories;
    }

    public function create($categoryData){
        $categoryData['id'] = Str::uuid();

        $this->category->createCategory($categoryData);
    }

    public function getCategoryById($idCategory){
        $category = DB::table('category')
            ->where('id', '=', $idCategory)
            ->first();

        return $category;
    }

    public function update($dataCategory, $idCategory){

        $existCategory = $this->category->findOne($idCategory);

        if($existCategory){
            $this->category->updateCategory($dataCategory,$idCategory );
        }

    }

    public function delete($idCategory){
        return DB::table('category')
            ->where('id', '=', $idCategory)
            ->delete();
    }
}
