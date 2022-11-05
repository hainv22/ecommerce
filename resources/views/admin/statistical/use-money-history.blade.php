@extends('layout.master_admin')
@section('content')

    <section class="content-header">
        <h1>
            Thống Kê
            <small>website</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">thống kê</li>
        </ol>
    </section>
    <section class="content">
        <div class="row" style="margin-bottom: 20px">
            <div class="col-md-7">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Doanh Số</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body" style="">
                        <div class="table-responsive">
{{--                            <span style="color: red">Không chọn gì mặc định lấy các ngày trong tháng và năm hiện tại.</span>--}}
{{--                            <div class="box-title">--}}
{{--                                <form action="" method="GET" class="form-inline">--}}
{{--                                    @if ($errors->first('dateAfter'))--}}
{{--                                        <span class="text-danger">{{ $errors->first('dateAfter') }}</span>--}}
{{--                                    @endif--}}
{{--                                    <select name="day" class="form-control">--}}
{{--                                        <option value="" >_ Ngày trong tháng _</option>--}}
{{--                                        @for ($i = 1; $i <=31; $i++)--}}
{{--                                            <option value="{{$i}}" {{ Request::get('day') == $i ? "selected='selected'" : "" }}>Ngày {{$i}}</option>--}}
{{--                                        @endfor--}}
{{--                                    </select>--}}
{{--                                    <select name="month" class="form-control">--}}
{{--                                        <option value="">_ Tháng trong năm _</option>--}}
{{--                                        @for ($i = 1; $i <=12; $i++)--}}
{{--                                            <option value="{{$i}}" {{ Request::get('month') == $i ? "selected='selected'" : "" }}>Tháng {{$i}}</option>--}}
{{--                                        @endfor--}}
{{--                                    </select>--}}
{{--                                    <select name="year" class="form-control">--}}
{{--                                        <option value="">_ Năm _</option>--}}
{{--                                        <option value="2022" {{ Request::get('year') == 2022 ? "selected='selected'" : "" }}>Năm 2022</option>--}}
{{--                                        <option value="2023" {{ Request::get('year') == 2023 ? "selected='selected'" : "" }}>Năm 2023</option>--}}
{{--                                    </select>--}}
{{--                                    <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Search</button>--}}
{{--                                </form>--}}
                            </div>
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Số Tiền</th>
                                    <th>Nội dung</th>
                                    <th>Ngày</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($data))
                                    @foreach ($data as $key => $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                @if($item->umh_status == \App\Models\UseMoneyHistory::SU_DUNG_TIEN)
                                                    Tiêu
                                                @else
                                                    Trả trung quốc
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->umh_money,0,',','.') }} vnd</td>
                                            <td>{{ $item->umh_content }}</td>
                                            <td>{{ $item->umh_use_date }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    <button type="button" class="btn btn-warning" id="js_withdraw" data-url-paid-owner="{{route('admin.use-money-history.withdraw.index')}}" style="float: right">Rút</button>
                </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="paid-owner-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Paid</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="pro_price">Số Tiền Rút: </label><span id="" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="number" name="js_money" id="js_money" class="form-control txt_quantity" value="" min="1" placeholder="money" required="">
                </div>
                <div class="modal-body">
                    <label for="pro_price">Nội dung : </label><span id="" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="text" name="js_content" id="js_content" class="form-control txt_quantity" value="" min="1" placeholder="Nội Dung" required="">
                </div>
                <div class="modal-body">
                    <label for="pro_price">Giá tiền Trung Tại Thời Điểm Này : </label><span id="" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="date" name="js_date" id="js_date" class="form-control txt_quantity" value="{{date('Y-m-d')}}" required="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="js_save" data-url-withdrawr="{{route('admin.use-money-history.withdraw.index')}}">Rút</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        $(document).on('click','#js_withdraw',function(e){
            $("#paid-owner-model").modal("show");
        });
        $(document).on('click','#js_save',function(e){
            e.preventDefault();
            let $this = $(this);
            let money_withdraw = $('#js_money').val();
            let content_withdraw = $('#js_content').val();
            let date_withdraw = $('#js_date').val();
            console.log(money_withdraw, content_withdraw, date_withdraw)
            if(money_withdraw == '' || money_withdraw < 0) {
                toastr.error('Giá trị phải lớn hơn 1');
                return false;
            }
            let URL = $this.attr('data-url-withdrawr');
            if(URL){
                $.ajax({
                    url:URL,
                    type:"GET",
                    data:{
                        money_withdraw: money_withdraw,
                        content_withdraw: content_withdraw,
                        date_withdraw: date_withdraw
                    },
                    success:function(results){
                        console.log(results)
                        if(results.code == 200) {
                            location.reload();
                            toastr.success(results.message);
                        } else {
                            toastr.error(results.message);
                        }
                        $('#decrementPrice').modal('hide');
                        // $('#body_list_products').html(results.data);
                        // $data_this = $this;
                    },
                    error:function(error){
                        console.log(error.messages);
                    }
                });
            }
        });
    </script>
@endsection
