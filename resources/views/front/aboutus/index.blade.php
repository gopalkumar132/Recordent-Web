@extends('layouts_front_new.master')
@section('meta-title', config('seo_meta_tags.aboutus_page.title'))
@section('meta-description', config('seo_meta_tags.aboutus_page.description'))
@section('canonical-url')
    <link rel="canonical" href="{{config('app.url')}}aboutus" />
@endsection
@section('content')
<style>
.the-title-h1 h1 {
    color: #5f94c4;
    font-family: var(--font-rubik);
    font-weight: 700;
    font-size: 44px;
}

element.style {
}
@media (max-width: 767px) {
.the-title-h1 h1{
    color: #5f94c4;
}
}
@media (max-width: 479px) {
.the-title-h1 h1{
    font-size: 26px;
}
}
.contact-from-plaintext p {
    color: #fff;
    font-size: 24px;
    font-weight: 400;
    text-align: center;
    margin-bottom: 15px;
}

</style>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.css">
 <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>

<section class="about-info">

	<div class="container">

		<div class="the-title the-title-h1 text-center" data-aos="zoom-in" data-aos-duration="2000">

			<h1>About Us</h1>

		</div>

		<div class="about-text text-center" data-aos="fade-up" data-aos-duration="1500">

			<p>Recordent is a technology platform helping businesses improve collections by credit profiling their customers; and reducing risk by providing insights into the payment history of prospective customers.</p>

            <p>Recordent’s platform enables businesses to submit their customer dues/invoices on a regular basis to collect payments faster and on-time. Inspired from the Credit Bureau model, Recordent informs customers on how their positive payment track record can be viewed by other businesses & lenders to offer better terms on credit or a loan; thus, motives and creates urgency to pay dues sooner than later.</p>

            <p>Recordent’s data aggregation engine helps businesses in making informed decisions before offering credit or a loan by providing credit reports on individuals and commercial entities. These reports provide information on customer’s payment behaviour towards loans and credit availed in the recent past. Recordent’s platform has access to 700million+ reports of individuals and businesses through its network of partnerships in India and USA with well known credit bureaus.</p>

            <p>Having the highest regard for privacy and data security, Recordent’s goal is to enable trust and accountability for businesses that offer a service, credit, or a loan to their customers.</p>

           

		</div>

		<!--<div class="know-more-link text-center">-->

		<!--    <a href="{{config('app.url')}}#as-easy">Know More</a>-->

		<!--</div>-->

	</div>

</section>

<!--first team-->

<section class="reporting-made the-team" id="our-team">

	<div class="container">

		<div class="the-title text-center" data-aos="zoom-in" data-aos-duration="2000">

			<h2 class="csd-d-none">Team</h2>

			<h2 class="nd-none csd-d-block">Team</h2>

		</div>

		<div class="reporting-six-step">

			<div class="d-flex flex-wrap">
					<div class="reporting-step-a text-center" data-aos="fade-up" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1000">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/Drishya.jpg')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>DRISHYA GOYEL</p>
							<p>Chief - Growth & Partnerships</p>
						</div>
						<a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#drishya"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/drishya-goyel-bb9303a/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				<div class="reporting-step-a text-center">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/ramana_krishnan.jpg')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>Ramana Krishnan</p>
							<p>Head - BD &amp; Memberships</p>
						</div>
						<a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#ramanaKrishna"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/ramana-krishnan/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
			    <div class="reporting-step-a text-center">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/tarun_rumalla.jpg')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>Tarun Rumalla</p>
							<p>Head - Member Services</p>
						</div>
						<a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#tarunRumalla"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/tarun-rumalla-34ba7911" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
			    <div class="reporting-step-a text-center" data-aos="fade-left" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1500">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/manish.png')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>Manish Ajwani</p>
							<p>Head of Products</p>
						</div>
						<a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#ManishAjwani"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://in.linkedin.com/in/manish-ajwani-51324953" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>

				   <div class="reporting-step-a text-center" data-aos="fade-up" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="2000">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/winny.png')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>WINNY PATRO</p>
							<p>CEO &amp; Co-Founder</p>
						</div>
						<a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#WinnyPatro"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/winnypatro/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				<div class="reporting-step-a text-center" data-aos="fade-up" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1000">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/harish.png')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>HARISH MAMTANI</p>
							<p>Founder</p>
						</div>
						<a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#harishMamtani"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/harishmamtani/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				
				   <div class="reporting-step-a text-center" data-aos="fade-up" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="2000">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/Kotesh.jpg')}}" style="padding-top: 70px;" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>KOTESWARA RAO</p>
							<p>Head of Technology</p>
						</div>
						<a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#kotesh"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/koteswara-rao-1881b514/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
					</div>
						
				<div class="reporting-step-a text-center" data-aos="fade-down" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="2500">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/jaysheth.png')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>JAY SHETH</p>
							<p>Founder and Advisor</p>
						</div>
						<a href="javascript:void(0" class="full-box-link position-absolute" data-toggle="modal" data-target="#JaySheth"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/jaysheth" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>

			
				

			</div>

		</div>

	</div>

</section>

<!--second team-->


<section class="reporting-made the-team" id="our-team">

	<div class="container">

		<div class="reporting-six-step">

			<div class="d-flex flex-wrap">
			    <div class="reporting-step-a text-center" data-aos="fade-up" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="2000">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/Bharatr.jpg')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>BHARAT NISTALA</p>
							<p>Business Development Manager</p>
						</div>
						<a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#Bharathnistala"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/bharat-nistala-b1b599a2/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				<div class="reporting-step-a text-center">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/Pushprajr.png')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>PUSHPARAJ GANPAT</p>
							<p>Associate Manager</p>
						</div>
						<a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#pushparaj"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/pushpraj-ganpat/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
			    <div class="reporting-step-a text-center">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/jyothir.jpg')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>JYOTI KAMAAL</p>
							<p>Freelancer - CRM</p>
						</div>
						<a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#jyoti"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/jyotikamaal/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
			    <div class="reporting-step-a text-center" data-aos="fade-left" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1500">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/arpithar.jpg')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>ARPITHA AGGITAKALYA</p>
							<p>HR Manager</p>
						</div>
						<a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#arpitha"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/arpithaaggitakalya" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				<div class="reporting-step-a text-center" data-aos="fade-up" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1000">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/harshika.JPG')}}" style="padding-top: 60px;" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>HARSHIKA KAMDAR</p>
							<p>Associate Product Manager</p>
						</div>
						<a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#harshika"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/harshika-kamdar/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				<div class="reporting-step-a text-center" data-aos="fade-up" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1000">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/balachandra.JPG')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>BALACHANDRA RAO</p>
							<p>Senior UX Designer</p>
						</div>
						<a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#balachandra"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/balachandrarao" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				<div class="reporting-step-a text-center" data-aos="fade-up" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1000">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/vamshi.JPG')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>VAMSHI GOLI</p>
							<p>Lead Developer</p>
						</div>
						<a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#vamshi"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/vamshi-goli-520b584a" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				<div class="reporting-step-a text-center" data-aos="fade-down" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1500">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/sandeepr.jpg')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>JALAGAM SANDEEP</p>
							<p>Software Developer</p>
						</div>
						<a href="javascript:void(0" class="full-box-link position-absolute" data-toggle="modal" data-target="#sandeep"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/sandeep-jalagam-88111a107/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				<div class="reporting-step-a text-center" data-aos="fade-right" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1000">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/anamr.jpeg')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>MOHAMMAD ANAM</p>
							<p>Software Developer</p>
						</div>
						<a href="javascript:void(0)" data-toggle="modal" data-target="#anam" class="full-box-link position-absolute"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://in.linkedin.com/in/mohammadanam" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
					   <div class="reporting-step-a text-center" data-aos="fade-down" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1500">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/sabjan.jpeg')}}" style="margin-top: 5px;" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>SABJAN SYED</p>
							<p>Software Developer</p>
						</div>
						<a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#sabjan"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/syed-sabjan-a25914206" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
				   <div class="reporting-step-a text-center" data-aos="fade-down" data-aos-offset="300" data-aos-easign="ease-in-sine" data-aos-duration="1500">
					<div class="position-relative">
						<div class="step-icon d-flex align-items-center justify-content-center">
							<img src="{{asset('front_new/images/team/madhuri.jpg')}}" alt="">
						</div>
						<div class="b-s-30"></div>
						<div class="step-text-info">
							<p>MADHURI BEERA</p>
							<p>Quality Analyst</p>
						</div>
						<a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#madhuri"></a>
					</div>
					<div class="b-s-10"></div>
					<a href="https://www.linkedin.com/in/madhuri-beera-a31986175/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				</div>
			</div>
		</div>
	</div>

				
</section>
<!--scroll bar-->

<!--<section>		

<div class="the-title text-center" data-aos="zoom-in" data-aos-duration="2000">

			<h2 class="csd-d-none">Team</h2>

			<h2 class="nd-none csd-d-block">Team</h2>

		</div>

 <div id="testimonial-slider" class="owl-carousel">

 	  <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/ramana_krishnan.jpg')}}" alt="">
              <a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#ramanaKrishna"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#ramanaKrishna">RAMANA KRISHNAN</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#ramanaKrishna">Head - BD &amp; Memberships</a></p>
          </div>
		<a href="https://www.linkedin.com/in/ramana-krishnan/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>

          <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/tarun_rumalla.jpg')}}" alt="">
              <a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#tarunRumalla"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#tarunRumalla">TARUN RUMALLA</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#tarunRumalla">Head - Member Services</a></p>
          </div>
         <a href="https://www.linkedin.com/in/tarun-rumalla-34ba7911" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>


  		 <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/manish.png')}}" alt="">
              <a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#ManishAjwani"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#ManishAjwani">MANISH AJWANI</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#ManishAjwani">Head of Products</a></p>
          </div>
         <a href="https://in.linkedin.com/in/manish-ajwani-51324953" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>


        <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/winny.png')}}" data-target="#JaySheth" alt="">
             <a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#WinnyPatro"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#WinnyPatro">WINNY PATRO</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#WinnyPatro">CEO &amp; Co-Founder</a></p>
          </div>
		<a href="https://www.linkedin.com/in/winnypatro/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>			
        </div>
	

		<div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/harish.png')}}" alt="">
              <a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#harishMamtani"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#harishMamtani">HARISH MAMTANI</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#harishMamtani">Founder</a></p>
          </div>
         <a href="https://www.linkedin.com/in/harishmamtani/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>									
							

        <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/jaysheth.png')}}" alt="">
              <a href="javascript:void(0" class="full-box-link position-absolute" data-toggle="modal" data-target="#JaySheth"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#JaySheth">JAY SHETH</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#JaySheth">Founder and Advisor</a></p>
          </div>
         <a href="https://www.linkedin.com/in/jaysheth" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>																	
        
      </div>
</section-->

	

<!--section>
	
      <div id="testimonial-slider1" class="owl-carousel">

      	<div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/Bharatr.jpg')}}" alt="">
              <a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#Bharathnistala"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#Bharathnistala">BHARAT NISTALA</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#Bharathnistala">Business Development<br>Manager</a></p>
          </div>
		<a href="https://www.linkedin.com/in/bharat-nistala-b1b599a2/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>

      	<div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/Pushprajr.png')}}" alt="">
              <a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#pushparaj"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#pushparaj">PUSHPARAJ GANPAT</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#pushparaj">Associate Manager</a></p>
          </div>
         <a href="https://www.linkedin.com/in/pushpraj-ganpat/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>

  		 <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/jyothir.jpg')}}" alt="">
              <a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#jyoti"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#jyoti">JYOTI KAMAAL</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#jyoti">Freelancer - CRM</a></p>
          </div>
         <a href="https://www.linkedin.com/in/jyotikamaal/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>


          <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/arpithar.jpg')}}" alt="">
              <a href="javascript:void(0)" data-toggle="modal" data-target="#arpitha" class="full-box-link position-absolute"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#arpitha">ARPITHA AGGITAKALYA</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#arpitha">HR Manager</a></p>
          </div>
        <a href="https://www.linkedin.com/in/arpithaaggitakalya" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>	

             <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/vamshi.JPG')}}" alt="">
              <a href="javascript:void(0)" data-toggle="modal" data-target="#vamshi" class="full-box-link position-absolute"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#vamshi">Vamshi Goli</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#vamshi">Lead Developer</a></p>
          </div>
        <a href="https://www.linkedin.com/in/vamshi-goli-520b584a" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>	

         <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/sandeepr.jpg')}}" data-target="#JaySheth" alt="">
             <a href="javascript:void(0)" class="position-absolute full-box-link" data-toggle="modal" data-target="#sandeep"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#sandeep">JALAGAM SANDEEP</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#sandeep">Software Developer</a></p>
          </div>
		<a href="https://www.linkedin.com/in/sandeep-jalagam-88111a107/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>			
        </div>
	

		<div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/anamr.jpeg')}}" alt="">
              <a href="javascript:void(0)" class="full-box-link position-absolute" data-toggle="modal" data-target="#anam"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#anam">MOHAMMAD ANAM</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#anam">Software Developer</a></p>
          </div>
         <a href="https://in.linkedin.com/in/mohammadanam" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>									
							

        <div class="testimonial">
          <div class="testimonial-content">
            <div class="pic">
              <img src="{{asset('front_new/images/team/vamsir.png')}}" alt="">
              <a href="javascript:void(0" class="full-box-link position-absolute" data-toggle="modal" data-target="#vamsi"></a>
            </div>
            <p class="name"><a href="javascript:void(0)" data-toggle="modal" data-target="#vamsi">VAMSI KRISHNA</a></p>
            <p class="title"><a href="javascript:void(0)" data-toggle="modal" data-target="#vamsi">Software Developer</a></p>
          </div>
         <a href="https://www.linkedin.com/in/vamsi-krishna-764653190/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>	
										
						
      </div>
</section>

<script type="text/javascript">
  $(document).ready(function() {
  $("#testimonial-slider").owlCarousel({
    items: 4,
    itemsDesktop:[1000,4],
    itemsDesktopSmall:[979,2],
    itemsTablet:[768, 2],
    itemsMobile:[650, 1],
    pagination: true,
    autoPlay: false
  });
});
</script>

<script type="text/javascript">
  $(document).ready(function() {
  $("#testimonial-slider1").owlCarousel({
    items: 4,
    itemsDesktop:[1000,4],
    itemsDesktopSmall:[979,2],
    itemsTablet:[768, 2],
    itemsMobile:[650, 1],
    pagination: true,
    autoPlay: false
  });
});
</script>

<--/section-->

<section class="career-opening">

	<div class="container">

		<div class="the-title text-center" data-aos="zoom-in" data-aos-duration="2000">

			<!--<h2>Career Openings</h2>-->

			<a href="{{config('app.url')}}careers">Career Openings</a>

		</div>

		<!--<ul class="post-of">-->

		<!--	<li data-aos="fade-right" data-aos-duration="1000"><a href="{{config('app.url')}}careers">1. Front End Developer</a></li>-->

		<!--	<li data-aos="fade-left" data-aos-duration="1500"><a href="{{config('app.url')}}careers">2. Backend Developer</a></li>-->

		<!--</ul>-->

	</div>

</section>
	
<section class="contact-us-se" id="contact-us">

	<div class="container">

		<div class="row align-items-center">

			<div class="col-12 col-md-6 col-lg-6 col-xl-6 text-white" data-aos="fade-right" data-aos-duration="1000">

				<p>

				    <address class="mb-0">
				        <p>Recordent Private Limited,</p>
						
						<p>Aditya Trade Center,Office No:-7-1-618/ACT/710,</p>

                        <p>Seventh Floor,Ameerpet,</p> 

                        <p>Hyderabad, Telangana,</p>

                        <p>India Pincode - 500038</p>
                        
                        <p>888 6634 100</p>

				    </address>

				</p>

			</div>

			<div class="col-12 col-md-6 col-lg-6 col-xl-6" data-aos="fade-left" data-aos-duration="1500">

				<div class="contact-from contact-from-plaintext">

					<p>Contact Us</p>

					{{--<form id="contact-form" name="contact-form">

						<div class="form-group">  

							<input type="name" class="form-control" aria-describedby="" placeholder="Name">                                    

						</div>

						<div class="form-group">  

							<input type="email" class="form-control" aria-describedby="" placeholder="Email ID">                                    

						</div>

						<div class="form-group">  

							<input type="text" class="form-control" aria-describedby="" placeholder="Mobile Number">                                    

						</div>

						<div class="form-group">

							<textarea placeholder="Message" class="form-control"></textarea>

						</div>

						<div class="text-center">

							<button type="submit" class="btn-send">send</button>

						</div>                                

					</form>--}}

					@include('front/aboutus/contactus')

				</div>

			</div>

		</div>

	</div>

</section>

<section class="join-us-journey">

	<div class="container">

		<div class="the-title text-center" data-aos="zoom-in" data-aos-duration="2000">

			<h2>Join Us in the Journey of <br class="cmd-d-none"> Co-writing the Growth Story </h2>

		</div>

		<div class="journey-points csd-d-none">

			<div class="d-flex justify-content-between flex-wrap">

				<div class="start-points" data-aos="zoom-out-left" data-aos-duration="1000">

					<div class="d-flex">

						<div class="number d-flex align-items-center justify-content-center">

							<p>1</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Co-Creation journey</h3>

							<p>We co-create culture through engaging conversations and discussions. </p>

							<p>Intellectual entrepreneurial mindsets with a passion to write the success story, take responsibility to participate in all facets of creating a dynamic organization.</p>

						</div>

					</div>

				</div>

				<div class="start-points" data-aos="zoom-out-right" data-aos-duration="1500">

					<div class="d-flex">

						<div class="number d-flex align-items-center justify-content-center">

							<p>2</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Innovation</h3>

							<p> We bring innovation each day to our work through technology that drives frugal and diverse ideas and tangible solutions.  </p>

							<p>We 'Never Say No' to challenges and collaborate to solve them to enhance our learning and success.</p>

						</div>

					</div>

				</div>

				<div class="start-points" data-aos="zoom-in-left" data-aos-duration="2000">

					<div class="d-flex">

						<div class="number d-flex align-items-center justify-content-center">

							<p>3</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Inspiring</h3>

							<p>We love what we do and inspire and empower each other to leap forward and embrace new beginnings.</p>

							<p>Trust and self-belief  make us 'Go the extra mile' to invest our best at work   every day.</p>

						</div>

					</div>

				</div>

				<div class="start-points" data-aos="zoom-in-right" data-aos-duration="1500">

					<div class="d-flex">

						<div class="number d-flex align-items-center justify-content-center">

							<p>4</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Unleash the 'Power In Us'</h3>

							<p>We build solutions and services that act as the catalyst for the business of our clients by bringing together the power of our brilliantly diverse backgrounds and unleash the 'Power In Us'.</p>

						</div>

					</div>

				</div>

				<div class="start-points" data-aos="zoom-out-left" data-aos-duration="1000">

					<div class="d-flex">

						<div class="number d-flex align-items-center justify-content-center">

							<p>5</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Living Unlimited Experiences </h3>

							<p>We live unlimited, far-reaching experiences each day to build a meaningful life by connecting us with each other and our clients and partners through every moment at work.</p>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="container csd-full-width">

		<div class="journey-points nd-none csd-d-block">

			<div class="owl-carousel owl-theme journey-points-slider">

				<div class="item">

					<div class="start-points">

						<div class="number d-flex align-items-center justify-content-center">

							<p>1</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Co-Creation journey</h3>

							<p>We co-create culture through engaging conversations and discussions. </p>

							<p>Intellectual entrepreneurial mindsets with a passion to write the success story, take responsibility to participate in all facets of creating a dynamic organization.</p>

						</div>

					</div>

				</div>

				<div class="item">

					<div class="start-points">

						<div class="number d-flex align-items-center justify-content-center">

							<p>2</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Innovation</h3>

							<p> We bring innovation each day to our work through technology that drives frugal and diverse ideas and tangible solutions.  </p>

							<p>We 'Never Say No' to challenges and collaborate to solve them to enhance our learning and success.</p>

						</div>

					</div>

				</div>

				<div class="item">

					<div class="start-points">

						<div class="number d-flex align-items-center justify-content-center">

							<p>3</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Inspiring</h3>

							<p>We love what we do and inspire and empower each other to leap forward and embrace new beginnings.</p>

							<p>Trust and self-belief  make us 'Go the extra mile' to invest our best at work   every day.</p>

						</div>

					</div>

				</div>

				<div class="item">

					<div class="start-points">

						<div class="number d-flex align-items-center justify-content-center">

							<p>4</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Unleash the 'Power In Us'</h3>

							<p>We build solutions and services that act as the catalyst for the business of our clients by bringing together the power of our brilliantly diverse backgrounds and unleash the 'Power In Us'.</p>

						</div>

					</div>

				</div>

				<div class="item">

					<div class="start-points">

						<div class="number d-flex align-items-center justify-content-center">

							<p>5</p>

						</div>

						<div class="point-detail">

							<h3 class="font-weight-bold">Living Unlimited Experiences </h3>

							<p>We live unlimited, far-reaching experiences each day to build a meaningful life by connecting us with each other and our clients and partners through every moment at work.</p>

						</div>

					</div>

				</div>                        

			</div>

		</div>

	</div>

	<div class="container">

		<div class="core-value-points core-value-points-plaintext">

			<div>

				<p class="font-weight-bold" data-aos="fade-down" data-aos-duration="1500">At Recordent, we challenge conventional thinking and promote an alternative stretch thinking approach to drive a positive impact on society. </p>

				<p data-aos="fade-up" data-aos-duration="1500">If you resonate with our 'Never Say It's Enough' spirit, then join us in this incredible journey of experiential learning that encompasses our core values of</p>

			</div>

			<div class="main-core-point text-uppercase"  data-aos="fade-right" data-aos-duration="1500">

				<span>TRANSPARENCY</span> <span>|</span> <span>AGILITY </span> <span>|</span><span>RESPECT </span>

				<span>GRATITUDE </span> <span>|</span> <span>EXCELLENCE</span> <span>|</span> <span>TRUST</span>

			</div>

		</div>

	</div>

</section>


<div id="ramanaKrishna" class="modal fade commap-team-popup" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					<p>RAMANA KRISHNAN</p>
					<p>Head - BD & Memberships</p>
				</h4>
			</div>
			<div class="modal-body">
				<div class="team-photo"> <img src="{{asset('front_new/images/team/ramana_krishnan.jpg')}}" alt=""> </div> 
				<div class="team-desc">
				   <ul>

						<li>Ramana Krishnan has over 9 years of experience across MNCs, SMEs, and Start-ups, heading new initiatives, managing national level BD & operations, and driving growth</li>

						<li>As the COO, of Autograde - an automobile equipment manufacturer SME, he helped grow its distribution by 700% to a pan-India presence</li>

						<li>He was instrumental in government level law regulations in UAE and Kenya in the field of road safety</li>

						<li>He co-founded an Education Technology startup with the aim of providing a broader metric for evaluation of a child's progress from merely a marks based approach</li>
						<li>At Tata Steel, he was instrumental in changing the distribution business of the region to a value-selling approach in a commoditised market</li>
						<li>He did his PGDM from IIM Calcutta and B.E. from NSIT, Delhi (now NSUT)</li>

					</ul>
				</div>
			</div>
		</div>
	</div>

</div>

<div id="tarunRumalla" class="modal fade commap-team-popup" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					<p>TARUN RUMALLA</p>
					<p>Head - Member Services</p>
				</h4>
			</div>
			<div class="modal-body">
				<div class="team-photo"> <img src="{{asset('front_new/images/team/tarun_rumalla.jpg')}}" alt=""> </div> 
				<div class="team-desc">
					<ul>
					<li>He comes with a rich experience of 15+ years in Credit & Risk management, Strategy, Account receivables management and Sales & Marketing.</li>   

					<li>His latest stint was with JM Financial, in the capacity of Head- Credit, where he <b>set up the entire business model,</b> underwriting policy, processes and systems, in a very short span of time the business grew to an <b>INR 200 + Crore AUM with absolutely nil overdue/NPAS.</b></li>
				</ul>
				</div>
			</div>
		</div>
	</div>

</div>

<div id="kotesh" class="modal fade commap-team-popup" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					<p>KOTESWARA RAO</p>
					<p>Head of Technology</p>
				</h4>
			</div>
			<div class="modal-body">
				<div class="team-photo"> <img src="{{asset('front_new/images/team/Kotesh.jpg')}}" alt=""> </div> 
				<div class="team-desc">
					<ul>
				<li>Kotesh comes with 14 Years of Experience in Managing Digital Solutions across various domains ranging from Banking, Field Services, Airline and Healthcare.</li>   

				<li>He comes with a vast experience in handling Complex Technical Implementations.</li>

				<li>In his recent Journey with Kony/Temenos, he was instrumental in taking an omni channel application live to the market in a short span of 7 Months which contributed to 3M+ Dollars in Revenue from a single account.</li>

				<li>He is an Alumnus of BITS - Pilani, Rajasthan, in B.E. (Hons) Computer Science.</li>
				</ul>
				</div>
			</div>
		</div>
	</div>

</div>


<div id="harishMamtani" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>HARISH MAMTANI</p>

					<p>Founder</p>

				</h4>

			</div>

			<div class="modal-body">              

				<div class="team-photo"> <img src="{{asset('front_new/images/team/harish.png')}}" alt=""> </div>    

				<div class="team-desc">

					<h4></h4>

					<ul>

						<li>Harish has 25+ years of experience in technology, finance, investing, and wealth management.</li>

						<li>He has managed over <b>$1.5 billion as a Wealth Advisor</b> at Merrill Lynch, Morgan Stanley, Bank of America, and Bluefish Capital.</li>

						<li>Prior to SEED (2013) and Recordent (2018), Harish was an advisor to Gray Ghost Ventures and spent a year as interim <b>CEO of Indian School Finance Company.</b></li>

						<li>Harish has served on the boards of <b>TiE Global Board of Trustees,</b> TiE Atlanta and Venture Atlanta. Currently, Harish serves on the Board of the Atlanta CEO Council.</li>

					</ul>

				</div>                

			</div>

		</div>      

	</div>

</div>



<div id="JaySheth" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p> JAY SHETH </p>

					<p>Founder and Advisor</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/jaysheth.png')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Jay Sheth is a global business leader with experience in leading transformative telecom and software based service businesses</li>

						<li>In 2019 Jay executed a successful exit for Karix India - a Blackstone promoted entity</li>

						<li>Jay led Karix from inception to become the market leader for mobile messaging services in India; serving over 1500 blue chip enterprises; achieving  over 40% CAGR growth in annual revenues; and similar year on year positive EBITDA growth over the preceding 10 years.</li>

						<li>Previously Jay was the Global CEO of mGage LLC with operations in USA, UK, Netherlands, Greece, UAE and India</li>

						<li>Jay has also served in executive positions at General Motors OnStar, GTE Wireless (now Verizon) and Sprint </li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>



<div id="WinnyPatro" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>WINNY PATRO</p>

					<p>CEO & Co-Founder</p>  

				</h4>

			</div>

			<div class="modal-body">              

				<div class="team-photo"> <img src="{{asset('front_new/images/team/winny.png')}}" alt=""> </div>

				<div class="team-desc">

					<ul>

						<li> Winny Patro has 10+ years of experience in public sector, entrepreneurship, business consulting and coaching.</li>

						<li>Till recently, Winny Patro served the Government of Andhra Pradesh (Indian State) in <b>CEO</b> position and <b>transformed two under-performing Govt organizations</b> into a very high performing ones in less than one year.</li>

						<li> Prior to Govt. of AP, he spearheaded projects in setting up of <b>e-commerce businesses for global & national retailers in sports</b> & fashion goods.</li>

						<li> Earlier, Patro <b>founded an NGO</b> to build life skills among underprivileged children and <b>co-founded 2 ventures</b> in digital space</li>

						<li>He post graduated from <b>IIM Calcutta</b> with 2 year full time MBA</li>

					</ul>

				</div>

			</div>

		</div>



	</div>

</div>


<div id="ManishAjwani" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>MANISH AJWANI</p>

					<p>Head of Products</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/manish.png')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Helming products for a large credit bureau and fintech, Manish comes with 20+ years of experience in Product Management, Software Development, Data, Technology, Analytics, Operations and Customer Service.</li>

						<li>He spearheaded the adoption and growth of global product in India, during his stint at Clearscore. His product strategies helped improve the cost of user acquisition, product engagement and revenue by 11x, 7x and 10x, respectively.</li>

						<li>At TransUnion CIBIL, he was a part of the Direct to Consumer core team where he developed products and solutions which contributed to 50% of the revenue.</b></li>

						<li>With an in-depth knowledge about the market and concocting the best in class products and solution, he is on a journey to create financial well-being of individuals and businesses.</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="Bharathnistala" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>BHARAT NISTALA</p>

					<p>Business Development Manager</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/Bharatr.jpg')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Bharat is 5+ years experienced Consultant with a demonstrated history of working in both Corporate and Government set-up.Skilled in Negotiation, Operations, Sales and Management.</li>

						<li>Headed management of SAAP Sports Academies and over a 50+ Government Schools under Grass roots Sports training program across Andhra Pradesh while working with TENVIC Sports Edu Pvt Ltd as Operations Manager.</li>

						<li>Onboarded over 180 companies with over 1000 Openings in the Govt online Job Portal while working with Andhra Pradesh Innovation Society.</b></li>

						<li>Coordinated over 50+ Recruitment drives conducted by Govt of AP under LEAP initiative.</li>

						<li>Retained 96% of responsible base of Corporate Accounts for Bharti Airtel when Telecom Industry was struck with the launch of Jio 4G offering free Internet services.</li>

						<li>Demonstrated achiever with exceptional knowledge of markets and business practices.</li>

						<li>He is a graduate in Bachelor’s Degree focused in Electrical and Electronics Engineering from JNTUK.</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="pushparaj" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>PUSHPARAJ GANPAT</p>

					<p>Associate Manager</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/Pushprajr.png')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Experienced Business Development Specialist with a demonstrated history of working in the education management industry. Skilled in Negotiation, Strategic Planning, Business Development, Wireless Technologies, and Telecommunications. Strong business development professional graduated from Osmania University.</li>

						
					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="jyoti" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>JYOTI KAMAAL</p>

					<p>Freelancer - CRM</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/jyothir.jpg')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Jyoti Kamaal,expertise as a Customer Experience Evangelist, 22 years experience with Luxury brands in hospitality and retail industry globally. Forte is to design dynamic, AI / EQ, intelligent CX business acumen call centers, CRM digital applications in US (Miami), Italy (Milan), Middle East (Dubai) and  12 cities in India
						Professionally talented to optimize a customer centric organizational culture.
						Strength is conceptualize a CRM data onsite and online business.
						Initiate to develop and operate a personalized CRM business framework, to attain data and quality at all digital customer connects, human touch points and front line executive level.
						</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="arpitha" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>ARPITHA AGGITAKALYA</p>

					<p>HR Manager</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/arpithar.jpg')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Arpitha has 5+ years experiencein  HumanResources with a demonstrated history of working in the Information technology and Services industry.</li>

						<li>She has an array of skills in Policy Formation, Employee Lifecycle Management,recommending and implementing action plan strategies to meet HR needs.</li>

						<li>Expertise in Employee Engagement, Learning and Development, Performance Tuning, Campus Recruitment, Niche Talent Acquisition.</li>

						<li>She is a Post Graduate with 2 years full time M.B.A - International Business focused in Foreign Trade, Human Resource Management, Marketing Management, Supply Chain Management from GITAM School of International Business.</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="drishya" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>Drishya Goyel</p>

					<p>Chief - Growth & Partnerships</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/Drishya.jpg')}}" style="margin-top: -25px;" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Drishya has over 11 years of experience across BFSI and Entrepreneurship.</li>

						<li>She has worked with Barclays, India in various capacities across Retail Products (Managing the Secured Assets Portfolio), Corporate & Investment Banking Strategy and in her last role at Barclays, she was Relationship Manager, Corporate Banking for conglomerates such as Godrej, Adani and Shriram Groups.</li>

						<li>Post her stint at Barclays, she founded a B2B food company, Hor Kidda Foods Pvt Ltd, and led the company profitably and with a 200% Y-o-Y growth. Her clients included marquee names such as Avendus Capital, Marico, Mastercard, PayPal, WeWork, CoWrks and Bytedance in less than 2 years.</li>

						<li>She completed her PGDM from IIM Calcutta and B. Com (Honours) from Calcutta University. She has also cleared CA – IPCC.</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="harshika" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>HARSHIKA KAMDAR</p>

					<p>Associate Product Manager</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/harshika.JPG')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Harshika is a Computer Engineer from Thadomal Shahani Engineering College, Mumbai.</li>

						<li>She has previously worked with Advarisk. She was responsible for the product flow, designing wireframes, UI/UX design and strategy and some part of data analysis.</li>

						<li>She has done many projects in Product Management and data analysis during her engineering. She has a skillset around Product Management Lifecycle and some data visualization tools and techniques.</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="madhuri" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>MADHURI BEERA</p>

					<p>Quality Analyst</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/madhuri.jpg')}}" style="margin-top: -25px;" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Madhuri Beera, has extensive working experience on Product based Applications related to Indian taxation domain.</li>

						<li>2.6 + years of experience in identification of the defects of product application, customer/client training and application support.</li>

						<li>Involved into testing of UAT feedback/issues and error handling also Good team player with good communication and interpersonal skills.</li>

						<li>Hard working, Self- motivated person and a Quick learner. Graduated B.Tech in Information Technology from Sri Indu college of Engineering, Hyderabad.</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="balachandra" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p> BALACHANDRA RAO </p>

					<p> Senior UX Designer</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/balachandra.JPG')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Balachandra has 10+ years’ experience across print and design industry where he’s leveraged best in class design tools for creating website and applications.</li>

						<li>In his last stint he has developed the ‘The Best Salon Finding App’ creating a world class user journey to find salons nearby and book appointments.</li>

						<li>During his tenure at Outlook and Mid-day, he was responsible for creating mailer designs for print mail.</li>

						<li>He is a graduate in Business Studies from Annamalai University, Chennai and certified in VISCOM and Advanced UX / UI Design.</li>


					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="sabjan" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p> SABJAN SYED </p>

					<p>Software Developer</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/sabjan.jpeg')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Sabjan has 4+ years of  experience in development ( full stack developer).</li>

						<li>He has good knowledge and command over Codeigniter , Laravel frameworks php, mysql and Rest Full API's.</li>

						<li>He is always keen on learning new technologies and a quick learner who understands existing code very quickly.</li>

						<li>He is good at Program Troubleshooter ,Debugging and  Testing.</li>

						<li>Sabjan is a B. Tech in Electronics and Communications Engineering from Sai Sakthi Engineering  College under JNTUA .</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>


<div id="vamshi" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>Vamshi Goli</p>

					<p>Lead Developer</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/vamshi.JPG')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Vamshi has 6+ years’ experience in  creating dynamic websites and business applications using different programming languages, including HTML/CSS, Javascript, PHP, and SQL.</li>

						<li>He handles a wide range of duties such as designing, developing, and executing programs. Possess solid knowledge of entire software development life-cycle, and capable of preparing as well as maintaining technical documents.</li>

						<li>Expertise in designing, developing, executing, testing, and debugging dynamic web pages or applications as well as performing modifications in existing code to add new features.</li>

						<li>Vamshi is a Graduate in Bachelor of Technology in Electronics and Communications Engineering from Christu Jyoti Institute of Engineering & Technology.</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="sandeep" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>JALAGAM SANDEEP</p>

					<p>Software Developer</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/sandeepr.jpg')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Sandeep has 3+ years of experience as a Full Stack Developer and a Learner, familiar with a wide range of programming utilities & languages. Knowledgeable of backend and frontend development requirements. Good Team Player. Sandeep always looks for creative and innovative solutions, latest tools, best practices and methodologies related to the software development, keeping the code efficient, simple, following design-patterns, testable and scalable.</li>

						<li>Sandeep is Graduate in Bachelor of Technology in Digital Techniques for Design and Planning from JNAFAU.</li>

						

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

<div id="anam" class="modal fade commap-team-popup" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">

					<p>MOHAMMAD ANAM</p>

					<p>Software Developer</p>

				</h4>

			</div>

			<div class="modal-body">

				<div class="team-photo"> <img src="{{asset('front_new/images/team/anamr.jpeg')}}" alt=""> </div>    

				<div class="team-desc">

					<ul>

						<li>Anam has over 1.5+ years of experience in Software Development.
						He is Strong in design and integration problem-solving skills. Expert in PHP, Codeigniter, Laravel Framework.</li>

						<li>He is B.Tech graduate focussed in Information Technology from MGIT, Hyderabad.</li>

					</ul>

				</div>    

			</div>

		</div>      

	</div>

</div>

@endsection

