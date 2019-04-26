@extends('guest.master')


@section('right-side')
  <div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-log-in"></span> وارد شوید</div>
    <div class="panel-body">
        @if(Session::has('error'))
          <div class="alert alert-danger text-center"><small>{{Session::get('error')}}</small></div>
        @endif
        <form action="{{url('/guest/login')}}" method="POST" >
          {{ csrf_field() }}
          <div class="form-group">
            <input type="text" name="username" class="form-control text-left" placeholder="نام کاربری" required>
          </div>

          <div class="form-group">
            <input type="password" name="password" class="form-control text-left" placeholder="گذرواژه" required>  
          </div>

          <div class="form-group">
            <input type="checkbox" name="remember" checked><small> <label for="remember">مرا به خاطر بسپار</label></small>
            <a href="{{url('/password/reset')}}">گذرواژه را فراموش کردم</a>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-block btn-info" value="ورود">
          </div>
        </form>
    </div>
  </div>
@endsection

@section('middle-side')
  <div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-link"></span> تازه‌ترین پیوند‌های ارسال شده</div>
    <div class="panel-body" id="linksPlace">
    </div>
  </div>
@endsection

@section('left-side')
  <div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-pencil"></span> به جمع ما بپیوندید :)</div>
    <div class="panel-body">
        @if(count($errors) > 0)
          <div class="alert alert-danger">
          @foreach($errors->all() as $error)
            <small>- {{$error}}</small><br/>
          @endforeach
          </div>
        @endif
        <form method="POST" action="{{url('/guest/register')}}" >
          {{ csrf_field() }}
          <div class="form-group">
            <input type="text" name="username" class="form-control text-left" placeholder="نام کاربری" min="6" maxlength="24" required>
          </div>

          <div class="form-group">
            <input type="password" name="password" class="form-control text-left" placeholder="گذرواژه" minlength="6" required>  
          </div>

          <div class="form-group">
            <input type="password" name="password_confirmation" class="form-control text-left" placeholder="تکرار گذرواژه" required>
          </div>

          <div class="form-group">
            {!! Captcha::img() !!}
          </div>

          <div class="form-group">
            <input type="text" name="captcha" class="form-control text-left" placeholder="رمز امنیتی" minlength="5" manlength="5" required>
          </div>

          <div class="form-group">
            <button class="btn btn-block btn-info">ثبت نام</button>              
          </div>
        </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    showLastLinks();
  </script>
@endsection