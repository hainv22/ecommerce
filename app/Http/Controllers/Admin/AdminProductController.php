<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProductRequest;
use App\Models\Category;
use App\Models\Log;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        // $categorysKM = Category::where('id', 2)->first()->delete();
        // $categoryparentsKM = Category::where('c_parent_id', 2)->pluck('id')->all();
        // $typeProducts = TypeProduct::where('tp_category_id', 2)->pluck('id')->all();
        // $productsKM = Product::whereIn('pro_category_id', $categoryparentsKM)->pluck('id')->all();


        // TypeProduct::destroy($typeProducts);
        // dd($typeProducts);
        $users = User::all();

        if ($request->ajax()) {
            if ($request->p_id) {
                $product = Product::findOrfail((int)$request->p_id);
            }
            if ($request->p_hot == 1 || $request->p_hot == 2) {
                $this->writeLogInDatabase($this->makeDataLogByRequest('update hot product', $request));
                $product->pro_hot       = !$product->pro_hot;
                $product->updated_at    = Carbon::now();
                $product->save();
            }
            if ($request->p_status == 1 || $request->p_status == 2) {
                $this->writeLogInDatabase($this->makeDataLogByRequest('update active product', $request));
                $product->pro_active       = !$product->pro_active;
                $product->updated_at    = Carbon::now();
                $product->save();
            }
        }

        $products = Product::with('category:id,c_name');
        $categorys = Category::all();

        if ($id = $request->id) {
            $products->where('id', $id);
        }
        if ($name = strtolower($this->stripVN($request->name))) {
            $products->where('pro_name', 'like', '%' . $name . '%');
        }
        if ($idCategory = $request->category) {
            $products->where('pro_category_id', $idCategory);
        }
        if ($hot = $request->hot) {
            if ($hot == 1) {
                $products->where('pro_hot', $hot);
            } else {
                $products->where('pro_hot', 0);
            }
        }
        if ($active = $request->status) {
            if ($active == 1) {
                $products->where('pro_active', $active);
            } else {
                $products->where('pro_active', 0);
            }
        }
        if ($sort = $request->sort) {
            switch ($sort) {
                case 1:
                    $products->orderBy('id', 'ASC');
                    break;
                case 2:
                    $products->orderBy('id', 'DESC');
                    break;
                case 3:
                    // $products->orderBy('total', 'ASC');
                    $products->orderBy('pro_price', 'ASC');
                    break;
                case 4:
                    // $products->orderBy('total', 'DESC');
                    $products->orderBy('pro_price', 'DESC');
                    break;
            }
        }
        if ($sort_pay = $request->sort_pay) {
            switch ($sort_pay) {
                case 1:
                    $products->orderBy('pro_pay', 'DESC');
                    break;
                case 2:
                    $products->orderBy('pro_pay', 'ASC');
                    break;
            }
        }

        if ($sort_pro_number = $request->sort_pro_number) {
            switch ($sort_pro_number) {
                case 1:
                    $products = $products->where('pro_number', '>', 0);
                    $products->orderBy('pro_number', 'DESC');
                    break;
                case 2:
                    $products = $products->where('pro_number', '>', 0);
                    $products->orderBy('pro_number', 'ASC');
                    break;
            }
        }

        if($request->ajax()) {
            if ($search = strtolower($this->stripVN($request->search))) {
                $products->where('pro_name', 'like', '%' . $search . '%');
            }
        }

        if ($user_id = $request->user_id) {
            $transactions_id_of_user = Transaction::where('tst_user_id', $user_id)->pluck('id')->toArray();
            $prds_id_of_user = array_unique(array_reverse(Order::whereIn('od_transaction_id', $transactions_id_of_user)->pluck('od_product_id')->toArray()));
            $products_ids = $products->whereIn('id', $prds_id_of_user)->pluck('id')->toArray();
            foreach($prds_id_of_user as $key => $value){
                if(!in_array($value, $products_ids)) {
                    unset($prds_id_of_user[$key]);
                }
            }
            $c = Collection::make(new Product);
            foreach($prds_id_of_user as $item){
                $c->push(Product::with('category:id,c_name')->where('id', $item)->get()[0]);
            }
            $viewData = [
                'products'      => $c,
                'categorys'     => $categorys,
                'query'         => $request->query(),
                'check'         => 1,
                'users' => $users,
            ];
            return view('admin.product.index', $viewData);
        }

        if (!($request->sort && $request->sort_pay)) {
            $products->orderByDesc('id');

        }
        if ($request->sort_pro_number) {
            switch ($sort_pro_number) {
                case 1:
                    $products = $products->where('pro_number', '>', 0);
                    $products->orderBy('pro_number', 'DESC');
                    break;
                case 2:
                    $products = $products->where('pro_number', '>', 0);
                    $products->orderBy('pro_number', 'ASC');
                    break;
            }
            $products = $products->paginate(10000);
            $viewData = [
                'products'      => $products,
                'categorys'     => $categorys,
                'users' => $users,
                'query'         => $request->query()
            ];
            return view('admin.product.index', $viewData);
        }
        $products = $products->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
        $viewData = [
            'products'      => $products,
            'categorys'     => $categorys,
            'users' => $users,
            'query'         => $request->query()
        ];
        if ($request->ajax()) {
            if ($request->transaction_get_products == 1) {
                $html = view('admin.transaction.data_product', $viewData)->render();
                return response([
                    'data' => $html ?? null
                ]);
            }
            $html = view('admin.product.data', $viewData)->render();
            // if ($request->p_hot == 1 || $request->p_hot == 2 || $request->p_status == 1 || $request->p_status == 2) {
            //     return response([
            //         'data' => $html ?? null,
            //         'messages'  => 'Update thành công !'
            //     ]);
            // }
            return response([
                'data' => $html ?? null,
            ]);
        }
        $this->writeLogInDatabase($this->makeDataLogByRequest('List Product', $request));
        return view('admin.product.index', $viewData);
    }

    public function create(Request $request)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('View Create Product', $request));
        $categorys = Category::select('id', 'c_name', 'c_parent_id')->get();
        $viewData = [
            'categorys'          => $categorys,
        ];
        return view('admin.product.create', $viewData);
    }

    public function store(AdminProductRequest $request)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('Create Product', $request));
            $data = $request->except('_token', 'pro_avatar', 'file');
            $data['pro_user_id'] = Auth::id();
            $data['pro_slug']   =   Str::slug($request->pro_name);
            if (!$request->pro_sale) {
                $data['pro_sale'] = 0;
            }

        if ($request->pro_avatar) {
            $image = upload_image('pro_avatar');
            if ($image['code'] == 1) {
                $data['pro_avatar'] = $image['name'];
            }
        }
        $product = Product::create($data);
        if ($product) {
            if ($request->file) {
                $this->syncAlbumImageAndProduct($request->file, $product->id);
            }
        }
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Insert thành công !'
        ]);
            return redirect()->back();
    }

    public function edit(Request $request, $id)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('Edit view Product', $request));
        $product = Product::with('images', 'orders.transaction.user', 'ownerTransactionDetail.ownerTransaction.ownerChina')->findOrfail($id);
        $categorys = Category::select('id', 'c_name', 'c_parent_id')->get();

        $viewData = [
            'categorys'         => $categorys,
            'product'           => $product,
        ];
        return view('admin.product.update', $viewData);
    }
    public function update(AdminProductRequest $request, $id)
    {
        $product = Product::findOrfail($id);
        $p_old = Product::findOrfail($id);
        $data = $request->except('_token', 'pro_avatar', 'attribute', 'file');

        $data['pro_slug']   =   Str::slug($request->pro_name);
        if ($request->pro_avatar) {
            $image = upload_image('pro_avatar');
            if ($image['code'] == 1) {
                $data['pro_avatar'] = $image['name'];
            }
        }

        $product->update($data);
        if ($request->file) $this->syncAlbumImageAndProduct($request->file, $id);
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Update thành công !'
        ]);
        $data_log = $this->makeDataLogByRequest('Update Product', $request) + array('old' => $p_old, 'new' => $product, 'diff_new' => array_diff($product->toArray(), $p_old->toArray()));
        $this->writeLogInDatabase($data_log);
        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('Delete Product', $request));
        DB::beginTransaction();
        $product = Product::with('images', 'orders')->findOrfail($id);
        if ($product) {
            // dd(empty($product->orders[0]));

            if (empty($product->orders[0])) {
                try {
                    foreach ($product->images as $item) {
                        $this->deleteImage($request, $item->id);
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    $request->session()->flash('toastr', [
                        'type'      => 'error',
                        'message'   => 'Có lỗi xảy ra, liên hệ Admin !'
                    ]);
                    return redirect()->back();
                }
                // $attributeOld=DB::table('attribute_product')
                //     ->where('ap_product_id',$id)
                //     ->pluck('id')
                //     ->toArray();
                $product->delete();
                DB::commit();
                $request->session()->flash('toastr', [
                    'type'      => 'success',
                    'message'   => 'Delete thành công !'
                ]);
                return redirect()->back();
            }
            $request->session()->flash('toastr', [
                'type'      => 'error',
                'message'   => 'Do sản phẩm đang thuộc một đơn hàng !'
            ]);
            return redirect()->back();
        }
        return redirect()->back();
    }

    // public function hot(Request $request, $id)
    // {
    //     $product                = Product::findOrfail($id);
    //     $product->pro_hot       = !$product->pro_hot;
    //     $product->updated_at    = Carbon::now();
    //     $product->save();

    //     if ($request->ajax()) {
    //         $products     = Product::orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
    //         $query  = $request->query();
    //         $html = view('admin.product.data', compact('products', 'query'))->render();
    //         return response([
    //             'data'      => $html ?? null,
    //             'messages'  => 'Update hot thành công !'
    //         ]);
    //     }
    //     return redirect()->back();
    // }

    // public function active(Request $request, $id)
    // {
    //     $product                    = Product::findOrfail($id);
    //     $product->pro_active        = !$product->pro_active;
    //     $product->updated_at        = Carbon::now();
    //     $product->save();

    //     if ($request->ajax()) {
    //         $products     = Product::orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
    //         $query  = $request->query();
    //         $html = view('admin.product.data', compact('products', 'query'))->render();
    //         return response([
    //             'data'      => $html ?? null,
    //             'messages'  => 'Update status thành công !'
    //         ]);
    //     }
    //     return redirect()->back();
    // }

    public function syncAlbumImageAndProduct($file, $productID)
    {
        foreach ($file as $key => $fileImage) {
            $ext = $fileImage->getClientOriginalExtension();
            $extend = [
                'png', 'jpg', 'jpeg', 'PNG', 'JPG'
            ];
            if (!in_array($ext, $extend)) {
                return false;
            }

            $filename = date('Y-m-d__') . Str::slug($fileImage->getClientOriginalName()) . '.' . $ext;

            $path = public_path() . '/uploads/' . date('Y/m/d');
            if (!\File::exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileImage->move($path, $filename);
            DB::table('images')
                ->insert([
                    'img_name'       => $filename,
                    'img_product_id' => $productID,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ]);
        }
    }
    public function deleteImage(Request $request, $imageID)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('Delete image', $request));
        DB::table('images')->where('id', $imageID)->delete();
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Delete thành công !'
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

    public function checkPurchase(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $view_check_purchase = view('admin.product.check-purchase.check_purchase', compact('product'))->render();
        return response([
            'data' => $view_check_purchase
        ]);
    }
}
