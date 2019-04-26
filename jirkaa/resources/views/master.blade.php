<!DOCTYPE html>
<html lang="fa">
	@include('partials._head')
	<body>
		@include('partials._navbar')
		<table id="mycontainer">
			<tr>
				<td id="right-side">
					@yield('right-side')
				</td>
				<td id="middle-side">
					@yield('middle-side')
				</td>
				<td id="left-side">
					@yield('left-side')
				</td>
			</tr>
		</table>
		@include('partials._scripts')
  </body>
</html>


