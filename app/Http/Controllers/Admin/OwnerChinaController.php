<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChangeMoneyOwnerHistory;
use App\Models\OwnerChina;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\UseMoneyHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerChinaController extends Controller
{
    public function index(Request $request)
    {
        $owner = OwnerChina::query();
        $owner = $owner->orderByDesc('id')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));

        $viewData = [
            'owner'  => $owner,
            'query'         =>  $request->query()
        ];
        return view('admin.owner_china.index', $viewData);
    }

    public function detail(Request $request, $id)
    {
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
            ChangeMoneyOwnerHistory::create([
                'cmh_owner_china_id' => $id,
                'cmh_money' => -(int)$request->money_paid,
                'cmh_money_after' => $oc_total_money_after,
                'cmh_yuan' => (int)$request->yuan_paid,
                'cmh_content' => $request->content_paid
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
