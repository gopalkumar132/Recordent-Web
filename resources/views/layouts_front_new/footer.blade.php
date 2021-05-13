<style>
footer .footer-linking-plain p {
    font-size: 18px;
    color: #fff;
    font-weight: 700;
    margin-bottom: 14px;
    text-transform: capitalize;
}
</style>
@php General::utmContainerDetect(); @endphp
<footer>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-5 col-xl-5">
                {{--<div class="join-our-mailer">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Email ID or Mobile Number" aria-label="Email ID or Mobile Number" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-info" type="button" id="button-addon2">Submit</button>
                        </div>
                    </div>
                    <p>Follow us on</p>
                </div>
                --}}
                @include('layouts_front_new/join-newsletter-email')
            </div>
            <div class="col-12 col-md-12 col-lg-7 col-xl-7">
                <div class="d-flex justify-content-between csd-flex-wrap">
                    <div class="footer-linking footer-linking-plain">
					<!--About Us section--->
                        <p>About us</p>
                        <ul>
                            <li><a href="{{route('aboutus')}}">About Us</a></li>
                            <li><a href="{{config('app.url')}}pricing-plan">Pricing</a></li>
                            <li><a href="{{route('aboutus')}}#our-team">Our Team</a></li>
                            <li><a href="{{route('aboutus')}}#contact-us">Contact Us</a></li>
                            <li><a href="{{route('careers')}}">Careers</a></li>
                        </ul>
                    </div>
                    <div class="footer-linking footer-linking-plain">
                        <p>Solutions</p>
                        <ul>
                            <li><a href="{{route('solutions')}}#report-payments">Submit Payments</a></li>
                            <li><a href="{{route('solutions')}}#messaging">Messaging</a></li>
                            <li><a href="{{route('solutions')}}#payment-options">Payment Options</a></li>
                            <li><a href="{{route('solutions')}}#payment-plans">Payment Plans</a></li>
                            <li><a href="{{route('solutions')}}#finance-options">Finance Options</a></li>
                            <li><a href="{{route('creditreport')}}">Credit Reports</a></li>
                        </ul>
                    </div>
                    <div class="footer-linking footer-linking-plain">
                        <p>Quick Links</p>
                        <ul>
                            <li><a href="{{config('app.url')}}register">Sign Up</a></li>
                            <li><a href="{{config('app.url')}}admin/login">Member Login</a></li>
                            <li><a href="{{route('faq')}}">FAQs</a></li>
								{{--<li><a href="javascript:void(0)">White Papers</a></li>--}}
                            <li><a href="{{route('your.reported.dues')}}">Check My Report</a></li>
                        </ul>
                    </div>
                    <div class="footer-linking footer-linking-plain">
                        <p>Legal</p>
                        <ul>
                            {{--<li><a href="javascript:void(0)" data-toggle="modal" data-target="#PrivacyPolicyModal">Privacy Policy</a></li>--}}
                            <li><a target="_blank" href="{{config('app.url')}}privacy-policy">Privacy Policy</a></li>
                            <li><a target="_blank" href="{{config('app.url')}}terms-and-conditions">Terms &amp; Conditions</a></li>
                            {{--<li><a href="javascript:void(0)" data-toggle="modal" data-target="#TermConditionModal">Terms &amp; Conditions</a></li>--}}
                            {{--<li><a href="javascript:void(0)">Dispute Resolution</a></li>--}}
                            <li><a href="{{route('security')}}">Data Security &amp; Privacy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

{{--<div class="modal fade commap-team-popup" id="PrivacyPolicyModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">-->
<!--    <div class="modal-dialog modal-lg">-->
<!--      <div class="modal-content">-->
<!--        <div class="modal-header">-->
<!--          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
<!--          <h4 class="modal-title text-center"  id="myLargeModalLabel">PRIVACY POLICY</h4>-->
<!--      </div>-->
<!--      <div class="modal-body">-->
<!--          <div class="privacy-policy-points">-->
<!--              <ul>-->
<!--                  <li>-->
<!--                      <p><storng>1. Use of Recordent:</storng> User’swill be able to use Recordent for the following:</p>-->
<!--                      <ul>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(a)</span><span class="about-contain">Upload,  view,  access  information,  data,  text etc.(hereafter referred to as the “Record(s)”)  </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">Search for various Record(s)available. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">Subscribe to Record(s)of other users. </span></p></li>-->
<!--                      </ul>-->
<!--                  </li>-->
<!--                  <li>-->
<!--                      <p><storng>2. Information collected by Recordent: </storng> The following information will be collected from a Userwhile using Recordent:</p>-->
<!--                      <ul>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">Record(s) submitted by a User; </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">Personal  information  like User’sname,  email  address,  telephone  number  with User’saccount, ID card details, customer data, their name address, fee payment details, ID card details etc. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">Log information: Search queries, IP address, crashes, date and time </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(d) </span><span class="about-contain">Local storage: Browser web storage, application data caches </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(e) </span><span class="about-contain">Cookies and similar technologies </span></p></li>-->
<!--                      </ul>-->
<!--                  </li>-->
<!--                  <li><p><storng>3. Personal  Information:</storng> Information Recordentcollect  when a  Usersigned  in  to Recordent may be associated with User’sAccount. When information is associated with User’sAccount, Recrodent try totreat it as personal information.</p></li>-->
<!--                  <li>-->
<!--                      <p><storng>4. Use information  Recordent  collect/  share:</storng> The  information  will  be  used  for  the following:</p>-->
<!--                      <p>Publish Record(s)through or  by the Recordant.  Notwithstanding  the  same Recordantmay share your data in case of the following:</p>-->
<!--                      <ul>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">Statistics regarding your account </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">Change of account password(c)Suspend/terminate account </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">Suspend/terminate account </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(d) </span><span class="about-contain">To satisfy any legal enforceable process </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(e) </span><span class="about-contain">For external processing </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">(f) </span><span class="about-contain">For legal reasons </span></p></li>-->
<!--                      </ul>-->
<!--                  </li>-->
<!--                  <li><p><storng>5. Information  Security:</storng> Recordentwork  hard  to  protect  Recordent  and itsUsers  from unauthorized   access   to   or unauthorized   alteration,   disclosure   or   destruction   of information.</p></li>-->
<!--                  <li><p><storng>6. Application:</storng> This Privacy Policy Applies to services offered by Recordent. Recordent’s Terms and Conditions and End User License Agreement (EULA), is incorporated herein by way of reference.</p></li>-->
<!--                  <li><p><storng>7. Changes in Privacy Policy:</storng> Recordent’sPrivacy Policy might change from time to time, and Recordent will provide notice of it. Recordantwill provide prominent notice if the changes might significantly affect the terms of the existing Privacy Policy.</p></li>-->
<!--                  <li><p><storng>8. Contact: </storng> For   any   information   regarding   the   Privacy   Policy,   please   contact <a href="mailto:hello@Recordent.com"> hello@Recordent.com. </a></p></li>-->
<!--              </ul>-->
<!--          </div>-->
<!--      </div>-->

<!--  </div>-->
  <!-- /.modal-content -->
<!--</div>-->
<!-- /.modal-dialog -->
<!--</div>--}}


{{--<div class="modal fade commap-team-popup" id="TermConditionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">-->
<!--    <div class="modal-dialog modal-lg">-->
<!--      <div class="modal-content">-->
<!--        <div class="modal-header">-->
<!--          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
<!--          <h4 class="modal-title text-center"  id="myLargeModalLabel">RECORDENT GENERAL TERMS AND CONDITIONS</h4>-->
<!--      </div>-->
<!--      <div class="modal-body">-->
<!--          <div class="privacy-policy-points">-->
<!--              <ul>-->
<!--                  <li>-->
<!--                      <p><storng class="d-flex"><span class="index-letter">1. </span> <span class="about-contain">Introduction:</span> </storng></p>-->
<!--                      <ul>-->
<!--                          <li><p class="d-flex"> <span class="index-letter"> (a) </span> <span class="about-contain">The website www.recordent.com,its mobile phone application(s), API integration or  host  to  host  integration,  technical  service  arrangement/integrations    and/or  its internet  based  applications  and/or  software  and  its  brand  Recordent  (collectively referred as “Recordent”)is owned and operated by Recordent Private Limited, a company  incorporated  under  the  Companies  Act, 2013  (18  of  2013),  having  its registered office at 1stFloor, Mid Town Plaza, Road no.1, Banjara Hills, Hyderabad, Telangana -500033</span></p></li>-->
<!--                      </ul>-->
<!--                      <ul>-->
<!--                          <li>-->
<!--                              <p class="d-flex"> <span class="index-letter">1.1</span> <span class="about-contain">If a  User register  with Recordent  orsubmit  any  material  to  or  use  any  of Recordent services, Recordent will  ask a  Userto  expressly  agree  to the  following  (as  may  be decided that Recordent’s sole and absolute discretion):</span> </p>-->
<!--                              <ul>-->
<!--                                  <li><p class="d-flex"><span class="index-letter">(a)</span><span class="about-contain">any Termsand conditions;</span></p></li>-->
<!--                                  <li><p class="d-flex"><span class="index-letter">(b)</span><span class="about-contain">Recordent’s Privacy Policy;</span></p></li>-->
<!--                                  <li><p class="d-flex"><span class="index-letter">(c)</span><span class="about-contain">Recordent’s End User License Agreement <strong>(“EULA”)</strong>;</span></p></li>-->
<!--                                  <li><p class="d-flex"><span class="index-letter">(d)</span><span class="about-contain">Recodent User Service Agreement </span></p></li>-->
<!--                                  <li><p class="d-flex"><span class="index-letter">(e)</span><span class="about-contain">Data Integrity standards </span></p></li>-->
<!--                                  <li><p class="d-flex"><span class="index-letter" style="opacity:0">(f) </span><span class="about-contain">(collectively called the <stronng>“Terms”</stronng>) </span></p></li>-->
<!--                                  <li><p class="d-flex"><span class="index-letter" style="opacity:0">(g) </span><span class="about-contain">Each of such termsare incorporatedand shall form part and parcel of these presents. </span></p></li>-->
<!--                              </ul>-->
<!--                          </li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">1.2 </span><span class="about-contain">Each  User  isrequested  to  read  the  Terms  carefully  before  registering,  accessing, browsing, uploading or using anything from Recordent. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">1.3 </span><span class="about-contain">By accessing or using Recordent, each User agree to be bound by the Terms including any additional guidelines, disclaimers and future modifications.  </span></p></li>-->
<!--                      </ul>-->
<!--                  </li>-->
<!--                  <li>-->
<!--                      <p><storng class="d-flex"><span class="index-letter">2. </span> <span class="about-contain">Acceptance by the User:</span> </storng></p>-->
<!--                      <ul>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.1 </span><span class="about-contain">Acceptance of the Terms shall constitute a valid and binding legal agreement between Recordent and the User. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.2 </span><span class="about-contain">A  User  Understand  that  the Term  shall  govern use  of Recordent,  including  any Recordent products, software, data feeds and services. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.3 </span><span class="about-contain">The Terms apply to all Users, including Users who are also contributors of Information on Recordent. The  expression <strong>“Information”</strong>  includes  the  text,  scripts,  graphics, photos, video, data, intimation, and other materials </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.4 </span><span class="about-contain">Recordent  mayuse  cookies;  by  using Recordentor  agreeing  to any  Terms, a  User consent to the same.  </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.5 </span><span class="about-contain">The Recordent has the right to deny registration of a Userwithout assigning any reason whatsoever. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.6 </span><span class="about-contain">A  User shall  not  impersonate  any  person  or  entity  or  falsely  state  or  otherwise misrepresent age, identity or affiliation with any person or entity. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.7 </span><span class="about-contain">Each User understand that they are duty bound and responsible to inform  and obtain their  counterparts  that  they  will  be  submitting  Information  to  Recordent  which  may include  details  of  such  counterparts  and  their  Information.  In  this  regard  from  such counterparts they have obtained necessary consent </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.8 </span><span class="about-contain">Recordent reserves its right  to  modify any  Terms and  other  policies  applicable  in general and/or to specificfor its offerings, at any time without giving a User any prior notice, and such changes shall be binding on a User. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.9 </span><span class="about-contain">A User shall re-visit the Terms from time to time to stay abreast of any changes that Recordentmay introduce to any Terms. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.10 </span><span class="about-contain">Further, Termsthat are  an  electronic  record under  theprovisions  of  the  Information Technology Act, 2000. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">2.11 </span><span class="about-contain">This  electronic  record  is  generated  by  a  computer  system  and  does  not  require  any physical or digital signatures. </span></p></li>-->
<!--                      </ul>-->
<!--                  </li>-->
<!--                  <li>-->
<!--                      <p><storng class="d-flex"><span class="index-letter">3. </span> <span class="about-contain">Use of Recordent</span> </storng></p>-->
<!--                      <ul>-->
<!--                          <li><p class="d-flex"><span class="index-letter">3.1 </span><span class="about-contain">In  order  to  access Recordent, a  User  maycreate  an  account.  When  creating a  User Account, User must provide accurate and complete information. It is important that the User must keep User Account and password secure and confidential </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">3.2 </span><span class="about-contain">A User must keep password confidential. A User must notify Recordentimmediately of any breach of security or unauthorised use of User Account or password as soon as they become  aware  of  it. A  User must not  use any other person’s  account  to  access Recordent. </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">3.3 </span><span class="about-contain">A User agree to be solely  responsible and liable  (to Recordent, and to others) for all activity that occurs under their User Account.  </span></p></li>-->
<!--                          <li><p class="d-flex"><span class="index-letter">3.4 </span><span class="about-contain">A User is also liable  for  any  activity on Recordentarising out of any  failure to keep their password confidential and for any losses arising out of such a failure </span></p></li>-->
<!--                      </ul>-->
<!--                  </li>-->
<!--                  <li>-->
<!--                      <p><storng class="d-flex"><span class="index-letter">4. </span> <span class="about-contain">Information</span> </storng></p>-->
<!--                      <ul>-->
<!--                        <li><p class="d-flex"><span class="index-letter">4.1 </span><span class="about-contain">As an account holder, A User shallsubmit only truthful Informationto Recordent. </span></p></li>-->
<!--                        <li><p class="d-flex"><span class="index-letter">4.2 </span><span class="about-contain">A  Userunderstand  that  whether  or  not  Information  is  published,  Recordent  does  not guarantee  any  confidentiality  with  respect  to the Information.If  the  Information  is required to be maintained as confidential, each Userisrequired to maintain the same. Recordent shall not liable or responsible for breach of User’sobligations. Further, by receiving  the  Information  on  Recordent,  it  relies  on  the  representation  that Userhasshared it in compliance with law and contract applicable for such acts. </span></p></li>-->
<!--                        <li><p class="d-flex"><span class="index-letter">4.3 </span><span class="about-contain">Each Usergrant to Recordentand/orthe other users of Recordent(as may be agreed by Recordent)a  worldwide,  irrevocable,  non-exclusive,  royalty-free licenseto  use, reproduce, store, adapt, publish, translate and distribute Information on and in relation to Recordentand  any  successor  website  /  reproduce,  store  and,  with User’sspecific consent,  publish  Information  on  and  in  relation  to Recordent. A  Useralso  grant  to Recordentthe right to sub-license the rights licensed to Recordentunder this section.4 </span></p></li>-->
<!--                        <li><p class="d-flex"><span class="index-letter">4.4 </span><span class="about-contain">EachUser understand  and  agree  that  the  Information  uploaded  may  be  used  by any third person.Recordent shall be entitled to disclose the source at its sole discretion. </span></p></li>-->
<!--                        <li><p class="d-flex"><span class="index-letter">4.5 </span><span class="about-contain">Each  User  alsounderstand  and  agree  that theyare  solely  responsible  for theirInformation  and  the  consequences  of  posting  or  publishing  it.  Recordent  does  not endorse any Information or any opinion, recommendation, or advice expressed therein, and  Recordent  expressly  disclaims  all  ownership  and  liability  in  connection  with Informationor use of it by any person. </span></p></li>-->
<!--                        <li>-->
<!--                            <p class="d-flex"><span class="index-letter">4.6 </span><span class="about-contain">Each Userrepresent and warrant to Recordent that  </span></p>-->
<!--                            <ul>-->
<!--                                <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">they have  (and  will  continue  to  have  during  use  of Recordentby  a  User)  all necessary authority, licenses, rights, consents and permissions which are required to share  the  Information  and enable  Recordent  to  use  the  Information  for  the purposes  of  hosting  the  Information  on Recordent,  and  otherwise  to  use  the Informationby Recordent or any third person. </span></p></li>-->
<!--                                <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">That they will not post or upload any Information which contains material which it is unlawful for to possessin India, or which it would be unlawful for Recordent to  use  or  possess  in  connection  with  the  provision  of  the  services  through Recordent </span></p></li>-->
<!--                                <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">Any third-party Information shared is with due consent and permission from  the concerned person </span></p></li>-->
<!--                            </ul>-->
<!--                        </li>-->

<!--                        <li><p class="d-flex"><span class="index-letter">4.7 </span><span class="about-contain">On  becoming  aware  of  any  potential  violation  of any  Terms,  Recordent  reserves  the right to decide whether Information complies with the Information requirements set out in any Termsand may remove such Information and/or terminate a User’s access for uploading  Information  which  is  in  violation  of any  Termsat  any  time,  without  prior notice to the concerned User and at Recordent’ssole discretion. </span></p></li>-->
<!--                        <li><p class="d-flex"><span class="index-letter">4.8 </span><span class="about-contain">Each Useragree and undertake to indemnify, defend and hold harmless Recordent and all its officers, directors, promoters, employees, agents and representatives against any and all loss and claims arising from any Information uploaded on Recordentor by use of Recordent . </span></p></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                  <p><storng class="d-flex"><span class="index-letter">5. </span> <span class="about-contain">Prohibited Conduct</span> </storng></p>-->
<!--                  <ul>-->
<!--                      <li>-->
<!--                          <p class="d-flex"><span class="index-letter">5.1 </span><span class="about-contain">By using Recordenta Useragree that theyshall not: </span></p>-->
<!--                          <ul>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">use Recordentfor spamming or any other illegal purposes; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">upload any promotional material or advertisement to theUser Account; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">infringe Recordent’s or any third party's intellectual property rights, rights of publicity or privacy; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(d) </span><span class="about-contain">post or transmit any message, data, image or program which violates any law; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(e) </span><span class="about-contain">refuse to cooperate in an investigation or provide confirmation of User’sidentity or any other information providedto Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(f) </span><span class="about-contain">remove,  circumvent,  disable,  damage  or  otherwise  interfere  with  security  related features of the Recordentor features that enforce limitations on the use of Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(g) </span><span class="about-contain">upload any  Information  which is in contempt of  any  court, or in breach of any  court order;  or  discriminates  on  the  basis  of  age,  sex,  religion,  race,  gender;  harassing, invasive of another's privacy, blasphemous; in breach of any contractual obligations or consists of or contains any instructions, advice or other information which may be acted upon  and  could,  if  acted  upon,  cause  illness,  injury  or  death,  or  any  other  loss  or damage;  or  constitutes  spam;  or  is  grossly  harmful,  offensive,  deceptive,  fraudulent, threatening, abusive, hateful, harassing, anti-social, menacing, hateful, discriminatory or  inflammatory;  or  causes  annoyance,  inconvenience  or  needless  anxiety  to  any person;  or  racially,  ethnically  objectionable,  disparaging,  relating  or  encouraging money laundering or  gambling, or harm minors in any  way or otherwise  unlawful in any manner whatever; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(h) </span><span class="about-contain">upload  any  Information  that  threatens  the  unity,  integrity,  defense,  security  or sovereignty of any country, or public order or causes incitement to the commission of any  cognizable  offence  or  prevents  investigation  of  any  offence  or  is  insulting  any nation; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(i) </span><span class="about-contain">upload any Information that contains software viruses,or any other computer code, files  or  programs  designed  to  interrupt,  destroy  or  limit  the  functionality  of  any computer resource; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(j) </span><span class="about-contain">reverse engineer, decompile, disassemble or otherwise attempt to discover the source code of Recordentor any part thereof or infringe any patent, trademark, copyright or other proprietary rights; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(k) </span><span class="about-contain">use Recordentin  any  manner  that  could  damage,  disable,  overburden,  or  impair, including, without limitation, using Recordentin an automated manner; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(l) </span><span class="about-contain">modify, adapt, translate or create derivative works based upon Recordentor any part thereof; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(m) </span><span class="about-contain">intentionally interfere with or damage operation of Recordentor anyother User’s use of Recordent, by any means, including uploading or otherwise disseminating viruses, adware,  spyware,  worms,  or  other  malicious  code  or  file  with  contaminating  or destructive features; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(n) </span><span class="about-contain">use  any  robot,  spider,  other  automatic  device,  or  manual  process  to  monitor  or  copy Recordentwithout prior written permission of Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(o) </span><span class="about-contain">interfere or disrupt Recordentor networks connected therewith </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(p) </span><span class="about-contain">take  any  action  that  imposes  an  unreasonably  or  disproportionately  large  load  on Recordent’sinfrastructure/network; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(q) </span><span class="about-contain">use any device, software or routine to bypass Recordent’srobot exclusion headers, or interfere or attempt to interfere, with Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(r) </span><span class="about-contain">forge headers or manipulate identifiers or other data in order to disguise the origin of any  Information  transmitted  through Recordentor  to  manipulate User’spresence  on Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(s) </span><span class="about-contain">sell/  sub-license  the   Information,  or  software  associated   with  or  derivedfrom Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(t) </span><span class="about-contain">use  the  facilities  and  capabilities  of Recordentto  conduct  any  activity  or  solicit  the performance of any illegal activity or other activity which infringes the rights of others; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(u) </span><span class="about-contain">breach any Termsor any other policy of Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(v) </span><span class="about-contain">provide false, inaccurate or misleading information to Recordent. </span></p></li>-->
<!--                          </ul>-->
<!--                      </li>-->
<!--                      <li><p class="d-flex"><span class="index-letter"> </span><span class="about-contain"> </span></p></li>-->
<!--                  </ul>-->
<!--              </li>-->
<!--              <li>-->
<!--                  <p><storng class="d-flex"><span class="index-letter">6. </span> <span class="about-contain">Commitments to be undertaken by User</span> </storng></p>-->
<!--                  <ul>-->
<!--                      <li><p class="d-flex"><span class="index-letter">6.1 </span><span class="about-contain">Each  User  understand  that  for  using  Recordent  they  will  be  bound  by  Data  Integrity standards and terms of the User Service Agreement as and when required by Recordent. </span></p></li>-->
<!--                  </ul>-->
<!--              </li>-->
<!--              <li>-->
<!--                  <p><storng class="d-flex"><span class="index-letter">7. </span> <span class="about-contain">Recordent’s Information</span> </storng></p>-->
<!--                  <ul>-->
<!--                      <li><p class="d-flex"><span class="index-letter">7.1 </span><span class="about-contain">With  the  exception  of  Information  submitted  to Recordentby  the  User,  all  other Information on the Recordentis either owned by or licensed to Recordent, and is subject to copyright, trade mark rights, and other intellectual property rights of Recordent or Recordent’s  licensors.  Any third-partytrade  or  service  marks  present  on  the Recordentnot  uploaded  or  posted  by a  Userare  trade  or  service  marks  of  their respective   owners   may   not   be   downloaded,   copied,   reproduced,   distributed, transmitted, broadcast, displayed, sold, licensed, or otherwise exploited for any other purpose whatsoever without the prior written consent of Recordent. </span></p></li>-->
<!--                      <li><p class="d-flex"><span class="index-letter">7.2 </span><span class="about-contain">Recordent will not be liable in relation to the Information, or use of, or otherwise in connection with Recordentfor any direct loss; for any indirect, special or consequential loss; or for any business losses, loss of revenue, income, profits or anticipated savings, loss  of  contracts  or  business  relationships,  loss  of  reputation  or  goodwill,  or  loss  or corruption of information or data. </span></p></li>-->
<!--                  </ul>-->
<!--              </li>-->
<!--              <li>-->
<!--                  <p><storng class="d-flex"><span class="index-letter">8. </span> <span class="about-contain">Cancellation and suspension of User Account</span> </storng></p>-->
<!--                  <ul>-->
<!--                      <li><p class="d-flex"><span class="index-letter">8.1 </span><span class="about-contain">The Termswill continue to apply to a User until terminated by Recordent or as set out in the respective terms and conditions. </span></p></li>-->
<!--                      <li>-->
<!--                          <p class="d-flex"><span class="index-letter">8.2 </span><span class="about-contain">Recordent may: </span></p>-->
<!--                          <ul>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">uspend any User Account; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">cancel any User Account; and/or  </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">edit a User Account at any time in Recordent’ssole discretion without notice or explanation. </span></p></li>-->
<!--                          </ul>-->
<!--                      </li>-->
<!--                      <li><p class="d-flex"><span class="index-letter">8.3 </span><span class="about-contain">Recordent  may,  at  any  time  terminate  its  offering  to  a  User  if  the  User  breach  any provision of any Terms(or have acted in manner which clearly shows that the User do not intend to, or are unable to comply with the provisions of any of the Terms), or if Recordent is required to do so by law. </span></p></li>-->
<!--                      <li><p class="d-flex"><span class="index-letter">8.4 </span><span class="about-contain">Any  cancellation  or  suspension  of  User  Account  by  a  User  shall  be subject  to  the acceptance of Recordent and upon a Userperforming required  formalities as may be required by Recordent from time to time </span></p></li>-->
<!--                  </ul>-->
<!--              </li>-->
<!--              <li>-->
<!--                  <p><storng class="d-flex"><span class="index-letter">9. </span> <span class="about-contain">Breach of the Terms</span> </storng></p>-->
<!--                  <ul>-->
<!--                      <li>-->
<!--                          <p class="d-flex"><span class="index-letter">9.1 </span><span class="about-contain">Without prejudice to Recordent’s other rights under any Terms, if a User breach any of the Termsin any way, or if Recordent suspect that a User breached any of the Termsin any way, Recordent may at its discretion may exercise any of the following</span></p>-->
<!--                          <ul>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(a) </span><span class="about-contain">send the User one or more formal warnings </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(b) </span><span class="about-contain">temporarily suspend User’s access to Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(c) </span><span class="about-contain">permanently prohibit a User from accessing Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(d) </span><span class="about-contain">block computers using User’s  IP address from accessing Recordent;  </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(e) </span><span class="about-contain">contact any or all of User’sinternet service providers and request that they block User’s access to Recordent; </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(f) </span><span class="about-contain">commence  legal  action  against  the  User,  whether  for  breach  of  contract  or otherwise; and/or  </span></p></li>-->
<!--                              <li><p class="d-flex"><span class="index-letter">(g) </span><span class="about-contain">suspend or delete concerned User Account on Recordent </span></p></li>-->
<!--                          </ul>-->
<!--                      </li>-->
<!--                      <li><p class="d-flex"><span class="index-letter">9.2 </span><span class="about-contain">Where we suspend or prohibit or block User’saccess to Recordentor a part thereof, A Usermust not take any action to circumvent such suspension or prohibition or blocking (including without limitation creating and/or using a different account).By creating a user account a User confirm that are not a blocked user. </span></p></li>-->
<!--                  </ul>-->
<!--              </li>-->
<!--              <li>-->
<!--                  <p><storng class="d-flex"><span class="index-letter">10. </span> <span class="about-contain">Miscellaneous</span> </storng></p>-->
<!--                  <ul>-->
<!--                      <li><p class="d-flex"><span class="index-letter">10.1 </span><span class="about-contain">By using Recordent in any manner each User agree that Recordent may assign, transfer, sub-contract  or  otherwise  deal  with Recordent’s rights  and/or  obligations  under any Terms. A User without Recordent’s prior written consent, assign, transfer, sub-contract or otherwise deal with any of their rights and/or obligations under any of the Terms. </span></p></li>-->
<!--                      <li><p class="d-flex"><span class="index-letter">10.2 </span><span class="about-contain">If  a  provision  of  a  contract  under any  Termsis  determined  by  any  court  or  other competent  authority  to  be  unlawfuland/or  unenforceable,  the  other  provisions  will continue in effect. If any unlawful and/or unenforceable provision of a contract under any Termswould be lawful or enforceable if part of it were deleted, that part will be deemed to be deleted, and the rest of the provision will continue in effect.  </span></p></li>-->
<!--                      <li><p class="d-flex"><span class="index-letter">10.3 </span><span class="about-contain">A contract under any Termswill inure to the benefit of Recordent and its successors and assigns and the benefit of the User, and is not intended to benefit or be enforceable by any third party not expressly stated herein. The exercise of the parties’ rights under a contract under any Termsis not subject to the consent of any third party. </span></p></li>-->
<!--                      <li><p class="d-flex"><span class="index-letter">10.4 </span><span class="about-contain">Any  Terms,  together  with  other  policies  of Recordent,  shall  constitute  the  entire agreement between a User and Recordentin relation to use of Recordent </span></p></li>-->
<!--                      <li><p class="d-flex"><span class="index-letter">10.5</span><span class="about-contain">Each  User agree  that  Recordent  may  provide them with  notices,  including  those regarding changes to any Terms, by email, regular mail, or postings on Recordent. </span></p></li>-->
<!--                      <li><p class="d-flex"><span class="index-letter">10.6 </span><span class="about-contain">Each of the Termsof Recordentshall be governed by and construed in accordance with the laws of republic of India. Any disputes relating to any Termsor the use of Recordentshall be subject to the exclusive jurisdiction of courts at Hyderabad, India </span></p></li>-->
<!--                  </ul>-->
<!--              </li>-->
<!--              <li>-->
<!--                  <p class="d-flex"><storng class="d-flex"><span class="index-letter">11. </span> <span class="about-contain">Contact Details: &nbsp</span> </storng> A Usercan contact Recordentby emailing at &nbsp<a href="mailto:hello@recordent.com">hello@recordent.com</a></p>-->
<!--              </li>-->
<!--          </ul>-->
<!--      </div>-->
<!--  </div>-->

<!--</div>-->
<!-- /.modal-content -->
<!--</div>-->
<!-- /.modal-dialog -->
<!--</div> --}}
<script src="{{asset('front_new/js/index.js')}}"></script>
<script>
    $(document).ready(function(){
        if ( 'serviceWorker' in navigator ) {
        window.addEventListener( 'load', function () {
            navigator.serviceWorker.register( "{{config('app.url')}}sw.js" ).then( function ( registration ) {
                // Registration was successful
                console.log( 'ServiceWorker registration successful with scope: ', registration.scope );
            }, function ( err ) {
                // registration failed :(
                console.log( 'ServiceWorker registration failed: ', err );
            } );
        } );
    }
      $('footer a[href*="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#0"]')
        .click(function (event) {

            // On-page links
            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
                location.hostname == this.hostname
            ) {
                // Figure out element to scroll to
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                // Does a scroll target exist?
                if (target.length) {

                    // Only prevent default if animation is actually gonna happen
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 130
                    }, 1000, function () {

                  });
                }
            }
        });
        if (window.location.hash) {
            setTimeout(function() {
                $('html, body').scrollTop(0).show();
                $('html, body').animate({
                    scrollTop: $(window.location.hash).offset().top -130
                    }, 1000)
            }, 0);
        }

    });
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{setting('site.google_analytics_tracking_id')}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{setting('site.google_analytics_tracking_id')}}');
</script>
<!-- Hotjar Tracking Code for www.recordent.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:2112472,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
