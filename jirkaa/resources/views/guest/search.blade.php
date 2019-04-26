@extends('guest.master')

@section('middle-side')
  <div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> نتیجه جستجو برای « {{$keywords}} »</div>
    <div class="panel-body" id="links-box">
    @if(count($links) == 0)
    	<div class="alert alert-info">جستجو بی نتیجه بود :(</div>
    @else
      @include('guest.partials._links')
     @endif
    </div>
  </div>
@endsection