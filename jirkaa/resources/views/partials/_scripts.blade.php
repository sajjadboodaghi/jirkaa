<!-- CDNs -->
	<!-- jQuery JS -->
	<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>

	<!-- Boostrap JS -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<!-- Sweet Alert JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<!-- Local -->
	<!-- <script src="{{url('/src/js/local/jquery-3.1.1.min.js')}}"></script> -->
	<!-- <script src="{{url('/src/js/local/bootstrap.min.js')}}"></script> -->
	<!-- <script src="{{url('/src/js/local/sweetalert.min.js')}}"></script> -->


<script>
	var baseUrl = "{{url('/')}}";
</script>
<!-- My JS File -->
<script src="{{url('/src/js/user.js')}}"></script>
<script>
	var user = {!! $user !!};
	makeUser(user);
</script>
<!-- preparing necessary variables -->
@yield('scripts')
