<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">{{$product->pro_name}}</h3>
    </div>
    <div class="box-body">
        <div class="form-group col-sm-12">
            <img src="{{ pare_url_file($product->pro_avatar) }}" alt="" width="150px" height="100px">
        </div>
        <div class="form-group col-sm-12">
            <span class="label label-success" style="font-size: 13px">{{ number_format($product->pro_price,0,',','.') }} VND</span>
            <span class="label label-info" style="font-size: 13px">{{ number_format($product->pro_money_yuan,2,',','.') }} NDT</span>
        </div>
    </div>
</div>
<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">Transaction</h3>
    </div>
    <div class="box-body">
        <div class="form-group col-sm-12">
            @foreach($product->orders as $value)
                <label><a target="_blank" href="{{route('admin.transaction.detail', $value->transaction->id)}}">Đơn - {{$value->transaction->id}} - ({{$value->transaction->user->name}}) - {{ number_format($value->od_price,0,',','.') }} - qty: {{ $value->od_qty}} / {{ date("d-m-Y", strtotime($value->transaction->tst_order_date)) }}</a></label>
                <br>
            @endforeach
        </div>
    </div>
</div>
<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">Owner Transaction</h3>
    </div>
    <div class="box-body">
        <div class="form-group col-sm-12">
            @foreach($product->ownerTransactionDetail as $value)
                <label><a target="_blank" href="{{route('admin.owner-china-transactions.detail', $value->ownerTransaction->id)}}">Đơn - {{$value->ownerTransaction->id}} - ({{$value->ownerTransaction->ownerChina->oc_name}}) - {{ number_format($value->otd_price,1,',','.') }} - qty: {{$value->otd_qty}}</a></label>
                <br>
            @endforeach
        </div>
    </div>
</div>
