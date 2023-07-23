<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\HelpersClass\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        $transactionStatusDefault = \config('contants.TRANSACTION_GET_STATUS.default');
        $transactionStatusTransported = \config('contants.TRANSACTION_GET_STATUS.transported');
        $transactionStatusSuccess = \config('contants.TRANSACTION_GET_STATUS.success');
        $transactionStatusCancel = \config('contants.TRANSACTION_GET_STATUS.cancel');

        // Tổng đơn hàng
        $totalTransactions  = DB::table('transactions')->select('id')->count();

        // Tổng thành viên
        $totalUsers         = DB::table('users')->select('id')->count();

        // Tổng sản phẩm
        $totalProducts      = DB::table('products')->select('id')->count();

        //danh sach don hang chua xu ly
        $transactions       = Transaction::orderBy('id', 'DESC')->where('tst_status', $transactionStatusDefault)->limit(6)->get();

        //san pham mua nhieu
        $proPayProducts    = Product::orderByDesc('pro_pay')->limit(5)->get();

        $moneyTransaction = Transaction::query();

        $message = '';
        if ($request->day) {
            $moneyTransaction->whereDay('created_at', (int)$request->day);
        }
        if ($request->month) {
            $moneyTransaction->whereMonth('created_at', (int)$request->month);
        }
        if ($request->year) {
            if (!($request->day) && !($request->month)) {
                // ngay va thang k co du lieu. group theo thang trong nam
                $moneyTransaction = $moneyTransaction
                    ->whereYear('created_at', (int)$request->year)
                    ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('MONTH(created_at) as day'))
                    ->groupBy('day')
                    ->distinct()
                    ->get();
            } else {
                //nguoc lai ngay thang co du lieu group theo ngay
                $moneyTransaction = $moneyTransaction
                    ->whereYear('created_at', (int)$request->year)
                    ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(created_at) as day'))
                    ->groupBy('day')
                    ->get();
            }
        } else {
            if ($request->day || $request->month)
                //ngay hoac thang co du lieu va nam k co du lieu nhay vao day query
                $moneyTransaction = $moneyTransaction
                    ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(created_at) as day'))
                    ->groupBy('day')
                    ->get();
        }
        if (!($request->day) && !($request->month) && !($request->year)) {
            // $moneyTransaction->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
            $moneyTransaction = $moneyTransaction
                ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
                ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(created_at) as day'))
                ->groupBy('day')
                ->get();
        }

        $transactionHistories = TransactionHistory::with('transaction.user')->orderBy('id', 'DESC')->paginate(50);



        // $moneyTransaction = $moneyTransaction
        //     ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(created_at) as day'))
        //     ->groupBy('day')
        //     ->get();
        $totalMoneyTransaction = $moneyTransaction->sum('totalMoney');


        // //thong ke don hang
        // //tiep nhan
        // $transactionDefault = Transaction::where('tst_status', 1)->select('id')->count();
        // //dang van chuyen
        // $transactionProcess = Transaction::where('tst_status', 2)->select('id')->count();
        // // thanh cong
        // $transactionSuccess = Transaction::where('tst_status', 3)->select('id')->count();
        // // cancel
        // $transactionCancel = Transaction::where('tst_status', -1)->select('id')->count();
        // $statusTransaction = [
        //     ['Tiếp Nhận', $transactionDefault, false],
        //     ['Đang Vận Chuyển', $transactionProcess, false],
        //     ['Bàn Giao', $transactionSuccess, false],
        //     ['Hủy bỏ', $transactionCancel, false],
        // ];


        // //doanh thu theo thang da xu ly
        // $revenueTransactionMonth = Transaction::where('tst_status', 3)
        //     ->whereMonth('created_at', date('m'))
        //     ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(created_at) day'))
        //     ->groupBy('day')
        //     ->get()->toArray();
        // // doanh thu moi tiep nhan
        // $revenueTransactionMonthDefault = Transaction::where('tst_status', 1)
        //     ->whereMonth('created_at', date('m'))
        //     ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(created_at) day'))
        //     ->groupBy('day')
        //     ->get()->toArray();
        // $listDay = Date::getListDayInMonth();
        // $arrRevenueTransactionMonth = [];
        // $arrRevenueTransactionMonthDefault = [];
        // foreach ($listDay as $day) {
        //     $total = 0;
        //     foreach ($revenueTransactionMonth as $key => $revenue) {
        //         if ($revenue['day'] == $day) {
        //             $total = $revenue['totalMoney'];
        //             break;
        //         }
        //     }
        //     $arrRevenueTransactionMonth[] = (int)$total;

        //     $total = 0;
        //     foreach ($revenueTransactionMonthDefault as $key => $revenue) {
        //         if ($revenue['day'] == $day) {
        //             $total = $revenue['totalMoney'];
        //             break;
        //         }
        //     }
        //     $arrRevenueTransactionMonthDefault[] = (int)$total;
        // }
        $logs = Log::with('user');
        $users = User::query()
            ->whereIn(
                'id',
                DB::table('logs')->select('user_id')->groupBy('user_id')->pluck('user_id')->toArray()
            )->get();

        if ($id_log= $request->id_log) {
            $logs->where('id', $id_log);
        }
        if ($user_id= $request->user_id) {
            $logs->where('user_id', $user_id);
        }
        if ($date= $request->date) {
            $logs->whereDate('created_at', $date);
        }
        if ($data= $request->data) {
            $logs->where('data', 'like', '%' . $data . '%');
        }
        if ($action= $request->action) {
            $logs->where('type', 'like', '%' . $action . '%');
        }
        $logs = $logs->orderByDesc('id')->paginate((int)config('contants.PER_PAGE_DEFAULT_ADMIN'));

        $viewData = [
            'totalTransactions' => $totalTransactions,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'transactions' => $transactions,
            'proPayProducts' => $proPayProducts,
            'moneyTransaction' => $moneyTransaction,
            'totalMoneyTransaction' => $totalMoneyTransaction,
            // 'statusTransaction' => json_encode($statusTransaction),
            // 'listDay'                               => json_encode($listDay),
            // 'arrRevenueTransactionMonth'            => json_encode($arrRevenueTransactionMonth),
            // 'arrRevenueTransactionMonthDefault'     => json_encode($arrRevenueTransactionMonthDefault),
            'transactionHistories' => $transactionHistories,
            'logs' => $logs,
            'query'         => $request->query(),
            'users' => $users
        ];
        return view('admin.index', $viewData);
    }

    public function readNotify(Request $request, $id)
    {
        if ($request->ajax()) {
            $notify = DB::table('notifications')->where('id', $id)->update(['read_at' => Carbon::now()]);
            return true;
        }
    }

    // public function readNotifyAll(Request $request)
    // {
    //     if($request->ajax()) {
    //         $notifies = DB::table('notifications')->where('read_at', NULL)->update(array('read_at' => Carbon::now()));
    //         // return response([
    //         //     'notifies' => $notifies
    //         // ]);
    //         // foreach($notifies as $notify) {
    //         //     $notify->update(['read_at' => Carbon::now()]);
    //         // }
    //         return true;
    //     }
    // }
}
