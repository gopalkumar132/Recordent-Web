@extends('layouts_front_new.master')
@section('meta-title', config('seo_meta_tags.home_page.title'))
@section('meta-description', config('seo_meta_tags.home_page.description'))
@section('canonical-url')
    <link rel="canonical" href="{{config('app.url')}}" />
@endsection
@section('content')
<style>
.reuse-text-info-h1 h2 {
	color: #000;
    font-size: 24px;
    font-weight: 500;
    margin: 0 0 8px;
}
.as-easy-as-section .plain-text-h2 p {
    color: #5f94c4;
    font-size: 46px;
    line-height: 1;
    font-weight: 400;
}
@media (min-width: 620px) and (max-width:2000px) {
.items
{
  font-family:var(--font-open-sans);
  font-weight:normal;
  color: black;
  line-height:30px;
  font-size: 18px;
}
.text
{
color:grey;
right:-95px;
}
.equifax_img
{
    left: 65px;
}
.slider2
{
    margin-left: 90px;
}
}
.new-four-easy-step .main-step-contain-h2 h2 {
    font-size: 20px;
    color: #000;
    margin-bottom: 10px;
}
@media (max-width:580px) {
  .as-easy-as-section .plain-text-h2 p{font-size:32px;}
}

@media (max-width: 479px) {
.reuse-text-info-h1 h2 {
    font-size: 18px;
}
}

/* .numberCircle {
    border-radius: 50%;
    width: 140px;
    height: 140px;
    padding-top: 36px;

    background: white;
    border: 5px solid #5f94c4;
    color: #666;
    text-align: center;

    font: 32px Arial, sans-serif;
}
.counter{font-size:56px !important;} */

/* Extra small devices (portrait phones, less than 576px) */
@media only screen and (max-width: 539px) {
    .equifax_img_tag {
        display: inline-block !important;
        max-width: 200px;
    }
}

/*Small devices (landscape phones, 576px and up) */
@media only screen and (min-width: 540px) and (max-width: 767.98px) {
    .equifax_img_tag {
        display: inline-block !important;
        max-width: 234px;
    }
}


</style>

<section class="main-slider-recordent">

	<div class="container">
		<div class="owl-carousel owl-theme how-recordent-work">
            <!-- check now equifax slider item -->
			<div class="item">
				<div class="d-flex align-items-center justify-content-between cmd-flex-wrap">
					<div class="slider2">
					   <img src="{{asset('front_new/images/slider2.png')}}" alt="">
					</div>
					<div class="slider-info-text text-center reuse-text-info reuse-text-info-h1">
				        <p class="items">Check your customer's credit<br> and payment history to make better decision</p>
                        <br><br>
                        <div class="row">
                            <div class="col-md-4 col-xs-12 text" style="color: grey;"><b>Data Powered by</b></div>
                            <div class="col-md-4 col-xs-12 equifax_img">
                                <img src="{{asset('front_new/images/equifax_logo.png')}}" class="equifax_img_tag" alt="equifax_logo">
                            </div>
                        </div>
                        <br>
						<div class="join-now-part">
							<p>Easy Sign Up. Become a Member</p>
							<a href="{{config('app.url')}}register" class="text-uppercase btn-joinnow">Check Now</a>
							<p>Already a member? <a href="{{config('app.url')}}admin/login"> Login</a> </p>
						</div>
					</div>
				</div>
			</div>
            <!-- join now slider item -->
            <div class="item">
                <div class="d-flex align-items-center justify-content-between cmd-flex-wrap">
                    <div class="slider-photo">
                        <div class="img-box">
                            <img src="{{asset('front_new/images/2.png')}}" alt="" class="object-fit-cover">
                        </div>
                    </div>
                    <div class="slider-info-text text-center reuse-text-info">
                        <h1>Payment collections hurting business growth?<br> Recordent helps collect dues faster</h1>
                        <p>Submit all dues and manage  <br class="cmd-d-none"> collections better on Recordent portal</p>
                        <div class="join-now-part">
                            <p>Easy Sign Up. Become a Member</p>
                            <a href="{{config('app.url')}}register" class="text-uppercase btn-joinnow">Join Now</a>
                            <p>Already a member? <a href="{{config('app.url')}}admin/login"> Login</a> </p>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>

</section>

<section class="video-section text-center">

	<div class="container">

		<div class="owl-carousel owl-theme video-section-slider">

			<div class="item">

			    <a href="javascript:void" data-toggle="modal" data-target="#exampleModalCenter" class=""><i class="fa fa-play" aria-hidden="true"></i> Watch Video </a>

				<!--<p class="text-white">100% of customer dues reported on <br class="cmd-d-none"> Recordent are recovered by a business in <br class="cmd-d-none"> Hyderabad within 30days</p>-->

				<p class="text-white">Businesses are collecting <br class="cmd-d-none"> upto 100% customer dues within <br class="cmd-d-none"> 30 days after submitting on Recordent</p>

			</div>

			<div class="item">

			    <a href="javascript:void" data-toggle="modal" data-target="#exampleModalCenter" class=""><i class="fa fa-play" aria-hidden="true"></i> Watch Video </a>

				<!--<p class="text-white">30% of long-pending customer dues <br class="cmd-d-none"> are recovered within12 days <br class="cmd-d-none"> after reporting on Recordent</p>-->
				<p class="text-white">30% of long-pending dues <br class="cmd-d-none"> collected within 12 days after  <br class="cmd-d-none"> submitting on Recordent</p>

			</div>

			<div class="item">

			    <a href="javascript:void" data-toggle="modal" data-target="#exampleModalCenter" class=""><i class="fa fa-play" aria-hidden="true"></i> Watch Video </a>

                <p class="text-white small-text">
                    "Customers who had absolutely stopped communicating with us on their overdue payments they themselves have reached out to us to discuss and settle the same, this happened only after submitting their overdue payment details on Recordent”
                </p>
                <p class="text-white small-text">Proprietor- Mr. Mahipal, Mahipal Ads, Hyderabad</p>
            </div>

            <div class="item">

                <a href="javascript:void" data-toggle="modal" data-target="#exampleModalCenter" class=""><i class="fa fa-play" aria-hidden="true"></i> Watch Video </a>

                <p class="text-white small-text">
                    "Customers who had not paid us for almost 3 years and who had entirely stopped communication have called us for details and now want to clear their dues, that too within a week of submitting their dues on Recordent"
                </p>
                <p class="text-white small-text">T. Rajesh Babu - Manager Marketing,SP Hi-Tech Printers Pvt Ltd, Hyderabad</p>
            </div>

            <div class="item">

                <a href="javascript:void" data-toggle="modal" data-target="#exampleModalCenter" class=""><i class="fa fa-play" aria-hidden="true"></i> Watch Video </a>

                <p class="text-white small-text">
                   “Recordent is a great software tool for recovering outstanding course fee dues from students. We have been seeing wonderful results as students (parents) are making fast payments upon receiving alerts”
                </p>
                <p class="text-white small-text">Lasya InfoTech Training Institute</p>
            </div>

            <div class="item">

                <a href="javascript:void" data-toggle="modal" data-target="#exampleModalCenter" class=""><i class="fa fa-play" aria-hidden="true"></i> Watch Video </a>

                <p class="text-white small-text">
                  More than 14 months old overdue was recovered just within 2 months of submitting to Recordent. The customer used digital payment options offered on Recordent platform to clear the outstanding invoice and Recordent credited the amount in our bank account within 24 hours.
                </p>
                <p class="text-white small-text">Arjun Makam - Business Head - Laundrokart</p>
            </div>

		</div>

	</div>

</section>



<section class="new-four-easy-step as-easy-as-section" id="as-easy">

    <div class="container">

        <div class="title-part text-center plain-text-h2">

            <!--<h2>As Easy as 1-2-3-4</h2>-->
			<p>As Easy as 1-2-3-4</p>

        </div>

        <div class="main-four-step">

            <div class="d-flex justify-content-between flex-wrap">

                <div class="main-step-contain main-step-contain-h2">

                    <div class="step-icon d-flex align-items-center justify-content-center">

                        <img src="{{asset('front_new/images/n_step_01.png')}}" alt="" class="img-fluid">

                    </div>

                    <div class="step-cont text-center">

                        <h2>1. ADD CUSTOMER DUES</h2>

                        <p>Become a member on Recordent and add your customer dues with easy upload options</p>

                    </div>

                </div>

                <div class="main-step-contain main-step-contain-h2">

                    <div class="step-icon d-flex align-items-center justify-content-center">

                        <img src="{{asset('front_new/images/n_step_02.png')}}" alt="" class="img-fluid">

                    </div>

                    <div class="step-cont text-center">

                        <h2>2. SEND NOTIFICATIONS</h2>

                        <p>Notify via SMS, IVR &amp; email to collect dues faster and help customers maintain a positive track record</p>

                    </div>

                </div>

                <div class="main-step-contain main-step-contain-h2">

                    <div class="step-icon d-flex align-items-center justify-content-center">

                        <img src="{{asset('front_new/images/n_step_03.png')}}" alt="" class="img-fluid">

                    </div>

                    <div class="step-cont text-center">

                        <h2>3. COLLECT DUES</h2>

                        <p>Offer payment options to collect dues faster. If not paid, dues show as unpaid on Recordent</p>

                    </div>

                </div>

                <div class="main-step-contain main-step-contain-h2">

                    <div class="step-icon d-flex align-items-center justify-content-center">

                        <img src="{{asset('front_new/images/n_step_04.png')}}" alt="" class="img-fluid">

                    </div>

                    <div class="step-cont text-center">

                        <h2>4. CHECK PAYMENT HISTORY</h2>

                        <p>Members can check payment history of customers before offering service, credit, or a loan</p>

                    </div>

                </div>

            </div>

            <div class="b-s-30"></div>

            <div class="text-center">

                <div class="join-now-part Join">
                    <p>Easy Sign Up. Become a Member</p>

                    <a href="{{config('app.url')}}register" class="text-uppercase btn-joinnow">Join Now</a>

                    <p>Already a member? <a href="{{config('app.url')}}admin/login"> Login</a> </p>

                </div>

            </div>

        </div>

    </div>

</section>



<section class="as-easy-as-section">

    <div class="container">

        <div class="title-part text-center">

            <h2>Get paid faster with Recordent</h2>

        </div>

        <div class="four-easy-step d-flex justify-content-between flex-wrap">

            <div class="indi-step one-step">

                <div class="step-name text-center">

                    <div class="d-flex cmd-flex-wrap">

                        <!--<div class="step-number cmd-w-100">-->

                        <!--    <p>1</p>-->

                        <!--</div>-->

                        <div class="cmd-w-100">

                            <p>Send <strong>automated notifications and <br class="cmd-d-none"> reminders to your customers</strong> <br class="cmd-d-none"> about payments and due dates</p>

                            <a href="javascript:void(0)">Learn more</a>

                        </div>

                    </div>

                </div>

                <div class="position-relative">

                    <div class="d-flex justify-content-between">

                        <div class="d-flex">

                            <div class="step-use-img">

                                <img src="{{asset('front_new/images/step_01.png')}}" alt="">

                            </div>

                        </div>

                        <div class="chat-type-boxes">

                            <div class="blue-chat-box position-relative">

                                <p>Ramesh Company has recorded your overdue payment on Recordent and is overdue by 10 days.</p>

                            </div>

                            <div class="yelow-chat-box position-relative one">

                                <p>रमेश कंपनी ने रिकॉर्डेड पर आपका अतिदेय भुगतान दर्ज किया है और 10 दिनों से अतिदेय है।</p>

                            </div>

                            <div class="yelow-chat-box position-relative">

                                <p>రమేష్ కంపెనీ మీ మీరిన చెల్లింపును రికార్డెంట్లో రికార్డ్ చేసింది మరియు 10 రోజులు ఆలస్యం అయింది. </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="indi-step two">

                <div class="step-name text-center">

                    <div class="d-flex cmd-flex-wrap">

                        <!--<div class="step-number cmd-w-100">-->

                        <!--    <p>2</p>-->

                        <!--</div>-->

                        <div class="cmd-w-100">

                            <p>Offer <strong>digital payment options</strong> to your<br class="cmd-d-none"> customers for easier and <br class="cmd-d-none"> faster payments</p>

                            <a href="{{config('app.url')}}solutions#payment-options">Learn more</a>

                        </div>

                    </div>

                </div>

                <div class="position-relative">

                    <div class="d-flex justify-content-between cmd-justify-content-center">

                        <div class="d-flex">

                            <div class="payment-mode position-relative">

                                <div class="type-of-pay position-relative blue-pay">

                                    <p>Bank UPI</p>

                                </div>

                                <div class="type-of-pay position-relative yellow-pay">

                                    <p>Mobile Wallet</p>

                                </div>

                                <div class="type-of-pay position-relative blue-pay">

                                    <p>Debit/Credit Card</p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!--<div class="indi-step three">-->

            <!--    <div class="b-s-30"></div>-->

            <!--    <div class="step-name text-center">-->

            <!--        <div class="d-flex cmd-flex-wrap">-->

            <!--            <div class="step-number cmd-w-100">-->

            <!--                <p>3</p>-->

            <!--            </div>-->

            <!--            <div class="cmd-w-100">-->

            <!--                <p>Your customers can <strong>get loan options</strong> <br class="cmd-d-none"> from our leading partners</p>-->

            <!--                <a href="javascript:void(0)">Coming soon</a>-->

            <!--            </div>-->

            <!--        </div>-->

            <!--    </div>-->

            <!--    <div class="position-relative">-->

            <!--        <div class="d-flex justify-content-between cmd-justify-content-center">-->

            <!--            <div class="d-flex w-100 justify-content-center">-->

            <!--                <div class="step-use-img">-->

            <!--                    <img src="{{asset('front_new/images/step_03.png')}}" alt="">-->

            <!--                </div>-->

            <!--            </div>-->

            <!--        </div>                            -->

            <!--    </div>-->

            <!--</div>-->

            <!--<div class="indi-step four">-->

            <!--    <div class="b-s-30"></div>-->

            <!--    <div class="step-name text-center">-->

            <!--        <div class="d-flex cmd-flex-wrap">-->

            <!--            <div class="step-number cmd-w-100">-->

            <!--                <p>4</p>-->

            <!--            </div>-->

            <!--            <div class="cmd-w-100">-->

            <!--                <p><strong>Offer instalment options</strong><br class="cmd-d-none"> to help your customers clear past dues</p>-->

            <!--                <a href="javascript:void(0)">Coming soon</a>-->

            <!--            </div>-->

            <!--        </div>-->

            <!--    </div>-->

            <!--    <div class="position-relative">-->

            <!--        <div class="d-flex justify-content-between cmd-justify-content-center">-->

            <!--            <div class="d-flex">-->

            <!--                <div class="step-use-img">-->

            <!--                    <img src="{{asset('front_new/images/step_04.png')}}" alt="">-->

            <!--                </div>-->

            <!--            </div>-->

            <!--        </div>                            -->

            <!--    </div>-->

            <!--</div>-->

        </div>

        <div class="b-s-20"></div>

        <div class="text-center">

            <div class="join-now-part Join">

                <p>Easy Sign Up. Become a Member</p>

                <a href="{{config('app.url')}}register" class="text-uppercase btn-joinnow">Join Now</a>

                <p>Already a member? <a href="{{config('app.url')}}admin/login"> Login</a> </p>

            </div>

        </div>

    </div>

</section>





<section class="the-power">

	<div class="container">

		<div class="the-title text-center">

			<h2><p>The Power Of Many</p>
<!-- <div class="container counter-section">
    <div class="row text-center">
        <div class="col-md-12 counter-box">
        <center>
        <p class="counter numberCircle">1500</p>
        <p>Crore Dues</p>
        </center>
        </div>
    </div>
</div><br> -->
<p>Check Payment History</p></h2>

	</div>

		<div class="row align-items-center">

			<div class="col-12 co-md-6 col-lg-6 col-xl-6">

				<div class="the-many-peopele position-relative">

					<div class="line-one"></div>

					<div class="line-two"></div>

					<div class="line-three"></div>

					<div class="line-four"></div>

					<div class="d-flex justify-content-between">

						<div class="the-many-indiv one position-relative">

							<img src="{{asset('front_new/images/individual_01.jpg')}}" alt="">

						</div>

						<div class="the-many-indiv">

							<img src="{{asset('front_new/images/6.png')}}" alt="">

						</div>

						<div class="the-many-indiv">

							<img src="{{asset('front_new/images/individual_03.jpg')}}" alt="">

						</div>

					</div>

					<div class="d-flex justify-content-between">

						<div class="the-many-indiv">

							<img src="{{asset('front_new/images/23.jpg')}}" alt="">

						</div>

						<div class="the-many-indiv five position-relative">

							<div class="card">

								<div class="owl-carousel owl-theme bus-indi-slider">

									<div class="item d-flex align-items-center justify-content-center">

										<div>

											<i class="ic-inv text-center d-block ic-in-ic"><img src="{{asset('front_new/images/ic_iIndividuals.png')}}" alt=""></i>

											<div class="b-s-10"></div>

											<p class="text-uppercase text-white">Individuals</p>

										</div>

									</div>

									<div class="item d-flex align-items-center justify-content-center">

										<div>

											<i class="ic-inv text-center d-block ic-busi-ic"><img src="{{asset('front_new/images/ics_businessse.png')}}" alt=""></i>

											<div class="b-s-10"></div>

											<p class="text-uppercase text-white">Business</p>

										</div>

									</div>

								</div>

							</div>

						</div>

						<div class="the-many-indiv">

							<img src="{{asset('front_new/images/individual_06.jpg')}}" alt="">

						</div>

					</div>

					<div class="d-flex justify-content-between">

						<div class="the-many-indiv">

							<img src="{{asset('front_new/images/individual_07.jpg')}}" alt="">

						</div>

						<div class="the-many-indiv">

							<img src="{{asset('front_new/images/individual_08.jpg')}}" alt="">

						</div>

						<div class="the-many-indiv">

							<img src="{{asset('front_new/images/individual_09.jpg')}}" alt="">

						</div>

					</div>

				</div>

			</div>

			<div class="col-12 co-md-6 col-lg-6 col-xl-6">

				<div class="text-center reuse-text-info">

					<h3>Offering credit to <br class="cmd-d-none"> your customer?</h3>

					<p>Know your customer's payment<br class="cmd-d-none"> history submitted by Recordent members</p>

					<div class="join-now-part">

						<p>Easy Sign Up. Become a Member</p>

						<a href="{{config('app.url')}}register" class="text-uppercase btn-joinnow">Join Now</a>

						<p>Already a member? <a href="{{config('app.url')}}admin/login"> Login</a> </p>

					</div>

				</div>

			</div>

		</div>

	</div>

</section>





<section class="video-section watch-you-video">

	<div class="container">

		<div class="row align-items-center">

			<div class="col-12 col-md-12 col-xl-6 col-lg-6">

				<div class="d-flex align-items-center justify-content-center watch-video" id="watch-video">

					<iframe width="560" height="315" src="https://www.youtube.com/embed/cc6_v_eYLdw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>

				</div>

			</div>

			<div class="col-12 col-md-12 col-xl-6 col-lg-6 text-center">

				<div class="join-now-part">

					<p>Easy Sign Up. Become a Member</p>

					<a href="{{config('app.url')}}register" class="text-uppercase btn-joinnow">Join Now</a>

					<p>Already a member? <a href="{{config('app.url')}}admin/login"> Login</a> </p>

				</div>

			</div>

		</div>

	</div>

</section>
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button>  -->

<div class="modal fade commap-team-popup video-pop" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">


      </div>
      <div class="modal-body">
      <section class="">

<div class="">

    <div class="">

        <div class="">

            <div class=" watch-video" id="watch-video">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

                <iframe width="560" height="315"  src="https://www.youtube.com/embed/cc6_v_eYLdw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope;" allowfullscreen></iframe>

            </div>

        </div>

    </div>

</div>

</section>
      </div>
    </div>
  </div>
</div>
<!-- <script src="{{asset('js/jquery.counterup.min.js')}}"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script> -->
<script>

// var counter = function (index, max ,result)
// { index++; result(index);
// if (index < max) setTimeout(this.counter, 10, index, max ,result);
// }
//  counter(0 ,1500 , (value)=>{

// var w_width=80+20*(value.toString().length-1);
// var padding_top=9*(value.toString().length);
// $('.numberCircle').css('padding-top',padding_top.toString()+'px');
// $('.numberCircle').height(w_width-padding_top);
//      $('.numberCircle').width(w_width);

//      $('.counter').text(value);
//      });

$("#exampleModalCenter").on('hidden.bs.modal', function (e) {
    $("#exampleModalCenter iframe").attr("src", $("#exampleModalCenter iframe").attr("src"));
});
</script>
@endsection
