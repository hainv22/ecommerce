<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminOwnerTransactionRequest;
use App\Models\Bao;
use App\Models\ChangeMoneyOwnerHistory;
use App\Models\Log;
use App\Models\Order;
use App\Models\OwnerChina;
use App\Models\OwnerTransaction;
use App\Models\OwnerTransactionDetail;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerChinaTransactionController extends Controller
{
    public function index(Request $request)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'List Transaction Owner China',
            'content' => null,
            'data' =>  json_encode($request->all())
        ]);
        $users = OwnerChina::all();
        $owner_transactions = OwnerTransaction::query()->with(['detail', 'owner']);
        if (Auth::user()->role != User::ADMIN) {
            $owner_transactions = $owner_transactions->where('ot_transaction_role', Transaction::CHUNG);
        }
        if ($user_owner_id = $request->user_owner_id) {
            $owner_transactions->whereHas('owner', function ($query) use ($user_owner_id) {
                $query->where('id', $user_owner_id);
            });
        }
        $owner_transactions = $owner_transactions->orderByDesc('id')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
        $viewData = [
            'transactions'  =>  $owner_transactions,
            'users' => $users,
            'query'         =>  $request->query()
        ];
        return view('admin.owner_transaction.index', $viewData);
    }

    public function create(Request $request)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'View Create Transaction Owner China',
            'content' => null,
            'data' =>  json_encode($request->all())
        ]);
        $users = OwnerChina::all();
        $viewData = [
            'users'  =>  $users
        ];
        return view('admin.owner_transaction.create', $viewData);
    }

    public function products(Request $request)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Get Product When Create Transaction Owner China',
            'content' => null,
            'data' =>  json_encode($request->all())
        ]);
        try {
            $products = Product::with('category:id,c_name');
            if ($search = strtolower($this->stripVN($request->search))) {
                $products->where('pro_name', 'like', '%' . $search . '%');
            }
            if ($name = strtolower($this->stripVN($request->name))) {
                $products->where('pro_name', 'like', '%' . $name . '%')->orWhere('pro_price', 'like', '%' . $name . '%');
            }
            $products->orderByDesc('id');
            $products = $products->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
            $viewData = [
                'products'      => $products,
                'query'         => $request->query()
            ];
            if ($request->ajax()) {
                $html = view('admin.owner_transaction.data_product', $viewData)->render();
                return response([
                    'data' => $html ?? null
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'code' => 400,
                'data' => $e->getMessage()
            ]);
        }
    }

    public function store(AdminOwnerTransactionRequest $request)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Store Transaction Owner China',
            'content' => null,
            'data' =>  json_encode($request->all())
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $total_money = 0;
            $total_products = 0;
            foreach ($data['txt_id_product'] as $key => $idProduct) {
                $product = Product::find($idProduct);
                $total_money += ($product->pro_money_yuan * $data['txt_quantity_product'][$key]);
                $total_products += $data['txt_quantity_product'][$key];
            }

            $transaction = OwnerTransaction::create([
                'ot_owner_china_id' => $data['ot_user_id'],
                'ot_total_money' => $total_money,
                'ot_total_products' => $total_products,
                'ot_note' => $data['ot_note'],
                'ot_status' => 1,
                'ot_order_date' => $data['ot_order_date'],
                'ot_transaction_role' => Transaction::CHUNG
            ]);

            foreach ($data['txt_id_product'] as $key => $idProduct) {
                OwnerTransactionDetail::create([
                    'otd_owner_transaction_id' => $transaction->id,
                    'otd_product_id' => $idProduct,
                    'otd_qty' => $data['txt_quantity_product'][$key],
                    'otd_price' => Product::find($idProduct)->pro_money_yuan,
                    'otd_note' => $data['od_note'][$key]
                ]);
            }


            if ($transaction) {
                $owner = OwnerChina::find($data['ot_user_id']);
                ChangeMoneyOwnerHistory::create([
                    'cmh_owner_china_id' => $data['ot_user_id'],
                    'cmh_money' => $total_money,
                    'cmh_money_after' => $owner->oc_total_money + $total_money,
                    'cmh_yuan' => 0,
                    'cmh_content' => "Sinh ra khi tao đơn id = {$transaction->id}",
                    'cmh_owner_transaction_id' => $transaction->id,
                    'cmh_money_before' => $owner->oc_total_money
                ]);
                $owner->update([
                    'oc_total_money' => $owner->oc_total_money + $total_money
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('toastr', [
                'type'      => 'error',
                'message'   => $e->getMessage()
            ]);
            return redirect()->back();
        }
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Tạo đơn hàng thành công !'
        ]);
        return redirect()->route('admin.owner-china-transactions.index');
    }

    public function getOwnerTransactionDetail(Request $request, $id)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'get Detail Transaction Owner China',
            'content' => null,
            'data' =>  json_encode($request->all())
        ]);
        $transaction = OwnerTransaction::query()->with(['detail', 'owner', 'changeMoneyOwnerHistories'])->findOrFail($id);
        $order = OwnerTransactionDetail::with('product:id,pro_name,pro_avatar')
            ->where('otd_owner_transaction_id', $id)
            ->get();
        return view('admin.owner_transaction.view', compact('transaction', 'order'));
    }

    public function updateSuccessDate(Request $request, $id)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Update da ve kho - nguoc lai',
            'content' => null,
            'data' =>  json_encode($request->all())
        ]);
        DB::beginTransaction();
        try {
            $detail = OwnerTransactionDetail::findOrfail($id);
            $detail->update([
                'otd_status' => $request->value == 'true' ? 2 : 1
            ]);
            $product = Product::findOrfail($id)->update([

            ]);
            if($request->value == 'true') {
                DB::table('products')
                    ->where('id', $detail->otd_product_id)
                    ->increment('pro_number', $detail->otd_qty);
            } else {
                DB::table('products')
                    ->where('id', $detail->otd_product_id)
                    ->decrement('pro_number', $detail->otd_qty);
            }
            $success = view('admin.owner_transaction.data.status', compact('detail'))->render();
            DB::commit();
            return response([
                'data' => 'success',
                'success' => $success,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'data' => 'error',
                'success' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Update Transaction Owner China',
            'content' => null,
            'data' =>  json_encode($request->all())
        ]);
        DB::beginTransaction();
        try {
            $total_money = 0;
            $total_products = 0;
            $data = $request->all();
            $transaction = OwnerTransaction::findOrFail($id);

            if(array_key_exists('txt_id_product', $data)) {
                $check = true;
                foreach ($transaction->detail as $key => $value) {
                    if($value->otd_status == 2) {
                        $check = false;
                        break;
                    }
                }
                if (!$check) {
                    $request->session()->flash('toastr', [
                        'type'      => 'error',
                        'message'   => 'bo tich hang da ve se tru so luong'
                    ]);
                    return redirect()->back();
                }
                $order_old = OwnerTransactionDetail::where('otd_owner_transaction_id', $id)->pluck('id')->toArray();
                $array_diff = array_diff($order_old, $data['product_ids']);
                $orders_delete = OwnerTransactionDetail::where('otd_owner_transaction_id', $id)->whereIn('id', $array_diff)->get();
                foreach($orders_delete as $order_delete) {
                    $order_delete->delete();
                }
                $orders = OwnerTransactionDetail::where('otd_owner_transaction_id', $id)->whereIn('id', $data['product_ids'])->get();
                foreach ($orders as $key => $item) {
                    $item->update([
                        'otd_qty' => $data['txt_quantity_product'][$key],
                        'otd_note' => $data['otd_note'][$key],
                    ]);
                    $total_money += ($item->otd_price * $data['txt_quantity_product'][$key]);
                    $total_products += $data['txt_quantity_product'][$key];
                }
                if(count($data['txt_id_product']) > count($orders)) {
                    for ($i = count($orders); $i < count($data['txt_id_product']); $i++) {
                        OwnerTransactionDetail::create([
                            'otd_owner_transaction_id' => $transaction->id,
                            'otd_product_id' => $data['txt_id_product'][$i],
                            'otd_qty' => $data['txt_quantity_product'][$i],
                            'otd_price' => Product::find($data['txt_id_product'][$i])->pro_money_yuan,
                            'otd_note' => $data['otd_note'][$i],
                            'otd_status' => 1
                        ]);
                        $total_money += (Product::find($data['txt_id_product'][$i])->pro_money_yuan * $data['txt_quantity_product'][$i]);
                        $total_products += $data['txt_quantity_product'][$i];
                    }
                }
                $tst_total_money_old_format = number_format($transaction->ot_total_money,0,',','.');
                $tst_total_money_new_format = number_format($total_money,0,',','.');
                $owner = OwnerChina::find($transaction->ot_owner_china_id);
                ChangeMoneyOwnerHistory::create([
                    'cmh_owner_china_id' => $transaction->ot_owner_china_id,
                    'cmh_money' => $total_money-$transaction->ot_total_money,
                    'cmh_money_after' => $owner->oc_total_money+($total_money-$transaction->ot_total_money),
                    'cmh_yuan' => 0,
                    'cmh_content' => "do cap nhat transaction id={$transaction->id}, Cập nhật: \n số lượng sp: {$transaction->ot_total_products} -> {$total_products} \n / Tiền: {$tst_total_money_old_format} -> $tst_total_money_new_format (la so tien cua don hang)",
                    'cmh_owner_transaction_id' => $transaction->id,
                    'cmh_money_before' => $owner->oc_total_money
                ]);
                $owner->update([
                    'oc_total_money' => $owner->oc_total_money+($total_money-$transaction->ot_total_money)
                ]);
                $transaction->update([
                    'ot_total_products' => $total_products,
                    'ot_total_money' => $total_money
                ]);
            }
            if (array_key_exists('ot_order_date', $data) || array_key_exists('ot_note', $data)) {
                $transaction->update([
                    'ot_order_date' => empty($data['ot_order_date']) ? $transaction->ot_order_date : $data['ot_order_date'],
                    'ot_note' => $data['ot_note']
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('toastr', [
                'type'      => 'error',
                'message'   => $e->getMessage()
            ]);
            return redirect()->back();
        }
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Cập nhật thành công !'
        ]);
        return redirect()->back();
    }

    function stripVN($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);

        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
}
