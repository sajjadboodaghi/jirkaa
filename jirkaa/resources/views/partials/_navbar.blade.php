<nav class="navbar navbar-default navbar-fixed-top">
	<table id="navbar-container">
		<tr>
			<td>
				<a id="brand" href="{{url('/user/dashboard')}}" class="navbar-item"><span class="glyphicon glyphicon-home"></span> جیرکا</a>
				<a class="navbar-item" href="{{url('/user/notifications')}}"><span class="glyphicon glyphicon-bell"></span> خبر <span id="notificationsCounter"></span></a>
				<a class="navbar-item" href="{{url('/link/create')}}"><span class="glyphicon glyphicon-send"></span> ارسال پیوند</a>
			</td>
			<form action="{{url('/search')}}" method="GET" name="search">
			<td><input id="searchkeywords" autocomplete="off" type="text" class="form-control" name="keywords" placeholder="جستجو..." required minlength="2"></td>
			<td><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button></td>
			</form>

			<td>
				<a class="navbar-item" href="{{url('/user/bestusers')}}" title="کاربرانی که پیوندهایشان بیشتر از همه داغ شده است"><span class="glyphicon glyphicon-certificate"></span> کاربران برتر</a>
			</td>
			<td>
				<a class="navbar-item" href="{{url('/user/followers')}}" title="فهرست کاربرانی که شما را دنبال می‌کنند"><span class="glyphicon glyphicon-user"></span> روابط دوستی</a>
			</td>
			<td>
				<a class="navbar-item" href="{{url('/daily')}}" title="مدیریت پیوندهای روزانه شما"><span class="glyphicon glyphicon-tasks"></span> مدیریت روزانه‌ها</a>
			</td>
			<td class="pull-left">
		        
	          <div class="dropdown">
				  <a class="dropdown-toggle" href="#" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				  	<img src="{{url('/src/images/avatars/small_'.$user->image)}}" class="navbar-user-image">
				  </a>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
				    <li><a href="{{url('/user/image')}}">تصویر کاربری <span class="glyphicon glyphicon-picture"></span></a></li>
				    <li><a href="{{url('/user/about')}}"> درباره من <span class="glyphicon glyphicon-comment"></span></a></li>
				    <li><a href="{{url('/user/profile/'.$user->username)}}">نمایه شما <span class="glyphicon glyphicon-eye-open"></span></a></li>
				    <li role="separator" class="divider"></li>
				    <li><a href="{{url('/user/email')}}">تغییر رایانامه <span class="glyphicon glyphicon-envelope"></span></a></li>
				    <li><a href="{{url('/user/password')}}">تغییر گذرواژه <span class="glyphicon glyphicon-lock"></span></a></li>
				    <li role="separator" class="divider"></li>
				    <li><a href="{{url('logout')}}">خروج <span class="glyphicon glyphicon-off"></span></a></li>
				  </ul>
				</div>
			</td>		
		</tr>
	</table>
</nav>