<html>
    <head>
        <title>{{setting('site.title')}}</title>
    </head>
    <body style="background: white; color: black">

    <div >
        <p> Hi {{$name}},</p>
        <p>We are delighted that you have started your journey with Recordent.</p>
		<p>As a member, it is important that you submit your customer dues to start receiving payments soon. Follow the steps below to submit individual and business dues.</p>
        <p>1. Login using registered email id or mobile number</p>
        <p>2. Click on 'Individual customer dues' or 'Business customer dues' and start submitting dues. Watch this short video that explains how dues can be submitted on Recordent. </p>
        <p><a href="https://www.youtube.com/watch?v=cc6_v_eYLdw" target="_blank">Watch Video</a></p>
        <p>3. If you have more customer dues to be submitted, you can use the bulk upload option by <a href="{{route('login')}}"> clicking here</a></p>
        <p>For any assistance, mail us {{$support_mail}} or call on {{$contact}}</p>
        <p>Best Regards,</p>
        <p>Team Recordent</p>
        <p>Recordent Private Limited</p>
        <p>{{route('home')}}</p>
        <p>Spread a word about us with your connections to make the Recordent community stronger for all of us. </p>
        <p>Follow us on - </p> 
        <p>Facebook - https://www.facebook.com/recordent/</p> 
        <p>Twitter - https://twitter.com/recordentindia</p> 
        <p>LinkedIn - https://www.linkedin.com/company/recordent </p> 
    </div>
</body>
</html>
