<!DOCTYPE html>



<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">

@php General::utmContainerDetect(); @endphp

  <head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MBZPKSQ');</script>
<!-- End Google Tag Manager -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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



    <link rel="manifest" href="{{config('app.url')}}manifest.json">



    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">



    <meta name="description" content="admin login">



    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">



    <link href="{{asset('front/css/style.css')}}" rel="stylesheet">



    <title>Admin - {{ Voyager::setting("admin.title") }}</title>



    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">



    @if (__('voyager::generic.is_rtl') == 'true')



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">



    <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">



    @endif







    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">







    <?php $admin_favicon = Voyager::setting('admin.favicon', ''); ?>



    @if($admin_favicon == '')



        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">



    @else



        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">



    @endif



    <style>



    :root{



  --white:#fff;



  --font-open-sans:'Open Sans', sans-serif;



  --font-rubik:'Rubik', sans-serif;



}







    body{font-weight:inherit !important;}



    .commap-team-popup{color:#273581; font-family:var(--font-rubik) !important;}



    .commap-team-popup b,



    .commap-team-popup strong,



    .commap-team-popup strong span{font-weight:700 !important;}



    .commap-team-popup.modal.fade .modal-dialog{transform:inherit !important;}



    .modal-backdrop.show{opacity:0.5 !important;}



    .commap-team-popup .modal-title{text-align:center; width:100%;}



    .commap-team-popup .modal-header{border:none; padding:8px;}

.d-flex{display:-ms-flexbox!important;display:flex!important}
.align-items-center{-ms-flex-align:center!important;align-items:center!important}
.justify-content-center{-ms-flex-pack:center!important;justify-content:center!important}
.flex-wrap{-ms-flex-wrap:wrap!important;flex-wrap:wrap!important}
.justify-content-between{-ms-flex-pack:justify!important;justify-content:space-between!important}
.mt{margin:0 !important;}
.mt-30{margin-top:30px;}

    .commap-team-popup .modal-content .team-desc ul li{position:relative; padding:0 0 0 25px;}



    .commap-team-popup .modal-content .team-desc ul li::after{height:10px; width:10px; border-radius:100%; background-color:#273581; position:absolute; content:""; left:0; top:6px;}



    .commap-team-popup .modal-content .team-desc ul li + li{margin-top:15px;}



    .commap-team-popup .modal-content{border:none; border-radius:0; padding:12px;}



    .commap-team-popup .modal-content .team-photo{height:160px; width:160px; margin:0 auto 30px;}



    .commap-team-popup .modal-content .team-photo img{max-width:100%;}



    .commap-team-popup .modal-header .close{position:absolute; padding:0; left:auto; right:0; top:-10px; height:30px; width:30px; text-shadow:none; color:#fff; z-index:9; opacity:1;}



    .commap-team-popup .modal-header .close::after{height:30px; position:absolute;  width:30px; border-radius:100%; background-color:#273581; top:0; left:0; content:""; z-index:-1;}



    .commap-team-popup .modal-title p{font-weight:700; font-size:18px; text-transform:uppercase;}



    .commap-team-popup .modal-title p ~ p{font-size:16px; text-transform:none; font-weight:400;}



    .faq-section{padding:30px 0 80px;}



    .faq-section .the-title h2{color:#000; font-weight:700; font-size:38px;}



    .ask-questions{font-family:var(--font-rubik);  font-size:20px; line-height:26px;}
    .ask-questions li ul{margin-top:10px; padding:0 0 0 40px;}


    .about-contain{margin-left:10px;}



    .ask-questions li p{margin-top:16px;}



    .ask-questions li a{text-decoration:underline;}



    .ask-questions li a:hover{text-decoration:none;}



    .ask-questions li p + p{margin-top:0;}



    .ask-questions li + li{margin-top:27px;}



    .ask-questions.p-policy li p{margin:0;}



    .ask-questions.p-policy li p + p{margin-top:20px;}



    .ask-questions.p-policy li strong span{font-weight:400;}



    .ask-questions li ul li + li{margin-top:5px;}



    .ask-questions li ul{margin-top:10px;}



    ul, ul li {



    padding: 0;



    list-style: none;



    margin: 0;



}




        .field-icon {
          float: right;
          margin-left: -25px;
          margin-top: -20px;
          position: relative;
          z-index: 2;
        }

        .container{
          padding-top:50px;
          margin: auto;
}


        body.login .form-group-default label{color:#273581; font-weight:500; font-size:10px;}



        .hidden-xs.col-sm-12.col-md-12{display:none;}



       body{font-family: 'Rubik', sans-serif !important;}



       #mainNav .navbar-nav{margin-top: 0px !important;}



    .login-container > form > .row > .col-md-6 { margin-bottom: 0px;}







      .bright-link {color: #273581 !important;font-weight: 500;}



      body.login .faded-bg{background:#fff;}



      body {



      /*background-image:url('{{ Voyager::image( Voyager::setting("admin.bg_image"), voyager_asset("images/bg.jpg") ) }}');*/



      background-color: {{ Voyager::setting("admin.bg_color", "#FFFFFF" ) }};



      }



      a{color:#273581;}



      .tab-content > div{padding-bottom:0;}



      body.login .login-container {  margin:25px auto 0;max-width: 700px; background: #fff; padding: 25px; right: 0px;left: 0px; top:0;border:1px solid #273581;   position: relative;      }



      body.login .login-sidebar { border-top:5px solid {{ config('voyager.primary_color','#22A7F0') }}; }



      .tab-content .row.padding-some{padding:0;}



      .padding-some .form-check.form-check-inline{padding:0 0 0 30px;}



      body.login .login-container form{padding-top:0;}



      .padding-some .form-check.form-check-inline + .form-check.form-check-inline{padding:0;}



      @media (max-width: 767px) {



         #mainNav .navbar-nav{margin-top: 10px !important;}







      body.login .login-sidebar { border-top:0px !important; border-left:5px solid {{ config('voyager.primary_color','#22A7F0') }}; }



      }



      body.login .form-group-default.focused{



      border-color:{{ config('voyager.primary_color','#22A7F0') }};



      }



      .login-button, .bar:before, .bar:after{



      background:{{ config('voyager.primary_color','#22A7F0') }};



      }



      .remember-me-text{



      padding:0 5px;



      }



      .mrautonew {  float: right; }



      .mrautonew > li > a { padding: 0 1rem !important;font-size: 1.1rem !important;text-transform: uppercase !important; font-weight: 700;}



      .login {    overflow: auto !important;}















      #get-otp-form ::-webkit-input-placeholder,



      #loginWithEmailGetOtpToMobileForm ::-webkit-input-placeholder{



  color: #495057;



}







#get-otp-form :-ms-input-placeholder ,



#loginWithEmailGetOtpToMobileForm :-ms-input-placeholder{



  color: #495057;



}







#get-otp-form ::placeholder,



#loginWithEmailGetOtpToMobileForm ::placeholder{



  color: #495057;



}











      body.login .login-sidebar{min-height: 800px;}



      body.login .login-button {color: #fff !important;background:#273581 !important;opacity: 1 !important; margin-top:30px; border-radius:10px; border:1px solid #273581; font-weight:700; font-size:14px;}



      body.login .login-button:hover{background:#fff !important; color:#273581 !important;}



      @media only screen and (min-width:320px) and (max-width:767px){



      body.login .login-sidebar{border: 0px !important;}



      .mrautonew { float: none;}



      body.login{ background: none;}



      .fix-login-menu { position: relative;   width: 100%;    left: 0;    right: 0;    min-height: 60px;}



      #admin-otp-login-tab{padding-bottom:0 !important;}



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



      body form .login-button:disabled{background-color: #b8bcbe !important}



      body.login .login-container p{font-size:14px; font-family: 'Rubik', sans-serif !important; text-transform:none; font-weight:500; color:#000;}



      .form-check-label{font-weight:500; letter-spacing:0.3px;}



      .new-user{font-weight:400; font-size:14px; color:#000; font-weight:500}











      @media only screen and (max-width: 767px) {



          body.login .login-container p{font-size:13px;}



          .new-user{font-size:13px;}



      }



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



        <a class="new_log navbar-brand js-scroll-trigger" href="{{config('app.url')}}home">



            <img src="{{asset('storage/'.setting('site.logo'))}}" style="width:140px"></a>



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



                        <div class="tab-content">



                            <div class="row padding-some">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="login_by" id="emaile" value="email" {{ old('email') ? 'checked' : '' }}>
                                <label class="form-check-label" for="emaile"> Login by Email</label>
                            </div>

                                <div class="form-check form-check-inline">



                                    <input class="form-check-input" type="radio" name="login_by" id="mobe" value="mobile" {{ !old('email') ? 'checked' : '' }}>



                                    <label class="form-check-label" for="mobe"> Login by Mobile</label>



                                </div>







                            </div>



                            <div class="tab-pane fade {{ !old('email') ? 'show active' : '' }}" id="admin-otp-login-tab" role="tabpanel" aria-labelledby="admin-otp-login-tab">



                                  @if (session('success'))



                                    <div class="alert alert-success">



                                      {{ session('success') }}



                                    </div>



                                  @else



                                    <div class="alert alert-success hide" role="alert"></div>



                                  @endif







                                  <div class="alert alert-danger hide" role="alert"></div>







                                  <form action="{{ route('admin.login.get-otp') }}" method="POST" id="get-otp-form">



                                        {{ csrf_field() }}







                                        <div class="row">



                                          <div class="col-md-12">



                                              <div class="form-group form-group-default w-49" id="emailGroup">



                                              <label>Mobile Number</label>



                                              <div class="controls">



                                                <input type="number" name="mobile_number" id="mobile_number" placeholder="Mobile Number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="10" class="form-control" required>



                                              </div>



                                            </div>



                                          </div>



                                          <!-- <div class="col-md-6">



                                              <div class="form-group form-group-default w-49" id="passwordGroup">



                                                  <label>{{ __('voyager::generic.password') }}</label>



                                                  <div class="controls">



                                                    <input type="password" name="password" placeholder="{{ __('voyager::generic.password') }}" class="form-control" required>



                                                  </div>



                                            </div>



                                          </div> -->



                                        </div>







                                       <!-- <div class="row">



                                          <div class="col-md-6">



                                              <div class="form-group" id="rememberMeGroup">



                                              <div class="controls">



                                                <input type="checkbox" name="remember" value="1"><span class="remember-me-text">{{ __('voyager::generic.remember_me') }}</span>



                                              </div>



                                            </div>



                                          </div>



                                      </div> -->








                                      <p>I/we have read and agree to the <a href="javascript:void(0)"  data-toggle="modal" data-target="#TermConditionModal">end user license agreement</a> <!-- <a href="{{route('end-user-license-agreement')}}" target="_blank">end user license agreement</a> --> by clicking login button</p>



                                           <!-- <p>I have read and agree to the <a href="javascript:void(0)"  data-toggle="modal" data-target="#TermConditionModal">end user license agreement</a> <a href="{{route('end-user-license-agreement')}}" target="_blank">end user license agreement</a>  by clicking login button</p>



                                        <span class="new-user" style="">New User? <a href="{{route('register')}}" class="bright-link">Sign up now</a></span> -->





                                        <div class="d-flex align-items-center justify-content-between mt-30">
                                        <span class="new-user" style="">New User? <a href="{{route('register')}}" class="bright-link"  style="font-size:14px">Sign Up now</a></span>
                                        <input type="submit" class="btn btn-block login-button signin float-right m-0" value="GET OTP">
                                        </div>




                                  </form>







                                  <form action="{{route('admin.login-with-otp')}}" method="POST" id="adminMobileLoginForm" class="hide">



                                        <div class="row">

                                          <input type="hidden" name="campaign_id" value="{{$campaignId}}"/>
                                          <input type="hidden" name="credit_report_redirect" value="{{$creditReportRedirect}}"/>
                                          <input type="hidden" name="membership_page_redirect" value="{{$membershipPageRedirect}}"/>
                                          <input type="hidden" name="help_support_redirect" value="{{$helpSupportRedirect}}"/>  
                                          <input type="hidden" name="mobile_number" value="">



                                            <div class="col-md-12">



                                                <div class="form-group form-group-default w-49" id="passwordGroup">



                                                    <label>OTP</label>



                                                    <div class="controls">



                                                      <input type="text" name="otp" placeholder="Enter OTP" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" class="form-control" autocomplete="off">



                                                    </div>



                                              </div>



                                            </div>







                                        </div>



                                        <div class="d-flex align-items-center justify-content-between ">
                                            <a href="Javascript:void" style="display: block;float: left;" class="bright-link" id="resendOtp">Didn't get OTP? Send again</a>



                                        <button type="submit" id="LoginMobileButton" class="btn btn-block login-button float-right m-0">



                                               <span class="signingin hidden"><span class="voyager-refresh"></span>...</span>



                                               <span class="signin">LOGIN</span>



                                            </button>
                                        </div>












                                  </form>







                                  <div style="clear:both"></div>











                            </div>







                            <div class="tab-pane fade {{ old('email') ? 'show active' : '' }}" id="admin-email-login-tab" role="tabpanel" aria-labelledby="admin-email-login-tab">



                            {{--@if (session('status'))



                            <div class="alert alert-success" role="alert">



                              {{ session('status') }}



                              @php



                                $v = Session::forget('status');



                              @endphp



                            </div>



                            @endif



                             @if(!$errors->isEmpty())



                              <div class="alert alert-red">



                                <ul class="list-unstyled">



                                  @foreach($errors->all() as $err)



                                  <li>{{ $err }}</li>



                                  @endforeach



                                </ul>



                              </div>



                            @endif --}}



                            <div class="alert alert-success hide" role="alert"></div>



                            <div class="alert alert-danger hide" role="alert"></div>



                            <form action="{{ route('admin.login-with-email-get-otp-to-mobile') }}" method="POST" id="loginWithEmailGetOtpToMobileForm" name="login-forms">



                              {{ csrf_field() }}







                                <div class="row">



                                    <div class="col-md-6">



                                        <div class="form-group form-group-default w-49" id="emailGroup">



                                        <label>{{ __('voyager::generic.email') }}</label>



                                        <div class="controls">



                                          <input type="text" name="email" maxlength="{{General::maxlength('email')}}" id="email" value="{{ old('email') }}" placeholder="{{ __('voyager::generic.email') }}" class="form-control" required>



                                        </div>



                                      </div>



                                    </div>



                                    <div class="col-md-6">



                                        <div class="form-group form-group-default w-49" id="passwordGroup">



                                            <label>{{ __('voyager::generic.password') }}</label>



                                            <div class="controls">



                                              <input type="password" name="password" id="myInput" maxlength="15" placeholder="{{ __('voyager::generic.password') }}" class="form-control" required>
                                              <i class="fa fa-eye field-icon" onclick="myFunction()"></i>



                                            </div>



                                      </div>


                                    </div>



                                </div>

                                 {{-- <div class="row">



                                <div class="col-md-6">
                                        <div class="form-group" id="rememberMeGroup">
                                        <div class="controls">
                                          <input type="checkbox" name="remember" value="1"><span class="remember-me-text">{{ __('voyager::generic.remember_me') }}</span>
                                        </div>
                                      </div>
                                    </div>
                                </div>--}}



                                     <p>I/we have read and agree to the <a href="javascript:void(0)"  data-toggle="modal" data-target="#TermConditionModal">end user license agreement</a> <!-- <a href="{{route('end-user-license-agreement')}}" target="_blank">end user license agreement</a> --> by clicking login button</p>

                            <input type="hidden" name="campaign_id" value="{{$campaignId}}"/>
                            <input type="hidden" name="credit_report_redirect" value="{{$creditReportRedirect}}"/>
                            <input type="hidden" name="membership_page_redirect" value="{{$membershipPageRedirect}}"/>
                            <input type="hidden" name="help_support_redirect" value="{{$helpSupportRedirect}}"/>

                            <div class="d-flex align-items-center justify-content-between mt-30">
                                <div>
                                    <div>
                                    <span class="" style="color:#000; font-weight:500">New User? <a href="{{route('register')}}" class="bright-link">Sign Up now</a></span>
                                    </div>
                                    <a href="{{route('password.request')}}" style="display: block;width: 100%;float: left;" class="bright-link ">Forgot Password?</a>
                                </div>
                                <input type="submit" class="btn btn-block login-button signin m-0" value="{{ __('voyager::generic.login') }}">
                            </div>

                              <br>

                            </form>



                            <form action="{{route('admin.login-with-email-otp-to-mobile')}}" method="POST" id="loginWithEmailOtpToMobileForm" class="hide">



                                        <div class="row">



                                          <input type="hidden" name="email" value="">

                                          <input type="hidden" name="campaign_id" value="{{$campaignId}}"/>
                                          <input type="hidden" name="credit_report_redirect" value="{{$creditReportRedirect}}"/>
                                          <input type="hidden" name="membership_page_redirect" value="{{$membershipPageRedirect}}"/>
                                          <input type="hidden" name="help_support_redirect" value="{{$helpSupportRedirect}}"/> 

                                          <input type="hidden" name="password" value="">



                                            <div class="col-md-12">



                                                <div class="form-group form-group-default w-49" id="passwordGroup">



                                                    <label>OTP</label>



                                                    <div class="controls">



                                                      <input type="text" name="otp" placeholder="Enter OTP" class="form-control" autocomplete="off">



                                                    </div>



                                              </div>



                                            </div>



                                        </div>



                                        <a href="Javascript:void" style="display: block;float: left;padding-top: 10px;" class="bright-link" id="resendOtp">Didn't get OTP? Send again</a>



                                        <button type="submit" id="" class="btn btn-block login-button float-right">



                                               <span class="signingin hidden"><span class="voyager-refresh"></span>...</span>



                                               <span class="signin">Submit</span>



                                            </button>











                                  </form>



                            <div style="clear:both"></div>











                        </div>



                         </div>



                    </div>



                </div>



            </div>



            <div class="row">



                <div class="col-md-12 hidden-xs">



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



                </div>



            </div>







        </div>



        <!-- .login-sidebar -->



      </div>



      <!-- .row -->



    </div>



    <!-- .container-fluid -->



























<div class="modal fade commap-team-popup" id="TermConditionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content faq-section">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <div class="the-title text-center">
            <h2>End User License Agreement</h2>
        </div>
      </div>
      <div class="modal-body">
          <div class="privacy-policy-points">


          <ul class="ask-questions p-policy">
            <li>
                <strong>1. Notice to the user</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a)</span>
                            <span class="about-contain">The website www.recordent.com its mobile phone application(s) computer
application(s), tablet applications, software, services and other related internet-
based applications including Recordent App (hereinafter collectively
referred to as the &quot;Recordent Platform&quot;) owned and operated by Recordent
Private Limited, a company incorporated under the laws of the Republic of
India having its Corporate Office at 1 st Floor, Midtown Plaza, Road No. 1,
Banjara Hills, Hyderabad, Telangana, Pincode- 500002 has adopted this
Recordent Platform User Account Terms.</span>
                        </p>
                    </li>

                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b)</span>
                            <span class="about-contain">The User is informed that the terms herein govern:</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter"> (i) </span>
                                    <span class="about-contain">User Account on the Recordent Platform and its use;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter"> (ii) </span>
                                    <span class="about-contain">The using/availing any services by/or through the Recordent Platform.</span>
                                </p>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <p class="d-flex">
                            <span class="index-letter"> (c) </span>
                            <span class="about-contain">It is therefore requested to the User to read this Recordent Platform User
Account Terms (&quot;Recordent Platform User Account Terms&quot;) carefully
before:</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(i)</span>
                                    <span class="about-contain">Granting acceptance to these presents;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ii)</span>
                                    <span class="about-contain">While using any services through or from the Recordent Platform;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter"> (iii) </span>
                                    <span class="about-contain">For creating any User Account on the Recordent Platform.</span>
                                </p>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(d)</span>
                            <span class="about-contain">It is also informed that a User Account on the Recordent Platform and/or
the using/availing any services by/or through the Recordent Platform shall
be subject to the terms of these presents, and in case User disagrees, User shall
not use the Recordent Platform and its services and shall leave the Recordent
Platform before acceptance of these terms or use of the Recordent Platform.</span>
                        </p>
                    </li>

                    <li>
                        <p class="d-flex">
                            <span class="index-letter"> (e) </span>
                            <span class="about-contain">Recordent Platform&#39;s General Terms and Conditions, Privacy Policy,
Disclaimers, Other Policies applicable in general, and/or to specific areas of
Recordent Platform are also considered as part of this Recordent Platform
User Account Terms. The aforesaid is incorporated herein by way of
reference, which shall be noted by The User or any persons acting for a User
from time to time. Any acceptance to the terms here shall be an acceptance to
such terms as well. By accepting these terms, The User further agrees that
their use of the Recordent Platform shall be subject to compliance with the
Recordent Platform General Terms and Conditions, Privacy Policy,
Disclaimers, Other Policies applicable in general, and/or to specific areas of
Recordent Platform.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(f)</span>
                            <span class="about-contain">The use of Recordent Platform and/or acceptance to these Recordent Platform
User Account Terms shall constitute a valid and binding legal contract between the User and Recordent Platform with respect to this Recordent
Platform User Account Terms, and other terms incorporated by way of the
reference here.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(g)</span>
                            <span class="about-contain">By accepting this Recordent Platform User Account Terms and/or by using
the Recordent Platform, the User acknowledges and confirms that they have
read and understood the same and shall be bound by such terms.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(h)</span>
                            <span class="about-contain">The User is informed that a User&#39;s right to use the Recordent Platform can be
denied at any point in time without any prior intimation, and they agree to the
same.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(i)</span>
                            <span class="about-contain">The User of Recordent Platform is informed that they shall re-visit the
Recordent Platform User Account Terms from time to time to stay abreast of
any changes that the Recordent Platform may introduce. The Recordent
Platform reserves the right to modify the Recordent Platform User Account
Terms at any time without giving a User any prior notice. Any updates
concerning such modifications shall be available only in the Recordent
Platform, and such changes shall be binding on The User in a retrospective
manner or at any given point in time</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(j)</span>
                            <span class="about-contain">The User confirms that they satisfy the criteria prescribed under applicable
laws, to enter into a contract and have legal competence and capacity.</span>
                        </p>
                    </li>

                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(k)</span>
                            <span class="about-contain">Recordent Platform may translate these Terms into other languages for
convenience of the User. Nevertheless, the English version governs the
relationship with Recordent Platform, and any inconsistencies among the
different versions will be resolved in favor of the English version.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>2. Grant of license/permission to use recordent platform</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a)</span>
                            <span class="about-contain">Upon the User accepting these terms the Recordent Platform grants and the
User accept a non-exclusive, non-transferable right to use the Recordent
Platform, specifically for the following:</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(i)</span>
                                    <span class="about-contain">Create a user id (&quot;User Account&quot;) and password for using the
Recordent Platform.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ii)</span>
                                    <span class="about-contain">Use Recordent Platform to upload, submit, inform, view, access,
analyze information, data, text, etc., score and rate
business/transactions/interactions/experience with a counterparty.
Also, score and rate performance of such counterparty during such
instances (hereinafter referred to as the &quot;Record&quot;);</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iii)</span>
                                    <span class="about-contain">To share or grant permission to share such Record;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iv)</span>
                                    <span class="about-contain">To search for and analyze various Record available;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(v)</span>
                                    <span class="about-contain">To subscribe to and view Record made available by other users;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(vi)</span>
                                    <span class="about-contain">Avail services provided in the Recordent Platform related to the
above after making applicable payments towards the charges
prescribed Recordent Platform from time to time;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(vii)</span>
                                    <span class="about-contain">Use payment services facilities given by Recordent Platform or
through third-party service providers.</span>
                                </p>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b)</span>
                            <span class="about-contain">By accepting these terms, The User consent, confirm, accept, undertake, and
authorize as follows:</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(i)</span>
                                    <span class="about-contain">The Recordent Platform or persons authorized or permitted by
Recordent Platform or other users of the Recordent Platform shall be
allowed to use the Record submitted by the User in the manner
provided Recordent Platform.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ii)</span>
                                    <span class="about-contain">The Recordent Platform shall be authorized to use Records for
commercial, non-commercial, analytical purposes or publications.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iii)</span>
                                    <span class="about-contain">Store Record for the period as per Recordent Platform&#39;s policies and
discretion and for designing its products and for providing its
services.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iv)</span>
                                    <span class="about-contain">Provide services by or through the Recordent Platform directly or
through service providers.</span>
                                </p>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(c)</span>
                            <span class="about-contain">The User further confirms as follows:</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(i)</span>
                                    <span class="about-contain">The Record submitted are truthful and do not/shall not violate any
law or regulations or norms or contracts applicable to the same.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ii)</span>
                                    <span class="about-contain">Before submitting Record and/or viewing and/or generating any
details on Recordent Platform or while using services of Recordent
Platform or through Recordent Platform (as may be applicable), The
User confirms that they have ensured that they do not violate laws
applicable at their location/country/state from where the User access
the Recordent Platform.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iii)</span>
                                    <span class="about-contain">That they understand that the Recordent Platform has not made any
representations or assurances on behalf of any parties and/ or
authorities, government, regulatory bodies, and judicial authority.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iv)</span>
                                    <span class="about-contain">The Record submitted does not violate intellectual property rights or
legal rights of any person or a third party in any manner.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iv)</span>
                                    <span class="about-contain">That the details submitted by you are true and correct and Recordent
Platform can rely on it for providing the services sought by you
through the Recordent Platform and any consequence of providing
wrong information is at your own risk. Recordent shall not have any responsibility or liability to you or any third party due to your acts or
the information which you upload or any of its exploitation in the
Recordent Platform.</span>
                                </p>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li>
                <strong>3. User account</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a)</span>
                            <span class="about-contain">While creating a User Account, the User must provide accurate and complete
information. Creation of User Account shall be at sole risk and responsibility
of the User</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b)</span>
                            <span class="about-contain">The User must keep User Account and password secure and confidential.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(c)</span>
                            <span class="about-contain">The User must notify the Recordent Platform immediately of any breach of
security or unauthorized use of User Account or password as soon as the User
becomes aware of the same. A User must not use any other person&#39;s account to
access the Recordent Platform, nor shall the User share his/her credentials to
another person.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(d)</span>
                            <span class="about-contain">The User agrees to be solely responsible and liable (to Recordent Platform,
and others) for all activity that occurs under their User Account.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(e)</span>
                            <span class="about-contain">The User is also liable for any activity on Recordent Platform arising out of
any failure to keep their password confidential and for any losses arising out
of such a failure.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(f)</span>
                            <span class="about-contain">As an account holder, the User shall submit only truthful Information to the
Recordent Platform.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(g)</span>
                            <span class="about-contain">The User understands that whether or not Information is published, the
Recordent Platform does not guarantee any confidentiality concerning the
information of the User in the Recordent Platform. If the information is
required to be maintained as confidential, The User is required to maintain the
same. The Recordent Platform shall not liable or responsible for breach of
User&#39;s obligations. Further, by receiving the Information on Recordent
Platform, it relies on the representation that the User has shared it in
compliance with law and contract applicable for such acts.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(h)</span>
                            <span class="about-contain">The User grant to Recordent Platform and/or the other users of Recordent
Platform (as may be agreed by Recordent Platform) a worldwide, irrevocable,
non-exclusive, royalty-free license to use, reproduce, store, adapt, publish,
translate and distribute Information on and concerning Recordent Platform
and any successor website / reproduce, store and, with User&#39;s specific consent,
publish Information on and concerning the Recordent Platform. The User also
grants to Recordent Platform the right to sub-license the rights licensed to the
Recordent Platform under this section.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(i)</span>
                            <span class="about-contain">The User understands and agrees that the Information uploaded may be used
by any third person. The Recordent Platform shall be entitled to disclose the
source at its sole discretion.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(j)</span>
                            <span class="about-contain">The User also understands and agrees that they are solely responsible for their
Information and the consequences of posting or publishing it. Recordent
Platform does not endorse any information or any opinion, recommendation,
or advice expressed therein, and the Recordent Platform expressly disclaims
all ownership and liability in connection with Information or use of it by any
person.</span>
                        </p>
                    </li>




                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(k)</span>
                            <span class="about-contain">The User declares to Recordent Platform that:</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(i)</span>
                                    <span class="about-contain">They have (and will continue to have during the use of Recordent
Platform by a User) all necessary authority, licenses, rights, consents
and permissions which are required to share the Information and
enable Recordent Platform to use the Information to host the
Information on Recordent Platform, and otherwise to apply the
Information by Recordent Platform or any third person.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ii)</span>
                                    <span class="about-contain">That they will not post or upload any Information which contains
material which it is unlawful to possess in India, or which it would be
illegal for Recordent Platform to use or possess in connection with
the provision of the services through Recordent Platform.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iii)</span>
                                    <span class="about-contain">Any third-party Information shared is with due consent and
permission from the concerned person</span>
                                </p>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(l)</span>
                            <span class="about-contain">On becoming aware of any potential violation of any terms, Recordent
Platform reserves the right to decide whether Information complies with the
Information requirements set out in any terms and may remove such
Information and/or terminate a User&#39;s access for uploading Information which
violates any terms at any time, without prior notice to the concerned User and
at Recordent Platform&#39;s sole discretion.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(m)</span>
                            <span class="about-contain">The User agrees and undertakes to indemnify, defend and hold harmless
Recordent Platform and all its officers, directors, promoters, employees,
agents, and representatives against any and all loss and claims arising from
any Information uploaded on Recordent Platform or by use of Recordent
Platform.</span>
                        </p>
                    </li>

                </ul>
            </li>

            <li>
                <strong>4. Prohibited Conduct</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a)</span>
                            <span class="about-contain">By using Recordent Platform, the User agrees that as follows:</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(i)</span>
                                    <span class="about-contain">Use Recordent Platform for spamming or any other illegal purposes;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ii) </span>
                                    <span class="about-contain">Upload any promotional material or advertisement to the User
Account;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iii) </span>
                                    <span class="about-contain">Infringe Recordent Platform&#39;s or any third party&#39;s intellectual property
rights, rights of publicity or privacy;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iv) </span>
                                    <span class="about-contain">post or transmit any message, data, image or program which violates
any law;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(v) </span>
                                    <span class="about-contain">Refuse to cooperate in an investigation or provide confirmation of
User&#39;s identity or any other information provided to Recordent
Platform;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(vi) </span>
                                    <span class="about-contain">Remove, circumvent, disable, damage or otherwise interfere with
security-related features of the Recordent Platform or features that
enforce limitations on the use of Recordent Platform;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(vii) </span>
                                    <span class="about-contain">Upload any information which is in contempt of any court or breach
of any court order; or discriminates based on age, sex, religion, race,
gender; harassing, invasive of another&#39;s privacy, blasphemous; in
breach of any contractual obligations or consists of or contains any
instructions, advice or other information which may be acted upon
and could, if acted upon, cause illness, injury or death, or any other
loss or damage; or constitutes spam; or is grossly harmful, offensive,
deceptive, fraudulent, threatening, abusive, hateful, harassing, anti-
social, menacing, hateful, discriminatory or inflammatory; or causes
annoyance, inconvenience or needless anxiety to any person; or
racially, ethnically objectionable, disparaging, relating or
encouraging money laundering or gambling, or harm minors in any
way or otherwise unlawful in any manner whatever;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(viii) </span>
                                    <span class="about-contain">Upload any information that threatens the unity, integrity, defense,
security or sovereignty of any country, or public order or causes
incitement to the commission of any cognizable offense or prevents
investigation of any offense or is insulting any nation;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ix)</span>
                                    <span class="about-contain">Upload any Information that contains software, virus or any other
computer code, files or programs designed to interrupt, destroy or
limit the functionality of any computer resource;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(x) </span>
                                    <span class="about-contain">Reverse engineer, decompile, disassemble or otherwise attempt to
discover the source code of Recordent Platform or any part thereof or
infringe any patent, trademark, copyright or other proprietary rights;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xi) </span>
                                    <span class="about-contain">Use Recordent Platform in any manner that could damage, disable,
overburden, or impair, including, without limitation, using Recordent
Platform in an automated manner;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xii) </span>
                                    <span class="about-contain">Modify, adapt, translate or create derivative works based upon
Recordent Platform or any part thereof;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xiii) </span>
                                    <span class="about-contain">Intentionally interfere with or damage operation of Recordent
Platform or any other User&#39;s use of Recordent Platform, by any
means, including uploading or otherwise disseminating viruses,
adware, spyware, worms, or other malicious code or file with
contaminating or destructive features;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xiv) </span>
                                    <span class="about-contain">Use any robot, spider, other automatic devices, or manual process to
monitor or copy Recordent Platform without prior written permission
of Recordent Platform;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xv) </span>
                                    <span class="about-contain">Interfere or disrupt Recordent Platform or networks connected
therewith;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xvi) </span>
                                    <span class="about-contain">Take any action that imposes an unreasonably or disproportionately
large load on Recordent Platform&#39;s infrastructure/network;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xvii) </span>
                                    <span class="about-contain">Use any device, software or routine to bypass Recordent Platform&#39;s
robot exclusion headers, or interfere or attempt to interfere, with
Recordent Platform;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xviii) </span>
                                    <span class="about-contain">Forge headers or manipulate identifiers or other data to disguise the
origin of any Information transmitted through Recordent Platform or
to manipulate User&#39;s presence on Recordent Platform;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xix) </span>
                                    <span class="about-contain">Sell/ sub-license the Information, or software associated with or
derived from the Recordent Platform;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xx) </span>
                                    <span class="about-contain">Use the facilities and capabilities of the Recordent Platform to
conduct any activity or solicit the performance of any illegal activity
or other activity which infringes the rights of others;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xxi) </span>
                                    <span class="about-contain">Breach any terms or any other policy of the Recordent Platform;</span>
                                </p>
                            </li>

                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(xxii) </span>
                                    <span class="about-contain">Provide false, inaccurate, or misleading information to the Recordent
Platform.</span>
                                </p>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li>
                <strong>5. Service Terms</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">The User accepting these terms understand that Recordent Platform&#39;s services
if any availed, may be further subject to the terms of separate service terms
given in the Recordent Platform applicable for the specific service. The User
shall be bound to the same while subscribing to the service and/or for using
the service (as may be applicable).</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">Upon the User opting to avail such services and/or for subscribing to the
service and/or for using the service (as may be applicable), the respective
service terms shall form part and parcel of this Recordent Platform User
Account Terms.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(c) </span>
                            <span class="about-contain">The service terms shall include the fee to be paid by the User to the services
availed through the Recordent Platform and the manner in which the fee shall
be paid.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(d) </span>
                            <span class="about-contain">The acceptance to such terms shall be a binding contract on the User in
addition to the term herein present.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>6. Data Integrity Standards</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">By accepting these terms, the User agrees to the Data Integrity Standards
prescribed by Recordent Platform from time to time.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">The User further confirms that they shall be solely responsible for complying
with the Data Integrity Standards prescribed by the Recordent Platform from
time to time.</span>
                        </p>
                    </li>

                </ul>
            </li>

            <li>
                <strong>7. Fee</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">By accepting the terms of this Recordent Platform User Account Terms, the
User agrees as follows:</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(i) </span>
                                    <span class="about-contain">The Recordent Platform shall be entitled to charge license fee or
service fee or subscription fee or such other fee for services offered
by the Recordent Platform or through it.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ii) </span>
                                    <span class="about-contain">The service terms applicable for the particular service or a schedule
of charges in the Recordent Platform shall have the applicable license
fee or service fee or subscription fee or such other fee (as may be
applicable).</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iii) </span>
                                    <span class="about-contain">The fee paid by the User to the Recordent Platform (if any) shall not
be refundable.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iv) </span>
                                    <span class="about-contain">The fee shall be paid along with the applicable taxes, cess, duties fee,
statutory fees etc (including Goods and Service Tax).</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(v) </span>
                                    <span class="about-contain">The User shall produce proof of tax withholding/deductions made to
comply with applicable law.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(vi) </span>
                                    <span class="about-contain">The User shall not be entitled to a service through the Recordent
Platform due to non-payment of a fee; the User may need to comply
with other norms.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(vii) </span>
                                    <span class="about-contain">In case of non-payment of any fee the user may be prohibited from
accessing and using Recordent Platform.</span>
                                </p>
                            </li>

                        </ul>
                    </li>
                </ul>
            </li>

            <li>
                <strong>8. Refund and Cancellation Policy</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="about-contain">A request once made by me/us shall not be cancelled and no charges relating to such order
shall be refunded however, there may be instances when no report, as may be applicable, is
generated because the information does not exist in Recordent’s database or the bureau
partner’s database or the details provided by me/us were not adequate to access the relevant
information. I/we also understand that Recordent India Private Limited may also refund the
charges in an event of cancellation of paid subscription services on a case by case basis. I/we
understand the need to contact Recordent customer support with a valid request to cancel
the subscription and only after conducting necessary due diligence Recordent India Private
Limited, on a case to case basis cancel the subscription and refund the subscription amount.
I/we also understand that the refund amount will be calculated on the pro-rata basis
depending on the length of time of the subscription services used and the usage policy. The
refund will always be credited to the original mode of payment.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>9. Intellectual And Proprietary Rights</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">The User agrees that they shall not breach intellectual property rights and
proprietary rights of the Recordent Platform.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">The User further confirms and agrees that they shall not reverse engineer,
decompile, disassemble or create derivative works of or modify the Recordent
Platform.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>10. Use Of Third-party Links</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">The User agrees that the use of Third-Party links may be subject to according
to the terms and conditions and privacy policy and such other terms of such
web application.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">The User shall exercise their own discretion while accessing any third-party
links that may appear in the Recordent Platform.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(c) </span>
                            <span class="about-contain">The User shall not hold the Recordent Platform responsible or liable for their
use of any third-party links.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(d) </span>
                            <span class="about-contain">Any access to third party links from the Recordent Platform will be at User&#39;s
sole risk and responsibility.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(e) </span>
                            <span class="about-contain">The Recordent Platform disclaims and discharges itself any liability that may
arise on it due to any act of such third parties.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>11. Payments Options</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">The Recordent Platform may provide options to make payment for services
from and through the Recordent Platform or for services from third parties.</span>
                        </p>
                    </li>

                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">It is informed to the User that the Recordent Platform may use third parties for
facilitating such payments possible, the transaction for making payments will
be further subject to the terms of such third parties and the banks involved in
the payment processing.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(c) </span>
                            <span class="about-contain">The User is also informed that the request and the process of making payment
and/or payment made shall be irrevocable and unalterable and shall take most
of the care to ascertain each detail. Any transaction or transfer of amounts
based on the request by User shall be at User&#39;s sole risk, responsibility, and
liability.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(d) </span>
                            <span class="about-contain">The User shall understand that the Recordent Platform does not undertake to
refund the same or arrange to refund the same. The User shall be solely
responsible and liable for such payments.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(e) </span>
                            <span class="about-contain">It is also informed to the User that the User shall be solely liable to check each
detail submitted for seeking payments. The Recordent Platform shall not be
responsible and liable for any error made by the User and/or due to any delay
or network errors.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(f) </span>
                            <span class="about-contain">The Recordent Platform disclaims any liability on behalf of the banks,
payment service providers, network, or any other person or ambiguity in the
payment process of failure of transaction or non-refund, etc.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>12. No Warranties, Liability Indemnification</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">By agreeing to this Recordent Platform User Account Terms the User consent
to the following</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(i) </span>
                                    <span class="about-contain">The Recordent Platform gives or makes no representation or warranty
(either express or implied) as to the suitability of web-application or
software or technology integrations or services or its contents for any
purpose or the completeness, accuracy, reliability, security or
availability nor do they accept any responsibility arising in any way.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ii) </span>
                                    <span class="about-contain">The Recordent Platform in no event shall be liable to a User (or to
any third party claiming under a User or otherwise) for any indirect,
special, incidental, consequential, or exemplary damages arising from
the use of, or inability to use Recordent Platform. These exclusions
would apply to any claims for loss of revenue or profits, lost data,
loss of goodwill, work stoppage, computer failure or malfunction, or
any other commercial damages or losses, even if the Recordent
Platform knew or should have known of the possibility of such
damages.</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iii) </span>
                                    <span class="about-contain">The User agrees to defend, indemnify and hold Recordent Platform,
and parties/persons acting under it harmless from and against:</span>
                                </p>
                                <ul>
                                    <li>
                                        <p class="d-flex">
                                            <span class="index-letter">(A) </span>
                                            <span class="about-contain">Any claims, actions, demands, liabilities, judgments, and
settlements, including without limitation, reasonable legal
fees resulting from or alleged to result from the use of
Recordent Platform in any manner;</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="d-flex">
                                            <span class="index-letter">(B) </span>
                                            <span class="about-contain">Any loss, costs, damages, expenses, and liability caused by
the use of Recordent Platform;</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="d-flex">
                                            <span class="index-letter">(C) </span>
                                            <span class="about-contain">Violation of any rights of a third party through the use of
Recordent Platform; and</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="d-flex">
                                            <span class="index-letter">(D) </span>
                                            <span class="about-contain">Any indirect, special, incidental, remote, punitive,
exemplary, or consequential damages arising out of User&#39;s
acts.</span>
                                        </p>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li>
                <strong>13. Termination Without Prejudice To Any Other Rights</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="about-contain">The Recordent Platform may terminate this Recordent Platform User Account
Terms if a User fails to comply with any terms or condition of this Recordent
Platform User Account Terms or in case Recordent Platform wish to withdraw
any access or services for any reasons.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>14. Governing Law, Jurisdiction, And Dispute Resolution</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">The laws of India, without regard to its conflict of laws rules, will govern
these Recordent Platform User Account Terms, as well as User&#39;s and
Recordent Platform&#39;s observance of them.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">If User take any legal action relating to User&#39;s use of the Recordent Platform
or these terms, the same shall be subject to the exclusive jurisdiction of courts
located in Hyderabad, India.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(c) </span>
                            <span class="about-contain">In case the User has any disputes with the Recordent Platform, User may raise
User&#39;s issues to the Recordent Platform and request for mediation.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(d) </span>
                            <span class="about-contain">If the matter is not resolved through such mediation, the same may then be
settled by Arbitration by a single Arbitrator appointed by the Recordent
Platform. The Arbitration shall be held following the provisions of the
Arbitration and Conciliation Act, 1996. The language of the Arbitration shall
be in English, and the place of Arbitration shall be Hyderabad India.</span>
                        </p>
                    </li>
                </ul>
            </li>


            <li>
                <strong>15. Breach Of The End User Terms Or Other Terms</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">Without prejudice to Recordent Platform&#39;s other rights, if User breach these
terms and, in any way, or if Recordent Platform suspect that User has
breached these terms in any way, Recordent Platform may</span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(i) </span>
                                    <span class="about-contain">Send User one or more formal warnings;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(ii) </span>
                                    <span class="about-contain">Temporarily suspend User&#39;s access to Recordent Platform and
services;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iii) </span>
                                    <span class="about-contain">Permanently prohibit User from accessing Recordent Platform and
services;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(iv) </span>
                                    <span class="about-contain">Block computers using User&#39;s IP address from accessing Recordent
Platform and services;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(v) </span>
                                    <span class="about-contain">Contact any or all of User&#39;s internet service providers and request that
they block User&#39;s access to Recordent Platform and services;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(vi) </span>
                                    <span class="about-contain">Commence legal action against User, whether for breach of contract
or recovery of amounts due or damages or otherwise;</span>
                                </p>
                            </li>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter">(vii)</span>
                                    <span class="about-contain"> Suspend or delete User&#39;s account on Recordent Platform and services.</span>
                                </p>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">Notwithstanding anything to the contrary contained this these terms after
termination or suspension of services or discontinuance of User Account on
Recordent Platform pursuant to User&#39;s breach or otherwise, User will not be
entitled to refund of any fee or charges paid, nor shall be discharged from any
pending obligation accrued in favor or Recordent Platform or by availing any
services through Recordent Platform.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>16. Recordent Platform&#39;s Information</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">Except for Information submitted to Recordent Platform by the User, all other
Information on the Recordent Platform is either owned by or licensed to
Recordent Platform, and is subject to copyright, trademark rights, and other
intellectual property rights of the Recordent Platform or the Recordent
Platform&#39;s licensors.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">Any third party trade or service marks present on the Recordent Platform not
uploaded or posted by a User are trade or service marks of their respective
owners may not be downloaded, copied, reproduced, distributed, transmitted,
broadcast, displayed, sold, licensed, or otherwise exploited for any other
purpose whatsoever without the prior written consent of Recordent Platform.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(C) </span>
                            <span class="about-contain">The Recordent Platform will not be liable concerning such information, or use
of, or otherwise in connection with Recordent Platform for any direct loss; for
any indirect, special or consequential loss; or for any business losses, loss of
revenue, income, profits or anticipated savings, loss of contracts or business
relationships, loss of reputation or goodwill, or loss or corruption of
information or data.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>17. Contact Us/grievance Redressal/ Feedback</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">In case the User needs any details or clarifications or redressal of grievances
or has any feedback, the User can contact the Recordent Platform on
<a href="mailto:contact@recordent.com">contact@recordent.com.</a></span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">Also, the Recordent Platform believes that User&#39;s feedback makes use of
Recordent Platform and services better; please feel free to share it with the
Recordent Platform on <a href="mailto:contact@recordent.com">contact@recordent.com.</a> Unless admitted explicitly by
Recordent Platform&#39;s or as required by law, all feedback shall be non-
confidential.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(c) </span>
                            <span class="about-contain">The Recordent Platform will assume no responsibility for reviewing
unsolicited ideas and will not incur any liability due to any similarities
between those ideas and materials that may appear in future programs of the
Recordent Platform.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(d) </span>
                            <span class="about-contain">Please do not reveal trade secrets or other confidential information in User&#39;s
messages to the Recordent Platform. Any and all rights to materials submitted
to Recordent Platform&#39;s become the exclusive property of the Recordent
Platform.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(e) </span>
                            <span class="about-contain">The User understands and confirms that the Recordent Platform may record
any calls or communication made to it by User or vice versa.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <li>
                <strong>18. Miscellaneous</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(a) </span>
                            <span class="about-contain">Any reference to a statutory provision shall include such provision from time
to time modified or re-enacted or consolidated so far as such modification or
re-enactment or consolidation applies or is capable of using it to any
transactions entered into hereunder.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(b) </span>
                            <span class="about-contain">Recordent Platform shall be permitted to assign, transfer, and subcontract its
rights and/or obligations under these terms without any notification or consent required. However, User shall not be permitted to assign, transfer, or
subcontract any of User&#39;s rights and/or obligations under these terms.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(c) </span>
                            <span class="about-contain">Notwithstanding anything contained in this Agreement, the Recordent
Platform shall not be responsible or liable for any acts or deed of the User or
and/or for any claims including that arising due to (i) no errors of the
Recordent Platform and/or (ii) for any indirect claims and/or (iii) for any
claims not adjudicated by a court of law as per due process and/or (iv) for any
claim on the Recordent Platform beyond the portion of Fee for any
undischarged part of the services pending delivery by the Recordent Platform
under this Agreement provided such fee was paid in advance by the User.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(d) </span>
                            <span class="about-contain">If a provision of a contract under these terms is determined by any court or
other competent authority to be unlawful and/or unenforceable, the other
provisions will continue in effect. If any unlawful and/or unenforceable
provision of a contract under any terms would be lawful or enforceable if part
of it were deleted, that part will be deemed to be deleted, and the rest of the
provision will continue in effect.</span>
                        </p>
                    </li>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter">(e) </span>
                            <span class="about-contain">These terms will inure to the benefit of the Recordent Platform and its
successors and assign and the benefit of the User and are not intended to
benefit or be enforceable by any third party not expressly stated herein. The
exercise of the parties&#39; rights under a contract under these terms is not subject
to the consent of any third party.</span>
                        </p>
                    </li>
                </ul>
            </li>

            <!-- <li>
                <strong>1. Notice to the user</strong>
                <span class="b-s-15"></span>
                <ul>
                    <li>
                        <p class="d-flex">
                            <span class="index-letter"> </span>
                            <span class="about-contain"></span>
                        </p>
                        <ul>
                            <li>
                                <p class="d-flex">
                                    <span class="index-letter"> </span>
                                    <span class="about-contain"></span>
                                </p>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li> -->



        </ul>

              <!-- <ul class="ask-questions p-policy">
                  <li>
                      <p><strong class="d-flex"><span class="index-letter">1. </span> <span class="about-contain">Introduction:</span> </strong></p>



                      <ul>



                          <li><p class="d-flex"> <span class="index-letter"> (a) </span> <span class="about-contain">The website www.recordent.com,its mobile phone application(s), API integration or  host  to  host  integration,  technical  service  arrangement/integrations    and/or  its internet  based  applications  and/or  software  and  its  brand  Recordent  (collectively referred as “Recordent”)is owned and operated by Recordent Private Limited, a company  incorporated  under  the  Companies  Act, 2013  (18  of  2013),  having  its registered office at 1stFloor, Mid Town Plaza, Road no.1, Banjara Hills, Hyderabad, Telangana -500033</span></p></li>



                      </ul>



                      <ul>



                          <li>



                              <p class="d-flex"> <span class="index-letter">1.1</span> <span class="about-contain">If a  User register  with Recordent  orsubmit  any  material  to  or  use  any  of Recordent services, Recordent will  ask a  Userto  expressly  agree  to the  following  (as  may  be decided that Recordent’s sole and absolute discretion):</span> </p>



                              <ul>



                                  <li><p class="d-flex"><span class="index-letter">(a)</span><span class="about-contain">any Termsand conditions;</span></p></li>



                                  <li><p class="d-flex"><span class="index-letter">(b)</span><span class="about-contain">Recordent’s Privacy Policy;</span></p></li>



                                  <li><p class="d-flex"><span class="index-letter">(c)</span><span class="about-contain">Recordent’s End User License Agreement <strong>(“EULA”)</strong>;</span></p></li>



                                  <li><p class="d-flex"><span class="index-letter">(d)</span><span class="about-contain">Recodent User Service Agreement </span></p></li>



                                  <li><p class="d-flex"><span class="index-letter">(e)</span><span class="about-contain">Data Integrity standards </span></p></li>



                                  <li><p class="d-flex"><span class="index-letter" style="opacity:0">(f) </span><span class="about-contain">(collectively called the <stronng>“Terms”</stronng>) </span></p></li>



                                  <li><p class="d-flex"><span class="index-letter" style="opacity:0">(g) </span><span class="about-contain">Each of such termsare incorporatedand shall form part and parcel of these presents. </span></p></li>



                              </ul>



                          </li>



                          <li><p class="d-flex"><span class="index-letter">1.2 </span><span class="about-contain">Each  User  isrequested  to  read  the  Terms  carefully  before  registering,  accessing, browsing, uploading or using anything from Recordent. </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">1.3 </span><span class="about-contain">By accessing or using Recordent, each User agree to be bound by the Terms including any additional guidelines, disclaimers and future modifications.  </span></p></li>



                      </ul>



                  </li>



                  <li>



                      <p><strong class="d-flex"><span class="index-letter">2. </span> <span class="about-contain">Acceptance by the User:</span> </strong></p>



                      <ul>



                          <li><p class="d-flex"><span class="index-letter">2.1 </span><span class="about-contain">Acceptance of the Terms shall constitute a valid and binding legal agreement between Recordent and the User. </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.2 </span><span class="about-contain">A  User  Understand  that  the Term  shall  govern use  of Recordent,  including  any Recordent products, software, data feeds and services. </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.3 </span><span class="about-contain">The Terms apply to all Users, including Users who are also contributors of Information on Recordent. The  expression <strong>“Information”</strong>  includes  the  text,  scripts,  graphics, photos, video, data, intimation, and other materials </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.4 </span><span class="about-contain">Recordent  mayuse  cookies;  by  using Recordentor  agreeing  to any  Terms, a  User consent to the same.  </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.5 </span><span class="about-contain">The Recordent has the right to deny registration of a Userwithout assigning any reason whatsoever. </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.6 </span><span class="about-contain">A  User shall  not  impersonate  any  person  or  entity  or  falsely  state  or  otherwise misrepresent age, identity or affiliation with any person or entity. </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.7 </span><span class="about-contain">Each User understand that they are duty bound and responsible to inform  and obtain their  counterparts  that  they  will  be  submitting  Information  to  Recordent  which  may include  details  of  such  counterparts  and  their  Information.  In  this  regard  from  such counterparts they have obtained necessary consent </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.8 </span><span class="about-contain">Recordent reserves its right  to  modify any  Terms and  other  policies  applicable  in general and/or to specificfor its offerings, at any time without giving a User any prior notice, and such changes shall be binding on a User. </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.9 </span><span class="about-contain">A User shall re-visit the Terms from time to time to stay abreast of any changes that Recordentmay introduce to any Terms. </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.10 </span><span class="about-contain">Further, Termsthat are  an  electronic  record under  theprovisions  of  the  Information Technology Act, 2000. </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">2.11 </span><span class="about-contain">This  electronic  record  is  generated  by  a  computer  system  and  does  not  require  any physical or digital signatures. </span></p></li>



                      </ul>



                  </li>



                  <li>



                      <p><strong class="d-flex"><span class="index-letter">3. </span> <span class="about-contain">Use of Recordent</span> </strong></p>



                      <ul>



                          <li><p class="d-flex"><span class="index-letter">3.1 </span><span class="about-contain">In  order  to  access Recordent, a  User  maycreate  an  account.  When  creating a  User Account, User must provide accurate and complete information. It is important that the User must keep User Account and password secure and confidential </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">3.2 </span><span class="about-contain">A User must keep password confidential. A User must notify Recordentimmediately of any breach of security or unauthorised use of User Account or password as soon as they become  aware  of  it. A  User must not  use any other person’s  account  to  access Recordent. </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">3.3 </span><span class="about-contain">A User agree to be solely  responsible and liable  (to Recordent, and to others) for all activity that occurs under their User Account.  </span></p></li>



                          <li><p class="d-flex"><span class="index-letter">3.4 </span><span class="about-contain">A User is also liable  for  any  activity on Recordentarising out of any  failure to keep their password confidential and for any losses arising out of such a failure </span></p></li>



                      </ul>



                  </li>



                  <li>



                      <p><strong class="d-flex"><span class="index-letter">4. </span> <span class="about-contain">Information</span> </strong></p>



                      <ul>



                        <li><p class="d-flex"><span class="index-letter">4.1 </span><span class="about-contain">As an account holder, A User shallsubmit only truthful Informationto Recordent. </span></p></li>



                        <li><p class="d-flex"><span class="index-letter">4.2 </span><span class="about-contain">A  Userunderstand  that  whether  or  not  Information  is  published,  Recordent  does  not guarantee  any  confidentiality  with  respect  to the Information.If  the  Information  is required to be maintained as confidential, each Userisrequired to maintain the same. Recordent shall not liable or responsible for breach of User’sobligations. Further, by receiving  the  Information  on  Recordent,  it  relies  on  the  representation  that Userhasshared it in compliance with law and contract applicable for such acts. </span></p></li>



                        <li><p class="d-flex"><span class="index-letter">4.3 </span><span class="about-contain">Each Usergrant to Recordentand/orthe other users of Recordent(as may be agreed by Recordent)a  worldwide,  irrevocable,  non-exclusive,  royalty-free licenseto  use, reproduce, store, adapt, publish, translate and distribute Information on and in relation to Recordentand  any  successor  website  /  reproduce,  store  and,  with User’sspecific consent,  publish  Information  on  and  in  relation  to Recordent. A  Useralso  grant  to Recordentthe right to sub-license the rights licensed to Recordentunder this section.4 </span></p></li>



                        <li><p class="d-flex"><span class="index-letter">4.4 </span><span class="about-contain">EachUser understand  and  agree  that  the  Information  uploaded  may  be  used  by any third person.Recordent shall be entitled to disclose the source at its sole discretion. </span></p></li>



                        <li><p class="d-flex"><span class="index-letter">4.5 </span><span class="about-contain">Each  User  alsounderstand  and  agree  that theyare  solely  responsible  for theirInformation  and  the  consequences  of  posting  or  publishing  it.  Recordent  does  not endorse any Information or any opinion, recommendation, or advice expressed therein, and  Recordent  expressly  disclaims  all  ownership  and  liability  in  connection  with Informationor use of it by any person. </span></p></li>



                        <li>



                            <p class="d-flex"><span class="index-letter">4.6 </span><span class="about-contain">Each Userrepresent and warrant to Recordent that  </span></p>



                            <ul>



                                <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">they have  (and  will  continue  to  have  during  use  of Recordentby  a  User)  all necessary authority, licenses, rights, consents and permissions which are required to share  the  Information  and enable  Recordent  to  use  the  Information  for  the purposes  of  hosting  the  Information  on Recordent,  and  otherwise  to  use  the Informationby Recordent or any third person. </span></p></li>



                                <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">That they will not post or upload any Information which contains material which it is unlawful for to possessin India, or which it would be unlawful for Recordent to  use  or  possess  in  connection  with  the  provision  of  the  services  through Recordent </span></p></li>



                                <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">Any third-party Information shared is with due consent and permission from  the concerned person </span></p></li>



                            </ul>



                        </li>







                        <li><p class="d-flex"><span class="index-letter">4.7 </span><span class="about-contain">On  becoming  aware  of  any  potential  violation  of any  Terms,  Recordent  reserves  the right to decide whether Information complies with the Information requirements set out in any Termsand may remove such Information and/or terminate a User’s access for uploading  Information  which  is  in  violation  of any  Termsat  any  time,  without  prior notice to the concerned User and at Recordent’ssole discretion. </span></p></li>



                        <li><p class="d-flex"><span class="index-letter">4.8 </span><span class="about-contain">Each Useragree and undertake to indemnify, defend and hold harmless Recordent and all its officers, directors, promoters, employees, agents and representatives against any and all loss and claims arising from any Information uploaded on Recordentor by use of Recordent . </span></p></li>



                    </ul>



                </li>



                <li>



                  <p><strong class="d-flex"><span class="index-letter">5. </span> <span class="about-contain">Prohibited Conduct</span> </strong></p>



                  <ul>



                      <li>



                          <p class="d-flex"><span class="index-letter">5.1 </span><span class="about-contain">By using Recordenta Useragree that theyshall not: </span></p>



                          <ul>



                              <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">use Recordentfor spamming or any other illegal purposes; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">upload any promotional material or advertisement to theUser Account; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">infringe Recordent’s or any third party's intellectual property rights, rights of publicity or privacy; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(d) </span><span class="about-contain">post or transmit any message, data, image or program which violates any law; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(e) </span><span class="about-contain">refuse to cooperate in an investigation or provide confirmation of User’sidentity or any other information providedto Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(f) </span><span class="about-contain">remove,  circumvent,  disable,  damage  or  otherwise  interfere  with  security  related features of the Recordentor features that enforce limitations on the use of Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(g) </span><span class="about-contain">upload any  Information  which is in contempt of  any  court, or in breach of any  court order;  or  discriminates  on  the  basis  of  age,  sex,  religion,  race,  gender;  harassing, invasive of another's privacy, blasphemous; in breach of any contractual obligations or consists of or contains any instructions, advice or other information which may be acted upon  and  could,  if  acted  upon,  cause  illness,  injury  or  death,  or  any  other  loss  or damage;  or  constitutes  spam;  or  is  grossly  harmful,  offensive,  deceptive,  fraudulent, threatening, abusive, hateful, harassing, anti-social, menacing, hateful, discriminatory or  inflammatory;  or  causes  annoyance,  inconvenience  or  needless  anxiety  to  any person;  or  racially,  ethnically  objectionable,  disparaging,  relating  or  encouraging money laundering or  gambling, or harm minors in any  way or otherwise  unlawful in any manner whatever; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(h) </span><span class="about-contain">upload  any  Information  that  threatens  the  unity,  integrity,  defense,  security  or sovereignty of any country, or public order or causes incitement to the commission of any  cognizable  offence  or  prevents  investigation  of  any  offence  or  is  insulting  any nation; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(i) </span><span class="about-contain">upload any Information that contains software viruses,or any other computer code, files  or  programs  designed  to  interrupt,  destroy  or  limit  the  functionality  of  any computer resource; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(j) </span><span class="about-contain">reverse engineer, decompile, disassemble or otherwise attempt to discover the source code of Recordentor any part thereof or infringe any patent, trademark, copyright or other proprietary rights; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(k) </span><span class="about-contain">use Recordentin  any  manner  that  could  damage,  disable,  overburden,  or  impair, including, without limitation, using Recordentin an automated manner; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(l) </span><span class="about-contain">modify, adapt, translate or create derivative works based upon Recordentor any part thereof; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(m) </span><span class="about-contain">intentionally interfere with or damage operation of Recordentor anyother User’s use of Recordent, by any means, including uploading or otherwise disseminating viruses, adware,  spyware,  worms,  or  other  malicious  code  or  file  with  contaminating  or destructive features; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(n) </span><span class="about-contain">use  any  robot,  spider,  other  automatic  device,  or  manual  process  to  monitor  or  copy Recordentwithout prior written permission of Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(o) </span><span class="about-contain">interfere or disrupt Recordentor networks connected therewith </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(p) </span><span class="about-contain">take  any  action  that  imposes  an  unreasonably  or  disproportionately  large  load  on Recordent’sinfrastructure/network; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(q) </span><span class="about-contain">use any device, software or routine to bypass Recordent’srobot exclusion headers, or interfere or attempt to interfere, with Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(r) </span><span class="about-contain">forge headers or manipulate identifiers or other data in order to disguise the origin of any  Information  transmitted  through Recordentor  to  manipulate User’spresence  on Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(s) </span><span class="about-contain">sell/  sub-license  the   Information,  or  software  associated   with  or  derivedfrom Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(t) </span><span class="about-contain">use  the  facilities  and  capabilities  of Recordentto  conduct  any  activity  or  solicit  the performance of any illegal activity or other activity which infringes the rights of others; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(u) </span><span class="about-contain">breach any Termsor any other policy of Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(v) </span><span class="about-contain">provide false, inaccurate or misleading information to Recordent. </span></p></li>



                          </ul>



                      </li>



                      <li><p class="d-flex"><span class="index-letter"> </span><span class="about-contain"> </span></p></li>



                  </ul>



              </li>



              <li>



                  <p><strong class="d-flex"><span class="index-letter">6. </span> <span class="about-contain">Commitments to be undertaken by User</span> </strong></p>



                  <ul>



                      <li><p class="d-flex"><span class="index-letter">6.1 </span><span class="about-contain">Each  User  understand  that  for  using  Recordent  they  will  be  bound  by  Data  Integrity standards and terms of the User Service Agreement as and when required by Recordent. </span></p></li>



                  </ul>



              </li>



              <li>



                  <p><strong class="d-flex"><span class="index-letter">7. </span> <span class="about-contain">Recordent’s Information</span> </strong></p>



                  <ul>



                      <li><p class="d-flex"><span class="index-letter">7.1 </span><span class="about-contain">With  the  exception  of  Information  submitted  to Recordentby  the  User,  all  other Information on the Recordentis either owned by or licensed to Recordent, and is subject to copyright, trade mark rights, and other intellectual property rights of Recordent or Recordent’s  licensors.  Any third-partytrade  or  service  marks  present  on  the Recordentnot  uploaded  or  posted  by a  Userare  trade  or  service  marks  of  their respective   owners   may   not   be   downloaded,   copied,   reproduced,   distributed, transmitted, broadcast, displayed, sold, licensed, or otherwise exploited for any other purpose whatsoever without the prior written consent of Recordent. </span></p></li>



                      <li><p class="d-flex"><span class="index-letter">7.2 </span><span class="about-contain">Recordent will not be liable in relation to the Information, or use of, or otherwise in connection with Recordentfor any direct loss; for any indirect, special or consequential loss; or for any business losses, loss of revenue, income, profits or anticipated savings, loss  of  contracts  or  business  relationships,  loss  of  reputation  or  goodwill,  or  loss  or corruption of information or data. </span></p></li>



                  </ul>



              </li>



              <li>



                  <p><strong class="d-flex"><span class="index-letter">8. </span> <span class="about-contain">Cancellation and suspension of User Account</span> </strong></p>



                  <ul>



                      <li><p class="d-flex"><span class="index-letter">8.1 </span><span class="about-contain">The Termswill continue to apply to a User until terminated by Recordent or as set out in the respective terms and conditions. </span></p></li>



                      <li>



                          <p class="d-flex"><span class="index-letter">8.2 </span><span class="about-contain">Recordent may: </span></p>



                          <ul>



                              <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">uspend any User Account; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">cancel any User Account; and/or  </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">edit a User Account at any time in Recordent’ssole discretion without notice or explanation. </span></p></li>



                          </ul>



                      </li>



                      <li><p class="d-flex"><span class="index-letter">8.3 </span><span class="about-contain">Recordent  may,  at  any  time  terminate  its  offering  to  a  User  if  the  User  breach  any provision of any Terms(or have acted in manner which clearly shows that the User do not intend to, or are unable to comply with the provisions of any of the Terms), or if Recordent is required to do so by law. </span></p></li>



                      <li><p class="d-flex"><span class="index-letter">8.4 </span><span class="about-contain">Any  cancellation  or  suspension  of  User  Account  by  a  User  shall  be subject  to  the acceptance of Recordent and upon a Userperforming required  formalities as may be required by Recordent from time to time </span></p></li>



                  </ul>



              </li>



              <li>



                  <p><strong class="d-flex"><span class="index-letter">9. </span> <span class="about-contain">Breach of the Terms</span> </strong></p>



                  <ul>



                      <li>



                          <p class="d-flex"><span class="index-letter">9.1 </span><span class="about-contain">Without prejudice to Recordent’s other rights under any Terms, if a User breach any of the Termsin any way, or if Recordent suspect that a User breached any of the Termsin any way, Recordent may at its discretion may exercise any of the following</span></p>



                          <ul>



                              <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">send the User one or more formal warnings </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">temporarily suspend User’s access to Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">permanently prohibit a User from accessing Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(d) </span><span class="about-contain">block computers using User’s  IP address from accessing Recordent;  </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(e) </span><span class="about-contain">contact any or all of User’sinternet service providers and request that they block User’s access to Recordent; </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(f) </span><span class="about-contain">commence  legal  action  against  the  User,  whether  for  breach  of  contract  or otherwise; and/or  </span></p></li>



                              <li><p class="d-flex"><span class="index-letter">(g) </span><span class="about-contain">suspend or delete concerned User Account on Recordent </span></p></li>



                          </ul>



                      </li>



                      <li><p class="d-flex"><span class="index-letter">9.2 </span><span class="about-contain">Where we suspend or prohibit or block User’saccess to Recordentor a part thereof, A Usermust not take any action to circumvent such suspension or prohibition or blocking (including without limitation creating and/or using a different account).By creating a user account a User confirm that are not a blocked user. </span></p></li>



                  </ul>



              </li>



              <li>



                  <p><strong class="d-flex"><span class="index-letter">10. </span> <span class="about-contain">Miscellaneous</span> </strong></p>



                  <ul>



                      <li><p class="d-flex"><span class="index-letter">10.1 </span><span class="about-contain">By using Recordent in any manner each User agree that Recordent may assign, transfer, sub-contract  or  otherwise  deal  with Recordent’s rights  and/or  obligations  under any Terms. A User without Recordent’s prior written consent, assign, transfer, sub-contract or otherwise deal with any of their rights and/or obligations under any of the Terms. </span></p></li>



                      <li><p class="d-flex"><span class="index-letter">10.2 </span><span class="about-contain">If  a  provision  of  a  contract  under any  Termsis  determined  by  any  court  or  other competent  authority  to  be  unlawfuland/or  unenforceable,  the  other  provisions  will continue in effect. If any unlawful and/or unenforceable provision of a contract under any Termswould be lawful or enforceable if part of it were deleted, that part will be deemed to be deleted, and the rest of the provision will continue in effect.  </span></p></li>



                      <li><p class="d-flex"><span class="index-letter">10.3 </span><span class="about-contain">A contract under any Termswill inure to the benefit of Recordent and its successors and assigns and the benefit of the User, and is not intended to benefit or be enforceable by any third party not expressly stated herein. The exercise of the parties’ rights under a contract under any Termsis not subject to the consent of any third party. </span></p></li>



                      <li><p class="d-flex"><span class="index-letter">10.4 </span><span class="about-contain">Any  Terms,  together  with  other  policies  of Recordent,  shall  constitute  the  entire agreement between a User and Recordentin relation to use of Recordent </span></p></li>



                      <li><p class="d-flex"><span class="index-letter">10.5</span><span class="about-contain">Each  User agree  that  Recordent  may  provide them with  notices,  including  those regarding changes to any Terms, by email, regular mail, or postings on Recordent. </span></p></li>



                      <li><p class="d-flex"><span class="index-letter">10.6 </span><span class="about-contain">Each of the Termsof Recordentshall be governed by and construed in accordance with the laws of republic of India. Any disputes relating to any Termsor the use of Recordentshall be subject to the exclusive jurisdiction of courts at Hyderabad, India </span></p></li>



                  </ul>



              </li>



              <li>



                  <p class="">



                      <strong class="d-flex">



                          <span class="index-letter">11. </span>



                        <span class="about-contain">Contact Details: &nbsp</span>



                  </strong>



                  A Usercan contact Recordentby emailing at &nbsp<a href="mailto:hello@recordent.com">hello@recordent.com</a></p>



              </li>



          </ul> -->



      </div>



  </div>







</div>







</div>







</div>























    <!-- Bootstrap core JavaScript -->



    <script src="{{config('app.url')}}front/vendor/jquery/jquery.min.js"></script>



    <script src="{{config('app.url')}}front/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>



    <!-- Plugin JavaScript -->



    <script src="{{config('app.url')}}front/vendor/jquery-easing/jquery.easing.min.js"></script>



    <!-- Contact Form JavaScript -->



    <script src="{{config('app.url')}}front/js/jqBootstrapValidation.js"></script>



    <script src="{{config('app.url')}}front/js/contact_me.js"></script>



    <!-- Custom scripts for this template -->



    <script src="{{config('app.url')}}front/js/freelancer.min.js"></script>



    <script async src="https://www.google-analytics.com/analytics.js"></script>



    <script>



    var appUrl = "{{env('APP_URL')}}";



    $(document).ready(function(){

        $('input:radio[name="login_by"][value="email"]').prop('checked', true);
        $("#admin-email-login-tab").addClass('show active');
        $("#admin-otp-login-tab").removeClass('show active');



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



    $("input[name=login_by]").on('change',function(){



        if($(this).val()=="mobile"){



            $("#admin-email-login-tab").removeClass('show active');



            $("#admin-otp-login-tab").addClass('show active');



        }else{



            $("#admin-otp-login-tab").removeClass('show active');



            $("#admin-email-login-tab").addClass('show active');



        }



    });



      $("#get-otp-form").on('submit',function(e){



        e.preventDefault();







        var form =$("#get-otp-form");



        form.find('input.login-button').attr('disabled','disabled');



        var mobile_number = form.find('input[name=mobile_number]').val();







        $("#admin-otp-login-tab").find('.alert').addClass("hide");



        $("#admin-otp-login-tab").find('.alert').html('');







        $.ajax({



         method: 'post',



         url: "{{route('admin.login.get-otp')}}",



         headers: {



           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')



         },



         data: {



           mobile_number: mobile_number,



           _token: $('meta[name="csrf-token"]').attr('content')



         }



        }).then(function (response) {



          form.addClass('hide');



          $("#adminMobileLoginForm").find('input[name=mobile_number]').val(response.mobile_number);



          $("#adminMobileLoginForm").removeClass('hide');



          $("#admin-otp-login-tab").find('.alert.alert-success').html(response.message);



          $("#admin-otp-login-tab").find('.alert.alert-success').removeClass('hide');



          form.find('input.login-button').removeAttr('disabled');







        }).fail(function (data) {







          $("#admin-otp-login-tab").find('.alert.alert-danger').html(data.responseJSON.message);



          $("#admin-otp-login-tab").find('.alert.alert-danger').removeClass('hide');



          form.find('input.login-button').removeAttr('disabled');







        });



      });







      $("#adminMobileLoginForm").on('submit',function(e){



            e.preventDefault();



            var form =$("#adminMobileLoginForm");



            form.find('button#LoginMobileButton').attr('disabled','disabled');



            var mobile_number = form.find('input[name=mobile_number]').val();



            var otp = form.find('input[name=otp]').val();
            var campaign_id = form.find('input[name=campaign_id]').val();
            var credit_report_redirect = form.find('input[name=credit_report_redirect]').val();
            var membership_page_redirect = form.find('input[name=membership_page_redirect]').val();
            var help_support_redirect = form.find('input[name=help_support_redirect]').val();








            $("#admin-otp-login-tab").find('.alert').addClass("hide");



            $("#admin-otp-login-tab").find('.alert').html('');








            $.ajax({



               method: 'post',



               url: "{{route('admin.login-with-otp')}}",



               headers: {



                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')



               },



               data: {



                   mobile_number: mobile_number,



                   otp:otp,


                    campaign_id:campaign_id,
                    credit_report_redirect:credit_report_redirect,
                    membership_page_redirect:membership_page_redirect,
                    help_support_redirect:help_support_redirect,
                   _token: $('meta[name="csrf-token"]').attr('content')



               }



            }).then(function (response) {





                localStorage.setItem('is_first_time', 1);
              if(!response.membershippage) {
                if(!response.helpsupport) {
                if(!response.checkcredit) {
                if(response.url=='{{ url('') }}')
                  window.location.href="{{url('admin')}}";
                else
                  window.location.href=response.url;
                } else {
                  window.location.href="{{url('admin/creditreports')}}";
                }
                }
                else {
                  window.location.href="{{url('admin/helpandsupport')}}";
                }
                } 
                else {
                  window.location.href="{{url('membership')}}";
                }







            }).fail(function (data) {







                  $("#admin-otp-login-tab").find('.alert.alert-danger').html(data.responseJSON.message);



                  $("#admin-otp-login-tab").find('.alert.alert-danger').removeClass('hide');



                  form.find('button#LoginMobileButton').removeAttr('disabled');



                });







      });







      //resend OTP



      $("#admin-otp-login-tab #resendOtp").on('click',function(e){



        e.preventDefault();



        $("#admin-otp-login-tab").find('.alert').addClass("hide");



        $("#admin-otp-login-tab").find('.alert').html('');



        $("#admin-otp-login-tab #get-otp-form").removeClass('hide');



        $("#admin-otp-login-tab #adminMobileLoginForm").addClass('hide');



        $("#admin-otp-login-tab #adminMobileLoginForm").find('input[name=mobile_number]').val('');



        $("#admin-otp-login-tab #adminMobileLoginForm").find('input[name=otp]').val('');







      });







      $("#admin-email-login-tab #loginWithEmailGetOtpToMobileForm").on('submit',function(e){



            e.preventDefault();



            var form =$(this);



            form.find('input.login-button').attr('disabled','disabled');



            var email = form.find('input[name=email]').val();



            var password = form.find('input[name=password]').val();
            var campaign_id = form.find('input[name=campaign_id]').val();
            var credit_report_redirect = form.find('input[name=credit_report_redirect]').val();
            var membership_page_redirect = form.find('input[name=membership_page_redirect]').val();
            var help_support_redirect = form.find('input[name=help_support_redirect]').val();

            $("#admin-email-login-tab").find('.alert').addClass("hide");
            $("#admin-email-login-tab").find('.alert').html('');

            $.ajax({
             method: 'post',
             url: "{{route('admin.login-with-email-get-otp-to-mobile')}}",
             headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },

             data: {
               email: email,
               password: password,
               campaign_id: campaign_id,
               credit_report_redirect: credit_report_redirect,
               membership_page_redirect: membership_page_redirect,
               help_support_redirect: help_support_redirect,
               _token: $('meta[name="csrf-token"]').attr('content')
             }



            }).then(function (response) {







              if(typeof response.noNeedOtp !== 'undefined'){



                localStorage.setItem('is_first_time', 1);
                if(!response.membershippage) {
                if(!response.helpsupport) {
                if(!response.checkcredit) {
                if(response.url=='{{ url('') }}')
                  window.location.href="{{url('admin')}}";
                else
                  window.location.href=response.url;
                } else {
                  window.location.href="{{url('admin/creditreports')}}";
                }
                }
                else {
                  window.location.href="{{url('admin/helpandsupport')}}";
                }
                } 
                else {
                  window.location.href="{{url('membership')}}";
                }



                return false;



              }







              form.addClass('hide');



              $("#loginWithEmailOtpToMobileForm").find('input[name=email]').val(response.email);



              $("#loginWithEmailOtpToMobileForm").find('input[name=password]').val(response.password);







              $("#loginWithEmailOtpToMobileForm").removeClass('hide');



              $("#admin-email-login-tab").find('.alert.alert-success').html(response.message);



              $("#admin-email-login-tab").find('.alert.alert-success').removeClass('hide');



              form.find('input.login-button').removeAttr('disabled');







            }).fail(function (data) {







              $("#admin-email-login-tab").find('.alert.alert-danger').html(data.responseJSON.message);



              $("#admin-email-login-tab").find('.alert.alert-danger').removeClass('hide');



              form.find('input.login-button').removeAttr('disabled');







            });



      });







      $("#admin-email-login-tab #loginWithEmailOtpToMobileForm").on('submit',function(e){



            e.preventDefault();



            var form =$(this);



            form.find('button.login-button').attr('disabled','disabled');



            var email = form.find('input[name=email]').val();



            var password = form.find('input[name=password]').val();



            var otp = form.find('input[name=otp]').val();
            var campaign_id = form.find('input[name=campaign_id]').val();
            var credit_report_redirect = form.find('input[name=credit_report_redirect]').val();
            var membership_page_redirect = form.find('input[name=membership_page_redirect]').val();
            var help_support_redirect = form.find('input[name=help_support_redirect]').val();







            $("#admin-email-login-tab").find('.alert').addClass("hide");



            $("#admin-email-login-tab").find('.alert').html('');







            $.ajax({



               method: 'post',



               url: "{{route('admin.login-with-email-otp-to-mobile')}}",



               headers: {



                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')



               },



               data: {



                   email: email,



                   password: password,



                   otp:otp,
                   campaign_id:campaign_id,
                   credit_report_redirect: credit_report_redirect,



                   _token: $('meta[name="csrf-token"]').attr('content')



               }



            }).then(function (response) {






                localStorage.setItem('is_first_time', 1);
               if(!response.membershippage) {
                if(!response.helpsupport) {
                if(!response.checkcredit) {
                if(response.url=='{{ url('') }}')
                  window.location.href="{{url('admin')}}";
                else
                  window.location.href=response.url;
                } else {
                  window.location.href="{{url('admin/creditreports')}}";
                }
                }
                else {
                  window.location.href="{{url('admin/helpandsupport')}}";
                }
                } 
                else {
                  window.location.href="{{url('membership')}}";
                }







            }).fail(function (data) {







                  $("#admin-email-login-tab").find('.alert.alert-danger').html(data.responseJSON.message);



                  $("#admin-email-login-tab").find('.alert.alert-danger').removeClass('hide');



                  form.find('button.login-button').removeAttr('disabled');



                });







      });



      //resend OTP



      $("#admin-email-login-tab #resendOtp").on('click',function(e){



        e.preventDefault();



        $("#admin-email-login-tab").find('.alert').addClass("hide");



        $("#admin-email-login-tab").find('.alert').html('');



        $("#admin-email-login-tab #loginWithEmailGetOtpToMobileForm").removeClass('hide');



        $("#admin-email-login-tab #loginWithEmailOtpToMobileForm").addClass('hide');



        $("#admin-email-login-tab #loginWithEmailOtpToMobileForm").find('input[name=email]').val('');



        $("#admin-email-login-tab #loginWithEmailOtpToMobileForm").find('input[name=password]').val('');



        $("#admin-email-login-tab #loginWithEmailOtpToMobileForm").find('input[name=otp]').val('');







      });



    });



    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;



    ga('create', '{{setting('site.google_analytics_tracking_id')}}', 'auto');



    ga('send', 'pageview');



</script>



<script async src='https://www.google-analytics.com/analytics.js'></script>

<script type="text/javascript">
  function myFunction() {
  var x = document.getElementById("myInput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>

  </body>



</html>
