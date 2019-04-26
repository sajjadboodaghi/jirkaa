@extends('guest.master')

@section('middle-side')
	<div class="panel panel-primary">
		<div class="panel-heading"><span class="glyphicon glyphicon-send"></span> ارسال نشانی برای بازنویسی گذرواژه</div>
		<div class="panel-body">
			<form name="emailForReset" method="post" action="{{url('password/email')}}" >
				<h5 class="alert alert-warning"><b>توجه: </b> در صورتی نشانی بازنویسی گذرواژه برای شما ارسال میشود که قبلا رایانامه خود را ثبت کرده باشید!</h5>
					{{csrf_field()}}
					
				@if(count($errors) > 0)
					<div class="alert alert-danger">
						<li>کاربری با این رایانامه پیدا نشد!</li>
					</div>
				@endif

				@if(Session::has('status'))
					<div class="alert alert-success">
						<li>نشانی بازنویسی گذرواژه برای شما ارسال شد :)</li>
					</div>
				@endif


				<div class="form-group">
					<input type="email" class="form-control text-left" name="email" placeholder="رایانامه (email)" ng-model="resetEmail" required>
				</div>
					
				<div class="form-group">
					<button class="btn btn-info btn-block" ng-disabled="emailForReset.$invalid">ارسال رایانامه</button>
				</div>
			</form>	
		</div>
	</div>
	
@endsection