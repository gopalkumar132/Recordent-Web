@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' All Business Records')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}All Business Reports
    </h1>
    <ul class="name_title">
        	<li>
        		
        		<a href="#" class="btn btn-sm btn-primary view"><i class="voyager-eye"></i> = View</a>
        		
        	</li> 
    </ul>
    <!-- <div class="pull-right" style="padding-top: 10px;"><a href="{{route('export-business')}}" class="btn btn-info download-mem-data btn-blue" >Download Businesses Data <i class="voyager-download"></i></a></div> -->
@stop

@section('content')
 <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>  
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        
        <div class="row">

           <!--  <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">

                         <div id="dataTable_filter" class="dataTables_filter">
                            <form action="{{route('business.all-records')}}" method="get">
                            <div class="row">
                                <div class="col-md-12">
                                 <div class="row new_width"> 
                                    <div class="col-md-2">
                                        <label>{{General::getLabelName('unique_identification_number')}}: </label>
                                        <input type="text" name="unique_identification_number"class="form-control" aria-controls="dataTable" value="{{!empty(app('request')->input('unique_identification_number')) ? app('request')->input('unique_identification_number') : '' }}" >
                                    </div>
                                    {{--<div class="col-md-2">
                                        <label> Concerned Person Name:</label>
                                        <input type="text" name="concerned_person_name"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('concerned_person_name')) ? app('request')->input('concerned_person_name') : '' }}">
                                    </div>--}} 
                                     <div class="col-md-2">
                                        <label>Concerned Person Phone:</label>
                                        <input type="text" name="concerned_person_phone"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('concerned_person_phone')) ? app('request')->input('concerned_person_phone') : '' }}" required>
                                    </div>
                                    {{--<div class="col-md-2">
                                        <label>Company Name:</label>
                                        <input type="text" name="company_name"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('company_name')) ? app('request')->input('company_name') : '' }}">
                                    </div> 
                                    <div class="col-md-2">
                                        <label>Sector:</label>
                                        <select name="sector_id"class="form-control " placeholder="" aria-controls="dataTable">
                                            <option value="">All</option>    
                                            @foreach($sectors as $sector)
                                                <option value="{{$sector->id}}" {{!empty(app('request')->input('sector_id') && app('request')->input('sector_id')==$sector->id) ? 'selected' : '' }}>{{$sector->name}}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                    <div class="col-md-2">
                                        <label>Due Amount (in INR):</label>
                                        <select name="due_amount"class="form-control " placeholder="" aria-controls="dataTable">
                                            <option></option>    
                                            <option value="less than 1000" {{app('request')->input('due_amount')=='less than 1000' ? 'selected' : '' }}>less than 1000</option>
                                            <option value="1000 to 5000" {{app('request')->input('due_amount')=='1000 to 5000' ? 'selected' : '' }}>1000 to 5000</option>
                                            <option value="5001 to 10000" {{app('request')->input('due_amount')=='5001 to 10000' ? 'selected' : '' }}>5001 to 10000</option>
                                            <option value="10001 to 25000" {{app('request')->input('due_amount')=='10001 to 25000' ? 'selected' : '' }}>10001 to 25000</option>
                                            <option value="25001 to 50000" {{app('request')->input('due_amount')=='25001 to 50000' ? 'selected' : '' }}>25001 to 50000</option>
                                            <option value="more than 50000" {{app('request')->input('due_amount')=='more than 50000' ? 'selected' : '' }}>more than 50000</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Due Date Period:</label>
                                        <select name="due_date_period" class="form-control " placeholder="" aria-controls="dataTable">
                                            <option></option>    
                                            <option value="less than 30days" {{app('request')->input('due_date_period')=='less than 30days' ? 'selected' : '' }}>less than 30days</option>
                                            <option value="30days to 90days" {{app('request')->input('due_date_period')=='30days to 90days' ? 'selected' : '' }}>30days to 90days</option>
                                            <option value="91days to 180days" {{app('request')->input('due_date_period')=='91days to 180days' ? 'selected' : '' }}>91days to 180days</option>
                                            <option value="181days to 1year" {{app('request')->input('due_date_period')=='181days to 1year' ? 'selected' : '' }}>181days to 1year</option>
                                            <option value="more than 1year" {{app('request')->input('due_date_period')=='more than 1year' ? 'selected' : '' }}>more than 1year</option>
                                            
                                        </select>
                                    </div>   
                                    
                                    <div class="col-md-2">
                                                <label>State:</label>
                                                <select class="form-control" name="state_id" id="state">
                                                    <option value="">ALL</option>
                                                     @if($states->count())  
                                                    @foreach($states as $state)
                                                        <option value="{{$state->id}}" {{app('request')->input('state_id')==$state->id ? 'selected' : '' }}>{{$state->name}}</option>
                                                    @endforeach  
                                                @endif
                                                </select>
                                    </div> 

                                    <div class="col-md-2">
                                        <label>City:</label>
                                        <select class="form-control " name="city_id" id="city">
                                            <option value="">ALL</option>
                                        
                                        </select>
                                    </div>
                                    --}}
                                    <div class="col-md-8 text-right text-md-right mt_form">
                                        <button type="submit" class="btn btn-primary btn-blue"  aria-controls="dataTable">Search</button>
                                        <a href="{{route('business.all-records')}}" class="btn btn-primary btn-red"  aria-controls="dataTable">Reset</a>
                                   </div>
                                      
                                 </div>
                                </div>

                            </div>
                           </form>
                        </div>
                    </div>
                </div>    


            </div>
            <select id="maincity" style="display: none">
                @if($cities->count())  
                    @foreach($cities as $city)
                        <option data-state-id="{{$city->state_id}}" value="{{$city->id}}">{{$city->name}}</option>
                    @endforeach  
                @endif
             </select>

            @if(!empty(app('request')->input('concerned_person_phone')) )
                    {{-- new --}}
                    <div class="col-md-12 all-record-listing-section hide">
                        @if(app('request')->session()->has('myCustomSuccessMessage'))
                        <div class="alert alert-success" role="alert">
                            {{app('request')->session()->get('myCustomSuccessMessage')}}
                        </div>
                        @else
                        <div class="alert alert-success hide" role="alert"></div>
                        @endif
                       <div class="alert alert-danger hide" role="alert"></div>
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div class="table-responsive">
                                 <table id="dataTable" class="table table-hover fixed_headerss">
                                        <thead>
                                        <tr>
                                            <th>Reported Organization</th>
                                            <th>Company Name</th>
                                            <th>{{General::getLabelName('unique_identification_number')}}</th>
                                            <th>Business Type</th>
                                            <th>Concerned Person Name</th>
                                             <th>Concerned Person Phone</th>
                                             <th>State, City</th>
                                             <th>Reported Date</th>
                                             <th>Due Date</th>
                                            <th>Reported Due</th>
                                            <th>Balance Due</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                             @php
                                                $dueId='';
                                                $consentPayment = '';
                                            @endphp
                                            @if($records->count())
                                                
                                                @php
                                                    $atleastOthersRecord =false;
                                                    $myRecordCount = 0;
                                                    $othersRecordCount = 0;
                                                    $firstRecords = $records->first();

                                                    $checkConsentResponseTimeValidation = General::checkConsentResponseTimeValidation(Auth::id(),app('request')->input('concerned_person_phone'),app('request')->input('unique_identification_number'),'BUSINESS'); 

                                                     $consentPayment = General::consentPayment(Auth::id(),app('request')->input('concerned_person_phone'),app('request')->input('unique_identification_number'),'BUSINESS');
                                                @endphp

                                                @foreach($records as $data)
                                                
                                                    @if($data->added_by == Auth::id())
                                                        @php $myRecordCount++; @endphp
                                                    @else
                                                         @php
                                                            if(!Auth::user()->hasRole('admin')){ 
                                                                $atleastOthersRecord= true;
                                                                if(!$checkConsentResponseTimeValidation){
                                                                    $dueId .= $data->id.','; 
                                                                    $othersRecordCount++;
                                                                    continue; 
                                                                }
                                                            }    
                                                         @endphp
                                                    @endif

                                                    @php
                                                        $sector = General::getSector($data->profile->sector_id);
                                                    @endphp
                                                    @if(Auth::user()->hasRole('admin') || $data->added_by == Auth::id())
                                                        <tr>
                                                            
                                                            <td>{{$data->addedBy->business_name}}@if(!empty($data->addedBy->userType)) ({{$data->addedBy->userType->name }}) @endif</td>
                                                            <td>{{$data->profile->company_name}}</td>
                                                            <td>{{$data->profile->unique_identification_number}}{{ $sector ? ' ('.General::getUniqueIdentificationTypeofSector($sector->unique_identification_type).')' : ''}}</td>
                                                            <td>{{ $sector ? $sector->name : ''}}</td>
                                                            <td>{{$data->profile->concerned_person_name}}</td>
                                                            <td>{{$data->profile->concerned_person_phone}}</td>
                                                            <td>{{General::getStateNameById($data->profile->state_id)}}, {{General::getCityNameById($data->profile->city_id)}}</td>
                                                            <td>{{date('d/m/Y', strtotime($data->created_at))}}</td>
                                                            <td>{{date('d/m/Y', strtotime($data->due_date))}}</td>
                                                            <td class="balance">{{General::ind_money_format($data->due_amount)}}</td>
                                                            <td class="balance">{{General::ind_money_format($data->due_amount - General::getPaidForDueOfBusiness($data->id))}}</td>
                                                            
                                                        </tr>
                                                    @endif    
                                                @endforeach 

                                                 @if(!empty($consentPayment) && $consentPayment->status==4)
                                                    <tr>
                                                        <td colspan="11" align="center" class="text-center">
                                                            <div class="text-center">
                                                                <a href="{{route('admin.business.report',['cp_id'=>$consentPayment->id])}}"class="btn btn-primary ">View Report</a>
                                                            </div>
                                                        </td>    
                                                    </tr>
                                                @else
                                                    @if($atleastOthersRecord)
                                                        @php
                                                            $requestConsentCheckStatusWithIgnoreRequestConsentBlockForHour = General::requestConsentCheckStatus(Auth::id(),app('request')->input('concerned_person_phone'),app('request')->input('unique_identification_number'),'BUSINESS','IGNORE_OR_STATUS_APPROVED');
                                                        @endphp
                                                        @if(!empty($requestConsentCheckStatusWithIgnoreRequestConsentBlockForHour))
                                                             <tr>
                                                                <td colspan="11">
                                                                    <label>Your consent request is accepted. <a href="{{route('admin.business.consent.payment',[$requestConsentCheckStatusWithIgnoreRequestConsentBlockForHour->id])}}" class="btn btn-primary  ">Make Payment</a></label>
                                                                </td>
                                                            </tr>
                                                        @else

                                                            @if($canRequestConsent->count()<2)
                                                                @if(!$checkConsentResponseTimeValidation)

                                                                    <script>$(".all-record-listing-section").removeClass("hide");</script>
                                                                   <tr>
                                                                        <td colspan="11" align="center" >
                                                                            <label>
                                                                                @if($myRecordCount>0)
                                                                                    <div>More data available which are added by another organization but to view those data Consent required.</div>
                                                                                @endif
                                                                                <button type="button" class="btn btn-primary btn-blue requestConsent" disabled>Request Consent</button>
                                                                            </label>
                                                                        
                                                                            <div class="text-center" id="nextConsentRequestCountDown"></div>
                                                                        </td>
                                                                         <td class="hide"></td>   
                                                                   </tr> 
                                                               @endif

                                                            @endif
                                                            @if($canRequestConsent->count()>=2)
                                                                <script>$(".all-record-listing-section").removeClass("hide");</script>
                                                                <tr><td colspan="11" align="center" >You have already raised consent for this user-{{app('request')->input('concerned_person_phone')}}. You can raise consent maximum two times in last 24 hours.</td>
                                                                 <td class="hide"></td>   
                                                                </tr>
                                                            @endif

                                                            <tr class="consentRequestStatusTr @if(!$requestConsentCheckStatus) hide  @endif " style="display:none">
                                                                <td colspan="11">
                                                                    @if($requestConsentCheckStatus)
                                                                        @if($requestConsentCheckStatus->status==1)
                                                                            <script>$(".all-record-listing-section").removeClass("hide");</script>
                                                                            <a href="" class="btn btn-primary checkRequestConsentStatus">Check Status</a> <label>Your consent request is pending.</label> 
                                                                        @elseif($requestConsentCheckStatus->status==3)
                                                                            @if($requestConsentCheckStatus->response_valid_at >= \Carbon\Carbon::now())
                                                                            <label>Your consent request is accepted. <a href="{{route('admin.business.consent.payment',[$requestConsentCheckStatus->id])}}" class="btn btn-primary ">Make Payment</a></label>
                                                                            @endif
                                                                        @elseif($requestConsentCheckStatus->status==4)
                                                                            <label>Your consent request is deny.</label>
                                                                        @endif
                                                                    @endif
                                                                </td>    
                                                            </tr>  
                                                        @endif 
                                                    @endif
                                                @endif    
                                            @else
                                                <tr><td colspan="10" align="center">No Record Found</td></tr>
                                                    @if(Auth::user()->hasRole('admin'))
                                                    <tr><td colspan="10" align="center"><a href="{{route('voyager.users.index')}}"><button type="button" class="btn btn-primary" aria-controls="dataTable">Reporting Organization</button></a></td></tr>
                                                @endif
                                            @endif
                                        </tbody>
                                    </table>
                                </div>    
                            </div>
                        </div>
                    </div>
            @endif -->

            @if(!Auth::user()->hasRole('admin'))
                <!-- <h1 class="page-title">
                    <i class="voyager-list"></i> All Consent
                </h1> -->
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="table-responsive consentPaymentListing">
                                @include('admin.business.all-records.consent-payment-list.index')
                            </div>    
                        </div>
                    </div>
                </div>
            @else
                <script>$(".all-record-listing-section").removeClass("hide");</script>
            @endif

        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script language="javascript" type="application/javascript">
    {{--$(document).ready(function(){
        var newOption = new Option('RAJKOT', 23, false, false);
        // Append it to the select
        $('#city_id').append(newOption).trigger('change');
        alert($('#city_id').val());
        $('#city_id').select2("destroy");
        $("#city_id").html('')
       $('#city_id').on('change',function(){
            alert($(this).val());
        });

        //clear selection
//        $('#city_id').val(null).trigger('change');
    });
  
 --}}

    $(document).ready(function(){
        if($("#state").val()!=''){ 
            @if(!empty(app('request')->input('state_id')))        
            var oldCity = "{{app('request')->input('city_id')}}";    
            var selected = '';
            $("#city").find('option').remove();
            $("#city").append('<option value="">ALL</option>');
            var stateId =  $("#state").val();
            $("#maincity option").each(function(){
                if($(this).data('state-id')==stateId){
                    var cityId = $(this).val();
                    if(oldCity==cityId) { selected= 'selected';}else{selected= ''}
                    $("#city").append('<option value="'+$(this).val()+'" '+selected+'>'+$(this).text()+'</option>');    
                }
            });
            @endif
          } 

        $("#state").on('change',function(){
            $("#city").find('option').remove();
            $("#city").append('<option value="">ALL</option>');

         if($("#state").val()!=''){  
            var stateId =  $("#state").val();
            $("#maincity option").each(function(){
                if($(this).data('state-id')==stateId){
                    $("#city").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>'); 
                }
            });
          }  
        });

      });  

 </script> 
@if(!empty(app('request')->input('concerned_person_phone'))  &&  $records->count() && !empty($dueId))
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
        // Set the date we're counting down to
            var now = new Date("{{\Carbon\Carbon::now()->format('F d,Y H:i:s')}}").getTime();
            var countDownDate = new Date("{{$next3MinForCounDown}}").getTime();
            // Update the count down every 1 second
            var x = setInterval(function() {
          // Get today's date and time
          var now = new Date().getTime();
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
            var unique_identification_number="{{app('request')->input('unique_identification_number')}}";
            var contactPhone = "{{app('request')->input('concerned_person_phone')}}";
            $.ajax({
               method: 'post',
               url: "{{route('admin.request-consent-store-business')}}",
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               data: {
                    unique_identification_number:unique_identification_number,
                    customer_type:'BUSINESS',
                    contact_phone:contactPhone,
                   due_id: dueId,
                   _token: $('meta[name="csrf-token"]').attr('content')
               }
            }).then(function (response) {
               /* $(document).find('.alert.alert-success').html(response.message);
                $(document).find('.alert.alert-success').removeClass('hide');*/
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
                    $("tr.consentRequestStatusTr").find("td").html("<a href='' class='btn btn-primary btn-blue checkRequestConsentStatus'>Check Status</a> <label>Your consent request is pending.</label>");
                    setTimeout(function(){
                        $("tr.consentRequestStatusTr").find("td").find("label").fadeOut(1000,function(){
                            $(this).remove();
                        });    
                    },5000);
                    
                }
                if(response.canRequestConsentAgain24Hour){
                    if(response.startCountDownTimer){
                        $(".checkRequestConsentStatus").attr('disabled','disabled');
                        //start the timer
                        var now = new Date(response.currentTimeInMilli).getTime();
                        var countDownDate = new Date(response.next3MinForCounDown).getTime();
                        // Update the count down every 1 second
                        var x = setInterval(function() {
                              // Get today's date and time
                              var now = new Date().getTime();
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