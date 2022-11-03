@extends('layout.master_admin')
@section('content')
    <section class="content-header">
        <h1>
            Owner China
            <small>index</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.owner-china.index') }}">Owner China</a></li>
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
                        <h3 class="box-title"><a href="{{ route('admin.owner-china-transactions.index') }}" class="btn btn-primary">Đơn China </a></h3>
                    </div>
                    <div class="box-header">
                        <div class="box-tools">
                            <form action="" method="GET" class="form-inline">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" value="{{ Request::get('email') }}" class="form-control" name="email" placeholder="nhập">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div id="js-data">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <th>ID -- STT</th>
                                    <th>Name</th>
                                    <th>Total</th>
                                    <th>Date-paid</th>
                                    <th>Action</th>
                                </tr>
                                @php
                                    $i = 0;
                                @endphp
                                @if(isset($owner))
                                    @foreach ($owner as $item)
                                        <tr>
                                            <td>{{ $item->id .'--'. ++$i }}</td>
                                            <td>{{ $item->oc_name }}</td>
                                            <td>{{number_format($item->oc_total_money,0,',','.') }}</td>
                                            <td>{{ $item->updated_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.owner-china.detail',$item->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i>View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    {!! $owner->links() !!}
                    <div></div>
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
@endsection
