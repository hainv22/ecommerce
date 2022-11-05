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
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Category Index',
            'content' => null,
            'data' => json_encode($request->all())
        ]);
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

    public function create()
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'view create category',
            'content' => null,
            'data' => null
        ]);
        $categorys = Category::all();
        return view('admin.category.create', compact('categorys'));
    }

    public function store(AdminCategoryRequest $request)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Create Category',
            'content' => null,
            'data' => json_encode($request->all())
        ]);
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

    public function edit($id)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'View Edit Category',
            'content' => null,
            'data' => null
        ]);
        $categorys = Category::all();
        $category = Category::findOrfail($id);
        return view('admin.category.update', compact('category', 'categorys'));
    }

    public function update(AdminCategoryRequest $request, $id)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Update Category',
            'content' => null,
            'data' => json_encode($request->all())
        ]);
        $category = Category::findOrfail($id);
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
        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Delete Category',
            'content' => null,
            'data' => json_encode($request->all())
        ]);
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
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Update Hot Category',
            'content' => null,
            'data' => json_encode($request->all())
        ]);
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
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Update active Category',
            'content' => null,
            'data' => json_encode($request->all())
        ]);
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
