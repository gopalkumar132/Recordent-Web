@extends('voyager::master')

@section('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('page_header')
<h1 class="page-title">
    <i class="{{ $dataType->icon }}"></i>
    {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' Member' }}
</h1>
@stop

@section('content')
<style type="text/css">
    .form-group {
        position: relative;
        margin-bottom: 30px;
    }

    form label.error {
        bottom: -25px;
        top: auto;
    }
</style>
<div class="page-content container-fluid">
        <form class="form-edit-add" id="msform" role="form" action="@if(!is_null($dataTypeContent->getKey())){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif" method="POST" enctype="multipart/form-data" autocomplete="off">
        @if(isset($dataTypeContent->id))
        {{ method_field("PUT") }}
        @endif
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-bordered">
                    {{-- <div class="panel"> --}}
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="panel-body">
                        <div class="form-group">
                            <label for="name">{{ __('voyager::generic.name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" maxlength="{{General::maxlength('name')}}" placeholder="{{ __('voyager::generic.name') }}" value="{{ $dataTypeContent->name ?? '' }}" required>
                        </div>

                        @can('editRoles', $dataTypeContent)
                        <div class="form-group">
                            <label for="default_role">{{ __('voyager::profile.role_default') }}</label>
                            @php
                          $roles = General::getRoles();
                          @endphp
                              <select class="form-control select2" id="role_id" name="role_id" required>
                                  <option>Select</option>
                                  @foreach($roles as $role)
                                  <option value="{{$role->id}}" {{old('role_id',$dataTypeContent->role_id ?? '')==$role->id ? 'selected' : ''}}>{{$role->display_name}}</option>
                                  @endforeach
                              </select>
                        </div>  
                    @endcan
                    @if(!isset($dataTypeContent->id))
                    <div class="form-group">
                        <label for="name">Country Code*</label>

                        <select class="form-control select2" id="country_code" name="country_code" required>
                            <option>Select</option>
                            @foreach($countriePhonecodes as $countrie)
                            <option value="{{$countrie->phonecode}}" {{old('country_code',$dataTypeContent->country_code)==$countrie->phonecode ? 'selected' : ''}}>+{{$countrie->phonecode}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="name">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile Number" required value="{{ $dataTypeContent->mobile_number ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Organization/Business Name</label>
                        <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Organization/Business Name" maxlength="{{General::maxlength('name')}}" value="{{ $dataTypeContent->business_name ?? '' }}" required>
                    </div>
                    @php
                    $userTypes = General::getUserTypes();
                    @endphp
                    <div class="form-group" @if(isset($dataTypeContent->id)) style="display: none" @endif>
                        <label for="name">User Type</label>
                        <select class="form-control select2" id="user_type" name="user_type" required>
                            <option>Select</option>
                            @foreach($userTypes as $userType)
                            <option value="{{$userType->id}}" {{old('user_type',$dataTypeContent->user_type ?? '')==$userType->id ? 'selected' : ''}}>{{$userType->name}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group" id="type_of_business_div" @if($dataTypeContent->user_type!=10 && $dataTypeContent->user_type!=11) style="display:none" @endif>
                        <label for="name">Type of Business</label>
                        <input type="text" class="form-control" id="type_of_business" name="type_of_business" placeholder="Type of Business" @if($dataTypeContent->user_type==10 && $dataTypeContent->user_type==11) required @endif value="{{ $dataTypeContent->type_of_business ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="name">Branch Name</label>
                        <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Branch Name" value="{{ $dataTypeContent->branch_name ?? '' }}">
                    </div>


                    @if(!isset($dataTypeContent->id))
                    <div class="form-group">
                        <label for="password">{{ __('voyager::generic.password') }}</label>
                        @if(isset($dataTypeContent->password))
                        <br>
                        <small>{{ __('voyager::profile.password_hint') }}</small>
                        @endif
                        <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password">
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="name">Address</label>
                        <textarea class="form-control" id="address" onkeypress="return blockSpecialChar(this,event)" maxlength="100" name="address" placeholder="Address">{{ $dataTypeContent->address ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="name">Country</label>
                        <select class="form-control select2" id="country_id" name="country_id">
                            @foreach($countries as $country)
                            <option value="{{$country->id}}" {{old('country_id',$dataTypeContent->country_id)==$country->id ? 'selected' : ''}}>{{$country->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(isset($dataTypeContent->id))
                    <div class="form-group">
                        <label for="name">State</label>
                        <select class="form-control select2" id="state_id" name="state_id">
                            <option value="">Select</option>
                            @foreach($states as $state)

                            <option value="{{$state->id}}" {{$dataTypeContent->state_id==$state->id ? 'selected' : ''}}>{{$state->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">City</label>
                        <select class="form-control" id="city_id" name="city_id">
                            <option value="">Select</option>

                            @if(!empty($dataTypeContent->state_id))
                            @foreach($cities as $city)
                            <option data-state-id="{{$city->state_id}}" value="{{$city->id}}" {{$dataTypeContent->city_id==$city->id ? 'selected' : ''}} {{ $dataTypeContent->state_id!=$city->state_id ? 'disabled="disabled"' : ''}}>{{$city->name}}</option>
                            @endforeach
                            @else
                            @foreach($cities as $city)
                            <option data-state-id="{{$city->state_id}}" value="{{$city->id}}" {{$dataTypeContent->city_id==$city->id ? 'selected' : ''}} disabled="disabled">{{$city->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>



                    {{-- <div class="form-group">
                                    <label for="name">Pincode</label>
                                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode" required
                                            value="{{ $dataTypeContent->pincode ?? '' }}">
                </div> --}}
                @else
                <div class="form-group">
                    <label for="name">State</label>
                    <select class="form-control select2" id="state_id" name="state_id">
                        <option value="">Select</option>
                        @foreach($states as $state)

                        <option value="{{$state->id}}">{{$state->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">City</label>
                    <select class="form-control" id="city_id" name="city_id">
                        <option value="">Select</option>

                        @foreach($cities as $city)

                        <option data-state-id="{{$city->state_id}}" value="{{$city->id}}" disabled="disabled">{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>

                @endif

                <div class="form-group">
                    <label for="email">Business Short</label>
                    <input type="name" class="form-control" id="business_short" name="business_short" placeholder="{{ __('voyager::generic.business_short') }}" value="{{ $dataTypeContent->business_short ?? '' }}">
                </div>

                @if(Auth::user()->role_id == 1)
                 <div class="form-group">
                    <label for="email">Email</label>
                    <input type="name" class="form-control" id="email" name="email" maxlength="{{General::maxlength('email')}}" placeholder="{{ __('voyager::generic.email') }}" value="{{ $dataTypeContent->email ?? '' }}">
                </div>
                @endif

                <div class="form-group">
                    <label for="name">GSTIN/Business PAN</label>
                    <input type="text" class="form-control" id="gstin_udise" name="gstin_udise" placeholder="GSTIN/UDISE" value="{{ $dataTypeContent->gstin_udise ?? '' }}" maxlength="15">
                </div>
                <input type="hidden" name="all_values" id="all_values" value="{{$dataTypeContent}}">
                <?php
                $lisofTrurnover=array("less than 5 crores","greater than 5 and less than 50 crores","greater than 50 and less than 250 crores","greater than 250 crores");
               ?>
            <div class="form-group">

                <label for="name">Company Turnover</label>
                <select name="company_turnover" id="company_turnover"  class="form-control" >
               <option value="" >Select</option>
               <?php

                foreach($lisofTrurnover as $val)
                {
                    $selected="";
                    if($val ==  strtolower($dataTypeContent->company_turnover))
                    {
                        $selected="selected";
                    }
                    echo "<option value='".$val."' ".$selected.">".ucfirst($val)."</option>";
                }
                ?>
                </select>
            </div>
                <div class="form-group">
                    <label for="name">Does your company fall under MSME</label>
                    <div class="form-check">
							<label class="radio-inline">
							<input type="radio" id="msme_yes" name="is_company_msme" <?php echo  $dataTypeContent->is_company_msme == "YES" ? "checked":'' ?> value="Yes" >Yes
							</label>
							<label class="radio-inline">
							<input type="radio" id="msme_no" name="is_company_msme" <?php  echo  $dataTypeContent->is_company_msme == "NO" ? "checked":'' ?> value="No" >No
							</label>
						</div>
                </div>

                @php
                if (isset($dataTypeContent->locale)) {
                $selected_locale = $dataTypeContent->locale;
                } else {
                $selected_locale = config('app.locale', 'en');
                }

                @endphp
                <div class="form-group" style="display: none">
                    <label for="locale">{{ __('voyager::generic.locale') }}</label>
                    <select class="form-control select2" id="locale" name="locale">
                        @foreach (Voyager::getLocales() as $locale)
                        <option value="{{ $locale }}" {{ ($locale == $selected_locale ? 'selected' : '') }}>{{ $locale }}</option>
                        @endforeach
                    </select>
                </div>
                @php
                if(isset($dataTypeRows)){
                $row = $dataTypeRows->where('data_type_id',1)->where('field', 'status')->first();
                $options = $row->details;
                }
                @endphp

                @if(isset($options->options) && isset($dataTypeRows))
                <div class="form-group">
                    <label for="Status">{{ __('Status') }}</label>

                    <select class="form-control select2" id="status" name="status">
                        @foreach ($options->options as $key=>$value)
                        <option value="{{ $key }}" {{$dataTypeContent->status==$key ? 'selected' : ''}}>{{ $value }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="user_old_status" value="{{$dataTypeContent->status}}">
                </div>
                @endif
                @if(!empty($dataTypeContent->email_sent_at))
                <input type="hidden" name="email_sent_at" value="{{$dataTypeContent->email_sent_at}}">
                @endif
                @if(!empty($dataTypeContent->email_verified_at))
                <input type="hidden" name="email_verified_at" value="{{$dataTypeContent->email_verified_at}}">
                @endif
                @if(!empty($dataTypeContent->mobile_verified_at))
                <input type="hidden" name="mobile_verified_at" value="{{$dataTypeContent->mobile_verified_at}}">
                @endif

            </div>
        </div>
</div>

<div class="col-md-4">
    <div class="panel panel panel-bordered panel-warning">
        <div class="panel-body">
            <div class="form-group avatar_check_errclass">
                @if(isset($dataTypeContent->avatar))
                <img src="{{ filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Voyager::image( $dataTypeContent->avatar ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                @endif
                <input type="file" data-name="avatar" name="avatar" accept="image/*">
            </div>
        </div>
    </div>

    @isset($dataTypeContent->id)
    @if(Auth::user()->role_id==1)
    <div class="prepaid-postpaid-block">
        <div class="alert alert-success hide" role="alert"></div>
        <div class="alert alert-danger hide" role="alert"></div>
        <h3>Reports</h3>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <h4>Individual</h4>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <span>Prepaid</span>
                    <label class="switch">
                        <input type="checkbox" class="switchbutton" name="reports_individual" {{ $dataTypeContent->reports_individual==1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span>Postpaid</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <h4>Business</h4>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <span>Prepaid</span>
                    <label class="switch">
                        <input type="checkbox" class="switchbutton" name="reports_business" {{ $dataTypeContent->reports_business==1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span>Postpaid</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <h4>US Business</h4>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <span>Prepaid</span>
                    <label class="switch">
                        <input type="checkbox" class="switchbutton" name="reports_us_business" {{ $dataTypeContent->reports_us_business==1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span>Postpaid</span>
                </div>
            </div>
        </div>
        <h3>Collection Fees</h3>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <h4>Individual</h4>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <span>Prepaid</span>
                    <label class="switch">
                        <input type="checkbox" class="switchbutton" name="collection_fee_individual" {{ $dataTypeContent->collection_fee_individual==1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span>Postpaid</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <h4>Business</h4>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <span>Prepaid</span>
                    <label class="switch">
                        <input type="checkbox" class="switchbutton" name="collection_fee_business" {{ $dataTypeContent->collection_fee_business==1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span>Postpaid</span>
                </div>
            </div>
        </div>
        <h3>Additonal Customer Dues</h3>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <h4>Individual</h4>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <span>Prepaid</span>
                    <label class="switch">
                        <input type="checkbox" class="switchbutton" name="reports_individual_additional_customer" {{ $dataTypeContent->reports_individual_additional_customer==1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span>Postpaid</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <h4>Business</h4>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <span>Prepaid</span>
                    <label class="switch">
                        <input type="checkbox" class="switchbutton" name="reports_business_additional_customer" {{ $dataTypeContent->reports_business_additional_customer==1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span>Postpaid</span>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endisset
</div>
</div>
 <div class="form-action text-center">
    <button type="submit" class="btn btn-primary save">
       <!--  {{ __('voyager::generic.save')  }} -->
       Update Profile
    </button>
 </div>
</form>

<iframe id="form_target" name="form_target" style="display:none"></iframe>
<form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
    {{ csrf_field() }}
    <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
    <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
</form>
</div>
<style>
    .select2-container--default .select2-results__option[aria-disabled=true] {
        display: none;
    }
</style>
@stop
@section('javascript')

<script type="text/javascript">
$("#msme_yes").on("click",function(){
		$("#company_turnover").prop('required',true);

	});
	$("#msme_no").on("click",function(){
		$("#company_turnover").prop('required',false);
	});
    function blockSpecialChar(myfield, e) {

        var key;
        var keychar;
        if (window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

        keychar = String.fromCharCode(key);

        if ((("~!@#$^&*_+}{'^]\[@?=>;:/()%|\/<>{}[]").indexOf(keychar) > -1)) {

            return false;
        } else {
            return true;
        }
    }
</script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $.validator.addMethod("alphaspace", function(value, element) {
            return this.optional(element) || /^[a-z ]+$/i.test(value);
        }, "Only alphabet and space allowed.");

        $.validator.addMethod("alphanumdashspace", function(value, element) {
            return this.optional(element) || /^[a-z0-9\- ]+$/i.test(value);
        }, "Only alphabets,numbers,- and space are allowed.");

        $.validator.addMethod("mobile_number_india", function(value, element) {
            return this.optional(element) || /^[6789]\d{9}$/i.test(value);
        }, "Please enter a valid number.");

        $.validator.addMethod("check_gstin", function(value, element) {
            if (value.toString().length == 10) {
                var valueToString = value.toString().toUpperCase();
                // var fourthChar = valueToString.charAt(3);
                // var allowedCharsAtFourthPosition = ["C","H","A","B","G","J","L","F","T"];
                if (valueToString) {
                    return this.optional(element) || /^[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}$/i.test(value);
                } else {
                    return false;
                }

            } else {
                return this.optional(element) || /^[0-3|9]{1}[0-9]{1}[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(value);
            }
        }, "Please enter a valid GSTIN/Business PAN.");

         $.validator.addMethod("file_upload", function(value, element) {
        var flag = true;
        var error_count = 0;
        $("[name^=avatar]").each(function(i, j) {
            $(this).parent('.avatar_check_errclass').find('label.error').remove();
            var thisValue = $(this).val();
            var pattern = /(.*png$)|(.*jpg$)|(.*jpeg$)$/i;
            var check_pattern = pattern.test(thisValue);
            if (thisValue != "") {
                if (!check_pattern) {
                    error_count++;
                    $(this).parent('.avatar_check_errclass').append('<label  id="duedate_check' + i + '-error" class="error">Invalid File Format.</label>');
                }
            }

        });
        var error_count_flag = error_count > 0 ? false : true;
        return error_count_flag;
    }, "");
    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
 });
        var validator = $("#msform").validate({
            rules: {
                name: {
                    maxlength: {{General::maxlength('name')}},
                    alphaspace: true
                },
                business_name: {
              required: true,
              alphanumdashspace:true,
              remote: {
                          url: "/businessname_validation",
                          type: "post",
                data: { business_name:

                  function () { return $("#business_name").val();
                              }

                 }
                      }
            },
                mobile_number: {
                    maxlength: 10,
                    mobile_number_india: true
                },
                branch_name: {
                    maxlength: 50,
                    alphaspace: true
                },
                gstin_udise: {
                    maxlength: 15,
                    minlength: 2,
                    required: true,
                    check_gstin: true
                },
                avatar: {
                file_upload: true,
            },
            },messages: {
            company_name: {
                    remote:"Business name is not valid"
                  }
                }
        });


        $("input[name=skip_optional_fields]").on('change', function() {
            checkOptionalFields();

        });

        $('input[class^="switchbutton"]').change(function() {
            var field = $(this).attr("name");
            var value;
            if (this.checked) {
                value = 1;
            } else {
                value = 0;
            }
            $.ajax({
                method: 'post',
                url: "{{url('user_prepaid_postpaid')}}",
                data: {
                    id: "{{ isset($dataTypeContent->id) ? $dataTypeContent->id : '' }}",
                    field: field,
                    value: value,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data == "success") {
                        $('.alert.alert-danger').addClass('hide');
                        $('.alert.alert-success').html("Value Updated Successfully");
                        $('.alert.alert-success').removeClass('hide');
                    } else {
                        $('.alert.alert-success').addClass('hide');
                        $('.alert.alert-danger').html("Something Went Wrong");
                        $('.alert.alert-danger').removeClass('hide');
                    }
                }
            });
            $('.alert.alert-success').addClass('hide');
            $('.alert.alert-danger').addClass('hide');
        });

    });
</script>

@stop
