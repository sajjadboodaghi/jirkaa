@extends('master')

@section('styles')
	<!-- CDN -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

	<!-- Local -->
		<!-- <link rel="stylesheet" href="{{url('/src/css/local/select2.min.css')}}"> -->
@endsection

@section('middle-side')
	<div class="panel panel-primary">
		<div class="panel-heading">
			<span class="glyphicon glyphicon-link"></span> با دیگران پیوند‌های جالب و مفید به اشتراک بگذارید
		</div>
		<div class="panel-body">
			<form id="newlink">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="عنوان پیوند" id="linktitle" minlength="3" required>
				</div>

				<div class="form-group">
					<input type="text" class="form-control text-left" placeholder="http://example.com" id="linkurl" required>
				</div>

				<div class="form-group">
					<select id="tags" class="form-control" name="tags[]" multiple="multiple" id="tags">
					</select>
				</div>

				<a onclick="storeLink()" class="btn btn-info btn-block"><span class="glyphicon glyphicon-send"></span> ارسال</a>

			</form>
		</div>
	</div>
@endsection

@section('scripts')
	
	<!-- I used local select2 js file because I've changed it a little bit (some messages were changed to persian) -->
	<script src="{{url('/src/js/select2.min.js')}}"></script>
	<script>
		var tags = {!! $tags !!};

		$('#tags').html(listAllTags(tags));

		function listAllTags(tags) {
			var output = "";
			for(var i = 0; i < tags.length; i++) {
				output += '<option value="' + tags[i].id + '">' + tags[i].title + '</option>';
			}
			return output;
		}

		$('#tags').select2({
				placeholder: 'برچسب‌ها...',
  				tags: true,
  				dir: 'rtl',
  				maximumInputLength: 20,
  				maximumSelectionLength: 2,
			});

		function storeLink() {
			data = {
				title: $('#newlink #linktitle').val(),
				url: $('#newlink #linkurl').val(),
				tags: ($('select#tags').val() != null ?  $('select#tags').val() : [])
			};

			rules = {title: 'عنوان@required|min:3|max:255', url: 'نشانی@required|max:500'};
			result = validate(data,rules);
			if(result.status == false) {
				messages = "";
				for(var message in result.messages) {
					messages +=  result.messages[message] + '<br>';
				}
				swal({
					title: 'مشکل در درج پیوند!',
					text: messages,
					type: 'warning',
					html: true,
					showConfirmButton: true,
		    		confirmButtonText: "متوجه شدم"
				});
				return false;
			} else {

				$.ajax({
					method: 'POST',
					url: baseUrl + '/link/store',
					data: data
				}).done(function(response) {
					swal({
						title: "ارسال شد :)",
						type: 'success',
						showConfirmButton: false,
			    		timer: 1000
					});
					window.location = baseUrl + '/link/create';
				}).fail(function(response) {
					if(response.status == 422) {
						if(response.responseText == "exists") {
							swal({
								title: 'شما قبلا این پیوند را به ثبت رسانده‌اید!',
								type: 'error',
								showConfirmButton: true,
					    		confirmButtonText: "متوجه شدم"
							});
						} else if ( response.responseText == "bookmarked") {
							swal({
								title: 'این پیوند را کاربر دیگری ثبت کرده است!',
								text: 'این پیوند به بخش نشان‌های شما اضافه شد.',
								type: 'success',
								showConfirmButton: true,
					    		confirmButtonText: "برو به نشان‌ها",
					    		confirmButtonColor: "#DD6B55",
					    		showCancelButton: true,
					    		cancelButtonText: "متوجه شدم",
							}, function() {
								window.location = baseUrl + '/search/bookmarks'
							});
						} else if (response.responseText == "alreadybookmarked") {
							swal({
								title: 'این پیوند را کاربر دیگری ثبت کرده است!',
								text: 'شما قبلا این پیوند را نشان کرده‌اید!',
								type: 'warning',
								showConfirmButton: true,
					    		confirmButtonText: "برو به نشان‌ها",
					    		confirmButtonColor: "#DD6B55",
					    		showCancelButton: true,
					    		cancelButtonText: "متوجه شدم",
							}, function() {
								window.location = baseUrl + '/search/bookmarks'
							});
						} else {
							messages = "";
							for(var message in response.responseJSON) {
								messages +=  response.responseJSON[message] + '<br>';
							}
							swal({
								title: 'مشکل در درج پیوند!',
								text: messages,
								type: 'warning',
								html: true,
								showConfirmButton: true,
					    		confirmButtonText: "متوجه شدم"
							});
						}
					} else {
						swal({
							title: "متاسفانه مشکلی پیش آمده است!",
							type: 'warning',
							showConfirmButton: true,
				    		confirmButtonText: "متوجه شدم"
						});
					}
				});
			}
		}

		notify("{{$notificationsCount}}");
		
	</script>
@endsection