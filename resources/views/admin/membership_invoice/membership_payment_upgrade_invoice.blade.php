<html>
    <head>
        <title>{{setting('site.title')}}</title>
    </head>
    <body style="background: white; color: black">

    <div >
        <p> Hello {{ $membership_payment->user->name }},</p>
        <p>Thank you for choosing to upgrade your membership to {{ $membership_payment->user->user_pricing_plan->pricing_plan->name }} with Recordent.</p>
        <p>Please find the invoice attached below for your upgrade.</p>
        <br>
        <p>Best Regards, <br>
        <span>Recordent Team.</span></p>
        <p><a href="{{route('admin.helpandsupport')}}"  target="_blank">Click here</a>&nbsp;if the above transaction was not done by you.</p>
    </div>
</body>
</html>
