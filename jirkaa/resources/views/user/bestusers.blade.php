@extends('master')

@section('middle-side')
<div class="panel panel-primary">
  <div class="panel-heading">کاربران برتر؛ کاربرانی که پیوندهای‌شان بیشتر از همه داغ شده است:</div>
  <div class="panel-body">
  @if(count($bestusers) == 0)
    <div class="alert alert-info">هنوز هیچ کاربری برگزیده نشده است! :(</div>
  @else
    <table>
    @foreach($bestusers as $key => $bestuser)
      <tr>
        <td width="45px;">
          <div class="btn btn-info numberInclude">{{$key + 1}}</div>
        </td>
        <td width="45px;">
          <img src="{{url('/src/images/avatars/small_'.$bestuser->image)}}" class="img-circle" width="35px;">
        </td>
        <td>
          <a class="btn btn-primary btn-block" href="{{url('/user/profile/'.$bestuser->username)}}"> {{$bestuser->username}}</a>
        </td>
        <td>
          @if(in_array($bestuser->id, $followingIds))
            <a id="followBtn-{{$bestuser->username}}" onclick="followUser('{{$bestuser->username}}')" class="btn btn-success btn-block">دنبال می‌کنید</a>
          @else
            <a id="followBtn-{{$bestuser->username}}" onclick="followUser('{{$bestuser->username}}')" class="btn btn-default btn-block">دنبال نمی‌کنید</a>
          @endif
        </td>
      </tr>
    @endforeach
    </table>
  @endif
  </div>
</div>
@endsection

@section('scripts')
  <script>
    notify("{{$notificationsCount}}");
  </script>
@endsection

