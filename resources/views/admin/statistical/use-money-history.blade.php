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
                            <span style="color: red">Không chọn gì mặc định lấy các ngày trong tháng và năm hiện tại.</span>
                            <div class="box-title">
                                <form action="" method="GET" class="form-inline">
                                    <input type="text" name="check" value="1" hidden>
{{--                                    <select name="month_use_money" class="form-control">--}}
{{--                                        <option value="">_ Tháng trong năm _</option>--}}
{{--                                        @for ($i = 1; $i <=12; $i++)--}}
{{--                                            <option value="{{$i}}" {{ Request::get('month-use-money') == $i ? "selected='selected'" : "" }}>Tháng {{$i}}</option>--}}
{{--                                        @endfor--}}
{{--                                    </select>--}}
                                    <select name="year_use_money" class="form-control">
                                        <option value="">_ Năm _</option>
                                        <option value="2022" {{ Request::get('year_use_money') == 2022 ? "selected='selected'" : "" }}>Năm 2022</option>
                                        <option value="2023" {{ Request::get('year_use_money') == 2023 ? "selected='selected'" : "" }}>Năm 2023</option>
                                    </select>
                                    <select name="type_use_money" class="form-control">
                                        <option value="">_ Type _</option>
                                        <option value="1" {{ Request::get('type_use_money') == 1 ? "selected='selected'" : "" }}>Sử dụng tiền chung</option>
                                        <option value="2" {{ Request::get('type_use_money') == 2 ? "selected='selected'" : "" }} >Trả trung quốc ( cái này tự sinh )</option>
                                        <option value="3" {{ Request::get('type_use_money') == 3 ? "selected='selected'" : "" }} >Mua băng dính, Mua thùng, Bút, Kim, Dây ...</option>
                                        <option value="5" {{ Request::get('type_use_money') == 5 ? "selected='selected'" : "" }} >Trả tiền đầu bao HN -> BN</option>
                                        <option value="6" {{ Request::get('type_use_money') == 6 ? "selected='selected'" : "" }} >Trả Tiền Vận Chuyển Trung Quốc -> Hà Nội</option>
                                    </select>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Search</button>
                                </form>
                            </div>
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Số Tiền</th>
                                    <th>Nội dung</th>
                                    <th>Ngày rút</th>
                                    <th>Ngày Tạo</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($data))
                                    @foreach ($data as $key => $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                @if($item->umh_status == \App\Models\UseMoneyHistory::SU_DUNG_TIEN)
                                                    Sử dụng tiền chung
                                                @elseif($item->umh_status == \App\Models\UseMoneyHistory::TRA_TRUNG_QUOC)
                                                    Trả trung quốc ( cái này tự sinh )
                                                @elseif($item->umh_status == \App\Models\UseMoneyHistory::MUA_BANG_DINH)
                                                    Mua băng dính, Mua thùng, Bút, Kim, Dây ...
                                                @elseif($item->umh_status == \App\Models\UseMoneyHistory::TRA_TIEN_DAU_BAO_HN_BN)
                                                    Trả tiền đầu bao HN -> BN
                                                @elseif($item->umh_status == \App\Models\UseMoneyHistory::TRA_TIEN_VAN_CHUYEN_TQ_HN)
                                                    Trả Tiền Vận Chuyển Trung Quốc -> Hà Nội
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->umh_money,0,',','.') }} vnd</td>
                                            <td>{{ $item->umh_content }}
                                            @if($item->umh_change_money_owner_id != 9999999999)
                                                    <a target="_blank" href="{{route('admin.owner-china.detail', $item->umh_change_money_owner_id)}}">(click)</a>
                                                @endif
                                            </td>
                                            <td>{{ $item->umh_use_date }}</td>
                                            <td>{{ $item->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>{{ number_format($total,0,',','.') }} vnd</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
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
                    <label for="pro_price">Số tiền rút: </label><span id="js_money_pay_format" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="number" name="js_money" id="js_money" class="form-control txt_quantity" value="" min="1" placeholder="money" required="">
                </div>
                <div class="modal-body">
                    <label for="pro_price">Nội dung : </label><span id="" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="text" name="js_content" id="js_content" class="form-control txt_quantity" value="" min="1" placeholder="Nội Dung" required="">
                </div>
                <div class="modal-body">
                    <label for="pro_price">Ngày sử dụng : </label><span id="" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="date" name="js_date" id="js_date" class="form-control txt_quantity" value="{{date('Y-m-d')}}" required="">
                </div>
                <div class="modal-body">
                    <label for="pro_status_type">Ngày sử dụng : </label><span id="" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <select name="js_type_status" id="js_type_status" class="form-control">
                        <option value="1" >Sử dụng tiền chung</option>
                        <option value="2" >Trả trung quốc ( cái này tự sinh )</option>
                        <option value="3" >Mua băng dính, Mua thùng, Bút, Kim, Dây ...</option>
                        <option value="5" >Trả tiền đầu bao HN -> BN</option>
                        <option value="6" >Trả Tiền Vận Chuyển Trung Quốc -> Hà Nội</option>
                    </select>
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
            let status = $('#js_type_status').val();
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
                        date_withdraw: date_withdraw,
                        status: status,
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

        $(document).on('keyup', '#js_money', function (e) {
            nStr = $(this).val();
            decSeperate = '.';
            groupSeperate = ',';
            nStr += '';
            x = nStr.split(decSeperate);
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
            }
            $("#js_money_pay_format").text(x1+x2 + ' đ')
        })
    </script>
@endsection
