@extends('master')

@section('right-side')
	<!-- daily links -->
	<div id="daily-links-box" class="panel panel-primary">
		<div class="panel-heading">
			<h5><span class="glyphicon glyphicon-tasks"></span> روزانه‌ها </h5>
		</div>
		<div class="panel-body" id="dailiesPlace"></div>
	</div>
@endsection
	
@section('middle-side')
	<!-- content -->
	<div id="middle-side-menu" class="panel panel-primary">	
		<div class="panel-heading  text-center" id="linksMenu">

			<div class="btn-group">
			  <a onclick="showFriendsLinks()" id="friendslinksbtn" type="button" class="btn-default btn btn-xs" title="پیوندهای کاربرانی که شما دنبال می‌کنید"><span class="glyphicon glyphicon-user"></span> پیوندهای دوستان</a>
			  <a onclick="showBookmarksLinks()" id="bookmarkslinksbtn" type="button" class="btn-default btn btn-xs" title="پیوندهایی که شما نشان کردید"><span class="glyphicon glyphicon-bookmark"></span> نشان‌های شما</a>
			  <a onclick="showHottestLinks()" id="hottestlinksbtn" type="button" class="btn-default btn btn-xs" title="داغ‌ترین پیوندها"><span class="glyphicon glyphicon-fire"></span> داغ‌ترین‌ها</a>
			  <a onclick="showHottest24Links()" id="hottest24linksbtn" type="button" class="btn-default btn btn-xs" title="پیوندهایی که امروز داغ شدند"><span class="glyphicon glyphicon-time"></span> داغ‌های امروز</a>
			  <a onclick="showLastLinks()" id="lastlinksbtn" type="button" class="btn-default btn btn-xs" title="تازه‌سازی پیوندها و خبر‌ها"><span class="glyphicon glyphicon-refresh"></span> تازه‌ترین‌ها</a>
			</div>
			
		</div>
		<div class="panel-body" id="linksPlace"></div>
	</div>
@endsection

@section('left-side')
	<!-- tags -->
	<div id="tags" class="panel panel-primary">	
		<div class="panel-heading">
			<h5><span class="glyphicon glyphicon-tags"></span> برچسب‌ها </h5>
		</div>
		<div class="panel-body" id="tagsPlace"></div>
	</div>
@endsection
		
@section('scripts')
	<script>
		showLastLinks();
		
		var tags = {!! $tags !!};
		$('#tagsPlace').html(showPopularTags(tags));
		
		var dailies = {!! $dailies !!};
		$('#dailiesPlace').html(showUserDailies(dailies));

		function getLinksOfCategory(cat_id) {
			if(!$('#daily-' + cat_id).attr('aria-expanded')) {
				var dailyCatLinksPlace = $('#dailyCatLinksPlace-'+cat_id);
				dailyCatLinksPlace.html("لطفا صبر کنید...");
				$.ajax({
					type: 'GET',
					url: baseUrl + '/daily/links-of-category/' + cat_id,
					success: function(response) {
						var catlinks = response;
						var output = "";
						if(catlinks.length == 0) {
							output = '<small>هنوز پیوندی به این دسته اضافه نکرده‌اید.</small>';
						} else {
							for(var i = 0; i < catlinks.length; i++) {
								output += '<small>- <a href="' + catlinks[i].url + '" target="_blank">' + catlinks[i].title + '</a></small><br>';
							}
						}
						dailyCatLinksPlace.html(output);
					}
				});
			}
		}

	</script>
@endsection

