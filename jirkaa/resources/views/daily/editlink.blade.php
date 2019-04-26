@extends('master')

@section('middle-side')
<div class="panel panel-primary">
	<div class="panel-heading">
		<a href="{{url('/daily/list')}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-th-list"></span> فهرست روزانه‌ها</a>
		<a href="{{url('daily')}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-plus"></span> درج روزانه جدید</a>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5><span class="glyphicon glyphicon-link"></span> ویرایش پیوند روزانه</h5>
			</div>
			<div class="panel-body">
				<form name="updateDailyLinkForm">
					<div class="form-group">
						<input  id="dailyLinkTitle" type="text" class="form-control" placeholder="عنوان پیوند" value="{{$link->title}}" required>
					</div>
					<div class="form-group">
						<input  id="dailyLinkUrl" type="text" class="form-control text-left" placeholder="http://example.com" value="{{$link->url}}" required>
					</div>

					<div class="form-group">
						<select id="dailyLinkCatId" class="form-control">
							<option value="0">بدون دسته</option>
							@foreach($cats as $cat)
								@if($cat->id == $link->cat_id)
									<option value="{{$cat->id}}" selected>{{$cat->title}}</option>
								@else
									<option value="{{$cat->id}}">{{$cat->title}}</option>
								@endif
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<input id="dailyLinkColumn" type="text" class="form-control" placeholder="ردیف (اختیاری - عدد لاتین)" value="{{$link->column}}">
					</div>

					<div class="form-group">
						<a class="btn btn-info btn-block" onclick="updateDailyLink({{$link->id}})">
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
		function updateDailyLink(link_id) {
			data = {title: $('#dailyLinkTitle').val() , url: $('#dailyLinkUrl').val()}
			if($('#dailyLinkCatId').val() == 0) {
				data['type'] = 'link';
				data['cat_id'] = 0;
			} else {
				data['type'] = 'cat-link';
				data['cat_id'] = $('#dailyLinkCatId').val();
			}

			if($('#dailyLinkColumn').val() == ""  || isNaN(Number($('#dailyLinkColumn').val())))
			{
				data['column'] = 1;
			} else {
				data['column'] = Number($('#dailyLinkColumn').val());
			}

			rules = {title: 'عنوان@required|max:255', url: 'نشانی@required|max:500'};
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
				url: baseUrl + '/daily/update-link/' + link_id,
				data: data,
				success: function(response) {
					swal({
						title: 'ذخیره شد :)',
						type: 'success',
						timer: 700,
						showConfirmButton: false
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
		};
		notify("{{$notificationsCount}}");
	</script>
@endsection