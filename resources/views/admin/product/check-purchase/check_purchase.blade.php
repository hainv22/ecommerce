@php
    $total_quantity = 0;
    foreach ($product->ownerTransactionDetail as $value) {
        $total_quantity += $value->otd_qty;
    }
@endphp
<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">{{$product->pro_name}}</h3>
    </div>
    <div class="box-body">
        <div class="form-group col-sm-12">
        <a target="_blank" href="{{ route('admin.product.update',$product->id) }}"><img src="{{ pare_url_file($product->pro_avatar) }}" alt="" width="150px" height="100px"></a>
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
                @if($value->transaction->tst_transaction_role == 2)
                    @php
                        $total_quantity -= $value->od_qty;
                    @endphp 
                @endif
                <label><a href="{{route('admin.transaction.detail', $value->transaction->id)}}">
                    {{$value->transaction->id}} - ({{$value->transaction->user->name}}) - {{ number_format($value->od_price,0,',','.') }} - qty: {{ $value->od_qty}} @if($value->transaction->tst_transaction_role == 2) ({{$total_quantity+$value->od_qty}} - {{$value->od_qty}} = {{$total_quantity}}) @endif  / 
                    {{ date("d-m-Y", strtotime($value->transaction->tst_order_date)) }} / 
                @if($value->transaction->tst_transaction_role == 2) <span class="label label-warning">Chung</span> @endif </a></label>
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
                <label><a target="_blank" href="{{route('admin.owner-china-transactions.detail', $value->ownerTransaction->id)}}">{{$value->ownerTransaction->id}} - ({{$value->ownerTransaction->ownerChina->oc_name}}) - {{ number_format($value->otd_price,1,',','.') }} - qty: {{$value->otd_qty}} / {{$value->ownerTransaction->ot_order_date}}</a></label>
                <br>
            @endforeach
        </div>
    </div>
</div>
