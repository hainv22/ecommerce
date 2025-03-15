<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use App\Models\Bao;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $user = User::query();
        // if ($email = $request->email) {
        //     $user->where('email', 'like', '%' . $request->email . '%')
        //         ->orwhere('name', 'like', '%' . $request->email . '%')
        //         ->orwhere('phone', 'like', '%' . $request->email . '%');
        // }

        $user = $user->orderByDesc('id')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));

        if ($request->ajax()) {
            $a = $request->search;
            if ($a == null || $a == '') {
                $user = User::orderByDesc('id')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
            } else {
                $user = User::where('email', 'like', '%' . $a . '%')
                ->orwhere('name', 'like', '%' . $a . '%')
                ->orwhere('phone', 'like', '%' . $a . '%')->orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
            }
            $query  = $request->query();
            $html = view('admin.user.data', compact('user', 'query'))->render();
            return response([
                'data'      => $html ?? null,
                'messages'  => 'thành công !'
            ]);
        }

        $viewData = [
            'user'  => $user,
            'query'         =>  $request->query()
        ];
        return view('admin.user.index', $viewData);
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(AdminUserRequest $request)
    {
        $data = $request->except('_token');
        if ($request->avatar) {
            $image = upload_image('avatar');
            if ($image['code'] == 1) {
                $data['avatar'] = $image['name'];

            }
        }
        $data['password'] = Hash::make(123);
        $user = User::create($data);
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Insert thành công !'
        ]);
        return redirect()->back();
    }

    public function edit($id)
    {
        $user = User::findOrfail($id);

        $viewData = [
            'user'           => $user,
        ];
        return view('admin.user.update', $viewData);
    }

    public function update(AdminUserRequest $request, $id)
    {
        $user = User::findOrfail($id);
        $data = $request->except('_token');

        if ($request->avatar) {
            $image = upload_image('avatar');
            if ($image['code'] == 1) {
                $data['avatar'] = $image['name'];
            }
        }

        if(!empty($_POST['password'])) {
            $data['password'] = Hash::make($request->password);
        }else{
            $data['password'] = $user['password'];
        }

        $user->update($data);
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Update thành công !'
        ]);
        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->id == 1) {
                $request->session()->flash('toastr', [
                    'type'      => 'error',
                    'message'   => 'Không thể xóa Supper Admin !'
                ]);
                return redirect()->back();
            }
            $transaction = Transaction::where('tst_user_id', $id)->first();
            if ($transaction) {
                $request->session()->flash('toastr', [
                    'type'      => 'error',
                    'message'   => 'Khách hàng đang có đơn hàng !'
                ]);
                return redirect()->back();
            }
        };
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Xóa user thành công !'
        ]);
        return redirect()->back();
    }

    public function showDetail(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $total_money_user = Transaction::where('tst_user_id', $id)->sum('tst_total_money');
        $total_money_paid = Transaction::where('tst_user_id', $id)->sum('tst_total_paid');
        $total_money_transport_paid = Transaction::where('tst_user_id', $id)->sum('total_transport_paid');
        $transactions = Transaction::where('tst_user_id', $id)->get();
        $total_transport_success = 0;
        $total_transport = 0;
        $tst_interest_rate_total = Transaction::where('tst_user_id', $id)->sum('tst_interest_rate');
        $tst_interest_rate_2023 = 0;
        $tst_interest_rate_2024 = Transaction::where('tst_user_id', $id)
        ->whereBetween('tst_order_date',[date('2024-02-01'), date('2025-01-28')])->sum('tst_interest_rate');

        $tst_interest_rate_2025 = Transaction::where('tst_user_id', $id)
        ->whereBetween('tst_order_date',[date('2025-02-01'), date('2026-02-15')])->sum('tst_interest_rate');

        foreach ($transactions as $transaction) {
            $transport_success = Bao::where('b_transaction_id', $transaction->id)->whereNotNull('b_success_date')->get();
            foreach ($transport_success as $item) {
                $total_transport_success += ($item->b_weight * $item->b_fee);
            }

            $transport_pending = Bao::with('transport')->where('b_transaction_id', $transaction->id)->whereNull('b_success_date')->get();
            foreach ($transport_pending as $item) {
                $total_transport += ($item->b_weight * $item->transport->tp_fee);
            }
        }

        $total_transport +=$total_transport_success;
        $viewData = [
            'user' => $user,
            'total_money_user' => $total_money_user,
            'total_money_paid' => $total_money_paid,
            'total_money_transport_paid' => $total_money_transport_paid,
            'total_transport' => $total_transport,
            'tst_interest_rate_total' => $tst_interest_rate_total,
            'tst_interest_rate_2023' => $tst_interest_rate_total-$tst_interest_rate_2024-$tst_interest_rate_2025,
            'tst_interest_rate_2024' => $tst_interest_rate_2024,
            'tst_interest_rate_2025' => $tst_interest_rate_2025,
        ];
        return view('admin.user.detail', $viewData);
    }
}
