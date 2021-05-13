@extends('layouts_front.app_new')
@section('content')
<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="login-container">
        <h5>Otp has been sent to your mobile number</h5>
        @include('layouts_front.error')
        <form action="{{ route('register') }}" method="POST" id="msform">
            @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-group-default" id="emailGroup">
                            <label>OTP*</label>
                            <div class="controls">
                                <input type="text" name="otp" id="otp" placeholder="Enter OTP" class="form-control" required>
                            </div>
                        </div>
                    </div>
                 </div>
                 <button type="submit"  class="btn btn-block login-button " value="Register" >
                    <span class="signingin hidden"><span class="voyager-refresh"></span>...</span>
                    <span class="signin">Register</span>
                </button>   
            <span class="float-right" style="padding-top: 15px;">Already have an account? <a href="{{config('app.url')}}admin/login" class="bright-link btn-border"> Login</a></span>
        </form>
        <div style="clear:both"></div>
    </div>
        <!-- .login-container -->
</div>
@endsection