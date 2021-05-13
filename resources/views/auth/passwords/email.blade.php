@extends('layouts_front.app_new')
@section('canonical-url')
    <link rel="canonical" href="{{config('app.url')}}password/reset" />
@endsection
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
 <?php $admin_favicon = Voyager::setting('admin.favicon', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
<style type="text/css">
body.login .faded-bg{background:#fff;}
.hidden-xs.col-sm-12.col-md-12{display:none;}
body {
/*background-image:url('{{ Voyager::image( Voyager::setting("admin.bg_image"), voyager_asset("images/bg.jpg") ) }}');*/
background-color: {{ Voyager::setting("admin.bg_color", "#FFFFFF" ) }};
}
body {
height: 100% !important; font-family: 'Rubik', sans-serif !important;
}
body.login .login-container {  margin:25px auto 0;max-width: 700px; background: #fff; padding: 25px; right: 0px;left: 0px; top: 0;border:1px solid #273581;   position: relative;}

body.login .form-group-default label{    color: #273581;
    font-weight: 500;
    font-size: 10px;
}
body.login .login-button {
	color: #fff !important;
	background: #273581 !important;
	opacity: 1 !important;
	
	border-radius: 10px;
	border: 1px solid #273581;
	font-weight: 700;
	font-size: 14px;
}
body.login .login-button:hover {
	background: #fff !important;
	color: #273581 !important;
}

      #abc ::-webkit-input-placeholder{ 
  color: #495057;
}

 #abc :-ms-input-placeholder{ 
  color: #495057;
}

#abc ::placeholder{
  color: #495057;
}

.butn-login{display: flex; margin-top:20px;
justify-content: space-between;
align-items: center;}



</style>
<div class="col-xs-12 col-sm-12 col-md-12">
  <div class="login-container">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif
    <p style="color: #273581 !important;
font-weight: 500; font-family: 'Rubik', sans-serif !important; font-size:14px">{{ __('Reset Password') }}</p>
    @include('layouts_front.error')
    <form action="{{ route('password.email') }}" method="POST" id="abc">
      @csrf
      <div class="form-group form-group-default" id="emailGroup">
        <label>{{ __('E-Mail Address') }}</label>
        <div class="controls">
          <input type="text" name="email" id="email" value="{{ old('email') }}" placeholder="E-mail" class="form-control" required>
        </div>
      </div>
      <div class="butn-login">
        <button type="submit" class="btn btn-block login-button">
      <span class="signingin hidden"><span class="voyager-refresh"></span>...</span>
      <span class="signin">{{ __('Send Password Reset Link') }}</span>
      </button>
      <span class="float-right" style="">Or <a href="{{config('app.url')}}admin/login" class="bright-link"> Login</a></span> 
     
      
      </div>
      
    </form>
    <div style="clear:both"></div>
    
    
  </div>
  <!-- .login-container -->
</div>
<!-- .login-sidebar -->
@endsection