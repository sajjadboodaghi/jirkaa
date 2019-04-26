@extends('master')

@section('middle-side')
<div class="panel panel-primary">
	<div class="panel-heading">
		<a href="{{url('/daily/list')}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-th-list"></span> فهرست روزانه‌ها</a>
		<a href="{{url('/daily')}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-plus"></span> درج روزانه جدید</a>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5><span class="glyphicon glyphicon-option-vertical"></span> ویرایش دسته روزانه</h5>
			</div>
			<div class="panel-body">
				<form name="updateDailyCatForm">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="نام دسته" id="catTitle" value="{{$cat->title}}" required>
					</div>

					<div class="form-group">
						<input type="text" class="form-control" placeholder="ردیف (اختیاری - عدد لاتین)" id="catColumn" value="{{$cat->column}}">
					</div>

					<div class="form-group">
						<a class="btn btn-info btn-block" onclick="updateDailyCat({{$cat->id}})">
						<span class="glyphicon glyphicon-ok"></span> 
							ذخیره
						</a>
					</div>
				</form>	
			</div>
		</div>
		
	</div>
</div>
@endsection

@section('scripts')
	<script>
		function updateDailyCat(cat_id) {
			data = {title: $('#catTitle').val()};
			if($('#catColumn').val() == "" || isNaN(Number($('#catColumn').val()))) {
				data['column'] = 1;
			} else {
				data['column'] = Number($('#catColumn').val());
			}

			rules = {title: 'عنوان@required|max:255'};
			result = validate(data,rules);
			if(result.status == false) {
				messages = "";
				for(var message in result.messages) {
					messages +=  result.messages[message] + '<br>';
				}
				swal({
					title: 'مشکل در ذخیره تغییرات!',
					text: messages,
					type: 'warning',
					html: true,
					showConfirmButton: true,
		    		confirmButtonText: "متوجه شدم"
				});
				return false;
			}
			
			$.ajax({
				type: 'POST',
				url: baseUrl + '/daily/update-category/' + cat_id,
				data: data,
				success: function(response) {
					swal({
						title: 'ذخیره شد :)',
						type: 'success',
						confirmButtonText: 'برو به فهرست روزانه‌ها',
						showCancelButton: true,
						cancelButtonText: 'همینجا بمان'
					}, function() {
						window.location = baseUrl + '/daily/list';
					})
				},
				error: function(response) {
					if(response.status == 422) {
						var messages = "";
						for(var i in response.data) {
							messages += response.data[i] + '<br>';
						}
						swal({
							title: 'مشخصات ارسال شده قابل قبول نیست!',
							text: messages,
							type: 'error',
							html: true,
							confirmButtonText: 'باشه'
						});
					} else {
						swal({
							title: 'متأسفانه مشکلی پیش آمده است! :(',
							type: 'warning',
							confirmButtonText: 'باشه'
						});
					}
				}
			});
		}
		notify("{{$notificationsCount}}");
	</script>
@endsection