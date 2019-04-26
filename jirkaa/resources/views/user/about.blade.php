@extends('master')

@section('middle-side')
  <div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-comment"></span> درباره من</div>
    <div class="panel-body" >
    
    @foreach($errors->all() as $error)
      <div class="alert alert-danger">
          {{$error}}
      </div>
    @endforeach
  

    @if(Session::has('message'))
      <div class="alert alert-success">{{Session::get('message')}}</div>
    @endif

    <form action="{{url('/user/updateabout')}}" method="POST">
        {{csrf_field()}}
        
        <div class="form-group">
          <textarea class="form-control" name="about" placeholder="درباره خودتان بنویسید..." rows="3">{{(old('about') != "" ? old('about') : $user->about)}}</textarea>
        </div>
  
        <button type="submit" class="btn btn-info btn-block"><span class="glyphicon glyphicon-ok"></span> ذخیره درباره من</button>
  
    </form>
      
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    notify("{{$notificationsCount}}");
  </script>
@endsection
