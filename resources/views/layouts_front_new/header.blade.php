<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MBZPKSQ');</script>
<!-- End Google Tag Manager -->     
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{csrf_token()}}" />
	<meta name="description" content="@yield('meta-description', 'Site Description')">
	<link rel="manifest" href="{{config('app.url')}}manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>@yield('meta-title', 'Recordent')</title>
    <?php $site_favicon = Voyager::setting('site.favicon', '');?>
    @if($site_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($site_favicon) }}" type="image/png">
    @endif
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    @yield('canonical-url')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{asset('front_new/images/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('front_new/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('front_new/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('front_new/css/owl.theme.default.min.css')}}"> 
    <link rel="stylesheet" href="{{asset('front_new/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('front_new/css/style.css')}}">  

    <script src="{{asset('front_new/js/jquery-3.js')}}"></script>     
    <script src="{{asset('front_new/js/bootstrap.js')}}"></script>
    <script src="{{asset('front_new/js/owl.carousel.min.js')}}"></script>
            
</head>