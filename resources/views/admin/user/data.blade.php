<div class="box-body table-responsive no-padding">
    <table class="table table-hover">
        <tbody>
        <tr>
            <th>ID -- STT</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Địa chỉ</th>
            <th>Role</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
        @php
            $i = 0;
        @endphp
        @if(isset($user))
            @foreach ($user as $item)
                <tr>
                    <td>{{ ++$i .'--'. $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->address }}</td>
                    <td>{{ Config::get('contants.GET_NAME_ROLE.'.$item->role, '[N\A]') }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.user.detail',$item->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i>View</a>
                        <a href="{{ route('admin.user.update',$item->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                        <a href="{{ route('admin.user.delete',$item->id) }}" class="btn btn-xs btn-danger js-delete-confirm"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
