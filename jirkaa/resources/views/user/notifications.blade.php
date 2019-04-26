@extends('master')


@section('middle-side')
  <div class="panel panel-primary">
    <div class="panel-heading">
      <span class="glyphicon glyphicon-bell"></span> خبرها
        @if(count($notifications) == 0)
    </div>
    <div class="panel-body" >
              <table>
                <tr>
                  <td class="btn-xs">خبر جدیدی برای نمایش وجود ندارد.</td>
                </tr>
              </table>
        @else
    <button class="btn btn-default btn-xs pull-left" onclick="cleanNotifications()" title="پاک کردن تمام خبرها"><span class="glyphicon glyphicon-erase"></span> پاک‌سازی</button>
    </div>
        <div class="panel-body" >
          <table class="table btn-xs">
            @foreach($notifications as $notify)
              @if($notify->type == "follow")
                <tr class="info">
                  <td class="text-center">
                    <a href="{{url('/user/profile/'.$notify->user->username)}}">{{$notify->user->username}}</a>
                  </td>
                  <td width="90px;">
                    <span class="numberInclude">{{$notify->created_at->diffForHumans()}}</span>
                  </td>
                  <td>
                    شما را دنبال کرد.
                  </td>
                </tr>
              @else 
                <tr class="warning">
                  <td class="text-center">
                    <a href="{{url('/user/profile/'.$notify->user->username)}}">{{$notify->user->username}}</a>
                  </td>
                  <td width="90px;">
                    <span class="numberInclude">{{$notify->created_at->diffForHumans()}}</span>
                  </td>
                  <td>
                    پیوند «<a href="{{$notify->link->url}}" target="_blank">{{$notify->link->title}}</a>» را داغ کرد.
                  </td>
                </tr>      
              @endif
            @endforeach
          </table>
        @endif
    </div>
  </div>
@endsection

@section('scripts')
    <script>  
        function cleanNotifications() {
          $.ajax({
            type: 'GET',
            url: baseUrl + '/user/cleannotifications',
            success: function(response) {
              window.location = baseUrl + '/user/notifications';
            }
          })
        }
    </script>
@endsection
