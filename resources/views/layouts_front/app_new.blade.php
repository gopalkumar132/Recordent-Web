 <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MBZPKSQ');</script>
<!-- End Google Tag Manager -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--<meta name="robots" content="none" />-->
    <?php $site_favicon = Voyager::setting('site.favicon', ''); ?>
    @if($site_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($site_favicon) }}" type="image/png">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>@yield('meta-title', 'Collect Dues Faster - Get Paid On Time with Recordent.')</title>
    <meta name="description" content="@yield('meta-description', 'Collections hurting business growth? Recordent helps you to collect dues faster and get paid on time. You focus on business & we will manage your collections.')">
    @yield('canonical-url')
    <link rel="manifest" href="{{config('app.url')}}manifest.json">
    <!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="{{asset('front/vendor/jquery/jquery.min.js')}}"></script>
    <link href="{{asset('front/css/style.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom.css')}}"><link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
     <link rel="stylesheet" href="{{config('app.url')}}admin/voyager-assets?path=css%2Fapp.css"> 
        <style>
          body{font-family: 'Rubik', sans-serif !important;}
          .login-container > form > .row > .col-md-6 { margin-bottom: 0px;}
          .bright-link {color: #273581 !important;font-weight: 500;}
           
			.mrautonew {  float: right; }
			.mrautonew > li > a { padding: 0 1rem !important;font-size: 1.1rem !important;text-transform: uppercase !important; font-weight: 700;}
			.login {    overflow: auto !important;}
    	    body.login .login-container { margin: 0 auto; max-width: 700px; background: #fff; padding: 25px;right: 0px;left: 0px; top: 4%;border-top: 5px solid #273581; position: relative;} 
			body.login .login-sidebar{min-height: 1200px;}
      body.login .login-button {color: #fff !important;background:#273581 !important;opacity: 1 !important;}
      #mainNav .navbar-nav{margin-top: 0px;}
			
			@media only screen and (min-width:320px) and (max-width:767px){
         body{height: auto !important;}
       
				body.login .login-sidebar{border: 0px !important;}
				.mrautonew { float: none;}
				.fix-login-menu { position: relative;   width: 100%;    left: 0;    right: 0;    min-height: 60px;}
				.fix-login-menu > .new_log {  position: absolute;top: 0px;padding-bottom: 20px;  left: 20px; }
				.fix-login-menu > .new_tog { position: absolute;top: 17px; right: 0; padding:0 !important; }
				.new_menucol { padding-top: 30px; margin: 0 auto !important;}body.login{background: none !important;}
        body.login .login-container {border: 2px solid #ddd;bottom: 20px;top: 0px;}
        

			}
			@media only screen and (min-width : 1280px) {
			 
			}
			
			@media only screen and (min-width:320px) and (max-width:767px){
			.mrautonew { float: none;}
				.fix-login-menu { position: relative;   width: 100%;    left: 0;    right: 0;    min-height: 60px;}
				.fix-login-menu > .new_log {  position: absolute;top: 0px;padding-bottom: 20px;  left: 20px; }
				.fix-login-menu > .new_tog { position: absolute;top: 10px; right: 0; }
				.new_menucol { padding-top: 30px; margin: 0 auto !important;}body.login{background: none !important;}
				.fix-login-menu > .new_tog { position: absolute;top: 17px; right: 0; padding:0 !important; }
			
			 #mainNav .navbar-nav{margin-top: 10px;}
			}
			
        body {
        background-image:url("{{asset('storage/'.setting('admin.bg_image'))}}");
        background-color: #FFFFFF;
        }
        body.login .login-sidebar {
            border-top:5px solid #22A7F0;
        }
        @media (max-width: 767px) {
            body.login .login-sidebar {
                border-top:0px !important;
                border-left:5px solid #22A7F0;
            }
        }
        body.login .form-group-default.focused{
            border-color:#22A7F0;
        }
        .login-button, .bar:before, .bar:after{
            background:#22A7F0;
        }
        .remember-me-text{
            padding:0 5px;
        }
        .copy.animated.fadeIn p{display:none;}
    </style>

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">

  
</head>

<body class="login">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MBZPKSQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
  <nav class="navbar navbar-expand-md bg-secondary text-uppercase fixed-top navbar-shrink" id="mainNav" style="border-radius: 0 !important; padding-top:0px;padding-bottom:0px;">
    <div class="container-fluid fix-login-menu">
      <a class="new_log navbar-brand js-scroll-trigger" href="{{config('app.url')}}home"><img src="{{asset('storage/'.setting('site.logo'))}}" style="width:140px"></a>
      <button class="new_tog navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <img src="{{asset('front_new/images/menu.jpg')}}" style="width:40px;">
        </button>
      <div class="collapse navbar-collapse new_menucol" id="navbarResponsive">
        
        <ul class="navbar-nav mrautonew">
 	  
 	 	<li class="nav-item mx-0 mx-lg-1">
 	 		<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{config('app.url')}}home">Home</a>
 	 	</li>
 	  
 	 	<li class="nav-item mx-0 mx-lg-1">
 	 		<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{config('app.url')}}admin/login">Login</a>
 	 	</li>
 	  
 	 	<li class="nav-item mx-0 mx-lg-1">
 	 		<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{config('app.url')}}register">Sign Up</a>
 	 	</li>
 	  </ul>
      </div>
    </div>
  </nav>
     
    <div class="container-fluid">
        <div class="row">  
            <div class="faded-bg animated"></div>
            <div class="hidden-xs col-sm-12 col-md-12">
                <div class="clearfix">
                    <div class="col-sm-12 col-md-10 col-md-offset-2">
                        <div class="logo-title-container">
                                <img class="img-responsive pull-left flip logo hidden-xs animated fadeIn" src="{{asset('storage/'.setting('admin.icon_image'))}}" alt="Logo Icon">
                                <div class="copy animated fadeIn">
                                    <h1>{{setting('admin.title')}}</h1>
                                    {{--<p>{{setting('admin.description')}}</p>--}}
                                </div>
                        </div> <!-- .logo-title-container -->
                    </div>
                </div>
            </div>  
            @yield('content')
        </div>
    </div> 
    
   

<!-- Bootstrap core JavaScript -->

  

  <script src="{{asset('front/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>



  <!-- Plugin JavaScript -->

  <script src="{{asset('front/vendor/jquery-easing/jquery.easing.min.js')}}"></script>



  <!-- Contact Form JavaScript -->

  <script src="{{asset('front/js/jqBootstrapValidation.js')}}"></script>

  <script src="{{asset('front/js/contact_me.js')}}"></script>



  <!-- Custom scripts for this template -->

  <script src="{{asset('front/js/freelancer.min.js')}}"></script>
 
<script async="" src="https://www.google-analytics.com/analytics.js"></script>
<!--Main-active-JS--> 
<!-- <script src="{{asset('front_new/js/main.js')}}"></script> -->
<script>
    $(document).ready(function(){
        if ( 'serviceWorker' in navigator ) {
        window.addEventListener( 'load', function () {
            navigator.serviceWorker.register( "{{config('app.url')}}sw.js" ).then( function ( registration ) {
                // Registration was successful
                console.log( 'ServiceWorker registration successful with scope: ', registration.scope );
            }, function ( err ) {
                // registration failed :(
                console.log( 'ServiceWorker registration failed: ', err );
            } );
        } );
    }
    });
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{setting('site.google_analytics_tracking_id')}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{setting('site.google_analytics_tracking_id')}}');

    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
    
    ga('create', '{{setting('site.google_analytics_tracking_id')}}', 'auto');
    
    ga('send', 'pageview');
</script>
<script async src='https://www.google-analytics.com/analytics.js'></script>
<!-- Hotjar Tracking Code for www.recordent.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:2112472,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>


</body>
</html>    
