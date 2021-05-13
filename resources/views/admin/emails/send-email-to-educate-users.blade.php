<html>
    <head>
        <title>{{setting('site.title')}}</title>
    </head>
    <body style="background: white; color: black">

    <div >
        <p> Hello {{$name}},</p>
        <p>Now that you have submitted your customer dues, make the most out of the Recordent platform-</p>
		<p>1. Manage your submitted dues from 'My Records' section. There are 5 icons you will see against any due that you have submitted - </p>
        <p><b>(i) Detail View:</b> This shows the profile of the customer you have uploaded the due against. You can change/update the contact details, add alternate contact number etc., for the various notifications from Recordent's end to reach the rightful customer. It is recommended to keep this updated as per the latest contact information of the customer.</p>
        <p><b>(ii) Add Record:</b> If you have more invoices against the same customer, you can add by clicking here. The new invoice amount, due date, proof of due etc., can be submitted and the remaining customer details (their contact info, GST details etc.) would be as per the existing record already filled.</p>
        <p><b>(iii) Update Payment:</b> On receiving the payment (either full payment or partial payment) from the customer, you can update the corresponding information against this due, with the relevant information such as payment date, amount etc. In case this is against a payment that was overdue, a payment gateway to pay Recordent fees would open up when you update this. To avoid sending reminders to customers who have already paid to you, it is recommended that this section be updated as soon as the customer makes their payment to you</p>
        <p><b>(iv) Edit Record:</b> If the due date has not become 'overdue' as yet, and the customer has requested for an additional grace period, you can update the new due date as per your discussions, here. You can also choose to upload any updated proof of the invoice, if required.</p>
        <p><b>(v) Payment History:</b> For all the payments (either in full or in part) made by the customer against a given invoice, you can check the history from this section.</p>
        <p>2. There is a 'Dispute' section which provides options to manage customer notifications or customer disputes status, if any. </p>
        <p>3. You can always update your organization information by accessing your profile page<a href="{{route('login')}}"></a> here</p>
        <p>We are always eager to know from our members about how we can improve. For any feedback or if you want to know about your existing plan, or want to upgrade to another plan, you can contact us via {{$support_mail}} or {{$contact}} </p>
        <p>Best Regards,</p>
        <p>Team Recordent</p>
        <p>Recordent Private Limited</p>
        <p>{{route('home')}}</p>

        <p>Follow us on - </p>
        <p>Facebook - https://www.facebook.com/recordent/</p>
        <p>Twitter - https://twitter.com/recordentindia</p>
        <p>LinkedIn - https://www.linkedin.com/company/recordent </p>
    </div>
</body>
</html>
