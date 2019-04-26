@extends('guest.master')

@section('middle-side')
	<div class="panel panel-primary">
		<div class="panel-heading"><span class="glyphicon glyphicon-edit"></span> بازنویسی گذرواژه</div>
		<div class="panel-body">
			<form method="post" action="{{url('password/reset')}}" >

				@if(count($errors) > 0)
					<div class="alert alert-danger">
						@foreach($errors->all() as $error)
							@if($error == "The email must be a valid email address.")
								<p>رایانامه وارد شده معتبر نیست!</p>
								@elseif($error == "The email field is required.")
									<p>رایانامه الزامی است!</p>
									@elseif($error == "The password field is required.")
										<p>گذرواژه جدید را وارد کنید!</p>
										@elseif($error == "The password confirmation does not match.")
											<p>گذرواژه جدید با تکرار آن مطابقت ندارد!</p>
											@elseif($error == "We can't find a user with that e-mail address.")
												<p>کاربری با این رایانامه پیدا نشد!</p>
												@elseif($error == "The password must be at least 6 characters.")
													<p>طول گذرواژه باید حداقل ۶ کاراکتر باشد!</p>
													@elseif($error == "This password reset token is invalid.")
														<p>این نشانی برای تغییر گذرواژه نامعتبر است! یک نشانی جدید درخواست کنید.</p>
														@else
															<p>{{$error}}</p>
											
							@endif

						@endforeach
					</div>
				@endif

				{{csrf_field()}}


				<div class="form-group">
					<input type="email" class="form-control text-left" name="email" value="{{$_GET['email']}}" placeholder="رایانامه (email)" required>
				</div>
					<input type="hidden" name="token" value="{{$token}}">
				
				<div class="form-group">
					<input type="password" name="password" class="form-control text-left" placeholder="گذرواژه جدید" ng-model="password" minlength="6" required>
				</div>

				<div class="form-group">
					<input type="password" name="password_confirmation" class="form-control text-left" placeholder="تکرار گذرواژه جدید" ng-model="password_confirmation" minlength="6" required>
				</div>

				<div class="form-group">
					<input type="submit" value="ثبت گذرواژه جدید" class="btn btn-info btn-block">
				</div>
			</form>
		</div>
	</div>
@endsection