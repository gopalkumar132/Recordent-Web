@extends('layouts_front_new.master')
@section('meta-title', config('seo_meta_tags.security_page.title'))
@section('meta-description', config('seo_meta_tags.security_page.description'))
@section('canonical-url')
    <link rel="canonical" href="{{config('app.url')}}security" />
@endsection
@section('content')

<style type="text/css">

@media only screen and (min-width:320px) and (max-width:426px) {
  .green-tick-1 {
    position: absolute;
    margin-top: 10px;
  }

  .green-tick-2 {
    position: absolute;
    float: left;
    margin-top: 130px;
    display: block;
  }
  .green-tick-3 {
    position: absolute;
    float: left;
    margin-top: 71px;
    display: block;
  }
  .green-tick-4 {
    margin-top: 194px;
    position: absolute;
    display: block;
  }

  .p-1 {
    float: left;
    margin-left: 37px;
  }

  .p-2 {
    float: left;
    margin-left: 32px;
    margin-top: 0px;
  }

  .p-3 {
    float: left;
    margin-left: 26px;
    margin-top: -13px;

  }

  .p-4 {
    float: left;
    margin-left: 20px;
    margin-top: -25px;
    display: block;
  }


}

@media only screen and (min-width:427px) and (max-width:525px) {
  .green-tick-1 {
    position: absolute;
    margin-top: 10px;
  }

  .green-tick-2 {
    position: absolute;
    float: left;
    margin-top: 105px;
    display: block;
  }
  .green-tick-3 {
    position: absolute;
    float: left;
    margin-top: 79px;
    display: block;
  }
  .green-tick-4 {
    margin-top: 10px;
    position: absolute;
    display: block;
  }

  .p-1 {
    float: left;
    margin-left: 37px;
  }

  .p-2 {
    float: left;
    margin-left: 32px;
    margin-top: 0px;
  }

  .p-3 {
    margin-left: 26px;
    margin-top: 0px;

  }

  .p-4 {
    float: left;
    margin-left: 20px;
    margin-top: -15px;
    display: block;
  }

}

@media only screen and (min-width:526px) and (max-width:767px) {

  .green-tick-1 {
    position: absolute;
    margin-top: 10px;
  }

  .green-tick-2 {
    position: absolute;
    float: left;
    margin-top: 45px;
    display: block;
  }
  .green-tick-3 {
    position: absolute;
    float: left;
    margin-top: 79px;
    display: block;
  }
  .green-tick-4 {
    margin-top: 10px;
    position: absolute;
    display: block;
  }

  .p-1 {
    float: left;
    margin-left: 37px;
  }

  .p-2 {
    float: left;
    margin-left: 32px;
    margin-top: 0px;
  }

  .p-3 {
    margin-left: 26px;
    margin-top: 0px;

  }

  .p-4 {
    float: left;
    margin-left: 20px;
    margin-top: -15px;
    display: block;
  }



}

@media only screen and (min-width:768px) and (max-width:991px) {

  .green-tick-1 {
    position: absolute;
    margin-top: 10px;
  }

  .green-tick-2 {
    position: absolute;
    float: left;
    margin-top: 72px;
    display: block;
  }
  .green-tick-3 {
    position: absolute;
    float: left;
    margin-top: 130px;
    display: block;
  }
  .green-tick-4 {
    margin-top: 188px;
    position: absolute;
    display: block;
  }

  .p-1 {
    float: left;
    margin-left: 37px;
  }

  .p-2 {
    float: left;
    margin-left: 32px;
    margin-top: 0px;
  }

  .p-3 {
    margin-left: 26px;
    margin-top: -13px;
    float: left;

  }

  .p-4 {
    float: left;
    margin-left: 20px;
    margin-top: -30px;
    display: block;
  }
}

@media only screen and (min-width:992px) and (max-width:1199px) {

  .green-tick-1 {
    position: absolute;
    margin-top: 10px;
  }

  .green-tick-2 {
    position: absolute;
    float: left;
    margin-top: 72px;
    display: block;
  }
  .green-tick-3 {
    position: absolute;
    float: left;
    margin-top: 105px;
    display: block;
  }
  .green-tick-4 {
    margin-top: 135px;
    position: absolute;
    display: block;
  }

  .p-1 {
    float: left;
    margin-left: 37px;
  }

  .p-2 {
    float: left;
    margin-left: 32px;
    margin-top: 0px;
  }

  .p-3 {
    margin-left: 26px;
    margin-top: -13px;
    float: left;

  }

  .p-4 {
    float: left;
    margin-left: 20px;
    margin-top: -30px;
    display: block;
  }
}
@media only screen and (min-width:1200px) and (max-width:1500px) {

  .green-tick-1 {
    position: absolute;
    margin-top: 10px;
  }

  .green-tick-2 {
    position: absolute;
    float: left;
    margin-top: 45px;
    display: block;
  }
  .green-tick-3 {
    position: absolute;
    float: left;
    margin-top: 80px;
    display: block;
  }
  .green-tick-4 {
    margin-top: 110px;
    position: absolute;
    display: block;
  }

  .p-1 {
    float: left;
    margin-left: 37px;
  }

  .p-2 {
    float: left;
    margin-left: 32px;
    margin-top: 0px;
  }

  .p-3 {
    margin-left: 26px;
    margin-top: -13px;
    float: left;

  }

  .p-4 {
    float: left;
    margin-left: 20px;
    margin-top: -30px;
    display: block;
  }

}

@media only screen and (min-width:1501px) and (max-width:2500px) {

  .green-tick-1 {
    position: absolute;
    margin-top: 10px;
  }

  .green-tick-2 {
    position: absolute;
    float: left;
    margin-top: 45px;
    display: block;
  }
  .green-tick-3 {
    position: absolute;
    float: left;
    margin-top: 80px;
    display: block;
  }
  .green-tick-4 {
    margin-top: 110px;
    position: absolute;
    display: block;
  }

  .p-1 {
    float: left;
    margin-left: 37px;
  }

  .p-2 {
    float: left;
    margin-left: 32px;
    margin-top: 0px;
  }

  .p-3 {
    margin-left: 26px;
    margin-top: -13px;
    float: left;

  }

  .p-4 {
    float: left;
    margin-left: 20px;
    margin-top: -30px;
    display: block;
  }  
}

@media (min-width: 768px) {
.text-center-2 {margin-top: 40px;margin-left: 235px;}
}
@media (min-width: 992px){
.text-center-2 {margin-left: 300px;}
}
@media (min-width: 1200px){
.text-center-2 {margin-left: 360px;}
}





</style>

<section class="data-security">
            <div class="container">
                <div class="the-title text-center" >
                    <h2>Data Security</h2>
                    <h3 style="margin-top: 20px;">Securing our Member's Data is of utmost priority</h3>
                </div>
                <div class="b-s-25"></div>
                <div class="row security-points">
                    <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="text-center position-relative">
                            <div class="security-img">
                                <img src="{{asset('front_new/images/data_secu_01.png')}}" alt="">
                            </div>
                            <h3>AES-256</h3>
                            <div class="b-s-25"></div>
                            <p>As per Industry Standards, all the Personal Identifiable Information (PII) Data, both at Rest and in Transit is encrypted for additional protection.</p>
                            <p>Explicit Consent mechanisms have been built for others to view a specific Member's data.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="text-center">
                            <div class="security-img">
                                <img src="{{asset('front_new/images/data_secu_02.png')}}" alt="">

                            </div>
                            <div class="b-s-25"></div>
                            <ul style="font-size: 20px;text-align: left;line-height: 26px;">
                            <img src="{{asset('front_new/images/green_tick.png')}}" height="18px" width="20px" class="green-tick-1" alt="" style="margin-right: 15px;"><li><span class="p-1">Data is Highly Available only to appropriate users</span></li>
                            <img src="{{asset('front_new/images/green_tick.png')}}" height="18px" width="20px" class="green-tick-2" alt="" style="margin-right: 15px;"><li><span class="p-2">Authorized Members can only Ingest Data</span></li>
                            <img src="{{asset('front_new/images/green_tick.png')}}" height="18px" width="20px" class="green-tick-3" alt="" style="margin-right: 15px;"><li><span class="p-3">Data Integrity maintained at all times</span></li>
                            <img src="{{asset('front_new/images/green_tick.png')}}" height="18px" width="20px" class="green-tick-4" alt="" style="margin-right: 15px;"><li><span class="p-4">Data Confidentiality is implemented with prior approval from Members</span></li>
                            </ul>
                            <!--<p>Recordent members or any other third party may not access other members speciﬁc customer PII information unless the customer speciﬁcally grants permission to the inquiring member/party for a speciﬁc use case.</p>
                            <p>Permissions are obtained form the customer and veriﬁed through the use of OTP and magic links.</p>-->

                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 col-xl-6 text-center-2">
                    <div class="text-center" style="text-align-last: left;font-size: 20px;">

                      <b><li style="text-align: left;list-style: none;">Recordent Maintains Data Security Compliance with:</li></b>
                      <li style="margin-left: 15px;">End Point Protection</li>
                      <li style="margin-left: 15px;">Data Loss Prevention Policies</li>
                      <li style="margin-left: 15px;">Intrusion Detection</li>
                      <li style="margin-left: 15px;">Access Controls</li>
                    </div>
                    </div>

                </div>
            </div>
        </section>
@endsection
