@extends('master')

@section('middle-side')
<div class="panel panel-primary">
  <div class="panel-heading">شما <span class="numberInclude">{{$followingCount}}</span> نفر را دنبال می‌کنید: <a href="{{url('/user/followers')}}" class="btn btn-default btn-xs pull-left">برو به فهرست دنبال کننده‌ها</a></div>
  <div class="panel-body">
  @if(count($following['data']) == 0)
    <div class="alert alert-info">شما هیچ کاربری را دنبال نمی‌کنید.</div>
  @else
    @foreach($following['data'] as $followedUser)
    <div class="userview">
          <img src="{{url('/src/images/avatars/large_'.$followedUser['image'])}}" class="img-thumbnail">
          <a class="btn btn-primary btn-xs btn-block" href="{{url('/user/profile/'.$followedUser['username'])}}"> {{$followedUser['username']}}</a>
          @if(in_array($followedUser['id'], $followersIds))
            <a id="followBtn-{{$followedUser['username']}}" onclick='followUser("{{$followedUser['username']}}")' class="btn btn-success btn-block btn-xs">شما را دنبال می‌کند</a>
          @else
            <a id="followBtn-{{$followedUser['username']}}" onclick='followUser("{{$followedUser['username']}}")' class="btn btn-danger btn-block btn-xs">شما را دنبال نمی‌کند</a>
          @endif
    </div>
    @endforeach
    <div style="clear:both">
      @if($following['prev_page_url'] != null)
          <a href="{{$following['prev_page_url']}}" class="btn btn-default btn-block"><span class="glyphicon glyphicon-triangle-right"></span> <b>صفحه قبل</b></a>
      @endif
      @if($following['next_page_url'] != null)
          <a href="{{$following['next_page_url']}}" class="btn btn-default btn-block"><b>صفحه بعد</b> <span class="glyphicon glyphicon-triangle-left"></span></a>
      @endif
      
    </div>
  @endif
  </div>
</div>
@endsection

@section('scripts')
  <script>
    notify("{{$notificationsCount}}");
  </script>
@endsection
