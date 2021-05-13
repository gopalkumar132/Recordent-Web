<html>
    <head>
        <title>{{setting('site.title')}}</title>
    </head>
    <body style="background: white; color: black">

    <div >
        <p> Hello {{ $membership_payment->user->name }},</p>
        @if(isset($membership_payment->user->business_short))
        <p>{{ $membership_payment->user->business_short }},</p>
        @else
        <p>{{ $membership_payment->user->business_name }},</p>
        @endif
        <p>Welcome to the Recordent family. </p>
        <p>Congratulations on your signing up for (membership plan - {{ $membership_payment->user->user_pricing_plan->pricing_plan->name }}) </p>
        <p>Your account information - </p>
        <p>Email: {{ $membership_payment->user->email }}</p>
        <p>INR {{ General::ind_money_format(($membership_payment->total_collection_value)) }} has been billed on your membership plan. Refer to the attached invoice for a detailed summary. </p>
        <p>Now, next what? <u><a href="{{url('/admin/login')}}" target="_blank">Login</a></u> on the Recordent platform and start submitting individual and business dues.  </p>
        <p>Best Regards,</p>
        <p>Team Recordent</p>
        <p>{{route('home')}}</p>
        <p>contact@recordent.com</p>
        <p>Follow us on - </p>
        <p>Facebook - https://www.facebook.com/recordent/</p>
        <p>Twitter - https://twitter.com/recordentindia</p>
        <p>LinkedIn - https://www.linkedin.com/company/recordent </p>
    </div>
</body>
</html>
