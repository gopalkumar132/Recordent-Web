<html>
<body>
    <style>
        @media  only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
        	<tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tbody>
                    <tr>
					    <td class="header" style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';box-sizing:border-box;padding:25px 0;text-align:center">
					        <a href="{{config('app.url')}}" style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';box-sizing:border-box;color:#bbbfc3;font-size:19px;font-weight:bold;text-decoration:none">
					           {{setting('site.title')}}
					        </a>
					    </td>
					</tr>

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                <!-- Body content -->
                                <tbody>
                                	<tr>
                                    	<td class="content-cell">
                                        	<h1 style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';box-sizing:border-box;color:#3d4852;font-size:19px;font-weight:bold;margin-top:0;text-align:left">Hello {{$name}},</h1>
											<p style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';box-sizing:border-box;color:#3d4852;font-size:16px;line-height:1.5em;margin-top:0;text-align:left">Your account is activated. For login please click <a href="{{config('app.url')}}admin/login"> here</a></p>
											

											<p style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';box-sizing:border-box;color:#3d4852;font-size:16px;line-height:1.5em;margin-top:0;text-align:left">Thanks,<br>{{setting('site.title')}} Team</p>

                                        
                                    	</td>
                                	</tr>
                            	</tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
					    <td>
					        {{--<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
					            <tbody><tr>
					                <td class="content-cell" align="center">
					                    <p>© 2019 Recordent. All rights reserved.</p>
					                </td>
					            </tr>
					        </tbody></table>--}}
					    </td>
					</tr>
                </tbody>
            </table>
            </td>
        </tr>
    </tbody>
</table>


</body>
</html>








