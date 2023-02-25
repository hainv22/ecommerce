<?php

namespace App\Http\Controllers\Admin;

use App\HelpersClass\Date;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Order;
use App\Models\Product;
// use App\Http\Requests\AdminStatisticalRequest;
// use App\Models\Product;
use App\Models\Transaction;
use App\Models\UseMoneyHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStatisticalController extends Controller
{
    public function index(Request $request)
    {
        $transactionStatusDefault = \config('contants.TRANSACTION_GET_STATUS.default');
        $transactionStatusTransported = \config('contants.TRANSACTION_GET_STATUS.transported');
        $transactionStatusSuccess = \config('contants.TRANSACTION_GET_STATUS.success');
        $transactionStatusCancel = \config('contants.TRANSACTION_GET_STATUS.cancel');

        // Tổng đơn hàng
         $totalTransactions = DB::table('transactions')->select('id')->count();

        // Tổng thành viên
         $totalUsers = DB::table('users')->select('id')->count();

        // Tổng sản phẩm
         $totalProducts = DB::table('products')->select('id')->count();

        //danh sach don hang chua xu ly
         $transactions = Transaction::orderBy('id', 'DESC')->where('tst_status', $transactionStatusDefault)->limit(6)->get();

        //top san pham xem nhieu
//         $topViewProducts = Product::orderByDesc('pro_view')->limit(5)->get();

        //san pham mua nhieu
         $proPayProducts = Product::orderByDesc('pro_pay')->limit(10)->get();

        //user mua nhieu nhat
        $userTransaction = TransacTion::query()
            ->with('user')
        // ->whereYear('tst_order_date', '2021')
            ->select(DB::raw('sum(tst_total_money) as totalMoney'), 'tst_user_id')
            ->groupBy('tst_user_id')
            ->orderBy('totalMoney', 'DESC')
            ->get();

        $moneyTransaction = Transaction::query();
        if (Auth::user()->role != User::ADMIN) {
            $moneyTransaction = $moneyTransaction->where('tst_transaction_role', Transaction::CHUNG);
        }
        $tien_lai = Transaction::query();
        if (Auth::user()->role != User::ADMIN) {
            $tien_lai = $tien_lai->where('tst_transaction_role', Transaction::CHUNG);
        }
        $useMoneyHistory = UseMoneyHistory::query();
        // dd(!($request->dateAfter > $request->dateBefore));
        if (!($request->dateBefore && $request->dateAfter)) {
            $message = '';
            if ($request->day) {
                $moneyTransaction->whereDay('tst_order_date', (int) $request->day);
                $tien_lai->whereDay('tst_order_date', (int) $request->day);
                $useMoneyHistory->whereDay('umh_use_date', (int)$request->day);
            }
            if ($request->month) {
                $moneyTransaction->whereMonth('tst_order_date', (int) $request->month);
                $tien_lai->whereMonth('tst_order_date', (int) $request->month);
                $useMoneyHistory->whereMonth('umh_use_date', (int)$request->month);
            }
            if ($request->year) {
                if (!($request->day) && !($request->month)) {
                    // ngay va thang k co du lieu. group theo thang trong nam
                    $moneyTransaction = $moneyTransaction
                        ->whereYear('tst_order_date', (int) $request->year)
                        ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('MONTH(tst_order_date) as day'))
                        ->groupBy('day')
                        ->distinct()
                        ->get();
                    $tien_lai = $tien_lai
                        ->whereYear('tst_order_date', (int) $request->year)
                        ->select(DB::raw('sum(tst_interest_rate) as totalMoney'), DB::raw('MONTH(tst_order_date) as day'))
                        ->groupBy('day')
                        ->distinct()
                        ->get();
                    $useMoneyHistory = $useMoneyHistory
                        ->where('umh_status', UseMoneyHistory::SU_DUNG_TIEN)
                        ->whereYear('umh_use_date', (int) $request->year)
                        ->select(DB::raw('sum(umh_money) as totalMoney'), DB::raw('MONTH(umh_use_date) as day'))
                        ->groupBy('day')
                        ->distinct()
                        ->get();
                } else {
                    //nguoc lai ngay thang co du lieu group theo ngay
                    $moneyTransaction = $moneyTransaction
                        ->whereYear('tst_order_date', (int) $request->year)
                        ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
                        ->groupBy('day')
                        ->get();
                    $tien_lai = $tien_lai
                        ->whereYear('tst_order_date', (int) $request->year)
                        ->select(DB::raw('sum(tst_interest_rate) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
                        ->groupBy('day')
                        ->get();
                    $useMoneyHistory = $useMoneyHistory
                        ->where('umh_status', UseMoneyHistory::SU_DUNG_TIEN)
                        ->whereYear('umh_use_date', (int) $request->year)
                        ->select(DB::raw('sum(umh_money) as totalMoney'), DB::raw('DATE(umh_use_date) as day'))
                        ->groupBy('day')
                        ->get();
                }
            } else {
                if ($request->day || $request->month)
                //ngay hoac thang co du lieu va nam k co du lieu nhay vao day query
                {
                    $moneyTransaction = $moneyTransaction
                        ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
                        ->groupBy('day')
                        ->get();
                    $tien_lai = $tien_lai
                        ->select(DB::raw('sum(tst_interest_rate) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
                        ->groupBy('day')
                        ->get();
                    $useMoneyHistory = $useMoneyHistory
                        ->where('umh_status', UseMoneyHistory::SU_DUNG_TIEN)
                        ->select(DB::raw('sum(umh_money) as totalMoney'), DB::raw('DATE(umh_use_date) as day'))
                        ->groupBy('day')
                        ->get();
                }

            }
            if (!($request->day) && !($request->month) && !($request->year)) {
                // $moneyTransaction->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', date('Y'));
                $moneyTransaction = $moneyTransaction
                    ->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', date('Y'))
                    ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
                    ->groupBy('day')
                    ->get();
                $tien_lai = $tien_lai
                    ->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', date('Y'))
                    ->select(DB::raw('sum(tst_interest_rate) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
                    ->groupBy('day')
                    ->get();
                $useMoneyHistory = $useMoneyHistory
                    ->where('umh_status', UseMoneyHistory::SU_DUNG_TIEN)
                    ->whereMonth('umh_use_date', date('m'))->whereYear('umh_use_date', date('Y'))
                    ->select(DB::raw('sum(umh_money) as totalMoney'), DB::raw('DATE(umh_use_date) as day'))
                    ->groupBy('day')
                    ->get();
            }

            // $moneyTransaction = $moneyTransaction
            //     ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
            //     ->groupBy('day')
            //     ->get();
            $totalMoneyTransaction = $moneyTransaction->sum('totalMoney');
            $tien_lai_total = $tien_lai->sum('totalMoney');
            $useMoneyHistoryTotal = $useMoneyHistory->sum('totalMoney');
        } else {
            if ($request->dateAfter >= $request->dateBefore) {
                $moneyTransaction = $moneyTransaction
                    ->whereBetween(DB::raw('DATE(tst_order_date)'), [$request->dateBefore, $request->dateAfter])
                    ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
                    ->groupBy('day')
                    ->get();
                $totalMoneyTransaction = $moneyTransaction->sum('totalMoney');

                $tien_lai = $tien_lai
                    ->whereBetween(DB::raw('DATE(tst_order_date)'), [$request->dateBefore, $request->dateAfter])
                    ->select(DB::raw('sum(tst_interest_rate) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
                    ->groupBy('day')
                    ->get();
                $tien_lai_total = $tien_lai->sum('totalMoney');
                // dd($moneyTransaction);
            } else {
                $request->session()->flash('toastr', [
                    'type' => 'error',
                    'message' => 'chọn ngày sai !',
                ]);

                return redirect()->back();
            }
        }

        //thong ke don hang
        //tiep nhan
        $transactionDefault = 0;
        //dang van chuyen
        $transactionProcess = 0;
        // thanh cong
        $transactionSuccess = 0;
        // cancel
        $transactionCancel = 0;


        $productsTop = Order::with('product');
        if (Auth::user()->role != User::ADMIN) {
            $productsTop = $productsTop->whereIn('od_transaction_id', Transaction::query()->where('tst_transaction_role', Transaction::CHUNG)->pluck('id')->toArray());
        }

        $revenueTransactionMonthDefault = Transaction::query();
        $revenueTransactionMonthProcess = Transaction::query();
        $revenueTransactionMonthSuccess = Transaction::query();
        $revenueTransactionMonthCancel = Transaction::query();

        if (Auth::user()->role != User::ADMIN) {
            $revenueTransactionMonthDefault = $revenueTransactionMonthDefault->where('tst_transaction_role', Transaction::CHUNG);
            $revenueTransactionMonthProcess = $revenueTransactionMonthProcess->where('tst_transaction_role', Transaction::CHUNG);
            $revenueTransactionMonthSuccess = $revenueTransactionMonthSuccess->where('tst_transaction_role', Transaction::CHUNG);
            $revenueTransactionMonthCancel = $revenueTransactionMonthCancel->where('tst_transaction_role', Transaction::CHUNG);
        }

        $revenueTransactionMonthDefault = $revenueTransactionMonthDefault->where('tst_status', (int) $transactionStatusDefault);
        // doanh thu Đang vận chuyển
        $revenueTransactionMonthProcess = $revenueTransactionMonthProcess->where('tst_status', (int) $transactionStatusTransported);
        //doanh thu theo thang da xu ly
        $revenueTransactionMonthSuccess = $revenueTransactionMonthSuccess->where('tst_status', (int) $transactionStatusSuccess);
        // doanh thu đã hủy bỏ
        $revenueTransactionMonthCancel = $revenueTransactionMonthCancel->where('tst_status', (int) $transactionStatusCancel);
        $transactionDefault = Transaction::where('tst_status', (int) $transactionStatusDefault);
        //dang van chuyen
        $transactionProcess = Transaction::where('tst_status', (int) $transactionStatusTransported);
        // thanh cong
        $transactionSuccess = Transaction::where('tst_status', (int) $transactionStatusSuccess);
        // cancel
        $transactionCancel = Transaction::where('tst_status', (int) $transactionStatusCancel);
        if (Auth::user()->role != User::ADMIN) {
            $transactionDefault = $transactionDefault->where('tst_transaction_role', Transaction::CHUNG);
            $transactionProcess = $transactionProcess->where('tst_transaction_role', Transaction::CHUNG);
            $transactionSuccess = $transactionSuccess->where('tst_transaction_role', Transaction::CHUNG);
            $transactionCancel = $transactionCancel->where('tst_transaction_role', Transaction::CHUNG);
        }





        $month = date('m');
        $year = date('Y');

        if ($request->month) {
            $productsTop->whereMonth('created_at', (int) $request->month);
            // doanh thu moi tiep nhan
            $revenueTransactionMonthDefault->whereMonth('tst_order_date', (int) $request->month);
            // doanh thu Đang vận chuyển
            $revenueTransactionMonthProcess->whereMonth('tst_order_date', (int) $request->month);
            //doanh thu theo thang da xu ly
            $revenueTransactionMonthSuccess->whereMonth('tst_order_date', (int) $request->month);
            // doanh thu đã hủy bỏ
            $revenueTransactionMonthCancel->whereMonth('tst_order_date', (int) $request->month);

            $transactionDefault->whereMonth('tst_order_date', (int) $request->month);
            //dang van chuyen
            $transactionProcess->whereMonth('tst_order_date', (int) $request->month);
            // thanh cong
            $transactionSuccess->whereMonth('tst_order_date', (int) $request->month);
            // cancel
            $transactionCancel->whereMonth('tst_order_date', (int) $request->month);
            $month = $request->month;
            $mt = $month . ' năm ' . $year;
        }
        if($request->year){
            $productsTop->whereYear('created_at', (int) $request->year);
            // doanh thu moi tiep nhan
            $revenueTransactionMonthDefault->whereYear('tst_order_date', (int) $request->year);
            // doanh thu Đang vận chuyển
            $revenueTransactionMonthProcess->whereYear('tst_order_date', (int) $request->year);
            //doanh thu theo thang da xu ly
            $revenueTransactionMonthSuccess->whereYear('tst_order_date', (int) $request->year);
            // doanh thu đã hủy bỏ
            $revenueTransactionMonthCancel->whereYear('tst_order_date', (int) $request->year);

            $transactionDefault->whereYear('tst_order_date', (int) $request->year);
            //dang van chuyen
            $transactionProcess->whereYear('tst_order_date', (int) $request->year);
            // thanh cong
            $transactionSuccess->whereYear('tst_order_date', (int) $request->year);
            // cancel
            $transactionCancel->whereYear('tst_order_date', (int) $request->year);
            $year = (int) $request->year;
            $mt = empty($request->month)? ' năm ' . $year : $request->month . ' năm ' . $year;;
        }
        if(!($request->year) && !($request->month)){
            $month = date('m');
            $year = date('Y');
            $productsTop->whereMonth('created_at', (int) $month)->whereYear('created_at', (int) $year);
            // doanh thu moi tiep nhan
            $revenueTransactionMonthDefault->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', (int) $year);
            // doanh thu Đang vận chuyển
            $revenueTransactionMonthProcess->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', (int) $year);
            //doanh thu theo thang da xu ly
            $revenueTransactionMonthSuccess->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', (int) $year);
            // doanh thu đã hủy bỏ
            $revenueTransactionMonthCancel->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', (int) $year);

            $mt = $month . ' năm ' . $year;

            $transactionDefault->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', (int) $year);
            //dang van chuyen
            $transactionProcess->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', (int) $year);
            // thanh cong
            $transactionSuccess->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', (int) $year);
            // cancel
            $transactionCancel->whereMonth('tst_order_date', date('m'))->whereYear('tst_order_date', (int) $year);
        }


        if(!empty($request->year) && empty($request->month)){
            $revenueTransactionMonthDefault = $revenueTransactionMonthDefault
                ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('MONTH(tst_order_date) day'))
                ->groupBy('day')
                ->get()->toArray();
            // doanh thu Đang vận chuyển
            $revenueTransactionMonthProcess = $revenueTransactionMonthProcess
                ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('MONTH(tst_order_date) day'))
                ->groupBy('day')
                ->get()->toArray();
            //doanh thu theo thang da xu ly
            $revenueTransactionMonthSuccess = $revenueTransactionMonthSuccess
                ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('MONTH(tst_order_date) day'))
                ->groupBy('day')
                ->get()->toArray();
            // doanh thu đã hủy bỏ
            $revenueTransactionMonthCancel = $revenueTransactionMonthCancel
                ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('MONTH(tst_order_date) day'))
                ->groupBy('day')
                ->get()->toArray();
        } else {
            $revenueTransactionMonthDefault = $revenueTransactionMonthDefault
                ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) day'))
                ->groupBy('day')
                ->get()->toArray();
            // doanh thu Đang vận chuyển
            $revenueTransactionMonthProcess = $revenueTransactionMonthProcess
                ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) day'))
                ->groupBy('day')
                ->get()->toArray();
            //doanh thu theo thang da xu ly
            $revenueTransactionMonthSuccess = $revenueTransactionMonthSuccess
                ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) day'))
                ->groupBy('day')
                ->get()->toArray();
            // doanh thu đã hủy bỏ
            $revenueTransactionMonthCancel = $revenueTransactionMonthCancel
                ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) day'))
                ->groupBy('day')
                ->get()->toArray();
        }
        $transactionDefault = $transactionDefault->select('id')->count();
        //dang van chuyen
        $transactionProcess = $transactionProcess->select('id')->count();
        // thanh cong
        $transactionSuccess = $transactionSuccess->select('id')->count();
        // cancel
        $transactionCancel = $transactionCancel->select('id')->count();

















        if(!empty($request->year) && empty($request->month)){
            $listDay = Date::getListDayInMonth(null, $year, null);
        } else {
            $listDay = Date::getListDayInMonth($month, $year, null);
        }
        $arrRevenueTransactionMonthDefault = [];
        $arrRevenueTransactionMonthProcess = [];
        $arrRevenueTransactionMonthSuccess = [];
        $arrRevenueTransactionMonthCancel = [];

        foreach ($listDay as $day) {
            $total = 0;
            foreach ($revenueTransactionMonthDefault as $key => $revenue) {
                if ($revenue['day'] == $day) {
                    $total = $revenue['totalMoney'];
                    break;
                }
            }
            $arrRevenueTransactionMonthDefault[] = (int) $total;

            $total = 0;
            foreach ($revenueTransactionMonthProcess as $key => $revenue) {
                if ($revenue['day'] == $day) {
                    $total = $revenue['totalMoney'];
                    break;
                }
            }
            $arrRevenueTransactionMonthProcess[] = (int) $total;

            $total = 0;
            foreach ($revenueTransactionMonthSuccess as $key => $revenue) {
                if ($revenue['day'] == $day) {
                    $total = $revenue['totalMoney'];
                    break;
                }
            }
            $arrRevenueTransactionMonthSuccess[] = (int) $total;

            $total = 0;
            foreach ($revenueTransactionMonthCancel as $key => $revenue) {
                if ($revenue['day'] == $day) {
                    $total = $revenue['totalMoney'];
                    break;
                }
            }
            $arrRevenueTransactionMonthCancel[] = (int) $total;
        }
        if(!empty($request->year) && empty($request->month)){
            $listDay = Date::getListDayInMonth($month, $year, 1);
        }
        // dd($arrRevenueTransactionMonthDefault);
        // $moneyTransaction = $moneyTransaction
        // ->whereBetween(DB::raw('DATE(tst_order_date)'), [$request->dateBefore, $request->dateAfter])
        // ->select(DB::raw('sum(tst_total_money) as totalMoney'), DB::raw('DATE(tst_order_date) as day'))
        // ->groupBy('day')
        // ->get();
        // top product t7
        $productsTop = $productsTop->select(DB::raw('sum(od_qty) as total, od_product_id'))
            ->groupBy('od_product_id')
            ->orderBy('total', 'DESC')
            ->take(5)
            ->get();

        $statusTransaction = [
            ['Tiếp Nhận', $transactionDefault, false],
            ['Đang Vận Chuyển', $transactionProcess, false],
            ['Bàn Giao', $transactionSuccess, false],
            ['Hủy bỏ', $transactionCancel, false],
        ];
        $viewData = [
             'totalTransactions' => $totalTransactions,
             'totalUsers' => $totalUsers,
             'totalProducts' => $totalProducts,
             'transactions' => $transactions,
//             'topViewProducts' => $topViewProducts,
             'proPayProducts' => $proPayProducts,
            'userTransaction' => $userTransaction,
            'mt' => $mt,
            'moneyTransaction' => $moneyTransaction,
            'tien_lai_total' => $tien_lai_total,
            'tien_lai' => $tien_lai,
            'totalMoneyTransaction' => $totalMoneyTransaction,
            'statusTransaction' => json_encode($statusTransaction),
            'listDay' => json_encode($listDay),
            'arrRevenueTransactionMonthDefault' => json_encode($arrRevenueTransactionMonthDefault),
            'arrRevenueTransactionMonthProcess' => json_encode($arrRevenueTransactionMonthProcess),
            'arrRevenueTransactionMonthSuccess' => json_encode($arrRevenueTransactionMonthSuccess),
            'arrRevenueTransactionMonthCancel' => json_encode($arrRevenueTransactionMonthCancel),
            'productsT7' => $productsTop,
            'useMoneyHistoryTotal' => $useMoneyHistoryTotal,
            'useMoneyHistory' => $useMoneyHistory,
        ];
        if($request->check == null)
        {
            Log::create([
                'user_id' => Auth::id(),
                'type' => 'View Màn Thống Kê Tổng',
                'content' => null,
                'data' => json_encode($request->all())
            ]);
            return view('admin.statistical.index', $viewData);
        } else {
            Log::create([
                'user_id' => Auth::id(),
                'type' => 'View Màn Thống Kê Trừ Tiền',
                'content' => null,
                'data' => json_encode($request->all())
            ]);
            $data = UseMoneyHistory::query();
            if ($type_status = $request->type_use_money)
            {
                $data = $data->where('umh_status', $type_status);
            } else {
                $data = $data->whereIn('umh_status', [
                    UseMoneyHistory::SU_DUNG_TIEN,
                    UseMoneyHistory::TRA_TRUNG_QUOC,
                    UseMoneyHistory::MUA_BANG_DINH,
                    UseMoneyHistory::TRA_TIEN_DAU_BAO_HN_BN,
                    UseMoneyHistory::TRA_TIEN_VAN_CHUYEN_TQ_HN
                ]);
            }
            if ($year_use_money = $request->year_use_money)
            {
                $data = $data->whereYear('umh_use_date', $year_use_money);
            }
            if ($month_use_money = $request->month_use_money)
            {
                $data = $data->WhereMonth('umh_use_date', $month_use_money);
            }
            $data = $data->orderByDesc('created_at')->get();
            $total = $data->sum('umh_money');
            $viewData = [
                'data' => $data,
                'total' => $total
            ];
            return view('admin.statistical.use-money-history', $viewData);
        }
    }

    public function withdraw(Request $request)
    {
        Log::create([
            'user_id' => Auth::id(),
            'type' => 'Sử dụng Chức Năng Trừ Tiền',
            'content' => null,
            'data' => json_encode($request->all())
        ]);
        DB::beginTransaction();
        try {
            UseMoneyHistory::create([
                'umh_money' => (int)$request->money_withdraw,
                'umh_content' => $request->content_withdraw,
                'umh_use_date' => $request->date_withdraw,
                'umh_status' => $request->status
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
        $request->session()->flash('toastr', [
            'type'      => "success",
            'message'   => "Update số tiền thành công"
        ]);
        return response([
            'code' => 200,
            'message' => 'Update số tiền thành công',
            'data' => ''
        ]);
    }
}
