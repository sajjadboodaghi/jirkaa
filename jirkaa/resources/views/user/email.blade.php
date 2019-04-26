@extends('master')

@section('middle-side')
  <div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> تغییر رایانامه</div>
    <div class="panel-body" >
    @foreach($errors->all() as $error)
      <div class="alert alert-danger">{{$error}}</div>
    @endforeach

    @if(Session::has('message'))
      <div class="alert alert-success">{{Session::get('message')}}</div>
    @endif
      <table>
        <form action="{{url('/user/updateemail')}}" method="POST">
        <tr>
          {{csrf_field()}}
          <td class="form-group">
            <input type="password" class="form-control" placeholder="گذرواژه" name="password" required>
          </td>
          
          <td>
            <input type="email" name="email" class="form-control text-left" placeholder="example@gmail.com" value="{{$user->email}}">
          </td>
          <td>
            <button type="submit" class="btn btn-info btn-block"><span class="glyphicon glyphicon-ok"></span> ذخیره</button>
          </td>
        </tr>
        </form>
      </table>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    notify("{{$notificationsCount}}");
  </script>
@endsection
