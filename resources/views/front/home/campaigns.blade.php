@extends('voyager::master')

@section('page_title', __('Campaigns'))

@section('page_header')

    <h1 class="page-title"><i class="voyager-mail"></i>Campaigns</h1>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
            </ul>
        </div>
    @endif
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block" id="alertmessage">
           <strong>{{ $message }}</strong>
        </div>
    @endif
@stop

@section('content')
    <style type="text/css">
        .errors
        {
            text-align: left;
            position: relative;
            margin-left: -30%;
        }
        label.error {
            position: relative;
        }
        .btn.btn-sm {
            color: #000 !important;
        }

        .navbar, .navbar .container-fluid, .nav.navbar-nav.navbar-right {
            display: block !important;
        }

        .navbar-fixed-top {
            z-index: 800;
            padding: 0rem !important;
            padding-left: 60px !important;
        }

        .app-container.expanded .navbar {
            padding-left: 0px !important;
        }

        .app-container.expanded .content-container .navbar-top {
            padding-left: 250px !important;
        }

        .app-container .content-container .side-body.padding-top {
            padding-top: 0px !important;
        }

        @media (max-width:768px) {
         .navbar-fixed-top {
                padding-left: 0 !important;
            }
        }

        .dropdown-toggle::after {
            display: none !important;
        }

    </style>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <style type="text/css">
    input[name='subject'] {text-transform: uppercase};
  </style>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <form action="{{route('admin.send-campaign-emails')}}"  id="campaigns" method="POST">

                            @csrf
                            <div class="submitdues-mainbody">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="promotion_type">Email Type</label><br>
                                            <select class="form-control" name="promotion_type" id="promotion_type">
                                                <option value="1">Campaign 1</option>
                                                <option value="2">Campaign 2</option>
                                                <option value="3">Campaign 3</option>
                                                <option value="4">Campaign 4</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="email_to">Email To</label><br>
                                            <select class="form-control" name="email_to" id="email_to">
                                                <option value="0">Members</option>
                                                <option value="1">Individual Customers</option>
                                                <option value="2">Business Customers</option>
                                                <option value="3">All Customers (Individual + Business)</option>
                                                <option value="4">All (Members + All Customers)</option>
                                                <option value="5">Custom</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="display: none;" id="custom_to_email_div">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="custom_email_ids">Custom: To Emails</label>
                                            <textarea class="form-control" id="custom_email_ids" name="custom_email_ids" rows="5" ></textarea>
                                            <small id="custom_emails" class="form-text text-muted">Note: Use comma (,) as a delimeter to seprate email ids.</small>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="subject">Email Subject</label>
                                            <input type="text" minlength="3" id="subject" maxlength="100" class="form-control" name="subject" required/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="email_content">Email Content</label>
                                            <textarea class="form-control" id="email_content" name="content" rows="5" maxlength="1000"  id="content"></textarea>
                                            <br>
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="form-action text-center">
                                            @if($show_send_button)
                                            <?php $disabled = ""; ?>
                                            @else
                                                <?php $disabled = "disabled"; ?>
                                            @endif
                                            <button type="submit" name="send" id="send_campaign_email_button" {{$disabled}} class="btn btn-primary btn-blue">SUBMIT</button>
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


<h1 class="page-title"><i class="voyager-mail"></i>UTM Containers</h1>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <form action=""  id="utm_containers" method="POST">

                            @csrf
                            <div class="submitdues-mainbody">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="subject">URL</label>
                                            <input type="text" minlength="3" id="utm_url" maxlength="200" class="form-control" name="utm_url" required/>
                                        </div>
                                        <span id="utm_url_error" style="color:red"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="subject">Medium</label>
                                            <input type="text" minlength="3" id="utm_medium" maxlength="100" class="form-control" name="utm_medium" required/>
                                        </div>
                                        <span id="utm_medium_error" style="color:red"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="subject">Source</label>
                                            <input type="text" minlength="3" id="utm_source" maxlength="100" class="form-control" name="utm_source"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="subject">ID</label>
                                            <input type="text" minlength="3" id="utm_id" maxlength="100" class="form-control" name="utm_id"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="subject">Campaign</label>
                                            <input type="text"  id="utm_campaign" maxlength="100" class="form-control" name="utm_campaign" required/>

                                        </div>
                                        <span id="utm_campaign_error" style="color:red"></span>
                                    </div>
                                </div>

                                <div class="row" id="utm_campaign_url_toggle">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="subject">UTM Campaign URL</label>
                                            <input type="text" minlength="3" id="utm_campaign_url" maxlength="100" class="form-control" name="utm_campaign_url"/>
                                            <button type="button" name="copy_utm_link" id="copy_utm_link"  class="btn btn-primary btn-blue" onclick="copyCampainLink();">Copy Link</button>
                                        </div>
                                    </div>


                                </div>

                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="form-action text-center">
                                            <button type="button" name="create_utm_link" id="create_utm_link"  class="btn btn-primary btn-blue">Create Link</button>
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





    <script src="{{asset('js/jquery.validate.min.js')}}"></script>

    <script>

        $("#campaigns").validate({
            rules: {
              subject : {
                required: true
              },
              content: {
                required: true
              }
            }
        });
        $('#utm_campaign_url_toggle').hide();
        $("#create_utm_link").on('click', function(){
            var requiredData = ['utm_url','utm_medium','utm_campaign'];
            var errorCount = 0;
            requiredData.forEach(function(item) {
            if($('#'+item).val()=="") {
              errorCount++;
              $('#'+item+'_error').html('This field is required');
            } else {
              $('#'+item+'_error').html('');
            }
          });

          if(errorCount==0) {
            $('#utm_campaign_url_toggle').show();
            var prepareCampaignUrl = $('#utm_url').val()+"?utm_medium="+$('#utm_medium').val()+"&utm_campaign="+$('#utm_campaign').val()+"&utm_id="+$('#utm_id').val()+"&utm_source="+$('#utm_source').val();
            $('#utm_campaign_url').val(prepareCampaignUrl);
          }
        });


        $(function(){
          $('#alertmessage').delay(2000).fadeOut();
        });

        $(function(){
            $('body').on('change', '#email_to', function(){
                var option = $(this).val();

                if (option == 5) {
                    $('#custom_to_email_div').show();
                } else {
                    $('#custom_to_email_div').hide();
                }
            });
        });

        $("#send_campaign_email_button").click(function (e) {
            $("#send_campaign_email_button").attr("disabled", true);
            $('#campaigns').submit();
        });

        $("a.dropdown-toggle span.caret").click(function(){
            $('li.dropdown.profile').toggleClass('open');
        });
    </script>

    <script type="text/javascript">
        $('#email_content').summernote({
        placeholder: 'Enter email content here.',
        tabsize: 2,
        height: 300
      });

      function copyCampainLink() {
        var copyText = document.getElementById("utm_campaign_url");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        alert("Copied the Link: " + copyText.value);
      }

    </script>


@endsection
