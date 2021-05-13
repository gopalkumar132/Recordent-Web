@extends('layouts_front.app_new')
@section('content')
<style type="text/css">
  
  body {
    height: 100% !important;
}

</style>
<div class="col-xs-12 col-sm-12 col-md-12">
  <div class="login-container">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif
    <p>{{ __('Update Email') }}</p>
    @include('layouts_front.error')
    <form action="{{ route('change.email') }}" method="POST">
      @csrf
      <div class="form-group form-group-default" id="emailGroup">
        <label>{{ __('E-Mail Address') }}</label>
        <div class="controls">
          <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="E-mail" class="form-control" required>
          <input type="hidden" name="userId" id="userId" value="{{ $userId }}">
        </div>
      </div>
      <button type="submit" class="btn btn-block login-button">
      <span class="signingin hidden"><span class="voyager-refresh"></span>...</span>
      <span class="signin">{{ __('Update Email') }}</span>
      </button>
      <span class="float-right" style="padding-top: 15px;">Or <a href="{{config('app.url')}}admin/login" class="bright-link"> Login</a></span>
    </form>
    <div style="clear:both"></div>
    
    
  </div>
  <!-- .login-container -->
</div>
<!-- .login-sidebar -->
@endsection