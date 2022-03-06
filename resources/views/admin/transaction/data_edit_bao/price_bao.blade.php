<span>
                                                        @if(empty($bao->b_success_date))
        {{$bao->transport->tp_fee}} đ / 1kg
    @else
        số cân * giá tiền/1kg <br/>
        {{$bao->b_weight}} * {{$bao->b_fee}} =
        <span style="color: red">{{number_format($bao->b_weight * $bao->b_fee,0,',','.') }} đ</span>
    @endif
                                                    </span>
