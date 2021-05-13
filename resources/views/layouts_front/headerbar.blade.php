
<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav" style="background-color: #3f51b5 !important; padding-top:0px;padding-bottom:0px;">
    <div class="container-fluid">
      <a class="navbar-brand js-scroll-trigger" href="{{route('home')}}"><img src="{{asset('storage/'.setting('site.logo'))}}" style="height:40px;width:auto"></a>
      <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        {{--<ul class="navbar-nav ml-auto">
          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#portfolio">Portfolio</a>
          </li>
          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#about">About</a>
          </li>
          <li class="nav-item mx-0 mx-lg-1">
            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact">Contact</a>
          </li>
        </ul>--}}
        {!!menu('front-main-menu','layouts_front.main-menu')!!}
      </div>
    </div>
  </nav>
	
	