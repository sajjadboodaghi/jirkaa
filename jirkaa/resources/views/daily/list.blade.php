@extends('master')

@section('middle-side')
	<div id="daily-links-box" class="panel panel-primary">
		<div class="panel-heading">
			<a href="{{url('/daily')}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-plus"></span> درج روزانه جدید</a>
		</div>
		<div class="panel-body" >
			@if(count($dailies) > 0)
				<table class="table">
						<tr>
							<td width="150px"></td>
							<td width="70px"><b>نوع</b></td>
							<td><b>عنوان</b></td>
							<td><b>ردیف</b></td>
						</tr>
						@foreach($dailies as $daily)
							@if($daily->type == "link")
								<tr>
									<td><a class="btn btn-danger btn-xs" onclick="deleteDailyLink({{$daily->id}})"><span class="glyphicon glyphicon-trash"></span> حذف</a> <a href="{{url('/daily/edit-link/'.$daily->id)}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-edit"></span> ویرایش</a></td>
									<td><span class="glyphicon glyphicon-link"></span> پیوند</td>
									<td><a href="{{$daily->url}}" target="_blank">{{$daily->title}}</a></td>
									<td>{{$daily->column}}</td>
								</tr>
							@elseif($daily->type == "cat")
								<tr>
									<td><a class="btn btn-danger btn-xs" onclick="deleteDailyCat({{$daily->id}})"><span class="glyphicon glyphicon-trash"></span> حذف</a> <a href="{{url('/daily/edit-category/'.$daily->id)}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-edit"></span> ویرایش</a></td>
									<td><span class="glyphicon glyphicon-option-vertical"></span> دسته</td>
									<td><a><b>{{$daily->title}}</b></a></td>
									<td><b>{{$daily->column}}</b></td>
								</tr>
									@foreach($dailies as $catlink)
										@if($catlink->cat_id == $daily->id)
											<tr>
												<td><a class="btn btn-danger btn-xs" onclick="deleteDailyLink({{$catlink->id}})"><span class="glyphicon glyphicon-trash"></span> حذف</a> <a href="{{url('/daily/edit-link/'. $catlink->id)}}" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-edit"></span> ویرایش</a></td>
												<td></td>
												<td><a href="{{$catlink->url}}" target="_blank"><small>- {{$catlink->title}}</small></a></td>
												<td><small>{{$catlink->column}}</small></td>
											</tr>
										@endif
									@endforeach
							@endif
						@endforeach
				</table>
					
			@else
				<p class="alert alert-info">هنوز هیچ پیوند یا دسته‌ای را اضافه نکرده‌اید.</p>
			@endif
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		function deleteDailyLink(daily_id) {
			swal({
				title: 'حذف پیوند روزانه',
				text: 'آیا از حذف این پیوند مطمئن هستید؟',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'بله٬ حذف کنید',
				confirmButtonColor: "#DD6B55",
				cancelButtonText: 'خیر'

			},function() {
				$.ajax({
					type: 'POST',
					url: baseUrl + '/daily/delete-link/' + daily_id,
					success: function(response) {
						swal({
							title: 'حذف شد',
							type: 'success',
							showConfirmButton: false
						})
						window.location = baseUrl + '/daily/list';
					},
					error: function(response) {
						swal({
							title: 'متأسفانه مشکلی پیش آمده است! :(',
							type: 'warning',
							confirmButtonText: 'باشه'
						});
					}
				});
			});
		};
		
		function deleteDailyCat(cat_id) {
			swal({
				title: 'حذف دسته روزانه',
				text: 'با حذف دسته تمام پیوندهای آن حذف خواهد شد. آیا از انجام این کار مطمئن هستید؟',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'بله٬ حذف کنید',
				confirmButtonColor: "#DD6B55",
				cancelButtonText: 'خیر'

			},function() {
				$.ajax({
					method: 'POST',
					url: baseUrl + '/daily/delete-category/' + cat_id,
					success: function(response) {
						swal({
							title: 'حذف شد',
							type: 'success',
							showConfirmButton: false
						})
						window.location = baseUrl + '/daily/list';
					},
					error: function(response) {
						swal({
							title: 'متأسفانه مشکلی پیش آمده است! :(',
							type: 'warning',
							confirmButtonText: 'باشه'
						});
					}
				});
			});
		}

		notify("{{$notificationsCount}}");
	</script>
@endsection