<script>
	// $Id: $
function zf_ValidateAndSubmit(){
		if(zf_CheckMandatory()){
			if(zf_ValidCheck()){
				if(isSalesIQIntegrationEnabled){
					zf_addDataToSalesIQ();
				}
				return true;
			}else{		
				return false;
			}
		}else{
			return false;
		}
	}
		function zf_CheckMandatory(){
		for(i = 0 ; i < zf_MandArray.length ; i ++) {
		  	var fieldObj=document.forms.form[zf_MandArray[i]];
		  	if(fieldObj) {  
				  	if(fieldObj.nodeName != null ){
				  		if ( fieldObj.nodeName=='OBJECT' ) {
								if(!zf_MandatoryCheckSignature(fieldObj)){
									zf_ShowErrorMsg(zf_MandArray[i]);
								 	return false;
								}
							}else if (((fieldObj.value).replace(/^\s+|\s+$/g, '')).length==0) {
							 if(fieldObj.type =='file')
								{ 
								 fieldObj.focus(); 
								 zf_ShowErrorMsg(zf_MandArray[i]);
								 return false;
								} 
				   	   	  	  fieldObj.focus();
				   	   	  	  zf_ShowErrorMsg(zf_MandArray[i]);
				   	   	  	  return false;
							}  else if( fieldObj.nodeName=='SELECT' ) {// No I18N
				  	   	   	 if(fieldObj.options[fieldObj.selectedIndex].value=='-Select-') {
								fieldObj.focus();
								zf_ShowErrorMsg(zf_MandArray[i]);
								return false;
							   }
							} else if( fieldObj.type =='checkbox' || fieldObj.type =='radio' ){
								if(fieldObj.checked == false){
									fieldObj.focus();
									zf_ShowErrorMsg(zf_MandArray[i]);
									return false;
			   					} 
							} 
				  	}else{
				  		var checkedValsCount = 0;
						var inpChoiceElems = fieldObj;
							for(var ii = 0; ii < inpChoiceElems.length ; ii ++ ){
						      	if(inpChoiceElems[ii].checked === true ){
						      		checkedValsCount ++;
						      	}
							}
							if ( checkedValsCount == 0) {
									inpChoiceElems[0].focus();
									zf_ShowErrorMsg(zf_MandArray[i]);
									return false;
							 	}
					}
			}
		}
		return true;
	}
	function zf_ValidCheck(){
		var isValid = true;
		for(ind = 0 ; ind < zf_FieldArray.length ; ind++ ) {
			var fieldObj=document.forms.form[zf_FieldArray[ind]];
		  	if(fieldObj) {
		  		if(fieldObj.nodeName != null ){
			  		var checkType = fieldObj.getAttribute("checktype"); 
			  		if( checkType == "c2" ){// No I18N
			  			if( !zf_ValidateNumber(fieldObj)){
							isValid = false;
							fieldObj.focus();
							zf_ShowErrorMsg(zf_FieldArray[ind]);
							return false;
						}
			  		}else if( checkType == "c3" ){// No I18N
			  			if (!zf_ValidateCurrency(fieldObj) || !zf_ValidateDecimalLength(fieldObj,10) ) {
							isValid = false;
							fieldObj.focus();
							zf_ShowErrorMsg(zf_FieldArray[ind]);
							return false;
						}
			  		}else if( checkType == "c4" ){// No I18N
			  			if( !zf_ValidateDateFormat(fieldObj)){
							isValid = false;
							fieldObj.focus();
							zf_ShowErrorMsg(zf_FieldArray[ind]);
							return false;
						}
			  		}else if( checkType == "c5" ){// No I18N
			  			if (!zf_ValidateEmailID(fieldObj)) {
							isValid = false;
							fieldObj.focus();
							zf_ShowErrorMsg(zf_FieldArray[ind]);
							return false;
						}
			  		}else if( checkType == "c6" ){// No I18N
			  			if (!zf_ValidateLiveUrl(fieldObj)) {
							isValid = false;
							fieldObj.focus();
							zf_ShowErrorMsg(zf_FieldArray[ind]);
							return false;
							}
			  		}else if( checkType == "c7" ){// No I18N
			  			if (!zf_ValidatePhone(fieldObj)) {
							isValid = false;
							fieldObj.focus();
							zf_ShowErrorMsg(zf_FieldArray[ind]);
							return false;
							}
			  		}else if( checkType == "c8" ){// No I18N
			  			zf_ValidateSignature(fieldObj);
			  		}
			  	}
		  	}
		}
         	return isValid;
	}
	function zf_ShowErrorMsg(uniqName){
		var fldLinkName;
		for( errInd = 0 ; errInd < zf_FieldArray.length ; errInd ++ ) {
			fldLinkName = zf_FieldArray[errInd].split('_')[0];
			document.getElementById(fldLinkName+"_error").style.display = 'none';
		}
		var linkName = uniqName.split('_')[0];
		document.getElementById(linkName+"_error").style.display = 'block';
	}
	function zf_ValidateNumber(elem) {
	 	var validChars = "-0123456789";
	 	var numValue = elem.value.replace(/^\s+|\s+$/g, '');
	 	if (numValue != null && !numValue == "") {
	 		var strChar;
	 		var result = true;
	 		if (numValue.charAt(0) == "-" && numValue.length == 1) {
	 			return false;
	 		}
	 		for (i = 0; i < numValue.length && result == true; i++) {
	 			strChar = numValue.charAt(i);
	 			if ((strChar == "-") && (i != 0)) {
	 				return false;
	 			}
	 			if (validChars.indexOf(strChar) == -1) {
	 				result = false;
	 			}
	 		}
	 		return result;
	 	} else {
	 		return true;
	 	}
	 }
	 function zf_ValidateDateFormat(inpElem){
	 	var dateValue = inpElem.value.replace(/^\s+|\s+$/g, '');
	 	if( dateValue == "" ){
	 		return true;
	 	}else{
			return( zf_DateRegex.test(dateValue) );
		}
	 }
	 function zf_ValidateCurrency(elem) {
	 	var validChars = "0123456789."; 
	 	var numValue = elem.value.replace(/^\s+|\s+$/g, '');
	 	if(numValue.charAt(0) == '-'){
	 		numValue = numValue.substring(1,numValue.length);
	 	}
	 	if (numValue != null && !numValue == "") {
	 		var strChar;
	 		var result = true;
	 		for (i = 0; i < numValue.length && result == true; i++) {
	 			strChar = numValue.charAt(i);
	 			if (validChars.indexOf(strChar) == -1) {
	 				result = false;
	 			}
	 		}
	 		return result;
	 	} else {
	 		return true;
	 	}
	 }
	 function zf_ValidateDecimalLength(elem,decimalLen) {
	 	var numValue = elem.value;
	 	if (numValue.indexOf('.') >= 0) {
	 		var decimalLength = numValue.substring(numValue.indexOf('.') + 1).length;
	 		if (decimalLength > decimalLen) {
	 			return false;
	 		} else {
	 			return true;
	 		}
	 	}
	 	return true;
	 }
	 function zf_ValidateEmailID(elem) {
        var check = 0;
        var emailValue = elem.value;
        if (emailValue != null && !emailValue == "") {
            var emailArray = emailValue.split(",");
            for (i = 0; i < emailArray.length; i++) {
                var emailExp = /^[\w]([\w\-.+'/]*)@([a-zA-Z0-9]([a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,22}$/;
                if (!emailExp.test(emailArray[i].replace(/^\s+|\s+$/g, ''))) {
                    check = 1;
                }
            }
            if (check == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    function zf_ValidateLiveUrl(elem) {
    	var urlValue = elem.value;
		if(urlValue !== null && typeof(urlValue) !== "undefined") {
			urlValue = urlValue.replace(/^\s+|\s+$/g, '');
			if(urlValue !== "") {
				var urlregex = new RegExp("^(((ht|f)tp(s?)://[-.\\w]*)|((w{3}\\.)[-.\\w]+))(/?)([-\\w.?,:'/\\\\+=&;%$#@()!~]*)?$"); // Same regex as website_url in security-regex.xml. But single backslash is replaced with two backslashes.
				return(urlregex.test(urlValue));
			}
        }
        return true;
    }
    function zf_ValidatePhone(inpElem){
    	var phoneFormat = parseInt(inpElem.getAttribute("phoneFormat")); 
    	var fieldInpVal = inpElem.value.replace(/^\s+|\s+$/g, '');
    	var toReturn = true ;
    	if( phoneFormat === 1 ){
    		if(inpElem.getAttribute("valType") == 'code'){
                var codeRexp = /^[+][0-9]{1,4}$/;
                if(fieldInpVal != "" && !codeRexp.test(fieldInpVal)){
		           return false;
				}
    		}else{
				var IRexp = /^[+]*[()0-9- ]+$/;
				if(inpElem.getAttribute("phoneFormatType") == '2'){
					IRexp = /^[0-9]+$/;
				}
	 			if (fieldInpVal != "" && !IRexp.test(fieldInpVal)) {
	 				toReturn = false;
	 				return toReturn;
	 			}
 		    }
 			return toReturn;
    	}else if( phoneFormat === 2 ){
    		var InpMaxlength = inpElem.getAttribute("maxlength");
    		var USARexp = /^[0-9]+$/;
    		if  ( fieldInpVal != "" && USARexp.test(fieldInpVal) &&  fieldInpVal.length == InpMaxlength ) {
				toReturn = true;
			}else if( fieldInpVal == "" ){
				toReturn = true;
			}else{
				toReturn = false;
			}
			return toReturn;
    	}
    }
  
  function zf_ValidateSignature(objElem) {
  		var linkName = objElem.getAttribute("compname");
  		var canvasElem = document.getElementById("drawingCanvas-"+linkName);
  		var isValidSign = zf_IsSignaturePresent(objElem,linkName,canvasElem);
   		var hiddenSignInputElem = document.getElementById("hiddenSignInput-"+linkName);
		if(isValidSign){
			hiddenSignInputElem.value = canvasElem.toDataURL();
		}else{
			hiddenSignInputElem.value = "";// No I18N
		}
		return isValidSign;
  	}

  	function zf_MandatoryCheckSignature(objElem){
  		var linkName = objElem.getAttribute("compname");
  		var canvasElem = document.getElementById("drawingCanvas-"+linkName);
  		var isValid = zf_IsSignaturePresent(objElem,linkName,canvasElem);
		return isValid;
  	}

  	function zf_IsSignaturePresent(objElem,linkName,canvasElem){
   		var context = canvasElem.getContext('2d'); // No I18N
   		var canvasWidth = canvasElem.width;
   		var canvasHeight = canvasElem.height;
   		var canvasData = context.getImageData(0, 0, canvasWidth, canvasHeight);
   		var signLen = canvasData.data.length;
   		var flag = false;
   		for(var index =0; index< signLen; index++) {
   	     	if(!canvasData.data[index]) {
   	       		flag =  false;
   	     	}else if(canvasData.data[index]) {
   		   		flag = true;
   		   		break;
   	     	}
   		}
		return flag;
  	}

	function zf_FocusNext(elem,event) {  
	 	if(event.keyCode == 9 || event.keyCode == 16){
	      return;
	    }
	    if(event.keyCode >=37 && event.keyCode <=40){
	       return;
	    } 	
	    var compname = elem.getAttribute("compname");
	    var inpElemName = elem.getAttribute("name");
	 	if (inpElemName == compname+"_countrycode") { 
	 		if (elem.value.length == 3) {
	 			document.getElementsByName(compname+"_first")[0].focus();
	 		}
	 	} else if (inpElemName == compname+"_first" ) { 
	 		if (elem.value.length == 3) {
	 			document.getElementsByName(compname+"_second")[0].focus();
	 		}
	 	}
	}

</script>


<div class="zf-templateWidth">
		<form action='https://forms.zohopublic.com/recordent/form/ProductEnquiry/formperma/RGoo1KJmaEy5E4xqwqGzHnT5Bl9oEW5ENAE41vkoW5E/htmlRecords/submit' name='form' method='POST' onSubmit='javascript:document.charset="UTF-8"; return zf_ValidateAndSubmit();' accept-charset='UTF-8' enctype='multipart/form-data' id='form'>
			<input type="hidden" name="zf_referrer_name" value=""><!-- To Track referrals , place the referrer name within the " " in the above hidden input field -->
		<input type="hidden" name="zf_redirect_url" value=""><!-- To redirect to a specific page after record submission , place the respective url within the " " in the above hidden input field -->
		<input type="hidden" name="zc_gad" value=""><!-- If GCLID is enabled in Zoho CRM Integration, click details of AdWords Ads will be pushed to Zoho CRM -->
		<div class="zf-templateWrapper join-our-mailer"><!---------template Header Starts Here---------->
		<p class="zf-frmTitle" style="font-size: 18px; margin-top:20px;  font-weight: bold; color: rgb(255, 255, 255); text-align: left;width: 100%; display: block; padding: 0px 0px 20px 0px;">Join our mailing list</p>
			<ul class="zf-tempHeadBdr">
				<li class="zf-tempHeadContBdr">
				
					<p class="zf-frmDesc"></p>
					<div class="zf-clearBoth"></div>
				</li>
			</ul><!---------template Header Ends Here---------->
		<!---------template Container Starts Here---------->
		
			<div class="zf-subContWrap zf-topAlign">
				<ul>
		<!---------Single Line Starts Here---------->
					<li class="zf-tempFrmWrapper zf-small">
						<div class="zf-tempContDiv">
							<span> 
							<div class="input-group">
				<input type="text" name="SingleLine" checktype="c1" value="" maxlength="255" fieldType=1 placeholder="Email or Mobile no" / class="form-control mail">
							    <div class="input-group-append">
							        <button class="zf-submitColor btn btn-info" onclick="mail()">Submit</button>
							    </div>
							</div>
								
							</span> 
							<p id="SingleLine_error" class="zf-errorMessage" style="display:none;">Invalid value</p>

						</div>
						<div class="zf-clearBoth"></div>
					</li><!---------Single Line Ends Here---------->
				</ul>
			</div><!---------template Container Starts Here---------->
			<ul>
				<li class="zf-fmFooter">
					
				</li>
			</ul>
<hr>
				<p class="zf-frmTitle" style="font-size: 18px; margin-top:20px;  font-weight: bold; color: rgb(255, 255, 255); text-align: left;width: 100%; display: inline">Follow us on</p>
<div style="padding:10px; display:inline">
<a href="https://www.linkedin.com/company/recordent/" target="_blank"><img src="{{asset('front_new/images/linkin.png')}}" class="socicon" height="34px" width="35px"></a>

	&nbsp<a href="https://www.facebook.com/recordent/"  target="_blank"><img src="{{asset('front_new/images/faceb.png')}}" class="fbicon" height="34px" width="35px"></a>

	<a href="https://twitter.com/recordentindia" target="_blank"><img src="{{asset('front_new/images/twitt.png')}}" class="socicon"  height="45px" width="45px"></a>

	<a href="https://www.youtube.com/channel/UC40khDgZqVTmbP7ilz5GfdQ" target="_blank"><img src="{{asset('front_new/images/tube.png')}}" style="margin-bottom: 2.8px;" class="socicon" height="50px"  width="37px"></a>
	</div>
	
		</div><!-- 'zf-templateWrapper' ends --></form>
	</div><!-- 'zf-templateWidth' ends -->
	
	<script type="text/javascript">var zf_DateRegex = new RegExp("^(([0][1-9])|([1-2][0-9])|([3][0-1]))[-](Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)[-](?:(?:19|20)[0-9]{2})$");
var zf_MandArray = []; 
var zf_FieldArray = [ "SingleLine"]; 
var isSalesIQIntegrationEnabled = false;
var salesIQFieldsArray = [];
</script>
<script type="text/javascript">
    function mail()
    {
    var email = $('.mail').val();
     $.ajax({
       type: "GET",
       url: '{{url("contact-email/querymsg")}}',  
       data: {email:email}, // serializes the form's elements.
       success: function(data)
       {

       }
    }); 
}
</script>