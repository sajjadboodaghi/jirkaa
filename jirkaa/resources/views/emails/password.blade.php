<html>
	<head>
		<style>
			body {
				direction: rtl;
				text-align: right;
			}	
		</style>
	</head>
	<body>
		<h1>نشانی بازنویسی گذرواژه</h1>
		<p>
			سلام.  <br>
			برای رفتن به صفحه بازنویسی گذرواژه به
			<a href="{{ $link = url('password/reset', $token) . '?email=' . urlencode($user->getEmailForPasswordReset())}}">این نشانی</a>
			بروید. 
		</p>
		
		<a href="{{url('/')}}">از طرف جیرکا</a>
	</body>
</html>