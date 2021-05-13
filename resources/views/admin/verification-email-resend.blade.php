@extends('voyager::master')


@section('page_header')
    <h1 class="page-title">
        {{ __('Verify Your Email Address') }}
    </h1>
@stop

@section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
						<div class="row">
						    <div class="col-xs-12 col-sm-12 col-md-12">

								<div class="login-container">
									<div class="card-body">
										{{ __('Before proceeding, please check your email for a verification link.') }}
										{{ __('If you did not receive the email') }}, <a href="{{ route('verification.email.resend') }}" class="bright-link">{{ __('click here to request another') }}</a>.
									</div>

								  <div style="clear:both"></div>


								</div> <!-- .login-container -->

							</div> <!-- .login-sidebar -->

						</div>
					</div>
				</div>
			</div>
		</div>

@endsection