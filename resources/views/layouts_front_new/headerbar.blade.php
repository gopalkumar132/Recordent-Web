@php General::utmContainerDetect(); @endphp
<header class="w-100 header position-relative">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <!--  <style type="text/css" media="screen">
     * {
       margin: 0;
       padding: 0;
     }

     div#banner {
       position: absolute;
       top: 0;
       left: 0;
       background-color: #eedde2;
       width: 100%;
       margin: 0 auto;
       padding: 10px;
       font-size: 15px;
     }
     div#banner-content {
       width: 800px;
       margin: 0 auto;
       padding: 10px;
       font-size: 15px;
     }
     div#main-content {
       padding-top: 35px;
    }
    </style> -->
<style>
@media (min-width: 992px){
.dropbtn {
    padding-top: 6%;
    position: relative;
    display: inline-block;
	color:#000;
}
}
@media (max-width: 991px) {
.dropbtn {
    text-transform: uppercase;
    color: #000;
    font-weight: 700;
}
}
</style>



  <div class="fixed-this">
<!--     <div id="banner">
      <marquee direction="left" scrollamount="15"> <i class="fa fa-wrench"></i> Recordent website maintenance is scheduled for Saturday 6 PM..</marquee>
   </div> -->
  <div id="main-content">
        <div class="container">
            <nav class="navbar navbar-expand-lg p-0">
                <a href="{{route('home')}}" class="navbar-brand">

                    <img src="{{asset('storage/'.setting('site.logo'))}}" alt="Recordent" width="180">
                </a>
                <button class="navbar-toggler p-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <img src="{{asset('front_new/images/menu.jpg')}}"></button>
                <div class="collapse navbar-collapse justify-content-end align-items-left" id="navbarSupportedContent">
                    <ul class="navbar-nav align-items-left">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('aboutus')}}" title="">About Us</a>
                        </li>
                        <li class="sepre"></li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('creditreport')}}" title="">Credit Reports</a>
                        </li>
                        <!--<li class="sepre"></li>-->
                        <!--<li class="nav-item">-->
                        <!--    <a class="nav-link" href="{{route('solutions')}}" title="">Solutions</a>-->
                        <!--</li>-->
                        <li class="sepre"></li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('pricing-plan')}}" title="">Pricing</a>
                        </li>
                        <li class="sepre"></li>
                        <li>
                        <div class="dropdown">
                        <p class="dropbtn">Check My Credit Report</p>
                        <div class="dropdown-content">
                        <a href="{{route('your.reported.dues')}}">Individual Report</a>
                        <a href="{{route('your.reported.bussinesdues')}}">Business Report</a>
                        </div>
                        </div>
                        </li>
                        <li class="nav-item btn-member-log">
                            <a class="nav-link blue-btn" href="{{config('app.url')}}admin/login" title="">Member Log In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link red-btn" href="{{config('app.url')}}register" title="">Sign Up</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
      </div>
</header>
<script>
var dropdown = document.getElementsByClassName("dropbtn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
  this.classList.toggle("active");
  var dropdownContent = this.nextElementSibling;
  if (dropdownContent.style.display === "block") {
  dropdownContent.style.display = "none";
  } else {
  dropdownContent.style.display = "block";
  }
  });
}
</script>
