@extends('layouts_front_new.master')
@section('content')
<section class="about-info">
    <div class="container">
        <div class="the-title text-center" data-aos="zoom-in" data-aos-duration="2000">
            <h2>Recordent Job Application</h2>
        </div>
        <div class="zf-templateWidth apply-for-job">
            <form
                action='https://forms.zohopublic.com/recordent/form/JobApplicationForm/formperma/XKHw9M7vOwtVE4fvZ0U4K_RrF7B_53JqdcKLrTuBkFE/htmlRecords/submit'
                name='form' method='POST' onSubmit='javascript:document.charset="UTF-8"; return zf_ValidateAndSubmit();'
                accept-charset='UTF-8' enctype='multipart/form-data' id='form'>
                <input type="hidden" name="zf_referrer_name" value="">
                <!-- To Track referrals , place the referrer name within the " " in the above hidden input field -->
                <input type="hidden" name="zf_redirect_url" value="">
                <!-- To redirect to a specific page after record submission , place the respective url within the " " in the above hidden input field -->
                <input type="hidden" name="zc_gad" value="">
                <!-- If GCLID is enabled in Zoho CRM Integration, click details of AdWords Ads will be pushed to Zoho CRM -->
                <div class="zf-templateWrapper">
                    <!---------template Header Starts Here---------->
                    <ul class="zf-tempHeadBdr">
                        <li class="zf-tempHeadContBdr">
                            <!--<h2 class="zf-frmTitle">Recordent Job Application</h2>-->
                            <p class="zf-frmDesc"></p>
                            <div class="zf-clearBoth"></div>
                        </li>
                    </ul>
                    <!---------template Header Ends Here---------->
                    <!---------template Container Starts Here---------->
                    <div class="zf-subContWrap zf-leftAlign">
                        <ul>
                            <!---------Section Starts Here---------->
                            <li class="zf-tempFrmWrapper zf-section">
                                <h2>Personal Information</h2>
                            </li>
                            <!---------Section Ends Here---------->
                            
                            <!--<li class="zf-tempFrmWrapper zf-name zf-namemedium"><label class="zf-labelName">Full name-->
                            <!--    <em class="zf-important">*</em>-->
                            <!--</label>-->
                            <li>
                                <div class="zf-tempContDiv zf-twoType ">
                                    <div class="zf-nameWrapper row">
                                        
                                        <!---------Name Starts Here---------->
                                        <div class="col-md-4 col-lg-4 col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label>First Name</label>
                                                </div>
                                                <div>
                                                    <input type="text" maxlength="255" name="Name_First" fieldType=7 /
                                                        placeholder="Enter First Name">
                                                </div>
                                                <p id="Name_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 col-lg-4 col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label>Last Name</label>
                                                </div>
                                                <div>
                                                    <input type="text" maxlength="255" name="Name_Last" fieldType=7 /
                                                        placeholder="Enter Last Name">
                                                </div>
                                            </div>
                                        </div>
                                        <!---------Name Ends Here---------->
                                        
                                        <div class="col-md-4 col-12 col-lg-4">
                                            <!---------Multiple Choice Starts Here---------->
                                            <div class="form-group">
                                                <label class="zf-labelName">Applying for<em class="zf-important">*</em>
                                                </label>
                                                <div class="zf-tempContDiv zf-mSelect">
                                                    <select name="MultipleChoice" checktype="c1"
                                                        multiple="multiple">
                                                        <option value="Software&#x20;Developer&#x20;-&#x20;Back&#x20;End">
                                                            Software&#x20;Developer&#x20;-&#x20;Back&#x20;End</option>
                                                        <option value="Software&#x20;Developer&#x20;-&#x20;Front&#x20;End">
                                                            Software&#x20;Developer&#x20;-&#x20;Front&#x20;End</option>
                                                        <option value="Senior&#x20;Software&#x20;Developer&#x20;-&#x20;Full&#x20;stack">
                                                            Senior&#x20;Software&#x20;Developer&#x20;-&#x20;Full&#x20;stack</option>
                                                    </select>
                                                    <p id="MultipleChoice_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div> 
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                            <!---------Multiple Choice Ends Here---------->
                                        </div>
                                        
                                        <div class="zf-clearBoth"></div>
                                    </div>
                                    
                                </div>
                                <div class="zf-clearBoth"></div>
                            </li>
                            
                            <li class="zf-tempFrmWrapper zf-large">
                                <div class="row">
                                    
                                    <div class="col-md-4 col-12 col-lg-4">
                                        <!---------Date Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-date">
                                            <div class="form-group">
                                                <label class="zf-labelName">Date of birth<em class="zf-important">*</em>
                                                </label>
                                                <div class="zf-tempContDiv"><span> <input type="text" name="Date" checktype="c4" value="" maxlength="25" placeholder="dd-MMM-yyyy" /></span>
                                                    <div class="zf-clearBoth"></div>
                                                    <p id="Date_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Date Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-4 col-12 col-lg-4">
                                        <!---------Radio Starts Here---------->
                                        <div class="zf-radio zf-tempFrmWrapper zf-sideBySide">
                                            <div class="form-group">
                                                <label class="zf-labelName">Gender<em class="zf-important">*</em>
                                                </label>
                                                <div class="zf-tempContDiv">
                                                    <div class="zf-overflow">
                                                        <span class="zf-multiAttType">
                                                            <input class="zf-radioBtnType" type="radio" id="Radio_1" name="Radio"
                                                                checktype="c1" value="Male">
                                                            <label for="Radio_1" class="zf-radioChoice">Male</label> </span>
                                                        <span class="zf-multiAttType">
                                                            <input class="zf-radioBtnType" type="radio" id="Radio_2" name="Radio"
                                                                checktype="c1" value="Female">
                                                            <label for="Radio_2" class="zf-radioChoice">Female</label> </span>
                                                        <div class="zf-clearBoth"></div>
                                                    </div>
                                                    <p id="Radio_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Radio Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-4 col-12 col-lg-4">
                                        <!---------Dropdown Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-small">
                                            <div class="form-group">
                                                <label class="zf-labelName">Education</label> 
                                                <div class="zf-tempContDiv">
                                                    <select class="zf-form-sBox" name="Dropdown" checktype="c1">
                                                        <option selected="true" value="-Select-">-Select-</option>
                                                        <option value="School">School</option>
                                                        <option value="Under&#x20;Graduate">Under Graduate</option>
                                                        <option value="Post&#x20;Graduate">Post Graduate</option>
                                                    </select>
                                                    <p id="Dropdown_error" class="zf-errorMessage" style="display:none;">Invalid value </p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Dropdown Ends Here---------->
                                    </div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="row">
                                    <div class="col-md-3 col-12 col-lg-3">
                                        <div class="form-group">
                                            <label class="zf-labelName">Current overall CTC<em class="zf-important">*</em></label>
                                            <div>
                                                <input type="text" name="Number" value="" maxlength="18" placeholder="Current overall CT">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-12 col-lg-3">
                                        <div class="form-group">
                                            <label class="zf-labelName">Current Fixed CTC<em class="zf-important">*</em></label>
                                            <div>
                                                <input type="text" name="Number1" value="" maxlength="18" placeholder="Current Fixed CTC">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-12 col-lg-3">
                                        <div class="form-group">
                                            <label class="zf-labelName">Current Variable CTC<em class="zf-important">*</em></label>
                                            <div>
                                                <input type="text" name="Number2" value="" maxlength="18" placeholder="Current Variable CTC">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-12 col-lg-3">
                                        <div class="form-group">
                                            <label class="zf-labelName">Willing to relocate anywhere in India<em class="zf-important">*</em></label>
                                            <div>
                                                <select name="Dropdown1"><option selected="true" value="-Select-">-Select-</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </li>
                            
                            <li class="zf-tempFrmWrapper">
                                
                                <div class="row">
                                    <div class="col-12 col-md-3 col-lg-3">
                                        <!--fileupload-->
                                        <!---------File Upload Starts Here---------->   
                                        <div class="form-group">
                                            <label class="zf-labelName">Resume<em class="zf-important">*</em></label>
                                            <div class="zf-tempContDiv">
                                                <input type="file" name="FileUpload" checktype="c1" />
                                                <p id="FileUpload_error" class="zf-errorMessage" style="display:none;">Choose any
                                                    file for this field.</p>
                                            </div>
                                            <div class="zf-clearBoth"></div>
                                        </div>
                                        <!---------File Upload Ends Here---------->
                                    </div>
                                    
                                    <div class="col-12 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="zf-labelName">Technical Skills</label>
                                            <div>
                                                <textarea name="MultiLine1" maxlength="65535"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-3 col-lg-3">
                                        <!---------Email Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-small">
                                            <div class="form-group">
                                                <label class="zf-labelName">Email<em class="zf-important">*</em></label>    
                                                <div class="zf-tempContDiv">
                                                    <span>
                                                        <input fieldType=9 type="text" maxlength="255"name="Email" checktype="c5" value="" placeholder="Enter Your Email" />
                                                    </span>
                                                    <p id="Email_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Email Ends Here---------->
                                    </div>
                                    
                                    <div class="col-12 col-md-3 col-lg-3">
                                        <!---------Phone Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-small">
                                            <div class="form-group">
                                                <label class="zf-labelName">Phone Number</label>
                                                <div class="zf-tempContDiv zf-phonefld">
                                                    <div class="zf-phwrapper zf-phNumber">
                                                        <span> <input type="text" compname="PhoneNumber" name="PhoneNumber_countrycode"
                                                                maxlength="20" checktype="c7" value="" phoneFormat="1"
                                                                isCountryCodeEnabled=false fieldType="11"
                                                                id="international_PhoneNumber_countrycode" valType="number"
                                                                phoneFormatType="1" placeholder="Enter Your Phone Number" />
                                                        </span>
                                                        <div class="zf-clearBoth"></div>
                                                    </div>
                                                    <p id="PhoneNumber_error" class="zf-errorMessage" style="display:none;">Invalid
                                                        value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Phone Ends Here---------->
                                    </div>
                                </div>
                                
                            </li>
                            
                            <li>
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <hr>
                                        </div>
                                        <div class="b-s-10"></div>
                                    </div>
                                </div>
                            </li>
                            
                            
                            
                            <!---------Section Starts Here---------->
                            <li class="zf-tempFrmWrapper zf-section">
                                <h2>Previous/Current Employment Details</h2>
                                <p></p>
                            </li>
                            <!---------Section Ends Here---------->
                            
                            <li class="zf-tempFrmWrapper zf-small">
                                
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Single Line Starts Here---------->
                                        <div class="form-group">
                                            <label class="zf-labelName">Company name</label>
                                            <div class="zf-tempContDiv">
                                                <span>
                                                    <input type="text" name="SingleLine" checktype="c1" value="" maxlength="255" fieldType=1 placeholder="Enter Company Name" />
                                                </span>
                                                <p id="SingleLine_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                            </div>
                                            <div class="zf-clearBoth"></div>
                                        </div>
                                        <!---------Single Line Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Date Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-date">
                                            <div class="form-group">
                                                <label class="zf-labelName">Date of joining</label>
                                                <div class="zf-tempContDiv">
                                                    <span>
                                                        <input type="text" name="Date1" checktype="c4" value="" maxlength="25" / placeholder="dd-MMM-yyyy" >
                                                    </span>
                                                    <div class="zf-clearBoth"></div>
                                                    <p id="Date1_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Date Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Date Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-date">
                                            <div class="form-group">
                                                <label class="zf-labelName">Date of leaving</label>
                                                <div class="zf-tempContDiv">
                                                    <span>
                                                        <input type="text" name="Date2" checktype="c4" value="" maxlength="25" placeholder="dd-MMM-yyyy" />
                                                    </span>
                                                    <div class="zf-clearBoth"></div>
                                                    <p id="Date2_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Date Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Single Line Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-small">
                                            <div class="form-group">
                                                <label class="zf-labelName">Designation</label>
                                                <div class="zf-tempContDiv">
                                                    <span>
                                                        <input type="text" name="SingleLine1" checktype="c1" value="" maxlength="255" fieldType=1 placeholder="Enter Your Designation"/>
                                                    </span>
                                                    <p id="SingleLine1_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Single Line Ends Here---------->
                                    </div>
                                    
                                </div>
                                
                            </li>
                            
                            <li>
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <hr>
                                        </div>
                                        <div class="b-s-10"></div>
                                    </div>
                                </div>
                            </li>
                            
                            
                            <!---------Section Starts Here---------->
                            <li class="zf-tempFrmWrapper zf-section">
                                <h2>Reference #1</h2>
                                <p></p>
                            </li>
                            <!---------Section Ends Here---------->
                            
                            
                            <li>
                                <div class="row">
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                         <!---------Name Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-name zf-namemedium">
                                            <div class="form-group">
                                                <label class="zf-labelName">First Name<em class="zf-important">*</em></label>
                                                <div class="zf-tempContDiv zf-twoType">
                                                    <div class="zf-nameWrapper">
                                                        <span>
                                                            <input type="text" maxlength="255" name="Name1_First" fieldType=7 / placeholder="Enter First Name">
                                                        </span>
                                                        <div class="zf-clearBoth"></div>
                                                    </div>
                                                    <p id="Name1_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Name Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                         <!---------Name Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-name zf-namemedium">
                                            <div class="form-group">
                                                <label class="zf-labelName">Last Name</label>
                                                <div class="zf-tempContDiv zf-twoType">
                                                    <div class="zf-nameWrapper">
                                                        <span>
                                                            <input type="text" maxlength="255" name="Name1_Last" fieldType=7 placeholder="Enter Last Name"/>
                                                        </span>
                                                        <div class="zf-clearBoth"></div>
                                                    </div>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Name Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Email Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-small">
                                            <div class="form-group">
                                                <label class="zf-labelName">Email<em class="zf-important">*</em></label>    
                                                <div class="zf-tempContDiv">
                                                    <span>
                                                        <input fieldType=9 type="text" maxlength="255" name="Email1" checktype="c5" value="" placeholder="Enter Email" />
                                                    </span>
                                                    <p id="Email1_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Email Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Phone Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-small">
                                            <div class="form-group">
                                                <label class="zf-labelName">Phone Number</label>
                                                <div class="zf-tempContDiv zf-phonefld">
                                                    <div class="zf-phwrapper zf-phNumber">
                                                        <span>
                                                            <input type="text" compname="PhoneNumber1"
                                                                name="PhoneNumber1_countrycode" maxlength="20" checktype="c7" value=""
                                                                phoneFormat="1" isCountryCodeEnabled=false fieldType="11"
                                                                id="international_PhoneNumber1_countrycode" valType="number" placeholder="Enter Phone Number"
                                                                phoneFormatType="1" />
                                                        </span>
                                                        <div class="zf-clearBoth"></div>
                                                    </div>
                                                    <p id="PhoneNumber1_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Phone Ends Here---------->
                                    </div>
                                    
                                </div>
                                
                                
                            </li>
                            
                            
                            <li>
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <hr>
                                        </div>
                                        <div class="b-s-10"></div>
                                    </div>
                                </div>
                            </li>
                            
                            <!---------Section Starts Here---------->
                            <li class="zf-tempFrmWrapper zf-section">
                                <h2>Reference #2</h2>
                                <p></p>
                            </li>
                            <!---------Section Ends Here---------->
                            
                            
                            <li>
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Name Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-name zf-namemedium">
                                            <div class="form-group">
                                                <label class="zf-labelName">First Name<em class="zf-important">*</em> </label>
                                                <div class="zf-tempContDiv zf-twoType">
                                                    <div class="zf-nameWrapper">
                                                        <span>
                                                            <input type="text" maxlength="255" name="Name2_First" fieldType=7  placeholder="First Name"/>
                                                        </span>
                                                        <div class="zf-clearBoth"></div>
                                                    </div>
                                                    <p id="Name2_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Name Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Name Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-name zf-namemedium">
                                            <div class="form-group">
                                                <label class="zf-labelName">Last Name </label>
                                                <div class="zf-tempContDiv zf-twoType">
                                                    <div class="zf-nameWrapper">
                                                        <span>
                                                            <input type="text" maxlength="255" name="Name2_Last" fieldType=7  placeholder="Last Name"/>
                                                        </span>
                                                        <div class="zf-clearBoth"></div>
                                                    </div>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Name Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Email Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-small">
                                            <div class="form-group">
                                                <label class="zf-labelName">Email<em class="zf-important">*</em> </label>
                                                <div class="zf-tempContDiv">
                                                    <span>
                                                        <input fieldType=9 type="text" maxlength="255" name="Email2" checktype="c5" value="" placeholder="Enter Email"/>
                                                    </span>
                                                    <p id="Email2_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Email Ends Here---------->
                                    </div>
                                    
                                    <div class="col-md-3 col-lg-3 col-12">
                                        <!---------Phone Starts Here---------->
                                        <div class="zf-tempFrmWrapper zf-small">
                                            <div class="form-group">
                                                <label class="zf-labelName">Phone Number</label>
                                                <div class="zf-tempContDiv zf-phonefld">
                                                    <div class="zf-phwrapper zf-phNumber">
                                                        <span>
                                                            <input type="text" compname="PhoneNumber2"
                                                                name="PhoneNumber2_countrycode" maxlength="20" checktype="c7" value=""
                                                                phoneFormat="1" isCountryCodeEnabled=false fieldType="11"
                                                                id="international_PhoneNumber2_countrycode" valType="number"
                                                                phoneFormatType="1" placeholder="Enter Phone Number" />
                                                        </span>
                                                        <div class="zf-clearBoth"></div>
                                                    </div>
                                                    <p id="PhoneNumber2_error" class="zf-errorMessage" style="display:none;">Invalid value</p>
                                                </div>
                                                <div class="zf-clearBoth"></div>
                                            </div>
                                        </div>
                                        <!---------Phone Ends Here---------->
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>



                    

        
                    <!---------template Container Starts Here---------->
                    <ul class="text-center">
                        <li class="zf-fmFooter"><button class="zf-submitColor">Submit</button></li>
                    </ul>
                </div><!-- 'zf-templateWrapper' ends -->
            </form>
        </div><!-- 'zf-templateWidth' ends -->
        <script type="text/javascript">var zf_DateRegex = new RegExp("^(([0][1-9])|([1-2][0-9])|([3][0-1]))[-](Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)[-](?:(?:19|20)[0-9]{2})$");
            var zf_MandArray = ["Name_First", "Name_Last", "MultipleChoice", "Date", "Radio", "FileUpload", "Email", "Name1_First", "Name1_Last", "Email1", "Name2_First", "Name2_Last", "Email2"];
            var zf_FieldArray = ["Name_First", "Name_Last", "MultipleChoice", "Date", "Radio", "Dropdown", "FileUpload", "Email", "PhoneNumber_countrycode", "SingleLine", "Date1", "Date2", "SingleLine1", "Name1_First", "Name1_Last", "Email1", "PhoneNumber1_countrycode", "Name2_First", "Name2_Last", "Email2", "PhoneNumber2_countrycode"];
            var isSalesIQIntegrationEnabled = false;
            var salesIQFieldsArray = [];</script>
    </div>
</section>

<script>
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
@endsection