<!DOCTYPE html>

<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">

<head>

    <title>@yield('page_title', setting('admin.title') . " - " . setting('admin.description'))</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <meta name="assets-path" content="{{ route('voyager.voyager_assets') }}"/>

    <link rel="manifest" href="{{config('app.url')}}manifest.json">
    <script src="{{ asset('js/jquery-3-4-1.js') }}"></script>

    <!-- Google Fonts -->

     <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">



    <!-- Favicon -->

    <?php $admin_favicon = Voyager::setting('admin.favicon', ''); ?>

    @if($admin_favicon == '')

        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">

    @else

        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">

    @endif







    <!-- App CSS -->

    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">



    @yield('css')

    @if(config('voyager.multilingual.rtl'))

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">

        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">

    @endif



    <!-- Few Dynamic Styles -->

    <style type="text/css">

        .voyager .side-menu .navbar-header {

            background:{{ config('voyager.primary_color','#22A7F0') }};

            border-color:{{ config('voyager.primary_color','#22A7F0') }};

        }

        .widget .btn-primary{

            border-color:{{ config('voyager.primary_color','#22A7F0') }};

        }

        .widget .btn-primary:focus, .widget .btn-primary:hover, .widget .btn-primary:active, .widget .btn-primary.active, .widget .btn-primary:active:focus{

            background:{{ config('voyager.primary_color','#22A7F0') }};

        }

        .voyager .breadcrumb a{

            color:{{ config('voyager.primary_color','#22A7F0') }};

        }

    </style>



    @if(!empty(config('voyager.additional_css')))<!-- Additional CSS -->

        @foreach(config('voyager.additional_css') as $css)<link rel="stylesheet" type="text/css" href="{{ asset($css) }}">@endforeach

    @endif



    @yield('head')



</head>



<body class="voyager @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">



<div id="voyager-loader">

    <?php $admin_loader_img = Voyager::setting('admin.loader', ''); ?>

    @if($admin_loader_img == '')

        <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Voyager Loader">

    @else

        <img src="{{ Voyager::image($admin_loader_img) }}" alt="Voyager Loader">

    @endif

</div>



<?php

if (starts_with(app('VoyagerAuth')->user()->avatar, 'http://') || starts_with(app('VoyagerAuth')->user()->avatar, 'https://')) {

    $user_avatar = app('VoyagerAuth')->user()->avatar;

} else {

    $user_avatar = Voyager::image(app('VoyagerAuth')->user()->avatar);

}

?>



<div class="app-container">

    <div class="fadetoblack visible-xs"></div>

    <div class="row content-container">

        @include('voyager::dashboard.navbar')

        @include('voyager::dashboard.sidebar')

        <script>

            (function(){

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

                        loader.style.left = (sidebar.clientWidth/2)+'px';

                        hamburgerMenu.className += ' is-active no-animation';

                    }



                   navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;

                   sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;

                   appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;

            })();

        </script>

        <!-- Main Content -->

        <div class="container-fluid">

            <div class="side-body padding-top">

                @yield('page_header')

                <div id="voyager-notifications"></div>

                @yield('content')

            </div>

        </div>

    </div>

</div>

@include('voyager::partials.app-footer')



<!-- Javascript Libs -->





<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.2.4.js"></script>
<script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>
<!--<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->
<script src="{{ asset('js/jquery.userTimeout.js') }}"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<script>
    $('.ms_select').multiselect({
            nonSelectedText: 'Select',
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true
            // buttonWidth:'200px'
        });
</script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script  src="{{ asset('js/form-validation.js') }}"></script>
<script>
$(document).userTimeout({
    logouturl: "{{url('logout')}}",
    //session: 7200000,
     session: 1200000,
    force: 10000,
});
</script>



<script>

	$(':input').removeAttr('placeholder');

</script>



<script>

    @if(Session::has('alerts'))

        let alerts = {!! json_encode(Session::get('alerts')) !!};

        helpers.displayAlerts(alerts, toastr);

    @endif



    @if(Session::has('message'))



    // TODO: change Controllers to use AlertsMessages trait... then remove this

    var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};

    var alertMessage = {!! json_encode(Session::get('message')) !!};

    var alerter = toastr[alertType];



    if (alerter) {

        alerter(alertMessage);

    } else {

        toastr.error("toastr alert-type " + alertType + " is unknown");

    }

    @endif

</script>

@include('voyager::media.manager')

@yield('javascript')

@stack('javascript')

@if(!empty(config('voyager.additional_js')))<!-- Additional Javascript -->

    @foreach(config('voyager.additional_js') as $js)<script type="text/javascript" src="{{ asset($js) }}"></script>@endforeach

@endif

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
    $(document).ready(function(){


        var isfirsttime_value=  localStorage.getItem('is_first_time');

      if(isfirsttime_value == 1)
      {

        $('.app-container').removeClass('expanded');
      }
      else{
        $('.app-container').addClass('expanded');
      }

       $('#clickhamber').removeClass('is-active');
   });

$("#clickhamber").on("click",function(e){

        if ($('.app-container').hasClass('expanded'))
        {
            localStorage.setItem('is_first_time', 1);
            $('.app-container').removeClass('expanded');
        }
        else{
            $('.app-container').addClass('expanded');
             localStorage.setItem('is_first_time', 2);
        }
        e.stopImmediatePropagation();
});

var observer = new MutationObserver(function(mutations) {
  mutations.forEach(function(mutation) {
    if (mutation.attributeName === "class") {
      var attributeValue = $(mutation.target).prop(mutation.attributeName);
      $("#clickhamber").removeClass('is-active');
    }
  });
});
observer.observe($("#clickhamber")[0], {
  attributes: true
});
</script>

</body>

</html>
