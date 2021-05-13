 	 @foreach($items as $menu_item) 
 	 		<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{ url($menu_item->link()) }}">{{ $menu_item->title }}</a>
 	 @endforeach
