<!-- CDN -->
	<!-- jQuery JS -->
	<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>

<!-- Local -->
	<!-- <script src="{{url('/src/js/local/jquery-3.1.1.min.js')}}"></script> -->

<script>
	var baseUrl = "{{url('/')}}";
</script>
<script src="{{url('/src/js/guest.js')}}"></script>

@yield('scripts')