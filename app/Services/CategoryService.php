<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use DomainException;

class CategoryService
{
    protected $repositoryCategory;

    public function __construct(Category $repositoryCategory)
    {
        $this->repositoryCategory = $repositoryCategory;
    }

    public function getCategories()
    {
        $responseCategories = $this->repositoryCategory->getAll();

        return $responseCategories;
    }

    public function create($categoryData)
    {
        $categoryData['id'] = Str::uuid();

        $this->repositoryCategory->createCategory($categoryData);
    }

    public function getCategoryById($idCategory)
    {
        $responseCategory = $this->repositoryCategory->findOne($idCategory);
        return $responseCategory;
    }

    public function update($dataCategory, $idCategory)
    {

        $existCategory = $this->repositoryCategory->findOne($idCategory);

        if ($existCategory) {
            $this->repositoryCategory->updateCategory($dataCategory, $idCategory);
        }
    }

    public function delete($idCategory)
    {
        $existsCategory = $this->repositoryCategory->findOne($idCategory);

        if(!$existsCategory){
            throw new DomainException('Categoria não existe!');
        }

        $responseCategoryRow = $this->repositoryCategory->deleteCategory($idCategory);

        return  $responseCategoryRow;
    }
}
