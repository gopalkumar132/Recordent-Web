@extends('voyager::master')

@section('page_title', __('Recordent - Sample Notifications'))

@section('page_header')

@stop

@section('content')

<head>
        <!--<link rel="stylesheet" type="text/css" href="resource/css/style.css">-->
        <!--<link rel="stylesheet" type="text/css" href="resource/queries.css">-->

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
    </head>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/*html {
    width: 1510px;

}*/

.link {
    color: #fff;

}

.sms-overdue-heading { text-decoration: none !important; }
/*------------------SMS----------------------*/

.table {
    display: inline-table;
    }
.table thead  {
    width:750px;
    height: 40px;
    display: inline-table;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.table>thead:first-child>tr:first-child>th {
  background-color: rgb(71 142 195);
  border-radius: 10px;
}

}

.table thead tr th a {
    color: #fff;
    text-decoration: none;

}
.paragraph-2 {
    font-size: 20px;
    margin-bottom: 20px;
}

.body-table {
    display: inline-table;
    padding-left: 30px;
    width: 600px;
    margin-bottom: 30px;
}

.body-table tbody  {
    border: 3px solid #000;
    text-align: justify;
}

.body-table tbody tr td:not(:last-child) {
    /*padding-right: 90px;*/
    border: 3px solid #000;
	width: 120px;
}

.body-table tbody tr td:not(:first-child) {
    /*padding-right: 10px;
    padding-top: 0px;*/
	padding: 0px 10px 0px 10px;


}
.body-table tbody tr td {
    border: 3px solid #000;
    padding: 20px 0px 0px 0px;
    hyphens: auto;
    -webkit-hyphens: auto;
}
/*------------------Heading-----------------------*/


.heading {
    text-align: center;
}

.h3 {
    margin-bottom: 10px;
    }

.heading a {
    cursor: pointer;
    }
.heading-1, .heading-2, .heading-3 {
    margin-top: 150px;
    background-color: #478ec3;
    text-align: center;
    display: inline-block;
    padding: 10px  300px 10px 300px;
    margin-bottom: 50px;
	border-radius: 15px;
}

.heading-2 {
    margin-top: 0px;
}
.heading-3 {
    margin-top: 0px;
}

/*------------------Email-----------------------*/

.row-2 {
    text-align: center;

    }


.row-pragraph {
    margin-bottom: 40px;
    display: inline-block;
    margin-left: 18%;
}

.pragraph {
    text-align: justify;
    hyphens: auto;
    -webkit-hyphens: auto;

}
/*-----------------Media Queries-----------------*/

	@media only screen and (max-width: 320px) {
		.paragraph-2 {
			font-size: 17px;
		}

		.table {
			margin-left: 0px;
		}

		.table thead  {
			width: 500px;
		}

		.body-table {
			margin-bottom: 50px;
		}
	}




	@media only screen and (max-width: 360px) {
		.heading-1, .heading-2, .heading-3 {
			padding: 10px  40px 10px 40px;
			margin-bottom: 50px;
			margin-left: 0px
			border-radius: 15px;
			width:389px;
		}
		.paragraph-2 {
			font-size: 15px;
		}

		.table {
			margin-left: 20px;
		}

		.table thead  {
			width: 550px;
			margin-left: 0px;
		}
		.body-table {
			padding-right: 30px;
			margin-bottom: 20px;
		}
		.body-table tbody tr td:not(:last-child) {
			padding: 0px 60px 10px 10px;
			width: 120px;

		}
		.body-table tbody  {
			text-align: start;
		}
		.row-pragraph {
			margin-left: 28 %;
	}
	}

	@media only screen and (min-width: 361px) and (max-width: 414px) {
		.heading-1, .heading-2, .heading-3 {
			padding: 10px  100px 10px 100px;
			border-radius: 15px;
			width:430px;
		}
		.paragraph-2 {
			font-size: 17px;
		}

		.table thead  {
			width: 600px;

		}
		.body-table tbody tr td:not(:last-child) {
			padding: 0px 80px 10px 10px;
			width: 120px;
		}

	}


	@media only screen and (min-width: 414px) and (max-width: 768px) {
		.heading-1, .heading-2, .heading-3 {
			padding: 10px  150px 10px 150px;
			border-radius: 15px;
			width:530px;
		}
		.paragraph-2 {
			font-size: 18px;
		}
		.table thead  {
			width: 700px;
		}
		.body-table tbody tr td:not(:last-child) {
			padding: 10px 80px 10px 10px;
			width: 120px;
		}
	}


	@media only screen and (min-width: 769px) and (max-width: 900px) {

		.heading-1, .heading-2, .heading-3 {
			padding: 10px  150px 10px 150px;
			width:729px;
		}
	}

</style>
		<body>
       <div class="heading">
           <a class="link"><h1 class="main heading-1 sample-sms">Sample SMSs</h1></a>
           <div class="row-1 sample-sms-content" style="display:none">
               <h3 class="h3">Days Overdue</h3>
               <table class="table">
                   <thead>
                          <tr>
                            <th class="th-sms" id="th-first" style="background-color:#478ec3"><a id="first" class="sms-overdue-heading">1 to 30</a></th>
                            <th class="th-sms" id="th-second" style="background-color:#478ec3"><a id="second" class="sms-overdue-heading">31 to 60</a></th>
                            <th class="th-sms" id="th-third" style="background-color:#478ec3"><a id="third" class="sms-overdue-heading">60 to 90</a></th>
                            <th class="th-sms" id="th-fourth" style="background-color:#478ec3"><a id="fourth" class="sms-overdue-heading">91 to 180</a></th>
                            <th class="th-sms" id="th-fifth" style="background-color:#478ec3" ><a id="fifth" class="sms-overdue-heading"> > 180 days</a></th>
                          </tr>
                   </thead>
               </table>
			   <div id="first_overdue" class="sms-overdue-content">
               <p class="paragraph-2"> Recordent sends the following SMSs for 1 to 30 days overdue</p>
               <table class="body-table">
                   <tbody>
                      <tr>
                        <td>SMS 1</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} shows as overdue on Recordent. You may want to clear the overdue payment.To know more, click here {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 2</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} on Recordent can be seen by other businesses with your consent.To know more click here {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 3</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as overdue on Recordent. You may want to clear the overdue payment.To know more click here {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 4</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as overdue on Recordent. This is another reminder to make the payment.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 5</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as overdue on Recordent, many payment reminders were sent earlier,you are advised to pay.For report click here {{route('home')}}/check-my-report</td>
                      </tr>
                     </tbody>

               </table>
			   </div>

			   <div id="second_overdue" class="sms-overdue-content">
               <p class="paragraph-2"> Recordent sends the following SMSs for 31 to 60 days overdue</p>
               <table class="body-table">
                   <tbody>
                      <tr>
                        <td>SMS 1</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} is overdue by 30+ days and still shows as unpaid on Recordent.To know more, click here {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 2</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} on Recordent can be seen & treated as negative by other businesses.To know more click here {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 3</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as unpaid on Recordent & is overdue by 30+days. Pay at the earliest.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 4</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as unpaid on Recordent.Please pay to ensure clean payment record.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 5</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} is still unpaid on Recordent,reminders were sent before,make payment at the earliest. For report click here {{route('home')}}/check-my-report</td>
                      </tr>
                     </tbody>

               </table>
			   </div>

			   <div id="third_overdue" class="sms-overdue-content">
               <p class="paragraph-2"> Recordent sends the following SMSs for 61 to 90 days overdue</p>
               <table class="body-table">
                   <tbody>
                      <tr>
                        <td>SMS 1</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} that was reported on Recordent is overdue by 60+Days.You are advised to pay.To know more, click here {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 2</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} on Recordent can be seen & will be treated as negative by other businesses.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 3</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} that was reported on Recordent is now overdue by 60+days.Pay at the earliest.To know more click here {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 4</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as unpaid on Recordent.Pay now to avoid negative payment record.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 5</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} is still unpaid on Recordent despite many reminders,please pay now.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                     </tbody>

               </table>
			   </div>

			   <div id="fourth_overdue" class="sms-overdue-content">
               <p class="paragraph-2"> Recordent sends the following SMSs for 91 to 180 days overdue</p>
               <table class="body-table">
                   <tbody>
                      <tr>
                        <td>SMS 1</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} is overdue by 90+days and still shows as unpaid on Recordent,pay immediately.To know more, click here {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 2</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} on Recordent can be seen by other businesses & may not offer you credit/loan.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 3</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as unpaid on Recordent despite reminders & is overdue by 90+days. Pay now.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 4</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as unpaid on Recordent.Pay today to improve your payment record.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 5</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} is overdue by {{route('home')}}/check-my-report+days and is still unpaid on Recordent despite reminders,pay now.Click for details</td>
                      </tr>
                     </tbody>

               </table>
			   </div>

			   <div id="fifth_overdue" class="sms-overdue-content">
               <p class="paragraph-2"> Recordent sends the following SMSs for 180 days and above overdue</p>
               <table class="body-table">
                   <tbody>
                      <tr>
                        <td>SMS 1</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} is overdue by 180+days and still shows as unpaid on Recordent.To know more, click here {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 2</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} on Recordent can be seen by other businesses & will not offer you credit/loan.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 3</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as unpaid on Recordent & further action may be taken on 180+days overdue.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 4</td>
                        <td>Your overdue payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} still shows as unpaid on Recordent.Pay & get your negative record corrected.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                       <tr>
                        <td>SMS 5</td>
                        <td>Your payment to {{Auth::user()->business_name}},{{isset(Auth::user()->city->name) ? Auth::user()->city->name : ''}} that is overdue by 180+ days is still unpaid on Recordent,action may be taken if not paid.Click for details {{route('home')}}/check-my-report</td>
                      </tr>
                     </tbody>

               </table>
			   </div>
           </div>
           <div class="row-2">
                 <a class="link"><h1 class="main heading-2 sample-email">Sample Email</h1></a>
               <div class="row-pragraph sample-email-content" style="display:none;">
                 <p class="pragraph">
                    To,<br>
                    ABC Enterprises,<br>
                    Maharashtra.<br>
                    Contact # XXXXXXXXX<br><br>

                    Subject: - Overdue payment to {{Auth::user()->business_name}} Limited.<br>
                    Hello Sir/Madam,<br><br>
                    This is with reference to your overdue payment details submitted by {{Auth::user()->business_name}} Limited on
                    Recordent.<br><br>
                    Your overdue payment of INR 1,00,000/- to XYZ Limited that was reported on Recordent can be seen
                    by other businesses  will not offer you credit or a loan. Pay immediately.<br>
                    You can also view your record and make the payment towards the overdue amount through our
                    portal.<br>
                    To view complete details of the overdue amount and to know more, please click here<br>
                       <a href="{{route('home')}}/check-my-business-report" target="_blank">{{route('home')}}/check-my-business-report</a><br><br>
                       You can also reach us on<a href = "mailto: support@recordent.com">support@recordent.com</a><br><br>
                       Thank You<br><br>
                       Best Regards<br>
                       Recordent<br><br>
                       To understand how Recordent works, please click here -<br>
                       <a href="https://www.youtube.com/watch?v=cc6_v_eYLdw" target="_blank">https://www.youtube.com/watch?v=cc6_v_eYLdw</a>
                  </p>

                 </div>
             </div>
           <div>
                 <a class="link"><h1 class="main heading-3 sample-ivr">Sample IVRs</h1></a>
				 <div class="sample-ivr-content" style="display:none;">
				 <audio class="audio" controls="" style="vertical-align: middle" src="{{asset('front_new/mp3/ivr-30.mp3')}}" type="audio/mp3" controlslist="nodownload">
                Your browser does not support the audio element.
            </audio>
			<audio class="audio"controls="" style="vertical-align: middle" src="{{asset('front_new/mp3/ivr-90.mp3')}}" type="audio/mp3" controlslist="nodownload">
                Your browser does not support the audio element.
            </audio>
			</div>

           </div>
       </div>
    </body>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
$(document).ready(function(){
	$('.sample-sms').on('click', function(){
		$('.sample-sms-content').toggle();
		$("#th-first").css('background-color',"#fff");
		$("#first").css('color',"#000");
	});
	$('.sample-email').on('click', function(){
		$('.sample-email-content').toggle();
	});
	$('.sample-ivr').on('click', function(){
		$('.sample-ivr-content').toggle();
	});


	$('.sms-overdue-content').not('#first_overdue').hide();
	$(".sms-overdue-heading").click(function() {
		var smsObj = {"first":"first_overdue","second":"second_overdue","third":"third_overdue","fourth":"fourth_overdue","fifth":"fifth_overdue"}
		var smsBgObj = {"first":"th-first","second":"th-second","third":"th-third","fourth":"th-fourth","fifth":"th-fifth"}
		$(".sms-overdue-content").hide();
		var thisId = this.id;
		$("#"+smsObj[thisId]).show();
		$("#"+thisId).css('color','#000');
		$('.sms-overdue-heading').not("#"+thisId).css('color',"#fff");
		$("#"+smsBgObj[thisId]).css('background-color','#fff');
		$('.th-sms').not("#"+smsBgObj[thisId]).css('background-color',"#478ec3");
	});

});
</script>

@endsection
