<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminTransactionRequest;
use App\Models\Bao;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
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
        $viewData = [
            'users'  =>  $users
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
                'tst_type' => 1
            ]);

            foreach ($data['txt_id_product'] as $key => $idProduct) {
                Order::create([
                    'od_transaction_id' => $transaction->id,
                    'od_product_id' => $idProduct,
                    'od_qty' => $data['txt_quantity_product'][$key],
                    'od_price' => Product::find($idProduct)->pro_price
                ]);
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
                        'b_transaction_id' => $transaction->id
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

    public function getTransactionDetail(Request $request, $id)
    {
        $transaction = Transaction::query()->findOrFail($id);
        $order = Order::with('product:id,pro_name,pro_slug,pro_avatar')
            ->where('od_transaction_id', $id)
            ->get();
        return view('admin.transaction.view', compact('transaction', 'order'));
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
