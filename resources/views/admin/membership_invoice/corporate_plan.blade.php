<html>
    <head>
        <title>{{setting('site.title')}}</title>
    </head>
    <body style="background: white; color: black">
        <p>Hello Admin,</p><br>
        @if(isset($user->business_short))
        <p>Member {{$user->business_short}}, is interested to subscribe for Corporate plan.</p>
        @else
        <p>Member {{$user->business_name}}, is interested to subscribe for Corporate plan.</p>
        @endif
        <br>
        <p>Date: {{ date('d-m-Y H:i')}}</p>
        <p>Name : {{$user->name}}</p>
        <p>Email : {{$user->email}}</p>
        <p>Mobile : {{$user->mobile_number}}</p><br>
        <p> Thanks & Regards,</p>
        <p>{{setting('site.title')}}</p>
    </body>
</html>
