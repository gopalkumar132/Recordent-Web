<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>404 NOT FOUND</title>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{csrf_token()}}" />
        <meta name="description" content="{{setting('site.description')}}">
        <link rel="manifest" href="{{config('app.url')}}manifest.json">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{{setting('site.title')}}</title>
        <?php $site_favicon = Voyager::setting('site.favicon', '');?>
        @if($site_favicon == '')
            <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
        @else
            <link rel="shortcut icon" href="{{ Voyager::image($site_favicon) }}" type="image/png">
        @endif
        <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
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
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,900" rel="stylesheet">

        <header class="w-100 header position-relative">
          <div class="fixed-this">
              <div id="main-content">
                <div class="container">
                    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                        <a href="/admin" class="navbar-brand">
                            <img src="{{asset('front_new/images/logo.png')}}" alt="Recordent" width="180">
                        </a>
                    </nav>
                </div>
              </div>
            </div>
        </header>
    </head>
    <body>
        <main>
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="error-content">
                        <h1><b>Oops !</b></h1>
                        <p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
                        <br>

                        <!-- <a href="/admin" id="home_button" >Go to Dashboard</a> -->
                        
                        <script type="text/javascript">
                            $.ajax({
                                url: "{{ route('ajax-get-login-status') }}",
                                method: "get",
                                success: function(response){
                                    
                                    var buton_url = "/admin";
                                    var home_button_text = 'Go to Dashboard';

                                    if (!response.is_user_logged_in) {
                                        buton_url = "/";
                                        home_button_text = 'Go Home';
                                    }

                                    var anchor = $("<a />");
                                    anchor[0].href = buton_url;
                                    anchor.html(home_button_text);
                                    $(".error-content").append(anchor);
                                }
                            });
                        </script>
                    </div>
                </div>  
            </div>
        </main>
        {{-- <footer>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-5 col-xl-5">
                        @include('layouts_front_new/join-newsletter-email')
                    </div>
                    <div class="col-12 col-md-12 col-lg-7 col-xl-7">
                        <div class="d-flex justify-content-between csd-flex-wrap">
                            <div class="footer-linking">
                                <h3>About us</h3>
                                <ul>
                                    <li><a href="{{route('aboutus')}}">About Us</a></li>
                                    <li><a href="{{config('app.url')}}pricing-plan">Pricing</a></li>
                                    <li><a href="{{route('aboutus')}}#our-team">Our Team</a></li>
                                    <li><a href="{{route('aboutus')}}#contact-us">Contact Us</a></li>
                                    <li><a href="{{route('careers')}}">Careers</a></li>
                                </ul>
                            </div>
                            <div class="footer-linking">
                                <h3>Solutions</h3>
                                <ul>
                                    <li><a href="{{route('solutions')}}#report-payments">Submit Payments</a></li>
                                    <li><a href="{{route('solutions')}}#messaging">Messaging</a></li>
                                    <li><a href="{{route('solutions')}}#payment-options">Payment Options</a></li>
                                    <li><a href="{{route('solutions')}}#payment-plans">Payment Plans</a></li>                               
                                    <li><a href="{{route('solutions')}}#finance-options">Finance Options</a></li>
                                    <li><a href="{{route('solutions')}}#customer-reports">Customer Reports</a></li>
                                </ul>
                            </div>
                            <div class="footer-linking">
                                <h3>Quick Links</h3>
                                <ul>
                                    <li><a href="{{config('app.url')}}register">Sign Up</a></li>
                                    <li><a href="{{config('app.url')}}admin/login">Member Login</a></li>
                                    <li><a href="{{route('faq')}}">FAQs</a></li>
                                    <li><a href="javascript:void(0)">White Papers</a></li>
                                    <li><a href="{{route('your.reported.dues')}}">Check My Report</a></li>
                                </ul>
                            </div>
                            <div class="footer-linking">
                                <h3>Legal</h3>
                                <ul>
                                    <li><a target="_blank" href="{{config('app.url')}}privacy-policy">Privacy Policy</a></li>
                                    <li><a target="_blank" href="{{config('app.url')}}terms-and-conditions">Terms &amp; Conditions</a></li>
                                    <li><a href="{{route('security')}}">Security</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer> --}}
        <style>
            .error-content {
              max-width: 410px;
              width: 100%;
              text-align: center;
              margin-bottom: 30px;

            }
            .error-content h1 {
                font-size: 3.6rem;
            }
            .error-content a {
              font-family: 'Montserrat', sans-serif;
              font-size: 14px;
              text-decoration: none;
              text-transform: uppercase;
              background: #273581;
              display: inline-block;
              padding: 15px 30px;
              border-radius: 10px;
              color: #fff;
              font-weight: 700;
            }

            @media only screen and (max-width: 767px) {
                
                .error-content  {
                  padding-top: 50px;
                }
                .error-content h1{
                    font-size: 3.6rem;
                }
            }
            
        </style>
        <script src="{{asset('front_new/js/index.js')}}"></script>  
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
              $('footer a[href*="#"]')
                // Remove links that don't actually link to anything
                .not('[href="#"]')
                .not('[href="#0"]')
                .click(function (event) {

                    // On-page links
                    if (
                        location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
                        location.hostname == this.hostname
                    ) {
                        // Figure out element to scroll to
                        var target = $(this.hash);
                        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                        // Does a scroll target exist?
                        if (target.length) {
                            
                            // Only prevent default if animation is actually gonna happen
                            event.preventDefault();
                            $('html, body').animate({
                                scrollTop: target.offset().top - 130
                            }, 1000, function () {
                                
                          });
                        }
                    }
                });
                if (window.location.hash) {
                    setTimeout(function() {
                        $('html, body').scrollTop(0).show();
                        $('html, body').animate({
                            scrollTop: $(window.location.hash).offset().top -130
                            }, 1000)
                    }, 0);
                }

            });
        </script>
        @include('partials.google-analytics')
        @include('partials.hot-jar-tracking')
    </body>
</html>
