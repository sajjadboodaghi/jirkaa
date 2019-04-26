<nav class="navbar navbar-default navbar-fixed-top">
	<table id="guest-navbar-container">
		<tr>
			<td>
				<a id="brand" href="{{url('/guest/welcome')}}" class="navbar-item"><span class="glyphicon glyphicon-home"></span> جیرکا</a>
			</td>
			<form name="search">
			<td><input id="searchkeywords" autocomplete="off" type="text" class="form-control" name="keywords" placeholder="جستجو..." minlength="2" required></td>
			<td><button class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button></td>
			</form>
			<td></td>
			<td>
				<a class="navbar-item"><span class="glyphicon glyphicon-education"></span> آموزش</a>
				<a class="navbar-item" href="{{url('/guest/about')}}"><span class="glyphicon glyphicon-question-sign"></span> درباره ما</a>
			</td>
		</tr>
	</table>
</nav>