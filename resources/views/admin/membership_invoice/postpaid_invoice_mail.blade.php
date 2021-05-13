<html>
    <head>
        <title>{{setting('site.title')}}</title>
    </head>
    <body style="background: white; color: black">

    <div >
        <p> Hello {{$membership_payment->user->business_name}},</p>
        <p>{{ $membership_payment->user->name }},</p>
        <br>
        <p>Please find the invoice attached for {{ $membership_payment->particular }}</p>
        <br>
        @if($membership_payment->postpaid==1)
        <p>You can pay by logging into your Recordent account or by Bank transfer.</p>
        <br>
        <p>Bank details:</p>
        <br>
        Name: Recordent Private Limited<br>
        HDFC Bank, Banjara Hills, Road No 3 Branch<br>
        Account Number: 50200036996639<br>
        IFSC : HDFC0002391
        <br><br>
        @endif
        <p>Best Regards,</p>
        <p>Recordent Team</p>
        <br>
        Registered Office: #P-306, Sri Rams Swathi Paradise, Alkapur Township, Rd No. 16, Hyderabad - 500089, Telangana.<br>
        Corporate Office: Aditya Trade Center,Office No:-7-1-618/ACT/710,Seventh Floor,Ameerpet,Hyderabad- 500038, Telangana. <br>
        CIN: U74999TG2018PTC124275<br>
        GSTIN: 36AAICR9328P1ZI
    </div>
</body>
</html>
