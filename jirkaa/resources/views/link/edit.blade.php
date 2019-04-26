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
			<span class="glyphicon glyphicon-edit"></span>
			ویرایش پیوند
		</div>
		<div class="panel-body">
			<form name="updateLinkForm" id="editlink">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="عنوان پیوند" minlength="3" id="linktitle" required>
				</div>

				<div class="form-group">
					<input type="text" class="form-control text-left" placeholder="http://example.com" id="linkurl" required>
				</div>

				<div class="form-group">
					<select id="tags" class="form-control" name="tags[]" multiple="multiple" ></select>
				</div>

				<a onclick="updateLink({{$link->id}})" class="btn btn-info btn-block"><span class="glyphicon glyphicon-floppy-saved"></span> ذخیره</a>

			</form>
		</div>
	</div>
@endsection

@section('scripts')
	<!-- I used local select2 js file because I've changed it a little bit (some messages were changed to persian) -->
	<script src="{{url('/src/js/select2.min.js')}}"></script>
	<script>

		var link_title = "{{$link->title}}";
		$('#editlink #linktitle').val(link_title);
		
		var link_url = "{{$link->url}}";
		$('#editlink #linkurl').val(link_url);

		var tags = {!! $tags !!};
		$('select#tags').html(listAllTags(tags));

		function listAllTags(tags) {
			var output = "";
			for(var i = 0; i < tags.length; i++) {
				output += '<option value="' + tags[i].id + '">' + tags[i].title + '</option>';
			}
			return output;
		}

		var oldtags = {!! $oldtags !!};
		$('#tags').select2({
				placeholder: 'برچسب‌ها...',
  				tags: true,
  				dir: 'rtl',
  				maximumInputLength: 20,
  				maximumSelectionLength: 2,
			}).val(oldtags).trigger('change');


		function updateLink(link_id) {
			data = {
				title: $('#editlink #linktitle').val(),
				url: $('#editlink #linkurl').val(),
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
					title: 'مشکل در ذخیره تغییرات!',
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
					url: baseUrl + '/link/update/' + link_id,
					data: data,
					success: function(response) {
						swal({
							title: 'تغییرات ذخیره شد :)',
							type: 'success',
							timer: 1500,
							showConfirmButton: false
						},function() {
							window.location = baseUrl + '/link/edit/' + link_id
						});
					},
					error: function(response) {
						if(response.status == 503) {
							swal({
									title: 'مشکل در ذخیره تغییرات!',
									text: 'متأسفانه مشکلی در ثبت تغییرات به وجود آمده است!',
									type: 'warning',
									html: true,
									showConfirmButton: true,
						    		confirmButtonText: "متوجه شدم"
								});
						} else if(response.status == 422) {
							if(response.responseText == "exists") {
								swal({
									title: 'این نشانی قبلا به ثبت رسیده است!',
									type: 'error',
									showConfirmButton: true,
						    		confirmButtonText: "متوجه شدم"
								});
							} else {
								messages = "";
								for(var message in response.responseJSON) {
									messages +=  response.responseJSON[message] + '<br>';
								}
								swal({
									title: 'مشکل در ذخیره تغییرات!',
									text: messages,
									type: 'warning',
									html: true,
									showConfirmButton: true,
						    		confirmButtonText: "متوجه شدم"
								});
							}

						}
					}
				
				});
			}
		};

		notify("{{$notificationsCount}}");
	</script>
@endsection