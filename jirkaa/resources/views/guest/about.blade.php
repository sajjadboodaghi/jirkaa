@extends('guest.master')

@section('right-side')
<div class="panel panel-default">
	<div class="panel-heading">
		<img src="{{url('/src/images/about-image.png')}}" class="img-thumbnail">
	</div>
</div>

@endsection

@section('middle-side')
	<div class="panel panel-primary">
		<div class="panel-heading"><span class="glyphicon glyphicon-question-sign"></span> درباره ما</div>
		<div class="panel-body">
			<blockquote class="blockquote-reverse">
				<p style="text-align: justify;">
					<b>سلام!</b><br>
					نبود و شایدم نشناختن جایی برای نگه‌داری چیزهای مفید و مورد نیازم و همین‌طور علاقم به اشتراک‌گذاری چیزهایی جالب با دیگران٬ باعث شد که از مدت‌ها قبل به فکر ساختن جایی برای این کار باشم. خدا رو شکر که بعد از حدود سه-چهار سال که این فکر رو تو ذهنم می‌پروروندم امسال موفق شدم که اینجا رو بسازم. <b>جیرکا</b> جایی هست که هم چیزهای جالب و موردنیازمون تو اینترنت رو واسمون نگه میداره و هم اینکه محیطی رو فراهم میکنه که چیزهای جالب رو برای هم به اشتراک بذاریم؛ تا همه با هم لذت ببریم :)
					
				</p>
  				<footer>پاییز ۹۵ <cite title="Source Title"><b>سجاد بوداغی</b></cite></footer>
  				<small>sajjad.boodaghi@gmail.com</small>
			</blockquote>
				
		</div>
	</div>
@endsection