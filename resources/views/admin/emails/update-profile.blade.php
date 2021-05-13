<html>
    <head>
        <title>{{setting('site.title')}}</title>
    </head>
    <body style="background: white; color: black">

    <div >
        <p> Hello {{$messageText['business_name']}},</p>
        <p>Welcome to Recordent community! Your {{$messageText['pricing_plan']}} membership account is created. </p>
        <p>Now, here's what you do next -<u><a href="{{url('/admin/login')}}" target="_blank">Login</a></u> and start submitting dues.</p>
        <p>We have created a short video to help you understand how to upload the customer dues.</p>
        <p><a href="https://www.youtube.com/watch?v=cc6_v_eYLdw" target="_blank">Watch Video</a></p>
        <p>For further assistance, speak to us at 888 6634 100 or email at contact@recordent.com.</p>
        <p>Best Regards,</p>

        <p>Team Recordent</p>
        <p>{{route('home')}}</p>
        <p>Spread a word about us with your connections to make the Recordent community stronger for all of us. </p>
        <p>Follow us on - </p>
        <p>Facebook - https://www.facebook.com/recordent/</p>
        <p>Twitter - https://twitter.com/recordentindia</p>
        <p>LinkedIn - https://www.linkedin.com/company/recordent </p>
    </div>
</body>
</html>
