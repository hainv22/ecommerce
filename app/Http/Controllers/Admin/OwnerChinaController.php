<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChangeMoneyOwnerHistory;
use App\Models\Log;
use App\Models\OwnerChina;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\UseMoneyHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerChinaController extends Controller
{
    public function index(Request $request)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('List Owner China', $request));
        $owner = OwnerChina::query();
        $owner = $owner->orderByDesc('id')->get();
        $total = $owner->sum('oc_total_money');

        $viewData = [
            'owner'  => $owner,
            'total' => $total,
            'query'         =>  $request->query()
        ];
        return view('admin.owner_china.index', $viewData);
    }

    public function detail(Request $request, $id)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('detail Owner China', $request));
        $owner = OwnerChina::findOrFail($id);
        $use = ChangeMoneyOwnerHistory::where([
            'cmh_owner_china_id' => $id,
            'cmh_role' => ChangeMoneyOwnerHistory::CHUNG
        ])->get();
        $viewData = [
            'owner' => $owner,
            'use' => $use
        ];
        return view('admin.owner_china.detail', $viewData);
    }

    public function paidOwner(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $owner = OwnerChina::findOrfail($id);
            $oc_total_money_old = $owner->oc_total_money;
            $oc_total_money_after = $oc_total_money_old-(int)$request->money_paid;
            if ((int)$request->money_paid > $oc_total_money_old) {
                return response([
                    'code' => 400,
                    'message' => 'số trả nhiều hơn số nợ!',
                    'data' => ''
                ]);
            }

            $owner->update([
                'oc_total_money' => $oc_total_money_after
            ]);
            $log = $this->writeLogInDatabase($this->makeDataLogByRequest('Tra Tien Trung Quoc', $request) + array('owner_id' => $owner->id));
            $change_money_owner_history = ChangeMoneyOwnerHistory::create([
                'cmh_owner_china_id' => $id,
                'cmh_money' => -(int)$request->money_paid,
                'cmh_money_after' => $oc_total_money_after,
                'cmh_yuan' => (int)$request->yuan_paid,
                'cmh_content' => $request->content_paid.", log_id_when_paidOwner: $log->id",
                'cmh_money_before' => $oc_total_money_old
            ]);
            UseMoneyHistory::create([
                'umh_money' => (int)$request->money_paid * (int)$request->yuan_paid,
                'umh_content' => "Trả Trung Quốc với change_history: {$change_money_owner_history->id} ($request->content_paid, 
                số tiền trả: $request->money_paid, giá tiền trung: $request->yuan_paid), log_id_when_paidOwner: $log->id",
                'umh_use_date' => date('Y-m-d'),
                'umh_change_money_owner_id' => $id,
                'umh_status' => UseMoneyHistory::TRA_TRUNG_QUOC,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'code' => 400,
                'message' => $e->getMessage(),
                'data' => ''
            ]);
        }
        $request->session()->flash('toastr', [
            'type'      => "success",
            'message'   => "Update số tiền thành công"
        ]);
        return response([
            'code' => 200,
            'message' => 'Update số tiền thành công',
            'data' => ''
        ]);
    }
}
