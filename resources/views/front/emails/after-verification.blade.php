<html>
    <head>
        <title>{{setting('site.title')}}</title>
    </head>
    <body style="background: white; color: black">
        <p>Hello Admin,</p>
        <p>{{Auth::user()->business_name}} ({{Auth::user()->email}}) has been registered and email has been verified. {{--To active his/her account your action is required--}}</p>

        <p> Thanks & Regards,</p>
        <p>{{setting('site.title')}}</p>
    </body>
</html>
