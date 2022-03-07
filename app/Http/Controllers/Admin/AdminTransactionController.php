<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminTransactionRequest;
use App\Models\Bao;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $transactions = Transaction::query();
        if ($user_id = $request->user_id) {
            $transactions->whereHas('user', function ($query) use ($user_id) {
                $query->where('id', $user_id);
            });
        }

        if ($phone = $request->phone) {
            $transactions->whereHas('user', function ($query) use ($phone) {
                $query->where('phone', $phone);
            });
        }

        if ($status = $request->status) {
            $transactions->where('tst_status', $status);
        }
        $transactions = $transactions->orderByDesc('id')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
        $viewData = [
            'transactions'  =>  $transactions,
            'users' => $users,
            'query'         =>  $request->query()
        ];
        return view('admin.transaction.index', $viewData);
    }

    public function create()
    {
        $users = User::all();
        $transports = Transport::all();
        $viewData = [
            'users'  =>  $users,
            'transports' => $transports
        ];
        return view('admin.transaction.create', $viewData);
    }

    public function store(AdminTransactionRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $total_money = 0;
            $total_products = 0;
            foreach ($data['txt_id_product'] as $key => $idProduct) {
                $product = Product::find($idProduct);
                $total_money += ($product->pro_price * $data['txt_quantity_product'][$key]);
                $total_products += $data['txt_quantity_product'][$key];
            }

            $transaction = Transaction::create([
                'tst_user_id' => $data['tst_user_id'],
                'tst_total_money' => $total_money,
                'tst_total_products' => $total_products,
                'tst_note' => $data['tst_note'],
                'tst_status' => 1,
                'tst_type' => 1,
                'tst_order_date' => $data['tst_order_date'],
                'tst_expected_date' => $data['tst_expected_date'],
                'tst_deposit' => $data['tst_deposit'],
            ]);

            foreach ($data['txt_id_product'] as $key => $idProduct) {
                Order::create([
                    'od_transaction_id' => $transaction->id,
                    'od_product_id' => $idProduct,
                    'od_qty' => $data['txt_quantity_product'][$key],
                    'od_price' => Product::find($idProduct)->pro_price,
                    'od_note' => $data['od_note'][$key]
                ]);
                DB::table('products')
                    ->where('id', $idProduct)
                    ->increment('pro_pay', $data['txt_quantity_product'][$key]);
            }

            if (!empty($data['b_weight'])) {
                $total_bao = 0;
                foreach ($data['b_weight'] as $key => $value) {
                    Bao::create([
                        'b_name' => $data['b_name'][$key],
                        'b_weight' => $data['b_weight'][$key],
                        'b_fee' => null,
                        'b_status' => 1,
                        'b_note' => $data['b_note'][$key],
                        'b_transaction_id' => $transaction->id,
                        'b_transport_id' => $data['b_transport_id'][$key]
                    ]);
                    $total_bao += 1;
                }
            }

            if ($transaction) {
                TransactionHistory::create([
                    'th_transaction_id' => $transaction->id,
                    'th_content' => "tạo đơn hàng thành công"
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
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $total_money = 0;
            $total_products = 0;
            $data = $request->all();
            $transaction = Transaction::with('transport')->findOrFail($id);
            if(array_key_exists('txt_id_product', $data)) {
                $order_old = Order::where('od_transaction_id', $id)->pluck('id')->toArray();
                $array_diff = array_diff($order_old, $data['product_ids']);
                $orders_delete = Order::where('od_transaction_id', $id)->whereIn('id', $array_diff)->get();
                foreach($orders_delete as $order_delete) {
                        DB::table('products')
                            ->where('id', $order_delete->od_product_id)
                            ->decrement('pro_pay', $order_delete->od_qty);
                    $order_delete->delete();
                }
                $orders = Order::where('od_transaction_id', $id)->whereIn('id', $data['product_ids'])->get();

                foreach ($orders as $key => $item) {
                    if($item->od_qty != (int)$data['txt_quantity_product'][$key]) {
                        DB::table('products')
                            ->where('id', $item->od_product_id)
                            ->decrement('pro_pay', $item->od_qty);
                        DB::table('products')
                            ->where('id', $item->od_product_id)
                            ->increment('pro_pay', $data['txt_quantity_product'][$key]);
                    }
                    $item->update([
                        'od_qty' => $data['txt_quantity_product'][$key],
                        'od_note' => $data['od_note'][$key],
                    ]);
                    $total_money += ($item->od_price * $data['txt_quantity_product'][$key]);
                    $total_products += $data['txt_quantity_product'][$key];

                }

                if(count($data['txt_id_product']) > count($orders)) {
                    for ($i = count($orders); $i < count($data['txt_id_product']); $i++) {
                        Order::create([
                            'od_transaction_id' => $transaction->id,
                            'od_product_id' => $data['txt_id_product'][$i],
                            'od_qty' => $data['txt_quantity_product'][$i],
                            'od_price' => Product::find($data['txt_id_product'][$i])->pro_price,
                            'od_note' => $data['od_note'][$i]
                        ]);
                        $total_money += (Product::find($data['txt_id_product'][$i])->pro_price * $data['txt_quantity_product'][$i]);
                        $total_products += $data['txt_quantity_product'][$i];
                        DB::table('products')
                            ->where('id', $data['txt_id_product'][$i])
                            ->increment('pro_pay', $data['txt_quantity_product'][$i]);
                    }
                }
                $tst_total_money_old_format = number_format($transaction->tst_total_money,0,',','.');
                $tst_total_money_new_format = number_format($total_money,0,',','.');
                TransactionHistory::create([
                    'th_transaction_id' => $transaction->id,
                    'th_content' => "Cập nhật: \n số lượng sp: {$transaction->tst_total_products} -> {$total_products} \n / Tiền: {$tst_total_money_old_format} -> $tst_total_money_new_format "
                ]);

                $transaction->update([
                    'tst_total_products' => $total_products,
                    'tst_total_money' => $total_money
                ]);
            }
            if (array_key_exists('b_weight', $data)) {
                $count_bao_old = Bao::where('b_transaction_id', $id)->count();
                $baos = [];
                $sum_old = 0;
                if (array_key_exists('id_bao', $data)) {
                    $baos_old = Bao::where('b_transaction_id', $id)->pluck('id')->toArray();
                    $input_array = array_diff($baos_old, $data['id_bao']);
                    $sum_old = Bao::where('b_transaction_id', $id)->whereIn('id', $input_array)->sum('b_weight');
                    Bao::where('b_transaction_id', $id)->whereIn('id', $input_array)->delete();
                    $baos = Bao::where('b_transaction_id', $id)->whereIn('id', $data['id_bao'])->get();
                }

                $sum_old += Bao::where('b_transaction_id', $id)->sum('b_weight');
                $total_bao = 0;
                $weight_total = 0;
                foreach ($baos as $key => $item) {
                    $item->update([
                        'b_name' => $data['b_name'][$key],
                        'b_weight' => $data['b_weight'][$key],
                        'b_status' => 1,
                        'b_note' => $data['b_note'][$key],
                        'b_transaction_id' => $transaction->id
                    ]);
                    $total_bao += 1;
                    $weight_total += $data['b_weight'][$key];
                }
                if(count($data['b_weight']) > count($baos)) {
                    for ($i = count($baos); $i < count($data['b_weight']); $i++) {
                        Bao::create([
                            'b_name' => $data['b_name'][$i],
                            'b_weight' => $data['b_weight'][$i],
                            'b_fee' => null,
                            'b_status' => 1,
                            'b_note' => $data['b_note'][$i],
                            'b_transaction_id' => $transaction->id,
                            'b_transport_id' => $data['b_transport_id'][$i]
                        ]);
                        $total_bao += 1;
                        $weight_total += $data['b_weight'][$i];
                    }
                }
                TransactionHistory::create([
                    'th_transaction_id' => $transaction->id,
                    'th_content' => "Cập nhật: \n số lượng bao: {$count_bao_old} -> {$total_bao} \n số cân: {$sum_old} -> $weight_total "
                ]);
            }
            if (array_key_exists('tst_order_date', $data) || array_key_exists('tst_expected_date', $data) || array_key_exists('tst_note', $data)) {
                $transaction->update([
                    'tst_order_date' => empty($data['tst_order_date']) ? $transaction->tst_expected_date : $data['tst_order_date'],
                    'tst_expected_date' => empty($data['tst_expected_date']) ? $transaction->tst_expected_date : $data['tst_expected_date'],
                    'tst_note' => $data['tst_note']
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

    public function getTransactionDetail(Request $request, $id)
    {
        $transports = Transport::all();
        $transaction = Transaction::query()->with(['baos', 'transaction_histories', 'transport'])->findOrFail($id);
        $order = Order::with('product:id,pro_name,pro_avatar')
            ->where('od_transaction_id', $id)
            ->get();
        $transport_success = Bao::where('b_transaction_id', $id)->whereNotNull('b_success_date')->get();
        $total_transport_success = 0;
        foreach ($transport_success as $item) {
            $total_transport_success += ($item->b_weight * $item->b_fee);
        }

        $total_transport = 0;
        $transport_pending = Bao::with('transport')->where('b_transaction_id', $id)->whereNull('b_success_date')->get();
        foreach ($transport_pending as $item) {
            $total_transport += ($item->b_weight * $item->transport->tp_fee);
        }
        $total_transport +=$total_transport_success;
        return view('admin.transaction.view', compact('transaction', 'order', 'total_transport', 'transports'));
    }

    public function getAction($action, $id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            if($transaction->tst_status != -1) {
                switch ($action) {
                    case 'process':
                        $transaction->tst_status = 2;
                        break;
                    case 'success':
                        $transaction->tst_status = 3;
                        break;
                    case 'cancel':
                        $transaction->tst_status = -1;
                        DB::beginTransaction();
                        try {
                            $orders = Order::where('od_transaction_id', $id)->get();
                            foreach ($orders as $order) {
                                Product::where('id',$order->od_product_id)->decrement('pro_pay', $order->od_qty);
                                // Product::where('id',$order->od_product_id)->decrement('pro_number', $order->od_qty);
                            }
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return redirect()->back();
                        }
                        DB::commit();
                        break;
                }
                $transaction->save();
            }
        }
        return redirect()->back();
    }

    public function order_detail_delete(Request $request, $id)
    {
        $order = Order::findOrfail($id);
        if ($order) {
            // if($order->od_sale){
            //     $money=$order->od_qty * ((100-$order->od_sale)/100) * $order->od_price;
            // }else{
            // }
            $money = $order->od_qty *  $order->od_price;

            DB::table('transactions')
                ->where('id', $order->od_transaction_id)
                ->decrement('tst_total_money', $money);
            DB::table('products')
                ->where('id', $order->od_product_id)
                ->decrement('pro_pay', $order->od_qty);
            $order->delete();
        }
        return redirect()->back();
    }

    public function delete($id)
    {
        $transaction = Transaction::findOrfail($id);
        // $order=Order::query()->where('od_transaction_id',$id)->get();
        if ($transaction) {
            // if($order){
            //     foreach($order as $item){
            //         DB::table('products')
            //         ->where('id',$item->od_product_id)
            //         ->decrement('pro_pay',$item->od_qty);
            //     }
            // }
            $transaction->delete();
            DB::table('orders')->where('od_transaction_id', $id)->delete();
        }
        return redirect()->back();
    }

    public function updateSuccessDate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $bao = Bao::with('transport')->findOrfail($id);
            $bao->update([
                'b_success_date' => $request->value == 'true' ? Carbon::now() : null,
                'b_fee' => $request->value == 'true' ? $bao->transport->tp_fee : null
            ]);
            $price_bao = view('admin.transaction.data_edit_bao.price_bao', compact('bao'))->render();
            $success_date = view('admin.transaction.data_edit_bao.success_date', compact('bao'))->render();
            DB::commit();
            return response([
                'data' => 'success',
                'price_bao' => $price_bao,
                'success_date' => $success_date,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
        }

    }

    public function updateTransportIdBao(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $bao = Bao::with('transport')->findOrfail($id);
            $bao->update([
                'b_transport_id' => $request->id_bao,
                'b_fee' => empty($bao->b_fee) == true ? null : Transport::findOrfail($request->id_bao)->tp_fee
            ]);
            $bao = Bao::with('transport')->findOrfail($id);
            $price_bao = view('admin.transaction.data_edit_bao.price_bao', compact('bao'))->render();

            DB::commit();
            return response([
                'data' => 'success',
                'price_bao' => $price_bao,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function updateMoney(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrfail($id);
            $tst_total_paid_old = $transaction->tst_total_paid;

            $tst_total_money_old = number_format($transaction->tst_total_money,0,',','.');
            $so_tien_no_cuoi_cu = number_format($transaction->tst_total_money - $transaction->tst_total_paid,0,',','.');
            $tst_total_money_new_format = number_format($transaction->tst_total_money - (int)$request->value - $transaction->tst_total_paid,0,',','.');
            $value_format = number_format($request->value,0,',','.');
            $so_tien_hang_da_tra_format = number_format((int)$request->value + $tst_total_paid_old,0,',','.');
            $transaction->update([
                'tst_total_paid' => (int)$request->value + $tst_total_paid_old
            ]);
            $con_no_tong = number_format($transaction->tst_total_money - $transaction->tst_total_paid, 0,',','.');
            TransactionHistory::create([
                'th_transaction_id' => $id,
                'th_content' => "Cập nhật Tiền hàng: \n Trả: {$value_format} đ \n/ Số tiền còn nợ = (số tiền nợ cuối cũ - số tiền trả lần này): {$so_tien_no_cuoi_cu} - {$value_format} = {$tst_total_money_new_format} /
                Tổng số tiền hàng đã trả: $so_tien_hang_da_tra_format
                (còn nợ tổng: $con_no_tong)"
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
        return response([
            'code' => 200,
            'message' => 'Update số tiền thành công',
            'data' => ''
        ]);
    }

    public function updateMoneyTransport(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrfail($id);

            $transport_success = Bao::where('b_transaction_id', $id)->whereNotNull('b_success_date')->get();
            $total_transport_success = 0;
            foreach ($transport_success as $item) {
                $total_transport_success += ($item->b_weight * $item->b_fee);
            }

            $total_transport = 0;
            $transport_pending = Bao::with('transport')->where('b_transaction_id', $id)->whereNull('b_success_date')->get();
            foreach ($transport_pending as $item) {
                $total_transport += ($item->b_weight * $item->transport->tp_fee);
            }
            $total_transport +=$total_transport_success;
            $total_transport_old = number_format($total_transport - $transaction->total_transport_paid,0,',','.');
            $tst_total_transport_new = $transaction->total_transport_paid + (int)$request->value;
            $so_tien_con_no_format = number_format($total_transport - $tst_total_transport_new,0,',','.');
            $tst_total_transport_new_format = number_format($tst_total_transport_new,0,',','.');
            $value_format = number_format($request->value,0,',','.');
            $transaction->update([
                'total_transport_paid' => $tst_total_transport_new
            ]);
            $con_no_tong = number_format($total_transport - $transaction->total_transport_paid,0,',','.');
            TransactionHistory::create([
                'th_transaction_id' => $id,
                'th_content' => "Cập nhật tiền vận chuyển: \n Trả: {$value_format} đ \n/
                Số tiền còn nợ =(số tiền nợ cuối cũ - số tiền trả lần này): {$total_transport_old} - {$tst_total_transport_new} = {$so_tien_con_no_format}/
                Tổng số tiền vận chuyển đã trả: $tst_total_transport_new_format
                (còn nợ tổng: $con_no_tong)"
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
        return response([
            'code' => 200,
            'message' => 'Update số tiền thành công',
            'data' => ''
        ]);
    }

    public function convertDeposit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrfail($id);
            if($transaction->tst_deposit <= 0) {
                return response([
                    'code' => 400,
                    'message' => 'tiền cọc nhỏ hơn 0',
                    'data' => ''
                ]);
            }

            $tst_total_paid_old = $transaction->tst_total_paid;
            $tst_total_paid_new = $tst_total_paid_old + $transaction->tst_deposit;

            $tst_total_money_format = number_format($transaction->tst_total_money - $transaction->tst_total_paid,0,',','.');
            $tst_total_paid_new_format = number_format($tst_total_paid_new,0,',','.');
            $tst_deposit_format = number_format($transaction->tst_deposit,0,',','.');
            $a = number_format($transaction->tst_total_money - $transaction->tst_total_paid - $transaction->tst_deposit,0,',','.');


            $transaction->update([
                'tst_deposit' => 0,
                'tst_total_paid' => $tst_total_paid_new

            ]);

            TransactionHistory::create([
                'th_transaction_id' => $id,
                'th_content' => "Convert tiền cọc: \n Tiền cọc: {$tst_deposit_format} đ \n/
                Số tiền còn nợ = (số tiền nợ cuối cũ - số tiền cọc): {$tst_total_money_format} - {$tst_deposit_format} = {$a} /
                Tổng số tiền hàng đã trả: $tst_total_paid_new_format
                (còn nợ tổng: $a)"
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response([
                'code' => 400,
                'message' => 'lỗi',
                'data' => ''
            ]);
        }
        return response([
            'code' => 200,
            'message' => 'Update số tiền thành công',
            'data' => ''
        ]);
    }

    // if($transaction->tst_status != -1) {
    //     switch ($action) {
    //         case 'process':
    //             $transaction->tst_status = 2;
    //             break;
    //         case 'success':
    //             $transaction->tst_status = 3;
    //             break;
    //         case 'cancel':
    //             $transaction->tst_status = -1;
    //             DB::beginTransaction();
    //             try {
    //                 $orders = Order::where('od_transaction_id', $id)->get();
    //                 foreach ($orders as $order) {
    //                     Product::where('id',$order->od_product_id)->decrement('pro_pay', $order->od_qty);
    //                     // Product::where('id',$order->od_product_id)->decrement('pro_number', $order->od_qty);
    //                 }
    //             } catch (\Exception $e) {
    //                 DB::rollBack();
    //                 return redirect()->back();
    //             }
    //             DB::commit();
    //             break;
    //     }
    //     $transaction->save();
    // }
}
