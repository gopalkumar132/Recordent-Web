<style type="text/css">
	.error{
		color: red;
	}
</style>
<html><head><meta charset="UTF-8"><title></title>
<!-- <link href="css/form.css" rel="stylesheet" type="text/css"><script src="js/validation.js"></script> -->
</head><body class="zf-backgroundBg"><!-- Change or deletion of the name attributes in the input tag will lead to empty values on record submission-->
<div class="zf-templateWidth"><form action='https://forms.zohopublic.com/recordent/form/Contactusforms/formperma/IkGTAxKiSplvDFhDYXhlIP6QCVYZB-sQaeczVoezGPM/htmlRecords/submit' name='form' method='POST' onSubmit='javascript:document.charset="UTF-8"; return zf_ValidateAndSubmit();' accept-charset='UTF-8' enctype='multipart/form-data' id='form'><input type="hidden" name="zf_referrer_name" value=""><!-- To Track referrals , place the referrer name within the " " in the above hidden input field -->
<input type="hidden" name="zf_redirect_url" value=""><!-- To redirect to a specific page after record submission , place the respective url within the " " in the above hidden input field -->
<input type="hidden" name="zc_gad" value=""><!-- If GCLID is enabled in Zoho CRM Integration, click details of AdWords Ads will be pushed to Zoho CRM -->
<div class="zf-templateWrapper"><!---------template Header Starts Here---------->
<!--<ul class="zf-tempHeadBdr"><li class="zf-tempHeadContBdr"><h2 class="zf-frmTitle"><em>Contact us form</em></h2>
<p class="zf-frmDesc"></p>
<div class="zf-clearBoth"></div></li></ul>--><!---------template Header Ends Here---------->
<!---------template Container Starts Here---------->
<div class="zf-subContWrap zf-topAlign"><ul>
<!---------Single Line Starts Here---------->
<!-- <tr><td style='width:100%;' > -->
<div class="form-group">
<li class="zf-tempFrmWrapper zf-small">
<div class="zf-tempContDiv"><span> <input type="text" name="SingleLine" class="form-control name" placeholder="Name" checktype="c1" value="" maxlength="80" fieldType=1 /></span> <p id="SingleLine_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li>
</div>
<!---------Single Line Ends Here---------->
<!---------Email Starts Here---------->  
<div class='form-group'>
<li class="zf-tempFrmWrapper zf-small">
<div class="zf-tempContDiv"><span> <input fieldType=9  type="email" maxlength="255" placeholder="Email" class="form-control email" name="Email" checktype="c5" value=""/></span> <p id="Email_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li>
</div>
<!---------Email Ends Here---------->
<!---------Phone Starts Here----------> 
<div class='form-group'>
<li  class="zf-tempFrmWrapper zf-small">
<div class="zf-tempContDiv zf-phonefld"><div
class="zf-phwrapper zf-phNumber"  
>
<span> <input type="text" compname="PhoneNumber" name="PhoneNumber_countrycode" class="form-control mobile" placeholder="Mobile" maxlength="20" checktype="c7" value="" phoneFormat="1" isCountryCodeEnabled=false fieldType="11" id="international_PhoneNumber_countrycode" valType="number" phoneFormatType="1"/>
</span>
<div class="zf-clearBoth"></div></div><p id="PhoneNumber_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li>
</div>
<!---------Phone Ends Here----------> 
<!---------Multiple Line Starts Here---------->
<div class="form-group">
<li class="zf-tempFrmWrapper zf-small">
<div class="zf-tempContDiv"><span> <textarea name="MultiLine" checktype="c1" maxlength="65535" class='form-control message' placeholder='Message'></textarea> </span><p id="MultiLine_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
</div><div class="zf-clearBoth"></div></li>
</div>
<!---------Multiple Line Ends Here---------->
</ul></div><!---------template Container Starts Here---------->
<ul>
<td colspan='2' style='text-align:center; padding-top:15px;font-size:12px;'>
	<li class="zf-fmFooter">
 <div class='text-center'>
 	<button type="submit" class="btn-send form" onclick="contact()" >Submit</button></div></li>
</td>
</ul></div>
<!-- 'zf-templateWrapper' ends --></form></div><!-- 'zf-templateWidth' ends -->
<script type="text/javascript">
    function contact()
    {
    var email = $('.email').val();
    var name = $('.name').val();
    var mobile = $('.mobile').val();
    var message = $('.message').val();
    if(email && name && mobile && message)
    {
        $.ajax({
       type: "GET",
       url: '{{url("contact-email/querymsg")}}',  
       data: {email:email,name:name}, // serializes the form's elements.
       success: function(data)
       {

       }
    }); 

    }
  return false; 
}
</script>
<script type="text/javascript">var zf_DateRegex = new RegExp("^(([0][1-9])|([1-2][0-9])|([3][0-1]))[-](Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)[-](?:(?:19|20)[0-9]{2})$");
var zf_MandArray = [ "Email"]; 
var zf_FieldArray = [ "SingleLine", "Email", "PhoneNumber_countrycode", "MultiLine"]; 
var isSalesIQIntegrationEnabled = false;
var salesIQFieldsArray = [];</script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>

$.validator.addMethod('customphone', function (value, element) {
    return this.optional(element) || /^[6-9]\d{9}$/.test(value);
}, "Please enter a valid phone number");
$.validator.addMethod('customname', function (value, element) {
    return this.optional(element) || /^\s*(\w.*)$/.test(value);
}, "Special characters not allowed");
$.validator.addMethod('customemail', function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/.test(value);
}, "Special characters not allowed");
                   
$(document).ready(function() {  
$("#form").validate({    

            rules: {
               
                  "SingleLine"    : {
                         required:true,
                         customname:true 
                        },
                  "Email":{
                     required: true,
                     // email: true,
                     customemail:true
                    },
                 "PhoneNumber_countrycode":{
                         required:true,
                         customphone:true,
                         maxlength:10
                        },
                "MultiLine":{
                    maxlength:500
                }
                 
            },
            messages : {
            	"SingleLine": {
                     required: "Please enter your name"
                    },
                  "Email":{
                     required: "Please enter your email",
                     //email: "Please enter a valid email"
                    },
                 "PhoneNumber_countrycode":{
                      required:"Please enter your mobile number",
                      maxlength: "Enter only 10 digits"
                        },

            	}         


    });                   
    });

</script>
</body></html>
