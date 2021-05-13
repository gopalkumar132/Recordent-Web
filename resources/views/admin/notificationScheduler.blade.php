@extends('voyager::master')


@section('page_header')
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script> -->


<h1 class="page-title" style="display: none">
    <i class="voyager-upload"></i> API Screens
</h1>
@stop

@section('content')

<style>
.input-container input {
    border: none;
    box-sizing: border-box;
    outline: 0;
    padding: .75rem;
    position: relative;
    width: 100%;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    color: transparent;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
}
</style>

<div class="nofication_module">
    <h1>Notification Scheduler</h1>

    <div class="config_block">
        <form action="{{ url('admin/add_notificationScheduler') }}" name="add_notification" id="add_notification" method="POST">
            @csrf
            <div class="block">
                <div class="row">

                    <div class="form-group col-md-3">
                        <label>Customer Type</label>

                        <select class="form-control customer_type" name="customer_type" id="customer_type">
                            <option>Select Type</option>
                            @foreach($customer_types as $customer_type)
                            <option value="{{$customer_type['id']}}">{{$customer_type->name}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group col-md-3">
                        <label for="sms_02">Notification Date</label>
                        {{-- <input type="text" name="notification_date11" id="notification_date11" class="form-control datepicker collectionsetevent"  data-date-format="YYYY-MM-DD" aria-controls="dataTable" value=""> --}}
                        <!-- <input type="text" name="notification_date" id="notification_date"
                                class="form-control datepicker disablePastdate collectionsetevent" data-date-format="YYYY-MM-DD" required
                                aria-controls="dataTable" value="" placeholder="YYYY-MM-DD" min="<?php echo date('Y-m-d'); ?>"> -->

                        <input type="date" name="notification_date" id="notification_date" class="form-control date_input" min="<?php echo date('Y-m-d'); ?>" data-date-inline-picker="true">
                    </div>
                    <?php
                    $time =  date("H:i");
                    ?>
                    <div class="form-group col-md-3">
                        <label for="sms_03">Start Time</label>
                        <select class="form-control" name="start_time" id="start_time">
                            <option>Select Time</option>
                            @for ($i = 0; $i <= 23; $i++)
                                @if($i> $time || true)
                                    @if ($i < 10)
                                    <option class="start_time_option" value="{{ $i }}">0{{ $i }} : 00</option>
                                    @else
                                    <option class="start_time_option" value="{{ $i }}">{{ $i }} : 00</option>
                                    @endif
                                @endif
                            @endfor
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="sms_04">Notification Type</label>
                        <select class="form-control" name="notification_type" id="notification_type">
                            <option>Select Type</option>
                            @foreach($notification_types as $notification_type)
                            <option value="{{$notification_type['id']}}">{{$notification_type->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-12 text-center repeat_block">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#repeat_model" disabled>
                            Repeat
                        </button>

                        <!-- Modal -->
                        <div class="modal " id="repeat_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Repeat</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">


                                        <div class="form-group form-group02">
                                            <label>Repeats</label>
                                            <select class="form-control" id="repeats" name="repeats" onchange="showDiv(this)">

                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                        </div>

                                        <div class="form-group form-group03" id="daily">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" checked name="inlineRadioOptions" id="inlineRadio51" value="option1">
                                                <label class="form-check-label" for="inlineRadio51">Every</label>
                                            </div>
                                            <div class="form-group form-check-inline form-checkes-input">
                                                <input type="number" value="" class="form-control" name="every_days">
                                            </div>
                                            <div class="form-group form-check-inline">
                                                Days
                                            </div>
                                        </div>


                                        <div class="form-group groupevery" id="weekly" style="display: none">
                                            <div class="form-check form-check-inline" onclick="Customdays('every_weekday')">
                                                <input class="form-check-input" type="radio" name="weekly_notification_days" id="inlineRadio61" value="every_weekday">
                                                <label class="form-check-label" for="inlineRadio61"> Every
                                                    Weekday</label>
                                            </div>

                                            <div class="form-check form-check-inline" onclick="Customdays('every_weekend')">
                                                <input class="form-check-input" type="radio" name="weekly_notification_days" id="inlineRadio62" value="every_weekend">
                                                <label class="form-check-label" for="inlineRadio62">Every
                                                    Weekend</label>
                                            </div>


                                            <div class="form-check form-check-inline" onclick="Customdays('custom')">
                                                <input class="form-check-input" type="radio" name="weekly_notification_days" id="inlineRadio635" value="custom">
                                                <label class="form-check-label" for="inlineRadio635">Custom</label>

                                            </div>

                                            <p class="week-text" style="display: none" id="every_weekday">The
                                                Notification repeats from Monday to Friday.</p>
                                            <p class="week-text" style="display: none" id="every_weekend">The
                                                Notification repeats on Saturday and Sunday.</p>

                                            <div class="weeks-check" style="display: none" id="custom">

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="custom_days[]" id="weekscheck01" value="sunday">
                                                    <label class="form-check-label" for="weekscheck01">S</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="custom_days[]" id="weekscheck02" value="monday">
                                                    <label class="form-check-label" for="weekscheck02">M</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="custom_days[]" id="weekscheck03" value="tuesday">
                                                    <label class="form-check-label" for="weekscheck03">T</label>
                                                </div>


                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="custom_days[]" id="weekscheck04" value="wednesday">
                                                    <label class="form-check-label" for="weekscheck04">W</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="custom_days[]" id="weekscheck05" value="thursday">
                                                    <label class="form-check-label" for="weekscheck05">T</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="custom_days[]" id="weekscheck06" value="friday">
                                                    <label class="form-check-label" for="weekscheck06">F</label>
                                                </div>


                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="custom_days[]" id="weekscheck07" value="saturday">
                                                    <label class="form-check-label" for="weekscheck07">S</label>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="ends">
                                            <h4 class="modiletitle">Ends</h4>

                                            <div class="form-group">
                                                <div class="form-check form-check">
                                                    <input class="form-check-input" type="radio" name="ends" id="ends01" value="never" onchange="endchanges('never')">
                                                    <label class="form-check-label" for="ends01">Never</label>
                                                </div>
                                            </div>

                                            <div class="form-group sec-date">

                                                <div class="form-check form-check-inline">
                                                    <div class="form-check form-check">
                                                        <input class="form-check-input" type="radio" name="ends" id="ends02" value="on" onchange="endchanges('on')">
                                                        <label class="form-check-label" for="ends02">On</label>
                                                    </div>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input type="text" name="ends_on" id="ends_on" class="form-control datepicker collectionsetevent" data-date-format="YYYY-MM-DD" aria-controls="dataTable" value="" disabled>
                                                </div>
                                            </div>

                                            <div class="form-group afterdiv">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="ends" id="ends03" value="after" onchange="endchanges('after')">
                                                    <label class="form-check-label" for="ends03">After</label>
                                                </div>
                                                <div class="form-group form-check-inline">
                                                    <input type="number" name="ends_after" id="ends_after" value="" class="form-control" disabled>
                                                </div>
                                                <div class="form-group form-check-inline">
                                                    Occurrence
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="text-align: left;">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="subblock block_disabled">
                    <h3>Inclusions</h3>

                    <div class="singleline">
                        <p>Where</p>
                        <select class="form-control">
                            <option>Amount Due</option>
                            <option>Customer Due Date</option>
                            <option>Customer Reported Date</option>
                            <option>Overdue status</option>
                            <option>Collection Start Date</option>
                        </select>
                        <p>is</p>
                        <select class="form-control">
                            <option>Equal to</option>
                            <option>Between</option>
                            <option>Greater than</option>
                            <option>Lesser than</option>
                        </select>

                        <select class="form-control">
                            <option>System Date</option>
                            <option>User defined value</option>
                            <option>Date From</option>
                        </select>

                        <p>and</p>

                        <select class="form-control">
                            <option>System Date</option>
                            <option>User defined value</option>
                            <option>Date From</option>
                            <option>Date To</option>
                        </select>
                    </div>
                </div>


                <div class="subblock">
                    <h3>Exclusions</h3>
                    <div class="subsubblock">
                        <h4>Customer Level <span>(To exclude a customer, please select a member first)</span> </h4>

                        <div class="inline">
                            <div class="member_add" id="member_add">
                                <div class="singleline">
                                    <p>If Member is</p>
                                    <select class="form-control customer_level_member select2" data-child="customer1" id="member" name="member[]">
                                        <option value="0">Select User</option>
                                        @foreach($users as $user)
                                        <option value="{{$user['id']}}">{{$user['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="singleline">
                                    <p>and Customer is</p>
                                    <select class="ms_select customer_level_members_customer" id="customer1" name="customer[0][]" multiple></select>
                                </div>
                            </div>
                            <div class="singleline singlelinebutoon">
                                <span class="btn btn-primary append_div"> + </span>
                            </div>
                            <div id="parent_div"></div>

                            <div style="clear:both;"></div>

                        </div>
                    </div>
                    <br>

                    <div class="subsubblock">
                        <h4>Member Level</h4>

                        <div class="singleline">
                            <p>If Member is</p>
                            <select class="ms_select" name="member_level[]" multiple>
                                @foreach($users as $user)
                                <option value="{{$user['id']}}">{{$user['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="subsubblock">
                        <h4>Bussiness Type</h4>

                        <div class="singleline">
                            <p>If Bussiness type is</p>
                            <select class="ms_select" name="bussiness_type[]" multiple>
                                @foreach($sectors as $sector)
                                <option value="{{$sector['id']}}">{{$sector['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="radio_block text-center" style="margin:10px 0 30px 0;">
                        {{-- <form> --}}
                        <label class="radio-inline">
                            <input type="radio" name="optradio" checked>Transactional
                        </label>
                        <label class="radio-inline disabled01">
                            <input type="radio" class="disabled" disabled="disabled" name="optradio">Promotional
                        </label>

                        {{--
                            </form> --}}

                    </div>


                    <div class="text-center">

                        <div class="form-group">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#templates01">
                                Templates
                            </button>

                            <!-- Modal -->
                            <div class="modal " id="templates01" tabindex="-1" role="dialog" aria-labelledby="templates01" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Templates</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="templates-layout" id="divTemplatesLayout">
                                                <!-- Dynamic content -->
                                            </div>
                                        </div>
                                        {{-- <div class="modal-footer" style="text-align: left;">
                                            <button type="button" class="btn btn-success" style="margin-bottom: 15px;">AddNew</button>
                                            <br />
                                            <button type="button" class="btn btn-primary">Save</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#templates11" disabled>View Members</button>


                        <div class="modal " id="templates11" tabindex="-1" role="dialog" aria-labelledby="templates11" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">View Members</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="templates-layout">
                                            <ul class="view_list">
                                                <li class="header"><span>Phone</span><span>Name</span> </li>
                                                <li><span>280000500</span><span>Prasad</span> <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a>
                                                </li>
                                                <li><span>280000500</span><span>Rishi</span> <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a>
                                                </li>
                                                <li><span>280000500</span><span>kowshik</span> <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="text-align: left;">
                                        <button type="button" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal view_exclusions_member" id="view_exclusions_member" tabindex="-1" role="dialog" aria-labelledby="templates11" aria-hidden="true" style="display: none;">
                            <div id="view_member">
                                {{-- @include('admin.notificationViewExclusionsMembers'); --}}
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">Schedule</button>
                    </div>
                </div>
        </form>
    </div>








    <div class="block">
        <h3 class="sech_title">Scheduled Notifications</h3>



        <div class="report_search">

            <div class="form-group block_001">
                <label for="sms_02">Date From</label>
                <input type="text" name="date_from" id="date_from" class="date_from form-control datepicker collectionsetevent" data-date-format="YYYY-MM-DD" aria-controls="dataTable" value="">
            </div>


            <div class="form-group block_001">
                <label for="sms_02">Date To</label>
                <input type="text" name="date_to" id="date_to" class="date_to form-control datepicker collectionsetevent" data-date-format="YYYY-MM-DD" aria-controls="dataTable" value="">
            </div>


            <div class="form-group block_002">
                <label for="sms_04">Customer Type</label>
                <select class="form-control search_customer_type" name="search_customer_type" id="search_customer_type">
                    <option value="0">Select Type</option>
                    <option value="All">All</option>
                    @foreach($customer_types as $customer_type)
                    <option value="{{$customer_type['id']}}">{{$customer_type->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group block_002">
                <label for="sms_04">Notification Type</label>
                <select class="form-control search_notification_type" name="search_notification_type" id="search_notification_type">
                    <option value="0">Select Type</option>
                    <option value="All">All</option>
                    @foreach($notification_types as $notification_type)
                    <option value="{{$notification_type['id']}}">{{$notification_type->name}}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-success search">Search</button>
                <button type="button" class="btn btn-danger reset">Reset</button>
            </div>
        </div>


        <div class="reports_scheduler">
            <div class="table_gird notificaion_table">
                @include('admin.notificationSchedulerTable');
            </div>
        </div>


    </div>


</div>


<div class="modal " id="emailModal" tabindex="-1" role="dialog" aria-labelledby="templates01" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="formNewTemplate">
            <div class="modal-header">
                <h5 class="modal-title">Create Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="height: 420px;">
                <div class="templates-layout" id="createNewTemplate">
                    <div class="form-group">
                        <textarea id="email_template" name="newEmailTemplate" class="form-control"></textarea>
                    </div>
                    <span id="errorSendEmail"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnCreateNewEmailTemp" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
    </div>
</div>

@endsection



<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<script type="text/javascript">
    function showDiv(select) {
        if (select.value == "weekly") {
            document.getElementById('weekly').style.display = "block";
            document.getElementById('daily').style.display = "none";
        } else if (select.value == "daily") {
            document.getElementById('daily').style.display = "block";
            document.getElementById('weekly').style.display = "none";
        } else {
            document.getElementById('daily').style.display = "none";
            document.getElementById('weekly').style.display = "none";
        }
    }

    function Customdays(value) {
        if (value == "every_weekday") {
            document.getElementById('every_weekday').style.display = "block";
            document.getElementById('every_weekend').style.display = "none";
            document.getElementById('custom').style.display = "none";
        } else if (value == "every_weekend") {
            document.getElementById('every_weekend').style.display = "block";
            document.getElementById('every_weekday').style.display = "none";
            document.getElementById('custom').style.display = "none";
        } else if (value == "custom") {
            document.getElementById('custom').style.display = "block";
            document.getElementById('every_weekday').style.display = "none";
            document.getElementById('every_weekend').style.display = "none";
        }
    }

    function endchanges(value) {
        console.log(value);
        if (value == 'on') {
            $('#ends_after').attr('disabled', 'disable');
            $('#ends_on').removeAttr('disabled');
        } else if (value == 'after') {
            $('#ends_on').attr('disabled', 'disable');
            $('#ends_after').removeAttr('disabled');
        } else {
            $('#ends_after').attr('disabled', 'disable');
            $('#ends_on').attr('disabled', 'disable');
        }
    }

    function close_model() {
        $('#repeat_model').modal('hide');
    }

    function append_string() {
        var length = $("#parent_div > div").length + 2;
        var options = $('#member').html();
        var html = '';
        html += '<div class="member_add'+length+'" id="member_add">';
        html += '<div class="singleline">';
        html += '<p>If member is</p>';
        html += '<select data-child="customer' + length + '" class="form-control customer_level_member" name="member[]">';
        html += options;
        html += '</select>';
        html += '</div>';
        html += '<div class="singleline">';
        html += '<p>and Customer is</p>';
        html += '<select id="customer' + length + '" class="ms_select customer_level_members_customer" name="customer[' + (length - 1) + '][]" multiple>';
        html += '</select>';
        html += '</div>';
        html += '<div class="singleline singlelinebutoon btn btn-sm btn-danger delete_div" data-id = "'+length+'">';
        html += '<i class="voyager-trash"></i>'
        // html += '<span class="btn btn-primary">'+'-'+'  </span>';
        html += '</div>';
        html += '</div>';

        return html;
    }

    function hideModalNShowOther() {
        console.log("coming here");
        $(".close").trigger('click');
        $("#emailModal").modal('show');
    }

//     function querySucceeded(data) {
//      posts = [];
//      posts = sortByKeyDesc(data, "person_name");
//      return posts;
//   }

    $(document).ready(function() {
        // $('#notification_date').click(function() {
        //     $('#notification_date').datepicker("show");
        // });
        $("#notification_date").change(function() {
            var CurrentDate = new Date();
            var SelectedDate = new Date($(this).val());
            console.log(CurrentDate + ' ' + SelectedDate);
            if(SelectedDate <= CurrentDate){
                var d       = new Date();
                var hour    = d.getHours();
                $("#start_time > option").each(function() {
                    if($(this).attr('value') <= hour){
                        $(this).css('display', 'none');
                    }
                });
            }
            else{
                $('.start_time_option').css('display', 'block');
            }
        });

        $(".append_div").click(function() {
            var new_div = append_string();
            var length = $("#parent_div > div").length + 2;
            if(length>10){
                return false;
            }
            // $(".member_add").clone().appendTo("#parent_div");
            // $("#parent_div").append($("#member_add").clone());
            $("#parent_div").append(new_div);
            $('.ms_select').multiselect({
                nonSelectedText: 'Select',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeSelectAllOption: true
                // buttonWidth:'200px'
            });
            $('.ms_select').multiselect('refresh');
        });

        var temp_length = $("#parent_div > div").length + 2;
        console.log(temp_length);
        $('body').on('click', '.delete_div', function() {

            var length = $(this).attr('data-id');
            var str = 'member_add'+length;
            $('.'+str).remove();

        });

        $('body').on('click', '.search', function() {
            console.log("test");

            var date_from = $('.date_from').val();
            var date_to = $('.date_to').val();
            var search_customer_type = $('.search_customer_type').val();
            var search_notification_type = $('.search_notification_type').val();
            console.log(date_from);
            console.log(date_to);
            console.log(search_customer_type);
            console.log(search_notification_type);
            // return false;
            $.ajax({
                url: '{{url("admin/seach_notificationScheduler")}}',
                type: 'POST',
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                data: {
                    "date_from": date_from,
                    "date_to": date_to,
                    "search_customer_type": search_customer_type,
                    "search_notification_type": search_notification_type,

                },

                success: function(data) {
                    $('#notification').html(data);
                },
                error: function(data) {
                    var responseText = jQuery.parseJSON(data.responseText);
                    if (responseText.error) {
                        toastr.error(responseText.error);
                    }
                    if (responseText.errors) {
                        $.each(responseText.errors, function(key, value) {
                            toastr.error(value);
                            return false;
                        });
                    }
                }
            });

        });

        $('body').on('click', '.reset', function() {
            $('#date_from').val('');
            $('#date_to').val('');
            $('.search_customer_type').prop('selectedIndex',0);
            $('.search_notification_type').prop('selectedIndex',0);
            $('.search').trigger('click');

        });

        $('body').on('click', '.stop', function() {
            console.log("test");
            var id = $(this).attr('data-id');
            var value = $(this).attr('data-value');
            console.log(value);

            $.ajax({
                url: "/admin/stop_notificationScheduler/" + id +"/"+ value,
                type: 'GET',
                success: function(data) {
                    console.log(data.message);
                    // return false;
                    location.reload();
                    toastr.success(data.message);
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            });
        });

        $('body').on('click', '.delete', function() {
            console.log("test");

            var r = confirm("Are you sure ?");
            if (r == true) {
                var id = $(this).attr('data-id');
                console.log(id);
                $.ajax({
                    url: "/admin/delete_notificationScheduler/" + id,
                    type: 'GET',
                    success: function(data) {
                        console.log("#####");
                        location.reload();
                        toastr.success('Notification Deleted Successfully');
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
            }

        });

        $('body').on('change', '.customer_type', function() {
            var customer_type_id = $('#customer_type').val();
            var member = $('#member').val();

            console.log("test");
            console.log(customer_type_id);
            console.log(member);

            $.ajax({
                url: "/admin/customer_notificationScheduler/" + customer_type_id + '/' + member,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('.customer_level_members_customer').empty();
                    $('.customer_level_members_customer').multiselect('rebuild');
                    $('.customer_level_member').val(0);
                    $('.customer_level_member').trigger('change');
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            });

        });

        $('body').on('change', '.customer_level_member', function() {
            var customer_type_id = $('#customer_type').val();
            var member = $(this).val();
            var element = $('#' + $(this).attr('data-child'));

            // console.log($(this).attr('data-child'));
            // console.log(element);

            $.ajax({
                url: "/admin/customer_notificationScheduler/" + customer_type_id + '/' + member,
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(data) {
                    console.log(data);
                    // student = [];
                    // student = querySucceeded(data.student);
                    // console.log(student);

                    if (data.student) {
                        element.empty();
                        $.each(data.student, function(k, v) {
                            var option1 = new Option(v.person_name, v.id, false, false);
                            element.append(option1).trigger('change');
                        });
                        element.multiselect('rebuild');
                    }
                    if (data.business_customer) {
                        element.empty();
                        $.each(data.business_customer, function(k, v) {
                            var option1 = new Option(v.company_name, v.id, false, false);
                            element.append(option1).trigger('change');
                        });
                        element.multiselect('rebuild');
                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            });

        });

        $('body').on('click', '.view_exclusions', function() {

            var id = $(this).attr('data-id');
            console.log(id);
            // $('#view_exclusions_member').modal('show');
            $.ajax({
                    url: "/admin/view_exclusions_members/" + id,
                    type: 'GET',
                    success: function(data) {
                        $('#view_member').html(data);
                        $('#view_exclusions_member').modal('show');
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });

        });

        $('body').on('click', '.close_view_exclusions_member', function() {
            $('#view_exclusions_member').modal('close');

        });

        $("#notification_type").on('change', function() {
            const notification_type = $("#notification_type").val();
            $.ajax({
                url: "{{ url('admin/getTemplateByType') }}",
                type: "GET",
                data: {template_type: notification_type},
                success: function(data) {
                    const jsonData = JSON.parse(data);
                    if(jsonData.hasOwnProperty('success')) {
                        let template_html = '';
                        $.each(jsonData.templates, (key, value)=> {
                            // console.log(value);
                            template_html += `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadioOptions-`+value.id+`" value="inlineRadioOptions">
                                <label class="form-check-label" for="inlineRadioOptions-`+value.id+`">`+value.content+`</label>
                            </div>`;
                        });
                        if(jsonData.hasOwnProperty('type') && jsonData.type == 'Email') {
                            template_html += `
                                <div class="form-check">
                                    <button id="btnCreateNewTemplate" type="button" class="btn" onclick="hideModalNShowOther()"><i class="fa fa-envelope mr-1 color-red" aria-hidden="true"></i>
                                        Create Custom Template
                                    </button>
                                </div>
                            `;
                        }
                        $("#divTemplatesLayout").html(template_html);
                    }
                }
            })
        });

        $("#btnCreateNewEmailTemp").on('click', function() {
            const emailTmp = tinymce.activeEditor.getContent();
            $.ajax({
                url: "",
                data: {emailTmp: emailTmp},
                type: "POST",
                function(data) {
                    
                }
            });
        })
    });
</script>

<script type="text/javascript" src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
    function editor() {
        tinymce.init({
            selector: "#email_template",
            plugins: "link image lists autoresize",
            toolbar: "undo redo | bold italic underline | fontselect | numlist bullist | cut copy paste | alignleft aligncenter alignright alignjustify | indent outdent",
            height: 270
        });
    }

    window.addEventListener('load', editor, false);
    // window.addEventListener('resize', editor, false);
</script>