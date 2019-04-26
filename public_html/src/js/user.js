$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
	}
});

var user;
function makeUser(user) {
	window.user = user;
}

function followUser(username) {
    followBtn = $("#followBtn-" + username);
    $.ajax({
      type: 'POST',
      url: baseUrl + '/user/follow/' + username
    }).done(function(response) {
      if(response.type == "followed") {
        followBtn.removeClass('btn-defualt').addClass('btn-success');
        followBtn.text('دنبال می‌کنید');
      } else if (response.type == "unfollowed") {
        followBtn.removeClass('btn-success').addClass('btn-default');
        followBtn.text('دنبال نمی‌کنید');
      }
    });
};

function showLinks(result, userHots, userBookmarks, extraparameter="") {
	links = result.data;
	userHots = JSON.parse(userHots);
	userBookmarks = JSON.parse(userBookmarks);
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
		if(link.user_id == window.user.id) {
			output += '<a class="btn btn-default btn-xs" href="' + baseUrl + '/user/profile/' + user.username + '">';
			output += '<span class="glyphicon glyphicon-user"></span> ';
			output += user.username;
			output += '</a>';
			output += ' <div onclick="getLinkTime('+link.id+')" class="btn btn-default btn-xs">';
			output += '<span class="glyphicon glyphicon-time"></span>';
			output += ' <span class="timeplace"></span>';
			output += '</div>';
			output += ' <button class="btn btn-default btn-xs">';
			output += '<span class="glyphicon glyphicon-fire"></span>';
			if(link.hotcount != 0) {
				output += ' <span>' + parsiShow(String(link.hotcount)) + '</span>';
			}
			output += '</button>';
			output += ' <div class="btn btn-danger btn-xs" onclick="deleteLink(' + link.id + ')">';
			output += '<span class="glyphicon glyphicon-trash"></span>'
			output += ' حذف';
			output += '</div>';
			output += ' <a class="btn btn-info btn-xs" href="' + baseUrl + '/link/edit/' + link.id + '">';
			output += '<span class="glyphicon glyphicon-edit"></span>';
			output += ' ویرایش';
			output += '</a>';
		} else {
			output += '<a class="btn btn-primary btn-xs" href="' + baseUrl + '/user/profile/' + link.user.username + '">';
			output += '<span class="glyphicon glyphicon-user"></span> ';
			output += link.user.username;
			output += ' </a>';
			output += ' <div class="btn btn-default btn-xs">';
			output += '<span onclick="getLinkTime('+link.id+')" class="glyphicon glyphicon-time"></span>';
			output += ' <span class="timeplace"></span>';
			output += '</div>';
			if(indexOf(userHots, link.id) != -1) {
				output += ' <div class="hotBtn btn btn-warning btn-xs">';
			} else {
				output += ' <div class="hotBtn btn btn-default btn-xs">';
			}
			output += '<span class="glyphicon glyphicon-fire"></span>';
			if(link.hotcount != 0) {
				output += ' <span class="counter">' + parsiShow(String(link.hotcount)) + '</span>';
			}
			output += '</div>';
			if(indexOf(userBookmarks, link.id) != -1) {
				output += ' <div class="bookmarkBtn btn btn-success btn-xs">';
			} else {
				output += ' <div class="bookmarkBtn btn btn-default btn-xs">';
			}
			output += '<span class="glyphicon glyphicon-bookmark"></span>';
			output += '</div>';
			output += ' <div class="btn btn-default btn-xs" onclick="reportLink(' + link.id + ')">';
			output += '<span class="glyphicon glyphicon-exclamation-sign"></span>';
			output += ' گزارش';
			output += '</div>';
		}
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

function getLinkTime(link_id) {
	var linktimeplace = $($('#' + link_id + ' .timeplace'));
	$.ajax({
		type: 'GET',
		url: baseUrl + '/link/linktime/' + link_id,
		success: function(response) {
			linktimeplace.text(parsiShow(response));
		}
		
	});

}

function indexOf(array, item) {
	for(var i = 0; i < array.length; i++) {
		if(array[i].toString() == item.toString()) {
			return i;
		}
	}
	return -1;
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

$('form[name="search"]').submit(function(e){
	e.preventDefault();
	var keywords = $('#searchkeywords').val();
	$.ajax({
		type: 'GET',
		url: baseUrl + '/search?keywords=' + keywords,
		success: function(response) {
			$("html, body").animate({scrollTop: 0},0);
			output = showLinks(response.links, response.userHots, response.userBookmarks, '&keywords='+keywords);
			completeoutput = '<div id="middle-side-menu" class="panel panel-primary">';
			completeoutput += '<div class="panel-heading  text-center" id="linksMenu">';
			completeoutput += '<a onclick="resetLinks()" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-share-alt"></span> برگشت به اصلی</a> <span class="glyphicon glyphicon-search"></span> نتیجه جستجو برای «<b>'+keywords+'</b>»';
			completeoutput += '</div>';
			completeoutput += '<div class="panel-body" id="linksPlace">' + output + '</div>';
			completeoutput += '</div>';
			$('#middle-side').html(completeoutput);
			$('#searchkeywords').val("");
			notify(response.notificationsCount);
		}
	})
});

function resetLinks() {
	$('#linksMenu').html('<div class="btn-group"><a onclick="showFriendsLinks()" id="friendslinksbtn" type="button" class="btn-default btn btn-xs" title="پیوندهای کاربرانی که شما دنبال می‌کنید"><span class="glyphicon glyphicon-user"></span> پیوندهای دوستان</a><a onclick="showBookmarksLinks()" id="bookmarkslinksbtn" type="button" class="btn-default btn btn-xs" title="پیوندهایی که شما نشان کردید"><span class="glyphicon glyphicon-bookmark"></span> نشان‌های شما</a><a onclick="showHottestLinks()" id="hottestlinksbtn" type="button" class="btn-default btn btn-xs" title="داغ‌ترین پیوندها"><span class="glyphicon glyphicon-fire"></span> داغ‌ترین‌ها</a><a onclick="showHottest24Links()" id="hottest24linksbtn" type="button" class="btn-default btn btn-xs" title="پیوندهایی که امروز داغ شدند"><span class="glyphicon glyphicon-time"></span> داغ‌های امروز</a><a onclick="showLastLinks()" id="lastlinksbtn" type="button" class="btn-default btn btn-xs" title="تازه‌سازی پیوندها"><span class="glyphicon glyphicon-refresh"></span> تازه‌ترین‌ها</a></div>');
	$('#linksPlace').html("لطفا صبر کنید...");
	showLastLinks();
}

function reportLink(link_id) {
	swal({
		title: 'گزارش محتوای نامناسب',
		text: 'آیا محتوای این پیوند نامناسب است؟',
		html: true,
		confirmButtonText: 'بله',
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		cancelButtonText: 'خیر'
	}, function() {
		$.ajax({
			type: 'POST',
			url: baseUrl + '/report/store/' + link_id,
			success: function() {
				swal({
					title: 'گزارش ارسال شد :)',
					text: 'از توجه و همکاری شما متشکریم.',
					type: 'success',
					confirmButtonText: 'متوجه شدم',
				})
			}
		});
	});
}

function showFriendsLinks() {
	$('#linksPlace').html("لطفا صبر کنید...");
	$.ajax({
		type: 'GET',
		url: baseUrl + '/search/friendslinks',
		success: function(response) {
			$('#linksPlace').html(showLinks(response.links, response.userHots, response.userBookmarks));
			$('#friendslinksbtn').removeClass('btn-default').addClass('btn-info');
			if($('#bookmarkslinksbtn').hasClass('btn-success')) {
				$('#bookmarkslinksbtn').removeClass('btn-success').addClass('btn-default');
			}
			if($('#hottestlinksbtn').hasClass('btn-warning')) {
				$('#hottestlinksbtn').removeClass('btn-warning').addClass('btn-default');
			}
			if($('#hottest24linksbtn').hasClass('btn-warning')) {
				$('#hottest24linksbtn').removeClass('btn-warning').removeClass('active').addClass('btn-default');
			}
			if($('#lastlinksbtn').hasClass('btn-danger')) {
				$('#lastlinksbtn').removeClass('btn-danger').addClass('btn-default');
			}
			notify(response.notificationsCount);
		}
	});
}


function showBookmarksLinks() {
	$('#linksPlace').html("لطفا صبر کنید...");
	$.ajax({
		type: 'GET',
		url: baseUrl + '/search/bookmarks',
		success: function(response) {
			$('#linksPlace').html(showLinks(response.links, response.userHots, response.userBookmarks));
			$('#bookmarkslinksbtn').removeClass('btn-default').addClass('btn-success');
			if($('#friendslinksbtn').hasClass('btn-info')) {
				$('#friendslinksbtn').removeClass('btn-info').addClass('btn-default');
			}
			if($('#hottestlinksbtn').hasClass('btn-warning')) {
				$('#hottestlinksbtn').removeClass('btn-warning').addClass('btn-default');
			}
			if($('#hottest24linksbtn').hasClass('btn-warning')) {
				$('#hottest24linksbtn').removeClass('btn-warning').removeClass('active').addClass('btn-default');
			}
			if($('#lastlinksbtn').hasClass('btn-danger')) {
				$('#lastlinksbtn').removeClass('btn-danger').addClass('btn-default');
			}
			notify(response.notificationsCount);
		}
	});
}


function showHottestLinks() {
	$('#linksPlace').html("لطفا صبر کنید...");
	$.ajax({
		type: 'GET',
		url: baseUrl + '/search/hottest',
		success: function(response) {
			$('#linksPlace').html(showLinks(response.links, response.userHots, response.userBookmarks));
			$('#hottestlinksbtn').removeClass('btn-default').addClass('btn-warning');
			if($('#friendslinksbtn').hasClass('btn-info')) {
				$('#friendslinksbtn').removeClass('btn-info').addClass('btn-default');
			}
			if($('#bookmarkslinksbtn').hasClass('btn-success')) {
				$('#bookmarkslinksbtn').removeClass('btn-success').addClass('btn-default');
			}
			if($('#hottest24linksbtn').hasClass('btn-warning')) {
				$('#hottest24linksbtn').removeClass('btn-warning').removeClass('active').addClass('btn-default');
			}
			if($('#lastlinksbtn').hasClass('btn-danger')) {
				$('#lastlinksbtn').removeClass('btn-danger').addClass('btn-default');
			}
			notify(response.notificationsCount);
		}
	});
}

function showHottest24Links() {
	$('#linksPlace').html("لطفا صبر کنید...");
	$.ajax({
		type: 'GET',
		url: baseUrl + '/search/hottest24',
		success: function(response) {
			$('#linksPlace').html(showLinks(response.links, response.userHots, response.userBookmarks));
			$('#hottest24linksbtn').removeClass('btn-default').addClass('btn-warning').addClass('active');
			if($('#friendslinksbtn').hasClass('btn-info')) {
				$('#friendslinksbtn').removeClass('btn-info').addClass('btn-default');
			}
			if($('#bookmarkslinksbtn').hasClass('btn-success')) {
				$('#bookmarkslinksbtn').removeClass('btn-success').addClass('btn-default');
			}
			if($('#hottestlinksbtn').hasClass('btn-warning')) {
				$('#hottestlinksbtn').removeClass('btn-warning').addClass('btn-default');
			}
			if($('#lastlinksbtn').hasClass('btn-danger')) {
				$('#lastlinksbtn').removeClass('btn-danger').addClass('btn-default');
			}
			notify(response.notificationsCount);
		}
	});
}

function showLastLinks() {
	$('#linksPlace').html("لطفا صبر کنید...");
	$.ajax({
		type: 'GET',
		url: baseUrl + '/search/last',
		success: function(response) {
			$('#linksPlace').html(showLinks(response.links, response.userHots, response.userBookmarks));
			$('#lastlinksbtn').removeClass('btn-default').addClass('btn-danger');
			if($('#friendslinksbtn').hasClass('btn-info')) {
				$('#friendslinksbtn').removeClass('btn-info').addClass('btn-default');
			}
			if($('#bookmarkslinksbtn').hasClass('btn-success')) {
				$('#bookmarkslinksbtn').removeClass('btn-success').addClass('btn-default');
			}
			if($('#hottestlinksbtn').hasClass('btn-warning')) {
				$('#hottestlinksbtn').removeClass('btn-warning').addClass('btn-default');
			}
			if($('#hottest24linksbtn').hasClass('btn-warning')) {
				$('#hottest24linksbtn').removeClass('btn-warning').removeClass('active').addClass('btn-default');
			}
			notify(response.notificationsCount);
		}
	});
}

function notify(notificationsCount) {
	if(notificationsCount > 0) {
		$('#notificationsCounter').html('<b>(' + parsiShow(notificationsCount.toString()) + ')</b>');
	}
}

function deleteLink(link_id) {
	var link = $('.link-container#' + link_id + ' .link a');
	swal({
		title: 'آیا از حذف این پیوند مطمئن هستید؟',
		text: link[0].outerHTML,
		html: true,
		confirmButtonText: 'بله٬ حذف کنید',
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		cancelButtonText: 'خیر'
	}, function() {
		$.ajax({
			type: "POST",
			url: baseUrl + '/link/delete/' + link_id,
			success: function(response) {
				link.parents('.link-container').fadeOut(1000);
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

$(document).on('click', '.hotBtn', function() {
	var hotbtn = $(this);
	var link = $($(this).parents('.link-container'));
	var linkid = link.attr('id');
	var counter = link.find('.counter');
	$.ajax({
		type: 'GET',
		url: baseUrl + '/hot/switch/' + linkid,
		success: function(response) {
			if(response.type == "hotted") {
				if(counter.length != 0) {
					counter.text(parsiShow(String(response.hotcount)));
				} else {
					hotbtn.append(' <span class="counter">۱</span>');
				}

				hotbtn.removeClass('btn-default').addClass('btn-warning');

			} else if (response.type == "unhotted") {
				if(counter.length != 0) {
					if(response.hotcount == 0) {
						counter.remove();
					} else {
						counter.text(' ' + parsiShow(String(response.hotcount)));
					}
				}

				hotbtn.removeClass('btn-warning').addClass('btn-default');			
			}
		}
	});
});

$(document).on('click', '.bookmarkBtn', function() {
	bookmarkBtn = $(this);
	var link = $(bookmarkBtn.parents('.link-container'));
	var linkid = link.attr('id');
	$.ajax({
		method: 'GET',
		url: baseUrl + '/bookmark/switch/' + linkid,
		success: function(response) {
			if(response.type == "bookmarked") {
				bookmarkBtn.removeClass('btn-default').addClass('btn-success');
			} else if(response.type == "unbookmarked") {
				bookmarkBtn.removeClass('btn-success').addClass('btn-default');
			}
		}
	});

});

function validate(data, rules) {
	var status = true;
	var messages = [];
	for(var field in rules) {
		currentrules = rules[field].split('@');
		caption = currentrules[0];
		currentrules = currentrules[1].split('|');
		for(var i = 0; i < currentrules.length; i++) {
			if(currentrules[i] == 'required') {
				if(data[field].trim() == "") {
					status = false;
					messages.push(caption + ' نمیتواند خالی رها شود!');					
				}
			} else if(currentrules[i].indexOf('max') != -1) {
				ruleValue = currentrules[i].split(':')[1];
				if(data[field].length > ruleValue) {
					status = false;
					messages.push('طول ' + caption + ' نمیتواند بیشتر از ' + (parsiShow(String(ruleValue))) + ' باشد!');
				}
			} else if(currentrules[i].indexOf('min') != -1) {
				ruleValue = currentrules[i].split(':')[1];
				if(data[field].length < ruleValue) {
					status = false;
					messages.push('طول ' + caption + ' نمیتواند کمتر از ' + (parsiShow(String(ruleValue))) + ' باشد!');
				}
			}
			
		}
	}
	return {status: status, messages: messages};
}

function showUserDailies(dailies) {
	if(dailies.length == 0) {
		output = '<div class="alert alert-info">';
		output += '<small>هنوز هیچ پیوند و یا دسته‌ای به اینجا اضافه نکرده‌اید. برای این کار به قسمت <a href="' + baseUrl + '/daily">مدیریت روزانه‌ها</a> بروید.</small>';
		output += '</div>';
		return output;
	} else {
		var output = "";
		for(var i = 0; i < dailies.length; i++) {
			daily = dailies[i];
			if(daily.type == 'link') {
				output += '<a href="'+ daily.url + '" target="_blank" class="daily-link">';
				output += '<span class="glyphicon glyphicon-link"></span> ';
				output += daily.title;
				output += '</a>';
			} else if(daily.type == 'cat') {
				output += '<a onclick="getLinksOfCategory('+ daily.id + ')" class="daily-link" type="button" data-toggle="collapse" data-target="#daily-' + daily.id + '" aria-expanded="false">';
				output += '<span class="glyphicon glyphicon-option-vertical"></span> ';
				output += daily.title;
				output += '</a>';
				output += '<div class="collapse" id="daily-' + daily.id + '">';
				output += '<div class="well" id="dailyCatLinksPlace-' + daily.id + '"></div>';
				output += '</div>';
			}
		}
		return output;
	}
}



function showPopularTags(tags) {
	var output = "";
	for(var i = 0; i < tags.length; i++) {
		output += '<div class="tag-box">';
		output += '<span class="tag-detail">[<span>' + parsiShow(String(tags[i].count)) + '</span>]</span>';
		output += ' <a class="tag" onclick="showTagsLinks('+tags[i].id+')">' + tags[i].title + '</a>';
		output += '</div>';
	}
	return output;
}

function showTagsLinks(tag_id) {
	$('#linksPlace').html("لطفا صبر کنید...");
	$.ajax({
		type: 'GET',
		url: baseUrl + '/tag/last/' + tag_id,
		success: function(response) {
			$("html, body").animate({scrollTop: 0},0);
			$('#linksMenu').html('<a onclick="resetLinks()" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-share-alt"></span> برگشت به اصلی</a><span class="label label-info"><span class="glyphicon glyphicon-tag"></span> ' + response.tag.title + '</span><span class="label label-default">به ترتیب تاریخ ارسال</span><a onclick="showHottestTagsLinks('+response.tag.id+')" class="btn btn-default pull-left btn-xs"><span class="glyphicon glyphicon-fire"></span> داغ‌ترین‌ها</a>');
			$('#linksPlace').html(showLinks(response.links, response.userHots, response.userBookmarks));
			notify(response.notificationsCount);
		}
	});
}

function showHottestTagsLinks(tag_id) {
	$('#linksPlace').html("لطفا صبر کنید...");
	$.ajax({
		type: 'GET',
		url: baseUrl + '/tag/hottest/' + tag_id,
		success: function(response) {
			$('#linksMenu').html('<a onclick="resetLinks()" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-share-alt"></span> برگشت به اصلی</a><span class="label label-info"><span class="glyphicon glyphicon-tag"></span> ' + response.tag.title + '</span><span class="label label-warning">به ترتیب داغ‌ترین‌ها</span><a onclick="showTagsLinks('+response.tag.id+')" class="btn btn-default pull-left btn-xs"><span class="glyphicon glyphicon-time"></span> جدیدترین‌ها</a>');
			$('#linksPlace').html(showLinks(response.links, response.userHots, response.userBookmarks));
			notify(response.notificationsCount);
		}
	});
}

function showProfileLinks(username) {
	$.ajax({
		type: 'GET',
		url: baseUrl + '/user/profilelinks/' + username,
		success: function(response) {
			$('#linksPlace').html(showLinks(response.links, response.userHots, response.userBookmarks));
			notify(response.notificationsCount);
		}
	})
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