<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BalanceService;
use Illuminate\Support\Facades\Validator;

class BalanceController extends Controller
{
    private $historicService;

    public function __construct(BalanceService $historicService)
    {
        $this->historicService = $historicService;
    }

    public function index()
    {
        return $this->renderBalanceView();
    }

    public function filter(Request $request)
    {
        $dateStart = $request->input('date_inicio');
        $dateFim = $request->input('date_fim');

        return $this->renderBalanceView($dateStart, $dateFim);
    }

    public function store(Request $request)
    {
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


        return response()->json(['success' => true, 'values' => $allData]);
    }

    private function renderBalanceView($dateStart = null, $dateFim = null)
    {
        $userId = auth()->user()->id;
        $categories = $this->historicService->listCategories();

        $resultBalance = $this->historicService->getBalanceUser($userId, $dateStart, $dateFim);

        // dd($resultBalance);
        return view('admin.balance.index', [
            'incomeTotal' => $resultBalance['incomeTotal'],
            'expenseTotal' => $resultBalance['expenseTotal'],
            'balance' => $resultBalance['balance'],
            'transactions' => $resultBalance['transactions'],
            'amountByCategory' => $resultBalance['amountByCategory'],
            'categories' => $categories,
        ]);
    }

    public function validateData(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'amount' => 'required',
        ]);
    }
}
