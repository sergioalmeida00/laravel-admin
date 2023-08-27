<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BalanceService;
use Illuminate\Support\Facades\Validator;

class BalanceController extends Controller
{
    private $historicService;

    public function __construct(BalanceService $historicService) {
        $this->historicService = $historicService;
    }

    public function index(){
        $userId = auth()->user()->id;
        $resultBalance = $this->historicService->getBalanceUser($userId);
        $categories = $this->historicService->listCategories();

        return view('admin.balance.index',[
            'incomeTotal' => $resultBalance['incomeTotal'],
            'expenseTotal' => $resultBalance['expenseTotal'],
            'balance' => $resultBalance['balance'],
            'transactions' => $resultBalance['transactions'],
            'categories' => $categories,
        ]);
    }

    public function store(Request $request){
        $userId = auth()->user()->id;
        $validatedData = $this->validateData($request);

        if ($validatedData->fails()) {
            return response()->json([
                'errors' => $validatedData->errors()->all(),
                'fields' => $validatedData->errors()->keys(),
            ]);
        }

        $allData = $request->all();
        $allData['user_id'] = $userId;

        $this->historicService->create($allData);


        return response()->json(['success' => true,'values' => $allData]);
    }

    public function validateData(Request $request){
        return Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'amount' => 'required',
        ]);
    }
}
