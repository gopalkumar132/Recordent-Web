<div class="clearfix container-fluid row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel widget">
                
            <h3> Search Options:</h3>
            <hr>
            <ul class="nav nav-tabs d-flex justify-content-center">
              
              <li class="active"><a data-toggle="tab" href="#individual">Individual</a></li>
              <li ><a data-toggle="tab" href="#business">Business</a></li>
            </ul>
            <div class="tab-content">
                <div id="business" class="tab-pane">
                    <form action="{{route('business.all-records')}}" method="get">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row new_width">
                                    <div class="advanced-search">
                                        <div class="d-flex justify-content-center flex-wrap">
                                            <div class="col-md-2">
                                                <label>{{General::getLabelName('unique_identification_number')}}: </label>
                                                <input type="text" name="unique_identification_number"class="form-control" aria-controls="dataTable" value="{{!empty(app('request')->input('unique_identification_number')) ? app('request')->input('unique_identification_number') : '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label> Concerned Person Name:</label>
                                                <input type="text" name="concerned_person_name"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('concerned_person_name')) ? app('request')->input('concerned_person_name') : '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Company Name:</label>
                                                <input type="text" name="company_name"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('company_name')) ? app('request')->input('company_name') : '' }}">
                                            </div>

                                        </div>
                    
                                        <div class="d-flex justify-content-center flex-wrap text-advanc-search">
                                            <a class="" data-toggle="collapse" href="#collapseBusiness" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            <ic class="voyager-search"></ic> Advanced Search
                                            </a>
                                        </div>
                    
                                        <div class="collapse" id="collapseBusiness">
                                            <div class="card card-body ">
                                                <div class="d-flex justify-content-center flex-wrap">
                                                     
                                                   <div class="col-md-2">
                                                        <label>Concerned Person Phone:</label>
                                                        <input type="text" name="concerned_person_phone"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('concerned_person_phone')) ? app('request')->input('concerned_person_phone') : '' }}">
                                                    </div>  
                                                     
                                                    @php
                                                        $sectors = General::getSectorList();
                                                    @endphp
                                                    <div class="col-md-2">
                                                        <label>Sector:</label>
                                                        <select name="sector_id"class="form-control " placeholder="" aria-controls="dataTable">
                                                            <option value="">All</option>    
                                                            @foreach($sectors as $sector)
                                                                <option value="{{$sector->id}}" {{!empty(app('request')->input('sector_id') && app('request')->input('sector_id')==$sector->id) ? 'selected' : '' }}>{{$sector->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> 
                                                    {{--
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
                                                    --}}   
                                                     @php
                                                        $states = General::getStateList();

                                                    @endphp
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
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="d-flex justify-content-center flex-wrap">
                                                <button type="submit" class="btn btn-primary"  aria-controls="dataTable">Search</button>
                                                <button type="reset" class="btn btn-primary"  aria-controls="dataTable">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                 </div>
                <div id="individual" class="tab-pane in active">      
                    <form action="{{route('all-records')}}" method="get">
                        <div class="row"> 
                        <div class="col-md-12">
                            <div class="row new_width">
                              <div class="advanced-search">
                                <div class="d-flex justify-content-center flex-wrap">
                                    <div class="col-md-2">
                                        <label>Aadhaar Number:</label>
                                        <input type="text" name="aadhar_number"class="form-control " placeholder="1111-2222-3333" data-mask="9999-9999-9999" aria-controls="dataTable" value="{{!empty(app('request')->input('aadhar_number')) ? app('request')->input('aadhar_number') : '' }}">
                                     </div>
                                    <div class="col-md-2">
                                        <label>Contact Phone:</label>
                                        <input type="text" name="contact_phone"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('contact_phone')) ? app('request')->input('contact_phone') : '' }}">
                                    </div>

                                </div>
            
                                <div class="d-flex justify-content-center flex-wrap text-advanc-search">
                                    <a class="" data-toggle="collapse" href="#collapseIndividual" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    <ic class="voyager-search"></ic> Advanced Search
                                    </a>
                                </div>
                                 <div class="collapse" id="collapseIndividual">
                                    <div class="card card-body ">
                                        <div class="d-flex justify-content-center flex-wrap">
                                            

                                            <div class="col-md-2">
                                                <label> Person's Name:</label>
                                                <input type="text" name="student_first_name"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('student_first_name')) ? app('request')->input('student_first_name') : '' }}">
                                            </div> 
                                            
                                            {{--
                                            <div class="col-md-2">
                                                <label>Due Amount (INR):</label>
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
                                            --}}    
                                            <div class="col-md-2">
                                                <label> DOB:</label>
                                                <input type="date" name="student_dob" class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('student_dob')) ? app('request')->input('student_dob') : '' }}">
                                            </div>

                                            <div class="col-md-2">
                                                <label>Father Name:</label>
                                                <input type="text" name="father_first_name"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('father_first_name')) ? app('request')->input('father_first_name') : '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Mother Name:</label>
                                                <input type="text" name="mother_first_name"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('mother_first_name')) ? app('request')->input('mother_first_name') : '' }}">
                                            </div>

                                        </div>
                                    </div>
                                </div>    
                                <div class="d-flex justify-content-center flex-wrap">
                                    <button type="submit" class="btn btn-primary" aria-controls="dataTable">Search</button>
                                     <button type="reset" class="btn btn-primary"  aria-controls="dataTable">Reset</button>
                                </div>
                             </div>   

                            </div>
                        </div>
                        

                            

                        </div>

                   </form>
                </div>  

            </div>

        </div>
    </div>
</div>
 @php
    $cities = General::getCityList();
@endphp
<select id="maincity" style="display: none">
    @if($cities->count())  
        @foreach($cities as $city)
            <option data-state-id="{{$city->state_id}}" value="{{$city->id}}">{{$city->name}}</option>
        @endforeach  
    @endif
 </select>
 <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script language="javascript" type="application/javascript">
    $(document).ready(function(){
        $(".tab-content #business").find("#state").on('change',function(){
            
             $(".tab-content #business").find("#city").find('option').remove();
            $(".tab-content #business").find("#city").append('<option value="">ALL</option>');

         if($(".tab-content #business").find("#state").val()!=''){  
            var stateId =  $("#state").val();
            $("#maincity option").each(function(){
                if($(this).data('state-id')==stateId){
                    $(".tab-content #business").find("#city").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>'); 
                }
            });
          }  
        });
        
        
        $('.panel-tab').on('click', function(event){
  event.preventDefault();
  $('.panel-stage').slideToggle('slow', function(event){
    if($(this).is(':visible')){
      $('.panel-tab').html('<ic class="voyager-search"></ic>  Advanced Search - close  <span>&#9650;</span>');
    } else {
      $('.panel-tab').html('<ic class="voyager-search"></ic>  Advanced Search - opne  <span>&#9660;</span>');
    }
  });
});

      });  

 </script> 
 
 