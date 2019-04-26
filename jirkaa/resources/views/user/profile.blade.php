@extends('master')


@section('right-side')
  <div class="panel panel-default">
    <div class="panel-heading text-center">
      <img src="{{url('/src/images/avatars/large_'.$profile->image)}}" class="img-thumbnail">
    </div>
    <div class="panel-body">

      <span class="btn btn-primary btn-block" >
        <b>{{$profile->username}}</b>
      </span>

      <div class="numberInclude btn btn-info btn-xs btn-block" >حدود {{$profile->created_at->diffForHumans()}} پیوست</div>
      @if($profile != $user)
        @if($followed)
          <a id="followBtn-{{$profile->username}}" onclick="followUser('{{$profile->username}}')" class="btn btn-success btn-block btn-xs">دنبال می‌کنید</a>
        @else
          <a id="followBtn-{{$profile->username}}" onclick="followUser('{{$profile->username}}')" class="btn btn-default btn-block btn-xs">دنبال کنید</a>
        @endif
      @endif
        <a class="btn btn-warning btn-block btn-xs numberInclude">{{$profile->friendsOfCount()}} دنبال کننده | {{$profile->friendsCount()}} دنبال شونده</a>
        <a class="btn btn-danger btn-block btn-xs numberInclude">{{$linksCount}} پیوند ارسال کرده است</a>

      @if(strlen($profile->about) > 0)
      <div class="well">
        <small>
          {{$profile->about}}
        </small>
      </div>      
      @endif

    </div>
  </div>
@endsection

@section('middle-side')
<div class="panel panel-primary">
  <div class="panel-heading"><span class="glyphicon glyphicon-link"></span> پیوندهای ارسال شده توسط <b>[{{$profile->username}}]</b></div>
  <div class="panel-body" id="linksPlace">
  </div>
</div>
@endsection

@section('scripts')
  <script>
    showProfileLinks("{{$profile->username}}");
  </script>
@endsection