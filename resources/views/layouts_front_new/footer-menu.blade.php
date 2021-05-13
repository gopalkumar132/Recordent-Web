@foreach($items as $menu_item) 
	<a class="nav-link " target="_blank" href="{{ url($menu_item->link()) }}">{{ $menu_item->title }}</a>
@endforeach