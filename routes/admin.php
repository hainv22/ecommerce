<?php

use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminRatingController;
use App\Http\Controllers\Admin\AdminSlideController;
use App\Http\Controllers\Admin\AdminStatisticalController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminTransportController;
use App\Http\Controllers\Admin\AdminTypeProductController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Route::get('login', [AdminLoginController::class, 'index'])->middleware('guest')->name('login');
    Route::post('login', [AdminLoginController::class, 'postLoginAdmin'])->name('post.login.admin')->middleware('guest');
    Route::get('logout', [AdminLoginController::class, 'getLogoutAdmin'])->name('get.logout.admin');
});
//Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'check_role:' . config('contants.ROLE.ADMIN')]], function () {
//    \UniSharp\LaravelFilemanager\Lfm::routes();
//});
Route::middleware(['auth'])->group(function () {
    Route::group(['prefix' => 'admin-ecommerce'], function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('admin.index');
        Route::get('ajax-read-notify/{id}', [AdminHomeController::class, 'readNotify'])->name('ajax.read.notify');

        // Route::get('ajax-read-notify-all', [AdminHomeController::class, 'readNotifyAll'])->name('ajax.read.notify.all');

        Route::group(['prefix' => 'category'], function () {
            Route::get('/', [AdminCategoryController::class, 'index'])->name('admin.category.index');
            Route::get('create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
            Route::post('create', [AdminCategoryController::class, 'store'])->name('admin.category.store');

            Route::get('update/{id}', [AdminCategoryController::class, 'edit'])->name('admin.category.edit');
            Route::post('update/{id}', [AdminCategoryController::class, 'update'])->name('admin.category.update');

            Route::get('delete/{id}', [AdminCategoryController::class, 'delete'])->name('admin.category.delete');
            Route::get('active/{id}', [AdminCategoryController::class, 'active'])->name('admin.category.active');
            Route::get('hot/{id}', [AdminCategoryController::class, 'hot'])->name('admin.category.hot');
            // Route::get('ajax-search-table',[AdminCategoryController::class,'ajax_search_table'])->name('ajax.admin.category.search');
        });



        Route::group(['prefix' => 'product'], function () {
            Route::get('/', [AdminProductController::class, 'index'])->name('admin.product.index');
            Route::get('create', [AdminProductController::class, 'create'])->name('admin.product.create');
            Route::post('create', [AdminProductController::class, 'store'])->name('admin.product.store');

            Route::get('update/{id}', [AdminProductController::class, 'edit'])->name('admin.product.edit');
            Route::post('update/{id}', [AdminProductController::class, 'update'])->name('admin.product.update');

            Route::get('delete/{id}', [AdminProductController::class, 'delete'])->name('admin.product.delete');
            Route::get('active/{id}', [AdminProductController::class, 'active'])->name('admin.product.active');
            Route::get('hot/{id}', [AdminProductController::class, 'hot'])->name('admin.product.hot');

            Route::get('delete-image/{id}', [AdminProductController::class, 'deleteImage'])->name('admin.product.delete_image');
            Route::get('get-type-product/{categoryId?}', [AdminProductController::class, 'getTypeProduct'])->name('admin.product.get.typeproduct');
        });



        Route::group(['prefix' => 'transaction'], function () {
            Route::get('/', [AdminTransactionController::class, 'index'])->name('admin.transaction.index');
            Route::get('/create', [AdminTransactionController::class, 'create'])->name('admin.transaction.create');
            Route::post('/create', [AdminTransactionController::class, 'store'])->name('admin.transaction.store');
            Route::get('delete/{id}', [AdminTransactionController::class, 'delete'])->name('admin.transaction.delete');
            Route::get('view/{id}', [AdminTransactionController::class, 'getTransactionDetail'])->name('admin.transaction.detail');
            Route::post('view/{id}', [AdminTransactionController::class, 'update'])->name('admin.transaction.update');
            Route::get('update-success-date/{id}', [AdminTransactionController::class, 'updateSuccessDate'])->name('admin.transaction.update.success.date');
            Route::get('order-delete/{id}', [AdminTransactionController::class, 'order_detail_delete'])->name('admin.order_detail.delete');
            Route::get('action/{action}/{id}', [AdminTransactionController::class, 'getAction'])->name('admin.transaction.action');
        });


        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('admin.user.index');
            Route::get('/create', [AdminUserController::class, 'create'])->name('admin.user.create');
            Route::post('/store', [AdminUserController::class, 'store'])->name('admin.user.store');
            Route::get('/update/{id}', [AdminUserController::class, 'edit'])->name('admin.user.update');
            Route::post('/update/{id}', [AdminUserController::class, 'update']);
            Route::get('/delete/{id}', [AdminUserController::class, 'delete'])->name('admin.user.delete');
        });

        Route::group(['prefix' => 'transport'], function () {
            Route::get('/', [AdminTransportController::class, 'index'])->name('admin.transport.index');
            Route::get('/create', [AdminTransportController::class, 'create'])->name('admin.transport.create');
            Route::post('/create', [AdminTransportController::class, 'store'])->name('admin.transport.store');
            Route::get('/update/{id}', [AdminTransportController::class, 'edit'])->name('admin.transport.update');
            Route::post('/update/{id}', [AdminTransportController::class, 'update']);
            Route::get('/delete/{id}', [AdminTransportController::class, 'destroy'])->name('admin.transport.delete');
        });

        Route::group(['prefix' => 'statistical'], function () {
            Route::get('/', [AdminStatisticalController::class, 'index'])->name('admin.statistical.index');
        });
    });
});
