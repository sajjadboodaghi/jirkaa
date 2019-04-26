@foreach($links as $link)
	<table class="link-container">
		<tr>
			<td rowspan="2" class="user-image-column">
				<img src="{{url('/src/images/avatars/small_'.$link->user->image)}}" class="user-image">
			</td>
			<td class="link">
				<a id="link-{{$link->id}}" href="{{$link->url}}" target="_blank">{{$link->title}}</a>
			</td>
		</tr>

		<tr>
			<td>
				<div class="btn btn-default btn-xs">
					<span class="glyphicon glyphicon-user"></span>
					{{$link->user->username}}
				</div>
				<div class="btn btn-default btn-xs">
					<span class="glyphicon glyphicon-time"></span>
					<span class="numberInclude">{{$link->created_at->diffForHumans()}}</span>
				</div>
				@if($link->hotcount != 0)
					<div class="btn btn-default btn-xs">
						<span class="glyphicon glyphicon-fire"></span>
						<span class="numberInclude">{{$link->hotcount}}</span>
					</div>
				@endif
			</td>
		</tr>
	</table>
	
@endforeach
<div class="text-center">
	{{$links->links()}}
</div>