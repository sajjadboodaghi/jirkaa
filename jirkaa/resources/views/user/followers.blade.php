@extends('master')

@section('middle-side')
<div class="panel panel-primary">
  <div class="panel-heading"><span class="numberInclude">توسط {{$followersCount}}</span> نفر دنبال می‌شوید:<a href="{{url('/user/following')}}" class="btn btn-default btn-xs pull-left">برو به فهرست دنبال شونده‌ها</a></div>
  <div class="panel-body">
  @if(count($followers) == 0)
    <div class="alert alert-info">شما هیچ دنبال کننده‌ای ندارید :(</div>
  @else
    @foreach($followers['data'] as $follower)
    <div class="userview">
          <img src="{{url('/src/images/avatars/large_'.$follower['image'])}}" class="img-thumbnail">
          <a class="btn btn-primary btn-xs btn-block" href="{{url('/user/profile/'.$follower['username'])}}"> {{$follower['username']}}</a>
          @if(in_array($follower['id'], $followingIds))
            <a id="followBtn-{{$follower['username']}}" onclick='followUser("{{$follower['username']}}")' class="btn btn-success btn-block btn-xs"> دنبال می‌کنید</a>
          @else
            <a id="followBtn-{{$follower['username']}}" onclick='followUser("{{$follower['username']}}")' class="btn btn-default btn-block btn-xs">دنبال نمی‌کنید</a>
          @endif
    </div>
    @endforeach
    <div style="clear:both">
      @if($followers['prev_page_url'] != null)
          <a href="{{$followers['prev_page_url']}}" class="btn btn-default btn-block"><span class="glyphicon glyphicon-triangle-right"></span> <b>صفحه قبل</b> </a>
      @endif
      @if($followers['next_page_url'] != null)
          <a href="{{$followers['next_page_url']}}" class="btn btn-default btn-block"><b>صفحه بعد</b> <span class="glyphicon glyphicon-triangle-left"></span></a>
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

