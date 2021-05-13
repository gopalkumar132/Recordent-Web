@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' US Credit Records')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}US Credit Records
    </h1>
    <ul class="name_title">
        	<li>
        		
        		<a href="#" class="btn btn-sm btn-primary view"><i class="voyager-eye"></i> = View</a>
        		
        	</li> 
        </ul>
@stop

@section('content')
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>  
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
		@if(!Auth::user()->hasRole('admin'))
                <!-- <h1 class="page-title">
                    <i class="voyager-list"></i> All Consent
                </h1> -->
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="table-responsive consentPaymentListing">
							
<!--Starts Generating GRID view for all payments -->
		
@inject('provider', 'App\Http\Controllers\UsBusinessReportController')
<table id="dataTable" class="table table-hover fixed_headerss all consent">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Contact Phone</th>
            <th>Consent Raised Date</th>
            <th>Consent Status</th>
            <th>Consent Action Date</th>
            <th>Payment Date</th>
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
	
		
		<?php
		
		//dd($consent_payment_dtls);
		if(!empty($consent_payment_dtls))
		{	
			$color = "";
			foreach($consent_payment_dtls as $key => $value){
				
				//echo General::decrypt($value->person_name);
				$color = "";
				if($value->status ==1 && $value->refund_status==""){
					
					$consent_status =  "Approved";
					$payment_status =  "Success";
					
				}else{
					$consent_status =  "Pending";
					$payment_status =  "Refund is in Process";
					$color = "#FF0000";
				}
				
				echo '<tr>';
				echo '<td>'.General::decrypt($value->person_name).'</td>';
				echo '<td>'.General::decrypt($value->contact_phone).'</td>';
				echo '<td>'.date("d/m/Y H:i:s", strtotime($value->created_at)).'</td>';
				echo '<td>'.$consent_status.'</td>';
				echo '<td>'.date("d/m/Y H:i:s", strtotime($value->created_at)).'</td>';
				echo '<td>'.date("d/m/Y H:i:s", strtotime($value->created_at)).'</td>';
				echo '<td style="color:'.$color.'!important;">'.$payment_status.'</td>';
				?>			
				<td><a href="{{route('admin.us.report',['c_id'=>$value->consent_request_id])}}"class="btn btn-primary ">View Report</a></td>
				</tr>
				<?php 
			}
			?>
			
		<?php }else { ?>
			<tr><td colspan="10" align="center">No Record Found</td></tr>
		<?php }?>
       
    </tbody>
</table>
<script>
$(document).ready(function(){

    consentCheckStatus = function(element,e){
        $this = $(element);
        if($this.attr('disabled')){
            e.preventDefault();
            return false;
        }
        e.preventDefault();
        var url =$this.attr('href');
        $this.attr('disabled','disabled');
        $.ajax({
           method: 'get',
           url: url,
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }               
        }).then(function (response) {
            var alertType = "info";
            var alertMessage = response.message;
            var alerter = toastr[alertType];
            alerter(alertMessage);
            $this.removeAttr('disabled');

            $this.parents('tr').fadeOut(1000,function(){
               $(this).html(response.newStatus);
               $(this).fadeIn(500);
            });

        }).fail(function (data) {
            var alertType = "error";
            var alertMessage = data.responseJSON.message;
            var alerter = toastr[alertType];
            alerter(alertMessage);
            $this.removeAttr('disabled');    

        });
    }
});        
</script>

<!--End of Code for GRID VIEW at here -->
</div>    
                        </div>
                    </div>
                </div>
             @else
                <script>$(".all-record-listing-section").removeClass("hide");</script>
            @endif
        </div>
    </div>
    
@if(!empty(app('request')->input('contact_phone')) &&  $records->count() && !empty($dueId))
@php
    $dueId = trim($dueId,",");
@endphp

<script>

 if($(".consentRequestStatusTr").text().trim()==''){
    $(".consentRequestStatusTr").addClass('hide');
 }
var xx = setInterval(function(){
  $(".consentRequestStatusTr").find("td").find("label").fadeOut(1000,function(){
    $(this).remove();
  });
  clearInterval(xx);
 },5000); 

@if(!empty($next3MinForCounDown))
    
    function startCountDown(){
         $(".checkRequestConsentStatus").attr('disabled','disabled');
        var now = new Date("{{\Carbon\Carbon::now()->format('F d,Y H:i:s')}}").getTime();
        // Set the date we're counting down to
            var countDownDate = new Date("{{$next3MinForCounDown}}").getTime();
            // Update the count down every 1 second
            var x = setInterval(function() {
          // Get today's date and time
          
          // Find the distance between now and the count down date
          var distance = countDownDate - now;
          if(distance < 0){
             $(".checkRequestConsentStatus").removeAttr('disabled');
            clearInterval(x);
          }
          now = now + 1000;
          // Time calculations for days, hours, minutes and seconds
          if(distance>=0){
              var days = Math.floor(distance / (1000 * 60 * 60 * 24));
              var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
              var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
              var seconds = Math.floor((distance % (1000 * 60)) / 1000);
              // Display the result in the element with id="demo"
              if(seconds<10){
                seconds = "0" + seconds;
              }
              document.getElementById("nextConsentRequestCountDown").innerHTML = 'Request in 0'+minutes + ":" + seconds + " Min";
            }
                
              // If the count down is finished, write some text
              if (distance < 0) {
                 $(".checkRequestConsentStatus").removeAttr('disabled');
                clearInterval(x);
                document.getElementById("nextConsentRequestCountDown").innerHTML='';
                $(".requestConsent").removeAttr('disabled');
              }
        }, 1000);
    }
    startCountDown();
@else
    $(".requestConsent").removeAttr('disabled');
@endif
    $(document).ready(function(){
        $(document).on('click','.checkRequestConsentStatus',function(e){
            if($(this).attr('disabled')){
                e.preventDefault();
            }
        });
        $(".requestConsent").on('click',function(){
            var thisButton = $(this);
            thisButton.attr('disabled','disabled');
            $(document).find('.alert').addClass('hide');
            $(document).find('.alert').html('');
            var dueId = "{{$dueId}}";
            var name="{{app('request')->input('student_first_name')}}";
            var contactPhone = "{{app('request')->input('contact_phone')}}";
            $.ajax({
               method: 'post',
               url: "{{route('admin.request-consent-store')}}",
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               data: {
                    name:name,
                    customer_type:'INDIVIDUAL',
                    contact_phone:contactPhone,
                    due_id: dueId,
                   _token: $('meta[name="csrf-token"]').attr('content')
               }
            }).then(function (response) {
                //$(document).find('.alert.alert-success').html(response.message);
                //$(document).find('.alert.alert-success').removeClass('hide');
                window.location.reload();
                thisButton.removeAttr('disabled');
                $(".requestConsent").attr('disabled','disabled');
                if(typeof response.lastRequestAccepted!='undefined'){
                    $(".checkRequestConsentStatus").removeAttr('disabled');
                    return false;
                }
                if($("tr.consentRequestStatusTr").length){
                    clearInterval(xx);
                    $("tr.consentRequestStatusTr").removeClass("hide");
                    $("tr.consentRequestStatusTr").find("td").html('');
                    $("tr.consentRequestStatusTr").find("td").html("<a href='' class='btn btn-primary checkRequestConsentStatus'>Check Status</a> <label>Your consent request is pending.</label>");
                    setTimeout(function(){
                        $("tr.consentRequestStatusTr").find("td").find("label").fadeOut(1000,function(){
                            $(this).remove();
                        });    
                    },5000);
                    
                }
                if(response.canRequestConsentAgain24Hour){
                    if(response.startCountDownTimer){
                        //start the timer
                        $(".checkRequestConsentStatus").attr('disabled','disabled');
                        var countDownDate = new Date(response.next3MinForCounDown).getTime();
                        var now = new Date(response.currentTimeInMilli).getTime();
                        // Update the count down every 1 second
                        var x = setInterval(function() {
                              // Get today's date and time
                               
                              // Find the distance between now and the count down date
                              var distance = countDownDate - now;
                              if(distance < 0){
                                clearInterval(x);
                              }
                              now = now + 1000;
                              // Time calculations for days, hours, minutes and seconds
                              if(distance>=0){
                                  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                  // Display the result in the element with id="demo"
                                  if(seconds<10){
                                    seconds = "0" + seconds;
                                  }
                                  document.getElementById("nextConsentRequestCountDown").innerHTML = 'Request in 0'+minutes + ":" + seconds + " Min";
                                }
                                    
                                  // If the count down is finished, write some text
                                  if (distance < 0) {
                                     $(".checkRequestConsentStatus").removeAttr('disabled');
                                    clearInterval(x);
                                    document.getElementById("nextConsentRequestCountDown").innerHTML='';
                                    $(".requestConsent").removeAttr('disabled');
                                  }
                            }, 1000);

                    }
                }else{
                    //$("tr.consentRequestStatusTr").addClass("hide");
                    //You have already raised consent for this user. You can raise consent maximum two times in last 24 hours.
                }
            }).fail(function (data) {
                $(document).find('.alert.alert-danger').html(data.responseJSON.message);
                $(document).find('.alert.alert-danger').removeClass('hide');
                thisButton.removeAttr('disabled');
                
            });
        })
    });
</script>    
@endif

@endsection

      