
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
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
    <link rel="manifest" href="{{config('app.url')}}manifest.json">
    <?php $site_favicon = Voyager::setting('site.favicon', ''); ?>
    @if($site_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($site_favicon) }}" type="image/png">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="customer login">
    <link rel="canonical" href="{{config('app.url')}}check-my-business-report" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{asset('front/css/style.css')}}" rel="stylesheet">
    <title>Check my report-{{ Voyager::setting("site.title") }}</title>
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">
    
    @if (__('voyager::generic.is_rtl') == 'true')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
    <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
    @endif
    
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <style>
    .hidden-xs.col-sm-12.col-md-12{display:none;}
      body{font-family: 'Rubik', sans-serif !important;}
      
       #mainNav .navbar-nav{margin-top: 0px !important;}
       body.login .faded-bg{background:#fff;}
body {
/*background-image:url('{{ Voyager::image( Voyager::setting("admin.bg_image"), voyager_asset("images/bg.jpg") ) }}');*/
background-color: {{ Voyager::setting("admin.bg_color", "#FFFFFF" ) }};
}
body {
height: 100% !important; font-family: 'Rubik', sans-serif !important;
}
    .tab-content > div{padding:20px 0 0 0;}
    .login-container > form > .row > .col-md-6 { margin-bottom: 0px;}
    .login-container .nav-tabs .nav-link{font-weight:500; font-size:15px; border-radius:0;}
    .login-container .nav-tabs .nav-link.active,
    .login-container .nav-tabs .nav-item.show .nav-link{background-color:#273581; color:#fff;}
      .bright-link {color: #273581 !important;font-weight: 500;}
      body {
      background-image:url('{{ Voyager::image( Voyager::setting("admin.bg_image"), voyager_asset("images/bg.jpg") ) }}');
      background-color: {{ Voyager::setting("admin.bg_color", "#FFFFFF" ) }};
      }
      body.login .login-container {  margin: 25px auto 0;max-width: 700px; background: #fff; padding: 25px 45px; right: 0px;left: 0px; top:0;border-top: 5px solid #273581;   position: relative;      }
      body.login .login-sidebar { border-top:5px solid {{ config('voyager.primary_color','#22A7F0') }}; }
      p{ font-size: 33px !important; color: #273581 !important;}
      @media (max-width: 767px) {
         #mainNav .navbar-nav{margin-top: 10px !important;}

      body.login .login-sidebar {
      border-top:0px !important;
      border-left:5px solid {{ config('voyager.primary_color','#22A7F0') }};
      }
      p{ font-size: 33px !important; color: #273581 !important;}
      }
      body.login .form-group-default.focused{
      border-color:{{ config('voyager.primary_color','#22A7F0') }};
      }
      body.login .login-container {  margin:25px auto 0;max-width: 700px; background: #fff; padding: 25px 45px; right: 0px;left: 0px; top:0;border:1px solid #273581;   position: relative;} 
      .login-button, .bar:before, .bar:after{
      background:{{ config('voyager.primary_color','#22A7F0') }};
      }
      .btn-submiteeee{display:-ms-flexbox;display:flex; -ms-flex-pack:end;justify-content:flex-end !important;}
      .remember-me-text{
      padding:0 5px;
      }
      .mrautonew {  float: right; }
      .mrautonew > li > a { padding: 0 1rem !important;font-size: 1.1rem !important;text-transform: uppercase !important; font-weight: 700;}
      .login {    overflow: auto !important;}
      
      body.login .login-sidebar{min-height: 800px;}
      body.login .login-button {color: #fff !important;background:#273581 !important;opacity: 1 !important; margin-top:0; border-radius:10px; border:1px solid #273581; font-weight:700; font-size:14px;}
      body.login .login-button:hover{background:#fff !important; color:#273581 !important;}
      @media only screen and (min-width:320px) and (max-width:767px){
      body.login .login-sidebar{border: 0px !important;}
      .mrautonew { float: none;}
      body.login{ background: none;}
      .fix-login-menu { position: relative;   width: 100%;    left: 0;    right: 0;    min-height: 60px;}
      .fix-login-menu > .new_log {  position: absolute;top: 0px;padding-bottom: 20px;  left: 20px; }
      .fix-login-menu > .new_tog { position: absolute;top: 17px; right: 0; padding:0 !important; }
      .new_menucol { padding-top: 30px; margin: 0 auto !important;}
      

      
      }
      .fade {
        opacity: 1 !important;
      }
      .btn-border{
        border: 2px solid;
        padding: 4px;
      }
      
      body.login .form-group-default label {
  color: #273581;
  font-weight: 500;
  font-size: 10px;
}
           #individualRegisterForm ::-webkit-input-placeholder{ 
  color: #495057;
}

 #individualRegisterForm :-ms-input-placeholder{ 
  color: #495057;
}

#individualRegisterForm ::placeholder{
  color: #495057;
}
 

.recvideo h2
{
  color: #273581;
}
.recvideo
{
  padding-left: 340px;
}
@media screen and (max-width:600px) {
 
   .recvideo .vidframe {height:300px;width:380px;}
   .recvideo {padding-left: 20px;}


}
    </style>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  </head>
  <body class="login">
  <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MBZPKSQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <nav class="navbar navbar-expand-md bg-secondary text-uppercase fixed-top navbar-shrink" id="mainNav" style="border-radius: 0 !important;padding-top:0px;padding-bottom:0px;">
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
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="row">
                <div class="col-md-12">
          
          
                    <div class="login-container">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
            
              <li class="nav-item">
                <a class="nav-link active" id="businessLogin-tab" data-toggle="tab" href="#businessLogin" role="tab" aria-controls="businessLogin" aria-selected="true">Business Report</a>
              </li>
              
             
            </ul>


                <div class="tab-pane  fade " id="businessLogin" role="tabpanel" aria-labelledby="businessLogin-tab">
              
                <div class="alert alert-success hide" role="alert">ter</div>
                            <div class="alert alert-danger hide" role="alert">tert</div>
                            <form action="{{ route('fetch-business-customer-otp') }}" method="POST" id="individualRegisterForm">
                              {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default w-49" id="business_mobile_number">
                                        <label>Mobile Number</label>
                                        <div class="controls">
                                          <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile Number" class="form-control">
                                        </div>
                                      </div>
                                    </div>
                                  
                                   {{--<p>OR</p>
                                    <!-- disabled email based login -->
                                    <div class="col-md-5">
                                      <div class="form-group form-group-default w-49" id="business_email">
                                        <label>Email</label>
                                        <div class="controls">
                                          <input type="text" name="email" maxlength="50" id="email" value="{{ old('email') }}" placeholder="{{ __('voyager::generic.email') }}" class="form-control">
                                       </div>
                                      </div>
                                    </div> --}} 

                                    <div class="col-md-12 mb-0">
                                        <div class="btn-submiteeee">
                                            <button type="submit" id="registerMobileButton" class="btn center-block login-button">
                                           <span class="signingin hidden"><span class="voyager-refresh"></span>...</span>
                                         <span class="signin">Submit</span>
                                        </button>  

                                        </div>
                                      
                                  </div>
                                </div>

                            </form>
                            <form action="{{ route('business.login') }}" method="POST" id="individualLoginForm" class="hide">
                                <div class="row">
                                  <input type="hidden" name="mobile_number" value="">
                                  <input type="hidden" name="email" value="">
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default w-49" id="passwordGroup">
                                            <label>OTP</label>
                                            <div class="controls">
                                              <input type="text" name="otp" placeholder="Enter OTP" class="form-control" autocomplete="off">
                                            </div>
                                      </div>
                                    </div>
                                    <div class="col-md-12 mb-0">
                                      <div class="btn-submiteeee">
                                      <button type="submit" id="LoginMobileButton" class="btn btn-block login-button">
                                       <span class="signingin hidden"><span class="voyager-refresh"></span>...</span>
                                     <span class="signin">Submit</span>
                                    </button>
                                    </div>
                                    
                                    <a href="Javascript:void" style="display: block;width: 100%;float: left;padding-top: 10px;" class="bright-link" id="resendOtp">Didn't get OTP? Send again</a>
                                  </div>  
                                </div>

                            </form>
                            <div style="clear:both"></div>
                            @if(!$errors->isEmpty())
                              <div class="alert alert-red">
                                <ul class="list-unstyled">
                                  @foreach($errors->all() as $err)
                                  <li>{{ $err }}</li>
                                  @endforeach
                                </ul>
                              </div>
                            @endif
                        </div>

                

                    </div>    
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="logo-title-container">
                        <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                        <img class="img-responsive pull-left flip logo hidden-xs animated fadeIn" src="{{ voyager_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                        <img class="img-responsive pull-left flip logo hidden-xs animated fadeIn" src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                        <div class="copy animated fadeIn">
                          <h1>{{ Voyager::setting('admin.title', 'Voyager') }}</h1>
                          {{-- 
                          <p>{{ Voyager::setting('admin.description', __('voyager::login.welcome')) }}</p>
                          --}}
                        </div>
                      </div>
                      <div class="recvideo">
                      <center>
                      <h2>Watch the video to know how Recordent works.</h2>
                      <iframe class="vidframe" width="520" height="300" src="https://www.youtube.com/embed/cc6_v_eYLdw">
                    </iframe>                      
                    
                </div>  
            </div>
           
        </div>
        <!-- .login-sidebar -->
      </div>
      <!-- .row -->
    </div>
    <!-- .container-fluid -->
    <!-- Bootstrap core JavaScript -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="{{config('app.url')}}front/vendor/jquery/jquery.min.js"></script>
    <script src="{{config('app.url')}}front/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Plugin JavaScript -->
    <script src="{{config('app.url')}}front/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Contact Form JavaScript -->
    <script src="{{config('app.url')}}front/js/jqBootstrapValidation.js"></script>
    <script src="{{config('app.url')}}front/js/contact_me.js"></script>
    <!-- Custom scripts for this template -->
    <script src="{{config('app.url')}}front/js/freelancer.min.js"></script>
    <script>
      var appUrl = "{{env('APP_URL')}}";
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
    
    /************* business ****************/

      $("#businessLogin #individualRegisterForm").on('submit',function(e){
        e.preventDefault();
        var form =$("#businessLogin #individualRegisterForm");
        var mobile_number = form.find('input[name=mobile_number]').val();
        var report_type = form.find('input[name=report_type]').val();
        var email = form.find('input[name=email]').val();

        $("#businessLogin").find('.alert').addClass("hide");
        $("#businessLogin").find('.alert').html('');

        $.ajax({
         method: 'post',
         url: "{{route('fetch-business-customer-otp')}}",
         headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         data: {
           mobile_number: mobile_number,
           report_type: report_type,
           email: email,
           _token: $('meta[name="csrf-token"]').attr('content')
         }
        }).then(function (response) {
          form.addClass('hide');
          $("#businessLogin #individualLoginForm").find('input[name=mobile_number]').val(response.mobile_number);
          console.log(response.email);
          $("#businessLogin #individualLoginForm").find('input[name=email]').val(response.email);
          $("#businessLogin #individualLoginForm").removeClass('hide');
          $("#businessLogin").find('.alert.alert-success').html(response.message);
          $("#businessLogin").find('.alert.alert-success').removeClass('hide');
          console.log(response);

            }).fail(function (data) {
              
            $("#businessLogin").find('.alert.alert-danger').html(data.responseJSON.message);
            $("#businessLogin").find('.alert.alert-danger').removeClass('hide');
        });
      });


      $("#businessLogin #individualLoginForm").on('submit',function(e){
        e.preventDefault();
        var form =$("#businessLogin #individualLoginForm");
        var mobile_number = form.find('input[name=mobile_number]').val();
        var email = form.find('input[name=email').val();
        var otp = form.find('input[name=otp]').val();

        $("#businessLogin").find('.alert').addClass("hide");
        $("#businessLogin").find('.alert').html('');

        $.ajax({
         method: 'post',
         url: "{{route('business.login')}}",
         headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         data: {
           mobile_number: mobile_number,
           otp:otp,
           email:email,
           _token: $('meta[name="csrf-token"]').attr('content')
         }
        }).then(function (response) {

          window.location.href=appUrl+'business/dashboard';
              
        }).fail(function (data) {
              
          $("#businessLogin").find('.alert.alert-danger').html(data.responseJSON.message);
          $("#businessLogin").find('.alert.alert-danger').removeClass('hide');

        });



      });

        //resend OTP
        $("#businessLogin #resendOtp").on('click',function(e){
        e.preventDefault();
        $("#businessLogin").find('.alert').addClass("hide");
        $("#businessLogin").find('.alert').html('');
        $("#businessLogin #individualRegisterForm").removeClass('hide');
        $("#businessLogin #individualLoginForm").addClass('hide');


      });

        });
    
    /************* individual ****************/
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id={{setting('site.google_analytics_tracking_id')}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', "{{setting('site.google_analytics_tracking_id')}}");
</script> 
<script>
$("#exampleModalCenter").on('hidden.bs.modal', function (e) {
    $("#exampleModalCenter iframe").attr("src", $("#exampleModalCenter iframe").attr("src"));
});
</script>
</body>
</html>