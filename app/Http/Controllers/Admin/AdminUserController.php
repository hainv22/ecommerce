<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index()
    {
        $user = User::paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
        $viewData = [
            'user'  => $user
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
}
