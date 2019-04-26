
function showLastLinks() {
	$('#linksPlace').html("لطفا صبر کنید...");
	$.ajax({
		type: 'GET',
		url: baseUrl + '/guest/last',
		success: function(response) {
			$('#linksPlace').html(showLinks(response.links));
		}
	});
}

$('form[name="search"]').submit(function(e){
	e.preventDefault();
	var keywords = $('#searchkeywords').val();
	$.ajax({
		type: 'GET',
		url: baseUrl + '/guest/search?keywords=' + keywords,
		success: function(response) {
			output = showLinks(response.links, '&keywords='+keywords);
			completeoutput = '<div id="middle-side-menu" class="panel panel-primary">';
			completeoutput += '<div class="panel-heading  text-center" id="linksMenu">';
			completeoutput += '<span class="glyphicon glyphicon-search"></span> نتیجه جستجو برای «<b>'+keywords+'</b>»';
			completeoutput += '</div>';
			completeoutput += '<div class="panel-body" id="linksPlace">' + output + '</div>';
			completeoutput += '</div>';
			$('#middle-side').html(completeoutput);
			$('#searchkeywords').val("");
			$("html, body").animate({scrollTop: 0},100);
		}
	})
});

function showLinks(result, extraparameter="") {
	links = result.data;
	if(links.length == 0) {
		return '<div class="alert alert-info">موردی پیدا نشد!</div>';
	}
	var output = "";
	for(var i = 0; i < links.length; i++) {
		link = links[i];
		output += '<table class="link-container" id="' + link.id + '">';
		output += '<tr>';
		output += '<td rowspan="2" class="user-image-column">';
		output += '<img src="' + baseUrl + '/src/images/avatars/small_' + link.user.image + '" class="user-image">';
		output += '</td>';
		output += '<td class="link">';
		output += '<a href="' + link.url + '" target="_blank">' + link.title + '</a>';
		output += '</td>';
		output += '</tr>';
		output += '<tr>';
		output += '<td>';
		output += '<a class="btn btn-primary btn-xs">';
		output += '<span class="glyphicon glyphicon-user"></span> ';
		output += link.user.username;
		output += ' </a>';
		output += ' <div class="btn btn-default btn-xs">';
		output += '<span onclick="getLinkTime('+link.id+')" class="glyphicon glyphicon-time"></span>';
		output += ' <span class="timeplace"></span>';
		output += '</div>';
		output += ' <div class="hotBtn btn btn-default btn-xs">';
		output += '<span class="glyphicon glyphicon-fire"></span>';
		if(link.hotcount != 0) {
			output += ' <span class="counter">' + parsiShow(String(link.hotcount)) + '</span>';
		}
		output += '</div>';
		output += '</td>';
		output += '</tr>';
		output += '</table>'
	}
	if(result.next_page_url != null) {
		var nextpageurl = result.next_page_url + extraparameter;
		output += "<a id='nextpagebtn' class='btn btn-primary btn-block' onclick='nextPage(\"" + nextpageurl + "\", \""+extraparameter+"\")'><span class='glyphicon glyphicon-menu-down'></span> <b>بیشتر</b> </a>";
	}
	return output;
}

function nextPage(url, extraparameter = "") {
	$('#nextpagebtn').html('لطفا صبر کنید...');
	$.ajax({
		type: 'GET',
		url: url,
		success: function(response) {
			$('#nextpagebtn').remove();
			output = showLinks(response.links, response.userHots, response.userBookmarks, extraparameter);
			$(output).hide().appendTo('#linksPlace').fadeIn(3000);
			$("html, body").animate({scrollTop: $(window).scrollTop()+$(window).height()-125},2500);
		}
	})
}

function getLinkTime(link_id) {
	var linktimeplace = $($('#' + link_id + ' .timeplace'));
	$.ajax({
		type: 'GET',
		url: baseUrl + '/guest/linktime/' + link_id,
		success: function(response) {
			linktimeplace.text(parsiShow(response));
		}
		
	});

}

$('#brand').on('mouseover', function() {
	$("html, body").animate({scrollTop: 0},200);
});

function parsiShow(string) {
	string = string.replace(/0/g, '۰');
	string = string.replace(/1/g, '۱');
	string = string.replace(/2/g, '۲');
	string = string.replace(/3/g, '۳');
	string = string.replace(/4/g, '۴');
	string = string.replace(/5/g, '۵');
	string = string.replace(/6/g, '۶');
	string = string.replace(/7/g, '۷');
	string = string.replace(/8/g, '۸');
	string = string.replace(/9/g, '۹');
	string = string.replace('ago', 'پیش');
	string = string.replace('seconds', 'ثانیه');
	string = string.replace('second', 'ثانیه');
	string = string.replace('minutes', 'دقیقه');
	string = string.replace('minute', 'دقیقه');
	string = string.replace('hours', 'ساعت');
	string = string.replace('hour', 'ساعت');
	string = string.replace('days', 'روز');
	string = string.replace('day', 'روز');
	string = string.replace('weeks', 'هفته');
	string = string.replace('week', 'هفته');
	string = string.replace('months', 'ماه');
	string = string.replace('month', 'ماه');
	string = string.replace('years', 'سال');
	string = string.replace('year', 'سال');
	string = string.replace('from now', 'بعد');
	return string;
}


$('.numberInclude').each(function() {
	$(this).text(parsiShow($(this).text()));
});

