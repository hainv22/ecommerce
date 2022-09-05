<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt "Tohoma";
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .page {
            width: 21cm;
            overflow:hidden;
            min-height:297mm;
            padding: 1.5cm;
            margin-left:auto;
            margin-right:auto;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .subpage {
            padding: 1cm;
            border: 5px red solid;
            height: 237mm;
            outline: 2cm #FFEAEA solid;
        }
        @page {
            size: A4;
            margin: 0;
        }
        button {
            width:100px;
            height: 24px;
        }
        .header {
            overflow:hidden;
        }
        .logo {
            background-color:#FFFFFF;
            text-align:left;
            float:left;
        }
        .company {
            padding-top:24px;
            text-transform:uppercase;
            background-color:#FFFFFF;
            text-align:right;
            float:right;
            font-size:16px;
        }
        .title {
            text-align:center;
            position:relative;
            color:#0000FF;
            font-size: 24px;
            top:1px;
        }
        .footer-left {
            text-align:center;
            text-transform:uppercase;
            padding-top:24px;
            position:relative;
            height: 150px;
            width:50%;
            color:#000;
            float:left;
            font-size: 13px;
            bottom:1px;
        }
        .footer-right {
            text-align:center;
            text-transform:uppercase;
            padding-top:24px;
            position:relative;
            height: 150px;
            width:50%;
            color:#000;
            font-size: 13px;
            float:right;
            bottom:1px;
        }
        .TableData {
            background:#ffffff;
            font: 11px;
            width:100%;
            border-collapse:collapse;
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size:12px;
            border:thin solid #d3d3d3;
        }
        .TableData TH {
            background: rgba(0,0,255,0.1);
            text-align: center;
            font-weight: bold;
            color: #000;
            border: solid 1px #ccc;
            height: 24px;
        }
        .TableData TR {
            height: 24px;
            border:thin solid #d3d3d3;
        }
        .TableData TR TD {
            padding-right: 2px;
            padding-left: 2px;
            border:thin solid #d3d3d3;
        }
        .TableData TR:hover {
            background: rgba(0,0,0,0.05);
        }
        .TableData .cotSTT {
            text-align:center;
            width: 10%;
        }
        .TableData .cotTenSanPham {
            text-align:left;
            width: 40%;
        }
        .TableData .cotHangSanXuat {
            text-align:left;
            width: 20%;
        }
        .TableData .cotGia {
            text-align:right;
            width: 120px;
        }
        .TableData .cotSoLuong {
            text-align: center;
            width: 50px;
        }
        .TableData .cotSo {
            text-align: right;
            width: 120px;
        }
        .TableData .tong {
            text-align: right;
            font-weight:bold;
            text-transform:uppercase;
            padding-right: 4px;
        }
        .TableData .cotSoLuong input {
            text-align: center;
        }
        @media print {
            @page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
</head>
{{--onload="window.print();"--}}
<body>
<div id="page" class="page">
    <div class="header">
        <div class="company">Kho Hải Anh</div>
    </div>
    <br/>
    <div class="title">
        HÓA ĐƠN THANH TOÁN: {{rand(0000000,9999999)}}-{{$transaction->id}}
        <br/>
        -------oOo-------
    </div>
    <br/>
    <br/>
    <table class="TableData">
        <tr>
            <th>STT</th>
            <th>Tên</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
        @foreach($order as $key => $item)
        <tr>
            <td class="cotSTT">{{++$key}}</td>
            <td class="cotTenSanPham">{{$item->product->pro_name}}</td>
            <td class="cotSoLuong" align='center'>{{ $item->od_qty }}</td>
            <td class="cotGia">{{ number_format($item->od_price,0,',','.') }} đ</td>
            <td class="cotSo">{{ number_format($item->od_price * $item->od_qty,0,',','.') }} đ</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="4" class="tong">
                @if(!empty($total_transport))
                    Tiền Hàng
                @else
                    Tổng
                @endif
            </td>
            <td class="cotSo" style="color: red;font-size: 15px;">{{number_format($transaction->tst_total_money,0,',','.') }} đ</td>
        </tr>
        @if(!empty($total_transport))
            @foreach ($transaction->baos as $key => $item)
            <tr>
                <td class="cotSTT">{{++$key}}</td>
                <td class="cotTenSanPham">{{$item->b_name}}</td>
                <td class="cotSoLuong" align='center'>{{ $item->b_weight }} kg</td>
                <td class="cotGia">{{number_format($item->b_fee,0,',','.') }} đ</td>
                <td class="cotSo">{{number_format($item->b_weight * $item->b_fee,0,',','.') }} đ</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="tong">Tiền Vận Chuyển</td>
                <td class="cotSo" style="color: red;font-size: 15px;">{{number_format($total_transport,0,',','.') }} đ</td>
            </tr>
            <tr>
                <td colspan="4" class="tong">Tổng (tiền hàng + tiền vận chuyển)</td>
                <td class="cotSo" style="color: red;font-size: 15px;">{{number_format($total_transport + $transaction->tst_total_money,0,',','.') }} đ</td>
            </tr>
        @endif
        @if($transaction->tst_total_paid > 0)
            <tr>
                <td colspan="4" class="tong">Đã trả</td>
                <td class="cotSo" style="color: red;font-size: 15px;">{{number_format($transaction->tst_total_paid + $transaction->total_transport_paid,0,',','.') }} đ</td>
            </tr>
            <tr>
                <td colspan="4" class="tong">Còn Nợ</td>
                <td class="cotSo" style="color: red;font-size: 15px;">{{number_format(($transaction->tst_total_money - $transaction->tst_total_paid) + ($total_transport - $transaction->total_transport_paid), 0,',','.')}} đ</td>
            </tr>
        @endif
    </table>
    <div class="footer-left"> Khách hàng <br/> ----- <br/> {{$transaction->user->name}} </div>
    <div class="footer-right"> Lạng Sơn, {{ date("d-m-Y", strtotime($transaction->tst_order_date )) }}<br/>
        Nhân viên <br/>
        Zalo: 036.753.0598
    </div>
</div>
</body>
</html>
