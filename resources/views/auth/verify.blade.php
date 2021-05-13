@extends('layouts_front.app_new')

@section('content')

    <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="login-container">

                <p>{{ __('Verify Your Email Address') }}</p>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                    {{ __('Your account has been created successfully.') }}
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}" class="bright-link">{{ __('click here to request another') }}</a>.
                    {{ __('If you are not sure about the email address you entered at the time of register. You can change it.') }}, <a href="{{ route('change.email') }}" class="bright-link">{{ __('click here to change email') }}</a>.
                </div>
                
              <div style="clear:both"></div>
              @include('layouts_front.error')
              
              
            </div> <!-- .login-container -->

        </div> <!-- .login-sidebar -->

@endsection