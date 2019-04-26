@extends('master')

@section('middle-side')
<div class="panel panel-primary">
	<div class="panel-heading">
		<a href="{{url('/daily/list')}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-th-list"></span> فهرست روزانه‌ها</a>
	</div>
	<div class="panel-body">
		
	<table>
		<tr>
			<td>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5><span class="glyphicon glyphicon-link"></span> پیوند روزانه جدید</h5>
					</div>
					<div class="panel-body">
						<form name="newDailyLinkForm">

							<div class="form-group">
								<input type="text" class="form-control" placeholder="عنوان پیوند" id="dailyTitle" required>
							</div>
							<div class="form-group">
								<input type="text" class="form-control text-left" placeholder="http://example.com" id="dailyUrl" required>
							</div>

							<div class="form-group">
								<select id="dailyCat" class="form-control">
									<option value="0">بدون دسته</option>
									@foreach($cats as $cat)
										<option value="{{$cat->id}}">{{$cat->title}}</option>
									@endforeach
								</select>
							</div>

							<div class="form-group">
								<input type="text" class="form-control" id="dailyColumn" placeholder="ردیف (اختیاری - عدد لاتین)">
							</div>

							<div class="form-group">
								<a class="btn btn-info btn-block" onclick="newDailylink()">
								<span class="glyphicon glyphicon-ok"></span> 
									ثبت پیوند
								</a>
							</div>
						</form>	
					</div>
					
				</div>		

			</td>
			<td>
			
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5><span class="glyphicon glyphicon-option-vertical"></span> دسته روزانه جدید</h5>
					</div>
					<div class="panel-body">
						<form>
							<div class="form-group">
								<input type="text" class="form-control" placeholder="نام دسته" id="catTitle" required>
							</div>

							<div class="form-group">
								<input type="text" class="form-control" placeholder="ردیف (اختیاری - عدد لاتین)" id="catColumn">
							</div>
							<div class="form-group">
								<a class="btn btn-info btn-block" onclick="newDailyCat()"><span class="glyphicon glyphicon-ok"></span> 
									ثبت دسته
								</a>
							</div>
						</form>	
					</div>
					
				</div>	

			</td>
		</tr>
	</table>

	</div>
</div>	
@endsection

@section('scripts')
	<script>
		function newDailylink() {
			data = {title: $('#dailyTitle').val(), url: $('#dailyUrl').val()}
			if($('#dailyCat').val() == 0) {
				data['type'] = 'link';
				data['cat_id'] = 0;
			} else {
				data['type'] = 'cat-link';
				data['cat_id'] = $('#dailyCat').val();
			}

			if($('#dailyColumn').val() == "" || isNaN(Number($('#dailyColumn').val()))) {
				data['column'] = 1;
			} else {
				data['column'] = Number($('#dailyColumn').val());
			}

			rules = {title: 'عنوان@required|max:255', url: 'نشانی@required|max:500'};
			result = validate(data,rules);
			if(result.status == false) {
				messages = "";
				for(var message in result.messages) {
					messages +=  result.messages[message] + '<br>';
				}
				swal({
					title: 'مشکل در درج پیوند روزانه!',
					text: messages,
					type: 'warning',
					html: true,
					showConfirmButton: true,
		    		confirmButtonText: "متوجه شدم"
				});
				return false;
			} else {
				$.ajax({
					type: 'POST',
					url: baseUrl + '/daily/store-link',
					data: data,
					success: function(response) {
						swal({
							title: 'ثبت شد :)',
							type: 'success',
							showConfirmButton: false,
							timer: 700
						});
						$('#dailyTitle').val("");
						$('#dailyUrl').val("");
						$('#dailyCat').val(0);
						$('#dailyColumn').val("");
					},
					error: function(response) {
						if(response.status == 422) {
							var messages = "";
							for(var i in response.responseJSON) {
								messages += response.responseJSON[i] + '<br>';
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
		};

		function newDailyCat() {
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
					title: 'مشکل در درج دسته روزانه!',
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
				url: baseUrl + '/daily/store-category',
				data: data,
				success: function(response) {
					$('#catTitle').val();
					$('#catColumn').val();
					swal({
						title: 'ثبت شد :)',
						type: 'success',
						timer: 500,
						showConfirmButton: false
					});
					window.location = baseUrl + '/daily';	
				},
				error: function(response) {
					if(response.status == 422) {
						var messages = "";
						for(var i in response.responseJSON) {
							messages += response.responseJSON[i] + '<br>';
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
			})
		}
		notify("{{$notificationsCount}}");
	</script>
@endsection