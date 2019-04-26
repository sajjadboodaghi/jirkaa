@extends('master')

@section('middle-side')
  <div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-lock"></span> تغییر گذرواژه</div>
    <div class="panel-body" >
    @if(count($errors) > 0)
      <div class="alert alert-danger">
        @foreach($errors->all() as $error)
          <li>{{$error}}</li>
        @endforeach
      </div>
    @endif

    @if(Session::has('message'))
      <div class="alert alert-success">{{Session::get('message')}}</div>
    @endif

    <form action="{{url('/user/updatepassword')}}" method="POST">
        {{csrf_field()}}
        
        <div class="form-group">
          <input type="password" class="form-control" placeholder="گذرواژه فعلی" name="oldpassword" required>
        </div>

        <div class="form-group">
          <input type="password" class="form-control" placeholder="گذرواژه جدید" name="newpassword" minlength="6" required>
        </div>

        <div class="form-group">
          <input type="password" class="form-control" placeholder="تکرار گذرواژه جدید" name="newpassword_confirmation" required>
        </div>
  
        <button type="submit" class="btn btn-info btn-block"><span class="glyphicon glyphicon-ok"></span> ذخیره گذرواژه جدید</button>
  
    </form>
      
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    notify("{{$notificationsCount}}");
  </script>
@endsection