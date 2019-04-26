<!DOCTYPE html>
<html lang="fa">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>پیدا نشد!</title>
		
		<!-- CDNs -->
			<!-- Boostrap CSS -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Local -->
			<!-- <link rel="stylesheet" href="{{url('/src/css/local/bootstrap.min.css')}}"> -->
		
		<!-- My CSS -->
		<link rel="stylesheet" href="{{url('src/css/style.css')}}">
	</head>

	<body>
		
		<nav class="navbar navbar-default navbar-fixed-top">
			<table id="guest-navbar-container">
				<tr>
					<td>
						<a href="{{url('/')}}" class="btn btn-primary">
							<span class="glyphicon glyphicon-home"></span> جیرکا
						</a>
					</td>
					<td>
						<button class="btn btn-default">
							<span class="glyphicon glyphicon-exclamation-sign"></span> صفحه‌ای با این نشانی پیدا نشد! :(
						</button>
					</td>
				</tr>
			</table>
		</nav>

		<table id="mycontainer" >
		</table>
		
  </body>
</html>