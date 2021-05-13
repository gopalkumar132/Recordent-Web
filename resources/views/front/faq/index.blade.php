@extends('layouts_front_new.master')
@section('meta-title', config('seo_meta_tags.faq_page.title'))
@section('meta-description', config('seo_meta_tags.faq_page.description'))
@section('canonical-url')
    <link rel="canonical" href="{{config('app.url')}}faq" />
@endsection
@section('content')
<style>
.the-title-faq-h1 h1{
    color: #000;
    font-family: var(--font-rubik);
    font-weight: 700;
    font-size: 44px;
}
@media (max-width: 479px){
.the-title-faq-h1 h1 {
    font-size: 26px;
}
}
h3,strong{
  cursor: pointer;
}

.faqs-content-div { display: none;}

</style>
<section class="faq-section">
            <div class="container">
                <div class="the-title text-center the-title-faq-h1">
                    <h1>FAQs</h1>
                </div>
                <ul class="ask-questions">
                       <li>
                       <p></P>
                        <h3 id="1">About Recordent</h3>
                        <li>
                        <ul class="faqs-content-div" id="faqs-content-div-1">
                        <li><strong class="faq-first" id="first-1">1. Why do I register for Recordent?</strong>
                        <ul class="faq-hidedefault" id="faq-first-1"><li>1. Recordent is an online technology portal for businesses to submit and
                        manage customer dues and collections.</li>
                        <li>2. Recordent sends notifications (SMS, IVR & Email) to the customers to
                        update the status of their dues and educates them on how other
                        businesses can check the payment history.</li>
                        <li>3. If customers do not clear dues, then those due records are marked as
                        outstanding. Such records can be accessed by other businesses (with the
                        consent from the customer) on Recordent before offering a service, credit
                        or a loan. Hence, the customers are obligated to clear outstanding dues
                        at the earliest.</li>
                        <li>4. Watch this <a href="https://youtu.be/cc6_v_eYLdw" target="_blank">YouTube video</a> for a clearer understanding of your product.
                        <link></li></ul>

                    </li>

                    <li>
                        <strong class="faq-first" id="first-2">2. How can Recordent collect dues from our clients when they are not using smartphones?</strong>
                        <ul class="faq-hidedefault" id="faq-first-2"><li>1. Recordent sends out communications through mailing, IVR calling and SMS.</li>
                        <li>2. All these are independent of the kind of phone and even a feature phone can
                          get SMS and IVR calls which are more than enough to notify the clients.</li></ul>
                    </li>

                    <li>
                        <strong class="faq-first" id="first-3">3. We have a team to deal with such cases, why do I need Recordent?</strong>
                        <ul class="faq-hidedefault" id="faq-first-3"><li>1. Recordent is here to support your resources and not replace them.</li>
                        <li>2. We at Recordent want you to focus on growing your business instead of
                          chasing your customers for pending payments.</li>
                        <li>3. Besides, even with existing customers, we would want you to focus on growth opportunity
                           discussions rather than hurting communications with payment reminders.</li>
                        <li>4. So, you can offload this responsibility - partially or fully is
                          completely up to you - and we notify your clients to make the payments.</li></ul>
                    </li>

                    <li>
                        <strong class="faq-first" id="first-4">4. Many times the dues are not being collected even when we do a
                          physical visit. What extra can your technology based company do to follow-up ?</strong>
                        <ul class="faq-hidedefault" id="faq-first-4"><li>1. If a customer of yours does not want to continue business with you,
                          they can 'ignore' your calls and physical visits.</li>
                        <li>2. The normal course of action from your end would be to either take
                          a legal action or stop further supplies.</li>
                        <li>3. Our method allows for a record to be created of the non-payment
                          and made available for view at a future date.</li>
                        <li>4. This induces the client to clear your dues to maintain a positive
                          track record, and avoid negative repercussions with other
                          businesses who may view that record, in future.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-first" id="first-5">5. How is Recordent different from invoicing software(s) that also send
                          out reminders?</strong>
                        <ul class="faq-hidedefault" id="faq-first-5"><li>1. Most invoicing software(s) send out reminders that are a replication
                          of your own follow-ups with customers - i.e., they just remind the
                          customer on your behalf.</li>
                        <li>2. But Recordent is like a Credit Bureau for non-Banking Businesses
                          and communicates creates an urgency to pay by the customers
                          through inducing them to maintain a positive payment history by
                          paying you on time.</li></ul>

                    </li>
                    <li>
                        <strong class="faq-first" id="first-6">6. What else would Recordent do if the client refuses to pay despite our
                          technology ?</strong>
                        <ul class="faq-hidedefault" id="faq-first-6"><li>1. Recordent has partnered with legal entities to provide solutions in
                          legal aspects (such as legal notices, filing of cases etc.) for our members.</li>
                        <li>2. We also have partnerships for arbitration and mediation, as an
                          alternate resource if you want to take that way.</li></ul>
                    </li>

                    <li>
                        <strong class="faq-first" id="first-7">7. How much time does it take to recover my dues?</strong>
                        <ul class="faq-hidedefault" id="faq-first-7"><li>1. Due collections dependents on the situation of the clients.</li>
                        <li>2.Our efforts are focused on creating an urgency and educating the
                          clients of our members so that they pay the dues and continue to
                          be in business.</li>
                       <li>3. Any client who understands the importance of credit report and its
                         benefits would prioritize these reported payments the way they
                         prioritize any other bank payments.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-first" id="first-8">8. Does Recordent accept international invoices and what services are
                          provided for these invoices ? If yes, will there be any extra charges
                          in this case ?</strong>
                        <ul class="faq-hidedefault" id="faq-first-8"><li>1. No, we do not accept international invoices currently.</li>
                        <li>2. Our members will be notified once the international segment is
                          active for reporting with the corresponding tariff as well.</li></ul>
                    </li>
                  </ul>
                </li>

                    <li></li>
                      <h3 id="2">New Registration/ Profile related</h3>
                      <p></p>
                      <li>
                      <ul class="faqs-content-div" id="faqs-content-div-2">
                        <li><strong class="faq-second" id="second-1">1. How can I use the services of Recordent?</strong>
                        <ul class="faq-hidedefault" id="faq-second-1"><li>1. You can sign-up to Recordent and submit customer dues and we will help you collect.</li>
                        <li>2. We have a range of other services, to help you reduce your
                          business risk.</li>
                        <li>3. <a href="/pricing-plan">Click here</a> to know our pricing</li></ul>
                    </li>
                    <li>
                        <strong class="faq-second" id="second-2">2. Can I change my GSTIN number and business details in future after
                          signing up?</strong>
                        <ul class="faq-hidedefault" id="faq-second-2"><li>1. Yes, you can edit all details like Business name, GSTIN number,
                          mobile number, email id at any given point of time.</li>
                        <li>2. <a href="/admin/login">Click here</a> to edit .</li></ul>
                    </li>
                    <li>
                        <strong class="faq-second" id="second-3">3. Can individuals become a member of Recordent?</strong>
                        <ul class="faq-hidedefault" id="faq-second-3"><li>1. No. As of now, individuals cannot join Recordent as members.</li>
                        <li>2. A compulsory GSTIN/Business PAN number is required for signing up.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-second" id="second-4">4. I have never signed up on Recordent and the website still says this
                          mobile number is already registered. What do I do?</strong>
                        <ul class="faq-hidedefault" id="faq-second-4"><li>1. Make sure you have never signed up. Check your past emails and SMSs.</li>
                        <li>2. If you still face this issue, <a href="/aboutus#contact-us">Click here</a> to contact us now.</li></ul>
                    </li></ul></li>
                    <li>
                    </li>
                      <h3 id="3">Reports</h3>
                      <p></p>
                      <li>
                      <ul class="faqs-content-div" id="faqs-content-div-3">
                        <li>
                        <strong class="faq-third" id="third-1">1. Why do I buy Reports from Recordent?</strong>
                        <ul class="faq-hidedefault" id="faq-third-1"><li>1. Checking your customer’s payment history helps you take informed
                          decisions whenever you offer credit to your existing or potential
                          customers thereby helps you better manage your business risk.</li></li></ul>

                    <li>
                        <strong class="faq-third" id="third-2">2. What information can I get from the credit reports?</strong>
                        <ul class="faq-hidedefault" id="faq-third-2"><li>1. Recordent provides you payment history report for banking and
                          non-banking transactions of your customers.</li>
                        <li>2. The banking data is powered by Equifax.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-third" id="third-3">3. I have paid for my reports and still haven’t received, what do I do?</strong>
                        <ul class="faq-hidedefault" id="faq-third-3"><li>1. You can check the report in your Recordent login under-Report History.</li>
                        <li>2. If you still don’t find the report, please click on the Help & Support option
                          in your login to contact us.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-third" id="third-4">4. Can I check my Recordent report and payment history without being a member?</strong>
                        <ul class="faq-hidedefault" id="faq-third-4"><li>1. Yes. As a customer you can view your Recordent report and
                          payment history for free.</li>
                        <li>2. <a href="/check-my-report">Click here</a> to check your report.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-third" id="third-5">5. What other types of reports that I can check as a Recordent member?</strong>
                        <ul class="faq-hidedefault" id="faq-third-5"><li>1. We provide you credit reports for all. whether your customers are
                          Individuals or businesses within India or International. Currently
                          International credit reports are available for US businesses only.</li>
                        <li>2. We provide you reports on various industry trends along with insights on
                          how your business has performed periodically based on your plan</li></ul>
                    </li>
                    <li>
                        <strong class="faq-third" id="third-6">6. I want to view a customer’s report, but the customer is not giving
                          consent. What do I do?</strong>
                        <ul class="faq-hidedefault" id="faq-third-6"><li>1. We recommend you to raise the request again and ask your
                          customer to fill up the consent form.</li>
                        <li>2. You can educate your customer about the link received in the SMS
                          through which they will be able to fill out the consent.</li>
                        <li>3. It is against our privacy policy to provide reports without receiving
                            consent from the customers.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-third" id="third-7">7. How much do I pay for the credit reports?</strong>
                        <ul class="faq-hidedefault" id="faq-third-7"><li>1. The pricing for the reports varies according to your membership
                          plans. <a href="/pricing-plan">Click here</a> to view membership plans.</li>
                        <li>2. Still have queries?<a href="/aboutus#contact-us" >Click here</a> to contact us now.</li></ul>

                    </li>
                    <li>
                        <strong class="faq-third" id="third-8">8. Can I access Credit reports without becoming a member?</strong>
                        <ul class="faq-hidedefault" id="faq-third-8"><li>1. No. You need to become a Recordent member in order to view your
                          customers credit report.</li>
                        <li>2. Still have queries?<a href="/aboutus#contact-us"> Click here</a> to contact us now.</li></ul>

                    </li>
                    <li>
                        <strong class="faq-third" id="third-9">9. I am a Recordent Member, how can I check the payment history of
                          the customer?</strong>
                        <ul class="faq-hidedefault" id="faq-third-9"><li>1. After a successful login, use the search option under the check
                          credit and payment history section on the dashboard and enter the
                          required information.</li>
                        <li>2. If the data is available, with a successful payment, you will be able
                          to view the details.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-third" id="third-10">10. Does Recordent charge for checking the payment history?</strong>
                        <ul class="faq-hidedefault" id="faq-third-10"><li>1. Yes. The fee charged for every individual credit and payment
                          history report is ₹100 and every business credit and payment
                          history report is ₹1200.</li>
                        <li>2. Paid members enjoy discounts on these charges.</li>
                        <li>3. <a href="/pricing-plan">Click here</a> for details.</li>
                        <li>4. <strong>*We do not charge you if there is no report found</strong></li></ul>
                    </li></ul></li>
                    <li>
                    </li>
                      <h3 id="4">Membership and pricing</h3>
                      <p></p>
                      <li>
                      <ul class="faqs-content-div" id="faqs-content-div-4">
                        <li>
                        <strong class="faq-fourth" id="fourth-1">1. Is there any fee for joining Recordent?</strong>
                        <ul class="faq-hidedefault" id="faq-fourth-1"><li>1. Recordent has different pricing plans according to your business
                          requirements.</li>
                        <li>2. <a href="/pricing-plan">Click here</a> to check out our pricing</li>
                        <li>3. You can start your <a href="/register?pricing_plan_id=1">‘FREE TRIAL’</a> now</li></ul>
                    </li>
                    <li>
                        <strong class="faq-fourth" id="fourth-2">2. What are the Recordent charges for collection of dues?</strong>
                        <ul class="faq-hidedefault" id="faq-fourth-2"><li>1. Recordent charges a minimal amount starting from 0.1%* as
                          facilitation fees only upon successful collection of dues.</li>
                        <li>2. <a href="/pricing-plan">Click here</a> for details.</li></ul>

                    </li>
                    <li>
                        <strong class="faq-fourth" id="fourth-3">3. What benefits do I get in a corporate plan?</strong>
                        <ul class="faq-hidedefault" id="faq-fourth-3"><li>1. The corporate plan is a - tailor made for our members to avail
                          special prices on all our products and services. Our executives
                          contact you personally and discuss prices according to your
                          requirements.</li>
                        <li>2. <a href="/aboutus#contact-us">Click here</a> if you are interested in our corporate plans. Our
                          executives will get in touch with you.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-fourth" id="fourth-4">4. Is there an expiry date for membership?</strong>
                        <ul class="faq-hidedefault" id="faq-fourth-4"><li>1. No, the membership will never expire, however the membership
                          plan selected by you has a 1 year validity.</li>
                        <li>2. It is always advisable to renew your membership to continue
                          availing -various benefits and special prices offered by Recordent.</li>
                        <li>3. <a href="/pricing-plan">Click here</a> for details.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-fourth" id="fourth-5">5. When can I upgrade my membership? How much will I be charged in
                          case I want to upgrade halfway through my current membership?</strong>
                        <ul class="faq-hidedefault" id="faq-fourth-5"><li>1. You can upgrade at any time you want.</li>
                        <li>2. You will be charged only for the difference based on your current membership plan</li>
                        <li>3. For example, if your Executive membership has 6 months
                          remaining and you choose to upgrade, we will calculate on pro-rata
                          and discount it from the new plan.</li>
                          <li>4. <a href="/pricing-plan">Click here</a> to Upgrade now.</li></ul>
                    </li>
                  </ul></li>
                    <li></li>
                      <h3 id="5">Managing Dues and Customers</h3>
                      <p></p>
                      <li>
                      <ul class="faqs-content-div" id="faqs-content-div-5">
                        <li>
                        <strong class="faq-fifth" id="fifth-1">1. How to submit customer dues?</strong>
                        <ul class="faq-hidedefault" id="faq-fifth-1"><li>1. After successful login, you can click on ‘Submit Dues’ to upload
                          dues individually or through a bulk upload process.</li>
                        <li>2. You can choose between Individual and Business dues according
                          to your customers</li>
                        <li>3. If you want to submit a single due, you can fill in the details of your
                          customer through our submit dues form.</li>
                        <li>4. In case you want to submit multiple dues, you can download a
                          Masterfile to fill in your dues and upload multiple dues by uploading
                          the sheet with your data.</li>
                        <li>5. Still not able to upload successfully? <a href="/aboutus#contact-us">Click here</a> to contact us.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-fifth" id="fifth-2">2. What type of customer dues can be submitted on Recordent?</strong>
                        <ul class="faq-hidedefault" id="faq-fifth-2"><li>1. You can submit all the customers (Individual and Businesses)</li>
                        <li>2. We accept all types of dues, future dues as well as outstanding</li>
                        <li>3. However, it is always advisable to submit dues as early as when
                          the customer has been invoiced. There are various benefits for
                          reporting customer dues early.</li>
                          <li>4. <a href="/pricing-plan">Click here</a> for details.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-fifth" id="fifth-3">3. Why do I submit the future dues? How is this helpful to me?</strong>
                        <ul class="faq-hidedefault" id="faq-fifth-3"><li>1. Your customer will be aware that their payments are being tracked
                          on Recordent and they can build a positive financial profile.</li>
                        <li>2. This will motivate your customers to make payments on time. Also,
                          if the customer does not pay by the due date, Recordent will auto
                          send payment reminders.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-fifth" id="fifth-4">4. Who in the Member's organization can submit customer dues?</strong>
                        <ul class="faq-hidedefault" id="faq-fifth-4"><li>1. The Member's organization will be provided with an appropriate level of access to submit, view and edit the records.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-fifth" id="fifth-5">5. What is the next step after submitting the customer dues?</strong>
                        <ul class="faq-hidedefault" id="faq-fifth-5"><li>1. Recordent will auto send SMS, IVR & Email notifications to the customer to help collect the payment.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-fifth" id="fifth-6">6. I submitted a wrong due record by mistake. Can I update or delete it?</strong>
                        <ul class="faq-hidedefault" id="faq-fifth-6"><li>1. Yes, you can - delete or edit the due record till 7 days from the date of recording.</li></ul>
                    </li>
                    <li>
                        <strong class="faq-fifth" id="fifth-7">7. I have uploaded a - file and the system is not accepting some of my records. Why is this happening? What do I do?</strong>
                        <ul class="faq-hidedefault" id="faq-fifth-7"><li>1. Please make sure you have filled in all the mandatory information.</li>
                        <li>2. Make sure all your information is correct and is placed under the proper fields and is in our bulk upload file format.</li>
                        <li>3. If you still have any problems uploading, <a href="/aboutus#contact-us">Click here</a> to contact us.</li></ul>
                    </li>
                  </ul></li>
                    <li></li>
                      <h3 id="6">Notifications</h3>
                      <p></p>
                      <li>
                      <ul class="faqs-content-div" id="faqs-content-div-6">
                        <li>
                        <strong class="faq-sixth" id="sixth-1">1. How does the notifications help in the collections?</strong>
                        <ul class="faq-hidedefault" id="faq-sixth-1"><li>1. Recordent sends notifications (SMS, IVR & Email) to the customers
                              to update the status of their dues and educates them on how other
                              businesses can check the payment history.</li></ul></li>
                          <li>
                        <strong class="faq-sixth" id="sixth-2">2. How is the customer notified about the dues? What content is sent in the notifications?</strong>
                        <ul class="faq-hidedefault" id="faq-sixth-2"><li>1. The customer is sent a notification as a reminder to pay the dues of
                            the member with the due date and due amount.</li>
                        <li>2. Customers are also educated that they can check their credit report
                            on our website.</li> </ul></li>
                            <li><strong class="faq-sixth" id="sixth-3">3. I am a registered member on Recordent. Where can I see the sample
    of notifications that will be sent to my customers?</strong>
                        <ul class="faq-hidedefault" id="faq-sixth-3"><li>1. After successfully logging in, go to the notifications section to see
                            sample SMSs in all languages.</li>
                        <li>2. Still have doubts? <a href="/aboutus#contact-us">Click here</a> to contact us now.</li></ul></li>
                        <li> <strong class="faq-sixth" id="sixth-4">4. How do we know if you have sent reminders to our customers ?</strong>
                        <ul class="faq-hidedefault" id="faq-sixth-4"><li>1. Our paid members get access to a detailed report that mentions the
communications from our side as well as any response the
customer may have recorded.</li>
                        <li>2. These are shared with the members on a timely basis.</li>
                        <li>3. Our paid memberships are extremely high Return on Investment,
                            starting at INR 599+gst/year and depending on the plan, averaging
                            about INR 2.5-3/year/customer</li>
                    </li></ul></ul></li>
                    <li></li>
                    <h3 id="7">Payment and Invoices</h3>
                    <p></p>
                    <li>
                    <ul class="faqs-content-div" id="faqs-content-div-7">
                      <li>
                      <strong class="faq-seventh" id="seventh-1">1. Can I check the payment history of a prospective customer without becoming a member?</strong>
                      <ul class="faq-hidedefault" id="faq-seventh-1"><li>1. No. You need to become a member by signing-up on Recordent.</li>
                      <li>2. <a href="/register">Click here</a> to sign-up now.</li></ul> </li> <li>
                      <strong class="faq-seventh" id="seventh-2">2. I cannot find an invoice from the past. I want to access it, where do I find it?</strong>
                      <ul class="faq-hidedefault" id="faq-seventh-2"><li>1. After logging into your account, in the profile section there is a section for Invoices.</li>
                      <li>2. In the invoices section, you will be displayed a list of all your invoices and receipts.</li>
                      <li>3. You can also filter your invoices based on dates.</li>
                      <li>4. Still not able to find your invoice? Click <a href="/aboutus#contact-us">Click here</a> to contact us.</li></li></ul><li>
                      <strong class="faq-seventh" id="seventh-3">3. After registration if any client is ready to pay, how will Recordent come to know? The client can secretly pay. Why should I update that on the platform?</strong>
                      <ul class="faq-hidedefault" id="faq-seventh-3"><li>1. Once the member receives payment and avoids updating on the platform, then Recordent still continues to notify the client about the unpaid due.</li>
                      <li>2. This gets cleared as being paid only when the member or the client comes back to us that it has been paid.</li>
                      <li>3. Without this feedback, the due would continue to show as unpaid, and may impact the customer's profile in the long run.</li>
                      <li>4. On clearing the due, it is advisable for the members or the customers to update on our platform as well.</li></ul></li>
                    </ul></li>

                    <li></li>
                      <h3 id="8">Disputes</h3>
                      <p></p><li>
                      <ul class="faqs-content-div" id="faqs-content-div-8">
                        <li>
                        <strong class="faq-eigth" id="eigth-1">1. I am a customer and I have been assigned invalid dues. What do I do?</strong>
                        <ul class="faq-hidedefault" id="faq-eigth-1"><li>1. You can raise a dispute in this case</li>
                        <li>2. You can select the option for a dispute in your customer report.</li>
                        <li>3. Choose the reason from dispute dropdown.</li>
                        <li>4. We will facilitate this dispute with our members and help you resolve it at the earliest</li>
                        <li>5. Still facing the issue? <a href="/aboutus#contact-us">Click here</a> to contact us</li></ul>
                      </li><li>
                        <strong class="faq-eigth" id="eigth-2">2. What happens if I raise a dispute?</strong>
                        <ul class="faq-hidedefault" id="faq-eigth-2"><li>1. The member will be notified of the dispute and will be requested to resolve the dispute at the earliest.</li></ul></li></ul>
                      </li>
                      <li></li>
                        <h3 id="9">E- Arbitration and Legal Assistance</h3>
                        <p></p><li>
                        <ul class="faqs-content-div" id="faqs-content-div-9"><li>
                        <strong class="faq-ninth" id="ninth-1">1. Are you providing legal support? Helping like appointing judge and hearings fast to end the case asap?</strong>
                        <ul class="faq-hidedefault" id="faq-ninth-1"><li>1. Recordent has partnered with legal entities to provide legal solutions (such as legal notices, filing of cases etc.) for our members.</li>
                        <li>2. We also have partnerships for arbitration and mediation, as an alternate recourse.</li>
                        <li>3. Arbitration or Mediation is often proven to be a faster process than going through the full court case procedure.</li></ul>
                      </li><li>
                        <strong class="faq-ninth" id="ninth-2">2. What is E-Arbitration?</strong>
                        <ul class="faq-hidedefault" id="faq-ninth-2"><li>1. E-Arbitration is a process of filing arbitration proceedings electronically from the comfort of your office or home without having to run around courts and without cumbersome paperwork, this is a completely legal method approved by the Justice Department of India.</li></ul></li>
                        <li><strong class="faq-ninth" id="ninth-3">3. How does E-Arbitration help in my collection?</strong>
                        <ul class="faq-hidedefault" id="faq-ninth-3"><li>1. Once an e-arbitration notice is served on your customer, it takes a maximum of 45 days to 60 days to get an arbitrage award, therefore speeding up the process of collection on bad debts and write offs.</li>
                        <li>2. (The Award is subject to claims/counterclaims and arbitration proceedings, award is at the judgement of the arbitrator and in no way guaranteed by Recordent)</li></ul></li>
                        <li><strong class="faq-ninth" id="ninth-4">4. I want to send a legal notice to my customer, how much does it cost?</strong>
                        <ul class="faq-hidedefault" id="faq-ninth-4"><li>1. Legal notice through Recordent platform would cost INR 3500/- plus taxes.</li>
                        <li>2. Want to initiate a legal notice ? <a href="#">Click here.</a></li></ul></li>
                        <li><strong class="faq-ninth" id="ninth-5">5. I have initiated a legal notice to a customer. Can I know the status of that?</strong>
                        <ul class="faq-hidedefault" id="faq-ninth-5"><li>1. Yes, we connect you to our legal partner once you have made the
payment for the same.</li>
<li>2. All replies to addresses on the notice would be your registered
address, therefore any response if triggered would reach your first
post which you can consult our legal partner for the further process.</li></ul></li></ul></li>
<li></li>
<h3 id="10">Terms and Conditions & Privacy Policy</h3>
<p></p><li>
<ul class="faqs-content-div" id="faqs-content-div-10">
  <li>
<strong class="faq-tenth" id="tenth-1">1. Is the data submitted on Recordent secure?</strong>
<ul class="faq-hidedefault" id="faq-tenth-1"><li>1. The PII (Personal Identification Information) present is visible to:</li>
<li>2. Member (submitter) of the data</li>
<li>3. The Customer (Business/individual) recorded on the platform, and
any third party that the customer has provided consent to access
the record / report</li></ul></li><li>
<strong class="faq-tenth" id="tenth-2">2. How can a customer permit a third party to view their data?</strong>
<ul class="faq-hidedefault" id="faq-tenth-2"><li>1. The prospective customer data can only be viewed after the
customer has provided consent through an OTP process.</li></ul></li><li>
<strong class="faq-tenth" id="tenth-3">3. As a member, if I have the customer consent, will I be able to see the
encrypted data?</strong>
<ul class="faq-hidedefault" id="faq-tenth-3"><li>1. No. The data is never decrypted. It is only displayed to you on a
need to know basis and only after customer consent.</li></ul></li><li>
<strong class="faq-tenth" id="tenth-4">4. As a customer can I revoke previously granted permission to a third
party to view my data?</strong>
<ul class="faq-tenth" id="faq-tenth-4"><li>1. No. You cannot revoke consent provided in the past, however every
consent has a validity of X days</li></ul></li></ul></li></li>
                </ul>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script type="text/javascript">
          $(document).ready(function(){
            $('.faqs-content-div').hide();
            $('.faq-hidedefault').hide();
            $('h3').on('click', function(){
              $(".faqs-content-div").not("#faqs-content-div-"+this.id).hide();
              $("#faqs-content-div-"+this.id).toggle();
              /*$('html, body').animate({
                scrollTop: $("#faqs-content-div-"+this.id).offset().top
              }, 2000);*/
            });
            $('strong').on('click', function() {
              $('.faq-hidedefault').not("#faq-"+this.id).hide();
              $("#faq-"+this.id).toggle();
              /*$('html, body').animate({
                scrollTop: $("#faq-"+this.id).offset().top
              }, 2000);*/
            });
          });
        </script>
@endsection
