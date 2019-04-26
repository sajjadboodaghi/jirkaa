@extends('master')


@section('middle-side')
  <div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-picture"></span> تصویر کاربری</div>
    <div class="panel-body" >
        <table> 
            <tr>  
                <td> 
                      <div class="form-group text-center">  
                    <img src="{{url('/src/images/avatars/large_'. $user->image)}}" style="width: 150px;height:150px" class="img-thumbnail">
                    @if($user->image != "default.jpg")
                          <a class="btn btn-danger btn-block btn-xs" onclick="deleteUserImage()"><span class="glyphicon glyphicon-trash"></span> حذف تصویر</a>
                    @endif
                      </div>
                </td>
                <td>  
                    @foreach($errors->all() as $error)
                      <div class="alert alert-danger">
                        <small>{{$error}}</small>
                      </div>
                    @endforeach
                    @if(Session::has('message'))
                      <div class="alert alert-success">
                        <small>{{Session::get('message')}}</small>
                      </div>
                    @endif
                    <h3>انتخاب تصویر جدید</h3>
                  <form action="{{url('/user/uploadimage')}}" method="POST" enctype="multipart/form-data">
                    <div class="form-group">  
                      <input type="file" id="imageFile" name="userimage" class="form-control" accept="image/*">
                    </div>

                    {{csrf_field()}}
                      <h4>محدوده مورد نظر:</h4>
                      <div class="form-group">
                      <table>
                        <tr>
                          <th>عمودی</th>
                          <th>
                           <select class="form-control" name="vertical">
                            <option value="top">بالا</option>
                            <option value="middle">وسط</option>
                            <option value="bottom">پایین</option>
                          </select> 
                          </th>
                        </tr>
                        <tr>
                          <th>افقی</th>
                          <th>
                           <select class="form-control" name="horizontal">
                            <option value="right">راست</option>
                            <option value="middle">وسط</option>
                            <option value="left">چپ</option>
                          </select> 
                          </th>
                        </tr>
                      </table>
                      </div>

                        <button class="btn btn-info btn-block"><span class="glyphicon glyphicon-send"></span> ارسال</button>

                  </form>
                </td>
            </tr>

        </table>
  
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function deleteUserImage() {
      swal({
        title: 'حذف تصویر',
        text: 'آیا از حذف تصویر کنونی مطمئن هستید؟',
        type: 'warning',
        confirmButtonText: 'بله٬ حذف کنید',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: 'خیر'
      },function() {
        $.ajax({
          type: 'POST',
          url: baseUrl + '/user/deleteimage'
        }).then(function(response) {
          window.location = baseUrl + '/user/image';
        }, function(response) {
          swal({
            title: 'متأسفانه مشکلی به وجود آمده است!',
            type: 'error',
            confirmButtonText: 'متوجه شدم',
          })
        });
      });
    };
    notify("{{$notificationsCount}}");
  </script>
@endsection