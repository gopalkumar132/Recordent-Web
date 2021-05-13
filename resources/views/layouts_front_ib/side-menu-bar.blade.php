<!-- BEGIN SIDEBAR -->
@php
if(session()->get('individual_client_report_type')){
	$url = config('app.url').'business/';
}else{
	$url = config('app.url').'individual/';
}

@endphp
<div class="side-menu sidebar-inverse ps ps--theme_default ps--active-y" data-ps-id="">
      <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
          <div class="navbar-header"> <a class="navbar-brand" href="{{$url.'dashboard'}}">
            <!--<div class="logo-icon-container"> <img src="{{asset('storage/'.setting('site.favicon'))}}"> </div>-->
            <!--<div class="title"><img src="{{asset('storage/'.setting('site.logo'))}}" alt="Logo Icon"></div>-->
            </a> 
          </div>
          
          @if(!empty(session()->get('individual_client_id')))
          <div class="panel widget center bgimage"
                            style="background-image:url({{config('app.url')}}front-ib/images/bg.jpg); background-size: cover; background-position: 0px;">
            <div class="dimmer"></div>
            @if(!empty(session()->get('individual_client_id')))
            <div class="panel-content"> <img src="{{config('app.url')}}front-ib/images/default.png" class="avatar" alt="{{Session::get('individual_client_mobile_number')}} avatar">
              @if(session()->get('individual_client_mobile_number'))
                <h4>{{Session::get('individual_client_mobile_number')}}</h4>
              @else
                <h4>{{Session::get('individual_client_email')}}</h4>
              @endif
              <div style="clear:both"></div>
            </div>
            @endif
          </div>
          @endif
        </div>
        @if(!empty(session()->get('individual_client_id')))
        <div id="adminmenu">
          <ul class="nav navbar-nav">
            <li class=""><a href="{{$url.'dashboard'}}">
              <span class="icon voyager-boat"></span> <span class="title">Dashboard</span></a>               
            </li>
      			{{--<li class=""><a href="{{$url.'records'}}">
      			  <span class="icon voyager-company"></span> <span class="title">My Reports</span></a>               
      			</li>--}}
          </ul>
        </div>
        @endif
      </nav>
      <div class="ps__scrollbar-x-rail" style="width: 60px; left: 0px; bottom: 0px;">
        <div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
      </div>
      <div class="ps__scrollbar-y-rail" style="top: 0px; height: 379px; right: 0px;">
        <div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 120px;"></div>
      </div>
    </div>
    <script type="text/javascript" async="" src="{{config('app.url')}}front-ib/js/analytics.js"></script> 
    <script>
                (function () {
                    var appContainer = document.querySelector('.app-container'),
                        sidebar = appContainer.querySelector('.side-menu'),
                        navbar = appContainer.querySelector('nav.navbar.navbar-top'),
                        loader = document.getElementById('voyager-loader'),
                        hamburgerMenu = document.querySelector('.hamburger'),
                        sidebarTransition = sidebar.style.transition,
                        navbarTransition = navbar.style.transition,
                        containerTransition = appContainer.style.transition;

                    sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition =
                        appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition =
                        navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = 'none';

                    if (window.innerWidth > 768 && window.localStorage && window.localStorage['voyager.stickySidebar'] == 'true') {
                        appContainer.className += ' expanded no-animation';
                        loader.style.left = (sidebar.clientWidth / 2) + 'px';
                        hamburgerMenu.className += ' is-active no-animation';
                    }

                    navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;
                    sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;
                    appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;
                })();
            </script> 
<!-- END SIDEBAR -->