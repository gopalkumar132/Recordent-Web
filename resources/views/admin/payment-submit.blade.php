<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<?php $site_favicon = Voyager::setting('site.favicon', '');?>
    @if($site_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($site_favicon) }}" type="image/png">
    @endif
</head>
<body>
@if(setting('admin.payment_gateway_type')=='paytm')	
				@yield('payment_redirect')
			@else
				{{!!$payuForm!!}}
			@endif
</body>
</html>