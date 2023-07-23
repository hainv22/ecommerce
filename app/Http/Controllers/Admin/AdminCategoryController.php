<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCategoryRequest;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('Category Index', $request));
        $categorys = Category::query()->orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
        if ($request->ajax()) {
            $a = $request->search;
            if ($a == null || $a == '') {
                $categorys = Category::query()->orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
                $query  = $request->query();
                $html = view('admin.category.data', compact('categorys', 'query'))->render();
                return response([
                    'data'      => $html ?? null,
                    'messages'  => 'Update status thành công !'
                ]);
            }
            $categorys = Category::query()->where('c_name', 'like', $a . '%')->orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
            $query  = $request->query();
            $html = view('admin.category.data', compact('categorys', 'query'))->render();
            return response([
                'data'      => $html ?? null,
                'messages'  => 'Update status thành công !'
            ]);
        }
        return view('admin.category.index', compact('categorys'));
    }

    public function create(Request $request)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('view create category', $request));
        $categorys = Category::all();
        return view('admin.category.create', compact('categorys'));
    }

    public function store(AdminCategoryRequest $request)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('Create Category', $request));
        $data = $request->except('_token', 'c_avatar');
        $data['c_slug']         = Str::slug($request->c_name);
        if ($request->c_avatar) {
            $image = upload_image('c_avatar');
            if ($image['code'] == 1) {
                $data['c_avatar'] = $image['name'];
            }
        }
        Category::create($data);
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Insert thành công !'
        ]);
        return redirect()->back();
    }

    public function edit(Request $request, $id)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('View Edit Category', $request));
        $categorys = Category::all();
        $category = Category::findOrfail($id);
        return view('admin.category.update', compact('category', 'categorys'));
    }

    public function update(AdminCategoryRequest $request, $id)
    {
        $category = Category::findOrfail($id);
        $c_old = Category::findOrfail($id);
        $data = $request->except('_token', 'c_avatar');
        if ($request->c_avatar) {
            $image = upload_image('c_avatar');
            if ($image['code'] == 1) {
                $data['c_avatar'] = $image['name'];
            }
        }
        $category->update($data);
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'update thành công !'
        ]);
        $data_log = $this->makeDataLogByRequest('Update Category', $request) + array('old' => $c_old, 'new' => $category);
        $this->writeLogInDatabase($data_log);
        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('Delete Category', $request));
        $idChildrenCategorys = Category::whereIn('c_parent_id', [$id])->pluck('id')->push((int)$id)->all();
        $products = Product::whereIn('pro_category_id', $idChildrenCategorys)->get();
        if (!empty($products[0])) {
            $request->session()->flash('toastr', [
                'type'      => 'error',
                'message'   => 'Đang có sản phẩm trong danh mục không thể xóa !'
            ]);
            return redirect()->back();
        }

        Category::destroy($idChildrenCategorys);
        $request->session()->flash('toastr', [
            'type'      => 'success',
            'message'   => 'Delete thành công !'
        ]);
        return redirect()->back();
    }

    public function hot(Request $request, $id)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('Update Hot Category', $request));
        $category           = Category::findOrfail($id);
        $category->c_hot = !$category->c_hot;
        $category->updated_at = Carbon::now();
        $category->save();

        if ($request->ajax()) {
            $categorys     = Category::orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
            $query  = $request->query();
            $html = view('admin.category.data', compact('categorys', 'query'))->render();
            return response([
                'data'      => $html ?? null,
                'messages'  => 'Update hot thành công !'
            ]);
        }
        return redirect()->back();
    }

    public function active(Request $request, $id)
    {
        $this->writeLogInDatabase($this->makeDataLogByRequest('Update active Category', $request));
        $idChildrenCategorys = Category::whereIn('c_parent_id', [$id])->pluck('id')->push((int)$id)->all();
        $products = Product::whereIn('pro_category_id', $idChildrenCategorys)->get();
        if (!empty($products[0])) {
            return response([
                'error'      => 'error',
                'messages'   => 'Đang có sản phẩm trong danh mục không thể ẩn !'
            ]);
        }

        $category           = Category::findOrfail($id);
        $category->c_status = !$category->c_status;
        $category->updated_at = Carbon::now();
        $category->save();

        if ($request->ajax()) {
            $categorys     = Category::orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
            $query  = $request->query();
            $html = view('admin.category.data', compact('categorys', 'query'))->render();
            return response([
                'data'      => $html ?? null,
                'messages'  => 'Update status thành công !'
            ]);
        }
        return redirect()->back();
    }

    // public function ajax_search_table(Request $request){
    //     if($request->ajax()){
    //         $a = $request->search;
    //         if($a == null || $a == '' ){
    //             $categorys = Category::query()->orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
    //             $query  = $request->query();
    //             $html = view('admin.category.data',compact('categorys','query'))->render();
    //             return response([
    //                 'data'      => $html ?? null,
    //                 'messages'  => 'Update status thành công !'
    //             ]);
    //         }
    //         $categorys = Category::query()->where('c_name','like','%'.$a.'%')->orderBy('id', 'DESC')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));
    //         $query  = $request->query();
    //         $html = view('admin.category.data',compact('categorys','query'))->render();
    //         return response([
    //             'data'      => $html ?? null,
    //             'messages'  => 'Update status thành công !'
    //         ]);
    //     }
    // }
}
