@extends('layout.master_admin')
@section('content')
<section class="content-header">
    <h1>
      Quản lý đơn hàng
      <small>index</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('admin.transaction.index') }}">transaction</a></li>
      <li class="active">list</li>

    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                  <h3 class="box-title"><a href="{{ route('admin.transaction.create') }}" class="btn btn-primary">Thêm mới </a></h3>
              </div>
                <div class="box-title">
                    <form action="" method="GET" class="form-inline">
                        <input type="text" value="{{ Request::get('transaction_id') }}" class="form-control" name="transaction_id" placeholder="Transaction ID">
                        <select name="user_id" class="js-example-basic-single form-control">
                            <option value="0">__ Chọn Khách Hàng __</option>
                            @if (isset($users))
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" {{ Request::get('user_id') == $user->id ? "selected='selected'" : "" }}>{{$user->name}} - @if(\Auth::user()->role == \App\Models\User::ADMIN) {{$user->phone}} @endif</option>
                                @endforeach
                            @endif
                        </select>
                        <input type="text" value="{{ Request::get('phone') }}" class="form-control" name="phone" placeholder="phone">
                        <input type="date" value="{{ Request::get('date') }}" class="form-control" name="date" placeholder="date">
                        <select name="status" class="form-control">
                            <option value="0">__Trạng Thái__</option>
                            <option value="1" {{ Request::get('status') == 1 ? "selected='selected'" : "" }}>Tiếp Nhận</option>
                            <option value="2" {{ Request::get('status') == 2 ? "selected='selected'" : "" }}>Đang Vận Chuyển</option>
                            <option value="3" {{ Request::get('status') == 3 ? "selected='selected'" : "" }}>Đã Bàn Giao</option>
                            <option value="-1" {{ Request::get('status') == -1 ? "selected='selected'" : "" }}>Hủy Bỏ</option>
                        </select>
                        <select name="transaction_role" class="form-control">
                            <option value="0">__Role__</option>
                            <option value="1" {{ Request::get('transaction_role') == 1 ? "selected='selected'" : "" }}>Riêng</option>
                            <option value="2" {{ Request::get('transaction_role') == 2 ? "selected='selected'" : "" }}>Chung</option>
                        </select>
                        <input type="text" value="{{ Request::get('kg') }}" class="form-control" name="kg" placeholder="kg">
                        <input type="text" value="{{ Request::get('code_order') }}" class="form-control" name="code_order" placeholder="Mã đơn bên thứ 3">
                        <select name="month" class="form-control">
                            <option value="">__ Tháng __</option>
                            @for($i=1;$i<=12;$i++)
                                <option value="{{$i}}" {{ Request::get('month') == $i ? "selected='selected'" : "" }}>Tháng {{$i}}</option>
                            @endfor
                        </select>
                        <select name="year" class="form-control">
                            <option value="">__ Năm __</option>
                            <option value="2022" {{ Request::get('year') == 2022 ? "selected='selected'" : "" }}>Năm 2022</option>
                            <option value="2023" {{ Request::get('year') == 2023 ? "selected='selected'" : "" }}>Năm 2023</option>
                        </select>
                        <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Search</button>
                    </form>
                </div><br>
                <div class="box-title">
                    <span>Tổng đơn hàng: <span style="color:red; font-size:20px">{{ count($transactions) }}</span></span>
                </div>
                <?php
                    $chung = 0;
                    $rieng = 0;
                ?>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                      <th>ID</th>
                      <th>Info</th>
                      {{-- <th>Account</th> --}}
                        <th>Money</th>
                        <th>Trạng thái thanh toán</th>
                        <th>Status</th>
                        <th>Trạng thái lock</th>
                      <th>Time</th>
                      <th>Action</th>
                    </tr>
                    @if(isset($transactions))
                        @foreach ($transactions as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <ul>
                                        <li>Name: {{ $item->user->name }}</li>
                                        <li>Email: {{ $item->user->email }}</li>
                                        @if(\Auth::user()->role == \App\Models\User::ADMIN) <li>Phone: {{ $item->user->phone }} </li> @endif
                                        <li>Address: {{ $item->user->address }}</li>
                                        <li>Mã đơn bên 3: {{ $item->tst_code_order }}</li>
                                        @if(\Auth::user()->role == \App\Models\User::ADMIN)
                                        <a target="_blank" href="{{ route('admin.user.detail',$item->user->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-user-circle"></i>User</a>
                                        @endif
                                        <a target="_blank" href="{{ route('admin.transaction.view.print',$item->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-download"></i>Print</a>
                                        <a target="_blank" href="{{ route('admin.transaction.detail',$item->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i>View</a>
                                    </ul>
                                </td>

                                <td>
                                    <ul>
                                    <?php
                                        $total_transport = 0;
                                        $check_money = false;
                                        $check_money_transport = false;
                                        if($item->tst_total_paid == $item->tst_total_money) {
                                            $check_money = true;
                                        }
                                        foreach ($item->baos as $bao) {
                                            if(!empty($bao->b_success_date)) {
                                                $total_transport += ($bao->b_weight * $bao->b_fee);
                                            } else {
                                                $total_transport += ($bao->b_weight * $bao->transport->tp_fee);
                                            }
                                        }
                                        if($item->total_transport_paid - $total_transport == 0) {
                                            $check_money_transport = true;
                                        }
                                    ?>
                                        <li>Tiền hàng: {{ number_format($item->tst_total_money,0,',','.') }}</li>
                                        <li>Tiền lãi: {{ number_format($item->tst_interest_rate,0,',','.') }}</li>
                                        @if(\Auth::user()->role == \App\Models\User::ADMIN)
                                            <li>Tiền vận chuyển: {{ number_format($total_transport,0,',','.') }}</li>
                                        @endif
                                        <li>Tổng :
                                        <span class="label label-success">
                                       {{ number_format($item->tst_total_money + $total_transport,0,',','.') }}
                                    </span></li>
                                        @if(\Auth::user()->role == \App\Models\User::ADMIN)
                                            số cân: @foreach($item->baos as $bao) - {{$bao->b_weight}} - @endforeach
                                            @if($item->tst_transaction_role == \App\Models\Transaction::ADMIN)
                                            <?php $rieng++ ?>
                                                <li><span class="label label-success">Riêng</span></li>
                                            @else
                                            <?php $chung++ ?>
                                                <li><span class="label label-warning">Chung</span></li>
                                            @endif
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                        @if($check_money && $check_money_transport)
                                            <span class="label label-success">
                                                Đã thanh toán hết
                                            </span>
                                        @else
                                            <span class="label label-danger">
                                                Chưa Thanh Toán Xong
                                            </span>
                                        @endif
                                </td>
                                <td>
                                    <span class="label label-{{ $item->getStatus($item->tst_status)['class'] }}">
                                        {{ $item->getStatus($item->tst_status)['name'] }}
                                    </span>
                                </td>
                                <td>
                                    <input type="checkbox" data-url-lock="{{route('admin.transaction.update.lock', $item->id)}}" onchange="update_tst_lock(this)" id="js_update_tst_lock" {{$item->tst_lock ==1 ? 'checked' : ''}} data-toggle="toggle" data-onstyle="danger" data-width="50" data-height="40">
                                </td>
                                <td>Ngày tạo: {{ date("d/m/Y H:i:s", strtotime($item->created_at)) }} <br>
                                    Ngày đặt hàng: {{date("d/m/Y", strtotime($item->tst_order_date))}}</td>
                                <td>
                                    <a href="{{ route('admin.transaction.detail',$item->id) }}" class="btn btn-md btn-info js-preview-transaction"><i class="fa fa-eye"></i>View</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
              {!! $transactions->appends($query)->links() !!}
              <div></div>
              <div class="box-title">
                    <span>Tổng đơn hàng: <span style="color:red; font-size:20px">{{ count($transactions) }}</span></span> <br>
                    <span>Đơn chung:  {{$chung}}</span> <br>
                    <span>Đơn riêng: {{$rieng}}</span> <br>
                </div>
            </div>
            <!-- /.box -->
          </div>
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <!-- /.row (main row) -->
  </section>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css"  />
<script src="https://code.jquery.com/jquery-3.2.1.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js" ></script>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endsection
@section('script')
    <script>
        function update_tst_lock(element) {
            let preStatus = $(element).prop('checked');
            let URL = $(element).attr('data-url-lock');
            if (URL) {
                $.ajax({
                    url: URL,
                    success: function (results) {
                        if (results.code == 200) {
                            location.reload();
                        }
                        console.log(results)
                    },
                    error: function (error) {
                        console.log(error.messages);
                    }
                });
            }
        }
    </script>
@endsection
