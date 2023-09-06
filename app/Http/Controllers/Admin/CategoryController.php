<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    private $categoryService;
    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getCategories();

        return view('admin.category.index',[
            'categories' => $categories
        ]);
    }


    public function store(Request $request)
    {

        $categoryData = $this->validateCategoryData($request);

        if($categoryData->fails()){
            return response()->json([
                'errors' => $categoryData->errors()->all(),
                'fields' => $categoryData->errors()->keys(),
            ]);
        }

        $this->categoryService->create($request->all());

        $request->session()->flash('success', 'Categoria adicionada com sucesso!');

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $categoryById = $this->categoryService->getCategoryById($id);
        return response()->json($categoryById);
    }

    public function update(Request $request, $id)
    {
        $categoryData = $this->validateCategoryData($request);

        if($categoryData->fails()){
            return response()->json([
                'errors' => $categoryData->errors()->all(),
                'fields' => $categoryData->errors()->keys(),
            ]);
        }

        $this->categoryService->update($request->all(), $id);

        return response()->json(['success' => true, 'id' => $id]);
    }

    public function destroy($id)
    {
        //
    }

    public function validateCategoryData(Request $request){
        return Validator::make($request->all(), [
            'name' => 'required'
        ]);
    }
}
