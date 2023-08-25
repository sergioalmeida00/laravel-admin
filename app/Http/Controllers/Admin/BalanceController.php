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
        $resultBalance = $this->historicService->listBalanceUser($userId);
        $categories = $this->historicService->listCategories();

        return view('admin.balance.index',[
            'amount' => $resultBalance,
            'categories' => $categories,

        ]);
    }

    public function store(Request $request){

        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'amount' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'errors' => $validatedData->errors()->all(),
                'fields' => $validatedData->errors()->keys(),
            ]);
        }
        return response()->json(['success' => true,'values' => $request->all()]);
    }
}
