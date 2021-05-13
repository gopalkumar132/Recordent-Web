@include('layouts_front_ib.header')
<body class="voyager users" data-select2-id="16">
<div id="voyager-loader" style="display: none;"> <img src="images/voyager-assets.png" alt="Voyager Loader"> </div>
<div class="app-container" data-select2-id="15">
  <div class="fadetoblack visible-xs"></div>
  <div class="row content-container" data-select2-id="14">
	  @include('layouts_front_ib.header-bar')
	  @include('layouts_front_ib.side-menu-bar')
	 
	  @include('layouts_front_ib.content')
  </div>
</div>
@include('layouts_front_ib.footer')
</body>
</html>



