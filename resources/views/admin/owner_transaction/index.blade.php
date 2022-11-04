@extends('layout.master_admin')
@section('content')
    <section class="content-header">
        <h1>
            don china
            <small>index</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.owner-china.index') }}">owner</a></li>
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
                        <h3 class="box-title"><a href="{{ route('admin.owner-china-transactions.create') }}" class="btn btn-primary">Thêm mới </a></h3>
                    </div>
                    <div class="box-title">
                        <form action="" method="GET" class="form-inline">
                            <select name="user_owner_id" class="form-control">
                                <option value="0">__ Chọn Khách Hàng __</option>
                                @if (isset($users))
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" {{ Request::get('user_owner_id') == $user->id ? "selected='selected'" : "" }}>{{$user->oc_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Search</button>
                        </form>
                    </div><br>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <th>Info</th>
                                <th>Money</th>
                                <th>Trạng thái </th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                            @if(isset($transactions))
                                @foreach ($transactions as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            <ul>
                                                <li>Name: {{ $item->owner->oc_name }}</li>
                                                <a target="_blank" href="{{ route('admin.owner-china.detail',$item->owner->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-user-circle"></i>Owner</a>
                                                <a target="_blank" href="{{ route('admin.owner-china-transactions.detail',$item->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i>View</a>
                                            </ul>
                                        </td>

                                        <td>
                                            <li>Tổng tiền trung: {{ number_format($item->ot_total_money,0,',','.') }} NDT</li>
                                        <td>
                                                <?php
                                                $check = true;
                                                foreach ($item->detail as $key => $value) {
                                                    if($value->otd_status == 1) {
                                                        $check = false;
                                                        break;
                                                    }
                                                }
                                                ?>
                                            @if($check)
                                                <span class="label label-success">
                                                Đã về đến kho hết
                                            </span>
                                            @else
                                                <span class="label label-danger">
                                                Có mẫu chưa về
                                            </span>
                                            @endif

                                        </td>
                                        <td>
                                            Ngày tạo: {{ date("d/m/Y H:i:s", strtotime($item->created_at)) }} <br>
                                            Ngày đặt hàng: {{date("d/m/Y", strtotime($item->ot_order_date))}}</td>
                                        <td>
                                            <a href="{{ route('admin.owner-china-transactions.detail',$item->id) }}" class="btn btn-md btn-info js-preview-transaction"><i class="fa fa-eye"></i>View</a>
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
