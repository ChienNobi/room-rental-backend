<?php

namespace App\Http\Controllers;

use App\Constants\Common;
use App\Http\Requests\Wallet\CreateWalletRequest;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(private readonly WalletService $service)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $r)
    {
        return $this->respond($this->service->paginate($r->all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWalletRequest $r)
    {
        $data = $r->only(['group_id', 'name', 'description', 'icon', 'report_exclude', 'total']);
        $data['icon'] = $data['icon'] ?? Common::WALLET_DEFAULT_ICON;
        $data['report_exclude'] = $data['report_exclude'] ?? false;
        $data['user_id'] = Auth::user()->id;
        $data['group_id'] = $data['group_id'] ?? 0;

        return $this->respond($this->service->store($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(CreateWalletRequest $request, $id)
    {
        $data = $request->only(['group_id', 'name', 'description', 'icon', 'report_exclude', 'total']);
        $data['group_id'] = $data['group_id'] ?? 0;
        return $this->respond($this->service->update(intval($id), $data));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }

    public function getOptions()
    {
        return $this->respond($this->service->findAll(['id', 'name', 'icon', 'report_exclude']));
    }
}
