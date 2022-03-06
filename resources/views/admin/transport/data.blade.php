<div class="box-body table-responsive no-padding">
    <table class="table table-hover">
      <tbody>
        <tr>
          <th>STT -- ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Description</th>
          <th>Times</th>
          <th>Action</th>
        </tr>
        @php
           $i = 0;
        @endphp
        @if(isset($transport))
            @foreach ($transport as $item)
                <tr>
                    <td>{{ ++$i . ' -- ' .  $item->id}}</td>
                    <td>{{ $item->tp_name }}</td>
                    <td>
                        <span class="label label-success" style="font-size: 13px">{{ number_format($item->tp_fee,0,',','.') }} VND</span>
                    </td>
                    <td>{{ $item->tp_description }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.transport.update',$item->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                        <a href="{{ route('admin.transport.delete',$item->id) }}" class="btn btn-xs btn-danger js-delete-confirm"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                </tr>
            @endforeach
        @endif
      </tbody>
    </table>
  <div>{!! $transport->appends($query ?? [])->links() !!}</div>
  </div>
