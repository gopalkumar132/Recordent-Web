@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Update Profile')

@section('page_header')

  <style>
      .field-icon {
       float: right;
       margin-left: -25px;
       margin-top: -24px;
       margin-right: 10px;
       position: relative;
       z-index: 2;
      }

      .container{
       padding-top:50px;
       margin: auto;
      } 
      label.error{position:static;}
      .update-profile-heading .page-title {
        display: block;
        margin: 3px 0px 15px 0px;
        padding: 12px 0px 15px 0px;
      }
      .update-profile-heading {
        text-align: center;
      }

      .update-profile-heading p {
         font-weight: 600;
       }
       input[type="text"],textarea{text-transform: uppercase};

      .modal {
        display: none; 
        position: fixed; 
        z-index: 1; 
        padding-top: 100px; 
        width: 100%; 
        height: 100%;
      }

      .modal-content {
        width: 100%;
        margin-top: 25%;
       }

      @media (max-width:580px) {
       .modal-content {
         width: 80%;
         height: 100%;
         margin-top: 30%;
         margin-left: 8%;
         word-break: break-all;
       }
      }
      label.success{color:green !important;}
  </style>
  <div class="update-profile-heading">
    <h1 class="page-title">
        Member Bank Account Details
    </h1>
  </div>
  
  @if(session()->has('success'))
  <div class="alert alert-success">
    {{ session()->get('success') }}
  </div>
  @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
            </ul>
        </div>
    @endif
@stop
@section('content')
<?php //var_dump(Auth::user()->email);
//dd(Auth::user()->type_of_business);
 ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<div class="page-content container-fluid">
 <div class="row">
  <div class="col-md-12">
   <div class="panel panel-bordered">
    <div class="submitdues-mainbody">
     <div class="panel-body">
      <form action="{{route('update-bank-details-store')}}" method="POST" id="edit_profile_form" enctype="multipart/form-data">
        @csrf
        
               <div class="col-md-12">
                 <div class="form-group">
                  <label for="contact_phone">Bank Account Number</label>
                  <input type="password" class="form-control" name="account_number" id="account_number" value="{{ old('account_number',Auth::user()->account_number) }}" placeholder="Account Number" required>
                  <br>
                </div>
              </div>
              <div class="col-md-12">
                 <div class="form-group">
                  <label for="contact_phone">Re-Enter Account Number</label>
                  <input type="text" class="form-control" name="account_number_confirmation" id="account_number_confirmation" value="{{ old('account_number',Auth::user()->account_number) }}" placeholder="Re-Enter Account Number" required>
                  <br>
                </div>
              </div>
              <div class="col-md-12">
                 <div class="form-group">
                  <label for="contact_phone">IFSC Code</label>
                  <input type="tel" class="form-control" name="ifsc_code" id="ifsc_code" value="{{ old('ifsc_code',Auth::user()->ifsc_code) }}" placeholder="IFSC Code" required maxlength="11">
                  <br>
                </div>
              </div>
               <div class="col-md-12">
                 <div class="form-group">
                  <label for="contact_phone">Account Holder Name</label>
                  <input type="tel" class="form-control" name="account_holder_name" id="account_holder_name" value="{{ old('account_holder_name',Auth::user()->account_holder_name) }}" placeholder="Account Holder Name" required>
                  <br>
                </div>
              </div>
             <div class="col-md-12">
              <div class="col-md-6">
                     <image class="download_img" src="https://image.flaticon.com/icons/png/128/109/109612.png">
                
                  <div class="form-group proofofdue_check_errclass files color">
                      <label>Upload Cancelled Cheque</label>
                      <input type="file"   class="form-control mydrop filesImg responsive" name="bank_check_proof"  accept='.jpg,.jpeg,.png' style="text-align:center !important;border-color: #ecf7fc;background-color: #ecf7fc;border:dashed;">
                      <p for="contact_phone">Note: Only png,jpeg files are allowed <span id="imgError" style="color:red;"></span></p>
                      <input type="hidden" name="old_file" value="{{Auth::user()->bank_check_proof}}">

                  </div>
                </div>
                <div class="col-md-6 img_uploaded_div">
                   @if(isset(Auth::user()->bank_check_proof))
              
                <!--  <a style="text-decoration:underline; font-weight: 600 !important;" target="_blank" href={{asset("storage")}}/{{Auth::user()->bank_check_proof}}>View/Download</a> -->
                 <img class="uploaded_image" src="{{asset("storage")}}/{{Auth::user()->bank_check_proof}}">
              
              @endif
              </div>
                </div>
           <div class="form-action">
            <button type="submit" class="btn btn-primary btn-blue" id="update-profile-check">Update Bank Details</button>
           </div>                                 
     </form>
    </div>
</div>
</div>
</div>
</div>
</div>


  
<script src="{{asset('js/jquery.validate.min.js')}}"></script>

<script type="text/javascript">


  $.validator.addMethod("ifsc_valid", function(value, element) {
        return this.optional(element) || /^[A-Z]{4}[0][A-Z0-9]{6}$/i.test(value);
    }, "Please enter valid IFSC code.");

    $.validator.addMethod("alphaspace", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Only alphabet and space allowed."); 
  
  var validator = $('#edit_profile_form').validate({
    ignore: '',
        rules: {
      
      account_number: {
        required: true,
        number:true,
        minlength : 9,
        maxlength : 18
            },
      account_number_confirmation : {
        minlength : 5,
        equalTo : "#account_number"
      },
      ifsc_code : {
       required: true,
       ifsc_valid : true,
      },
      account_holder_name : {
       required: true,
       alphaspace:true,
       minlength:3,
       maxlength:80
      },
    
            
        },
    messages: {
      account_number_confirmation : {
        equalTo : "Bank Account Number does not match"
       }
      }
    });
  

</script>
<style>

  .uploaded_image {
    width: 200px;
    height: auto;
    clear: both;
    display: block;
    padding: 2px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
}
img {
    vertical-align: middle;
}
.img_uploaded_div{
  top: 40px;
}



 .filesImg input {
    outline: 2px dashed #92b0b3;
    outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear;
    padding: 120px 0px 85px 35%;
    text-align: center !important;
    margin: 0;
    width: 100% !important;
}
.filesImg input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
 }

/* .filesImg:after {  pointer-events: none;
    position: absolute;
    top: 38px;
    left: 0;
    width: 50px;
    right: 0;
    height: 56px;
    content: "";
    background-image: url(https://image.flaticon.com/icons/png/128/109/109612.png);
    display: block;
    margin: 0 auto;
    background-size: 100%;
    background-repeat: no-repeat;
} */
input[type=file] {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 838px;
}

.color input{ background-color:#f1f1f1;}
.filesImg:before {
    position: absolute;
    bottom: 43px;
    left: 0px;
    pointer-events: none;
    width: 100%;
    right: 0;
    height: 57px;
    content: "Click or drag and drop here. ";
    display: block;
    margin: 0 auto;
    font-size: 18px;
    color: #2ea591;
    font-weight: 600;
    text-transform: lowercase;
    text-align: center;
}

.voyager input[type=file] {
    padding-left: 224px !important;
    height: 135px !important;
    padding-top: 98px !important;
}



@media screen and (min-width: 1850px) and (max-width: 2000px) {
    .voyager input[type=file] {
        padding-left: 283px !important;
}
}

@media screen and (min-width: 1200px) and (max-width: 1400px) {
    .voyager input[type=file] {
        padding-left:146px !important;}
        input[type=file] {
            width: 568px;}

}
@media only screen and (max-width: 600px) {
    .voyager input[type=file] {
    padding-left: 3px !important;
    height: 137px !important;
    padding-top: 89px !important;
    width: 234px;

}
.remove-submitdues{
    margin-top: 10px !important;
}
.filesImg:before {
    position: absolute;
    bottom: 52px;
    left: 0px;
    pointer-events: none;
    width: 100%;
    right: 0;
    height: 80px;
    content: "Click or drag and drop here. ";
    display: block;
    margin: 0 auto;
    font-size: 14px;
    color: #2ea591;
    font-weight: 600;
    text-transform: lowercase;
    text-align: center;}
}
.upload-arrow {
  position:absolute;
  margin-left:250px;
  margin-top: 15px;
  
}
.download_img {  pointer-events: none;
    position: absolute;
    top: 38px;
    left:0px;
    width: 50px;
    right: 0;
    height: 56px;
    content: none;
    display: block;
    margin: 0 auto;
    background-size: 100%;
    background-repeat: no-repeat;
}
.download_img_addrec
{  pointer-events: none;
    position: absolute;
    top: 38px;
    left: 0;
    width: 50px;
    right: 0;
    height: 56px;
    content: none;
    display: block;
    margin: 0 auto;
    background-size: 100%;
    background-repeat: no-repeat;
}
</style>

@endsection