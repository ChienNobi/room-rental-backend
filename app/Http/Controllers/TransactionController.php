<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\GetTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function __construct(protected readonly TransactionService $service)
    {
    }

    public function index(GetTransactionRequest $request)
    {
        $result = $this->service->paginate(
            $request->only('category_id', 'wallet_id', 'start_date', 'end_date'),
            ['wallet:id,name', 'category:id,name']
        );
        return $this->respond($result);
    }

    public function store(CreateTransactionRequest $request)
    {
        $result = $this->service->store($request->only('group_id', 'amount', 'category_id', 'wallet_id', 'image', 'report_exclude', 'description', 'action_time'));
        return $this->respond($result);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->respond($this->service->show($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateTransactionRequest $request, $id)
    {
        $result = $this->service->updateTransaction($id, $request->only('group_id', 'amount', 'category_id', 'wallet_id', 'image', 'report_exclude', 'description', 'action_time'));
        return $this->respond($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);
        return $this->respond($result);
    }
}
