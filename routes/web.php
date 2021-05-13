<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
   return view('front/home/index');
})->name('home');

Route::get('/home-new', function () {
   return view('front/home-new/index');
});

Route::get('/home', function () {
    return redirect('/');
});
Route::get('/aboutus', function () {
   return view('front/aboutus/index');
})->name('aboutus');

// Route::get('/helpandsupport', function () {
//    return view('front/home/helpandsupport');
// })->name('helpandsupport');

Route::get('/creditreport', function () {
   return view('front/home/creditreport');
})->name('creditreport');

// Route::get('sendemail/sendsupportmsg', 'SendEmailController@index');

Route::get('contact-email/querymsg', 'HomeController@contact_email');

Route::get('/faq', function () {
   return view('front/faq/index');
})->name('faq');
Route::get('/solutions', function () {
   return view('front/solutions/index');
})->name('solutions');
Route::get('/security', function () {
   return view('front/security/index');
})->name('security');
Route::get('/careers', function () {
   return view('front/careers/index');
})->name('careers');
Route::get('/careers/apply', function () {
   return view('front/careers/apply');
})->name('careers.apply');


Route::get('/pricing-plan', function () {
   return view('front/pricing-plan/index');
})->name('pricing-plan');

// terms and conditions page
Route::get('terms-and-conditions', function () {
   /*$page = App\Pages::where('slug', '=', 'term-and-condition')->firstOrFail();
   return view('front/terms-and-conditions/index',compact('page'));*/
   return view('front/terms-and-conditions/index');
})->name('term-and-condition');

// privacy policy
Route::get('privacy-policy', function () {

  /* $page = App\Pages::where('slug', '=', 'privacy-policy')->firstOrFail();
   dd($page);
   return view('front/privacy-policy/index',compact('page'));*/
   return view('front/privacy-policy/index');
})->name('privacy-policy');

// end user license agreement page
Route::get('end-user-license-agreement', function () {
   //$page = App\Pages::where('slug', '=', 'end-user-license-agreement')->firstOrFail();
   return view('front/end-user-license-agreement/index');
})->name('end-user-license-agreement');

Route::get('landingpage', function () {
   return view('landing-page/index');
})->name('landing-page');
Route::get('thankyou', function () {
   return view('thank-you/index');
})->name('thankyou');


//Trade Tracking landing & Thank you page
Route::get('promotion_ea3', function () {
   return view('landing-page/trade-tracking');
})->name('trade-tracking-landing-page');

Route::get('thankyou_ea3', function () {
   return view('thank-you/trade-tracking');
})->name('thankyou_ea3');

/*POST*/
Route::get('post/{slug}','Front\PostController@view')->name('view-post');
Route::get('test_api','ApiController@index');

//Route::get('/home', 'HomeController@index')->name('home')->middleware('disablepreventback');
Auth::routes(['verify'=>false]);
Route::get('admin/sdhfkjdf897987kj/{id}','EncryptController@loginBehalfMe')->name('admin-login-behalf');
Route::post('admin/login/get-otp', 'Auth\LoginController@getOtp')->name('admin.login.get-otp');
Route::post('admin/login/with-otp', 'Auth\LoginController@loginWithOtp')->name('admin.login-with-otp');

Route::post('admin/login/login-with-email-get-otp-to-mobile', 'Auth\LoginController@loginWithEmailGetOtpToMobile')->name('admin.login-with-email-get-otp-to-mobile');
Route::post('admin/login/login-with-email-otp-to-mobile', 'Auth\LoginController@loginWithEmailOtpToMobile')->name('admin.login-with-email-otp-to-mobile');

Route::get('ajax/get-login-status', 'Front\AuthController@getLoginStatus')->name('ajax-get-login-status');


//Route::get('auth/verify', 'Auth\VerificationController@authVerify')->name('auth.verify');
//Route::get('auth/email-resend', 'Auth\VerificationController@emailResend')->name('verification.email.resend');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.email.verify');

Route::get('change-email', 'Auth\VerificationController@changeEmail')->name('change.email');
Route::post('change-email', 'Auth\VerificationController@updateEmail')->name('change.email');

Route::get('check-my-report','IbLoginController@index')->name('your.reported.dues')->middleware('individual.redirect.if.authenticated');

Route::get('check-my-business-report','IbLoginController@bussines')->name('your.reported.bussinesdues')->middleware('individual.redirect.if.authenticated');

Route::post('verifyemaiid','HomeController@EmailVerificationApicall')->name('verifyemaiid');
Route::get('get-pricing-plan/{planid?}','HomeController@pricing_plan')->name('get-pricing-plan')->middleware('admin.user');
Route::get('membership','HomeController@membership')->name('membership')->middleware('admin.user')->middleware('member');
Route::get('invoices','HomeController@invoices')->name('invoices')->middleware('admin.user')->middleware('member');
Route::post('multiple_invoice_payment','HomeController@multiple_invoice_payment')->name('multiple_invoice_payment')->middleware('admin.user');
Route::post('multiple-invoice-payment-callback', 'HomeController@multiplePaymentCallback')->name('multiple-invoice-payment-callback');
Route::post('multiple_invoice_download', 'HomeController@multiple_invoice_download')->name('multiple_invoice_download');
Route::post('superadmin_invoice_download', 'HomeController@multiple_invoice_download')->name('superadmin_invoice_download');

Route::get('e-arbitration', 'EArbitrationController@EArbitrationList')->name('e-arbitration');
Route::post('e-arbitration-sent-mail', 'EArbitrationController@EArbitrationSendMail')->name('e-arbitration-sent-mail');
Route::get('invoice/{id}','HomeController@postpaid_invoice')->name('postpaid_invoice')->middleware('admin.user')->middleware('member');

Route::get('download-zip', 'HomeController@downloadZip');

Route::get('upgrade-plan-due/{id}/{type?}','HomeController@upgrade_plan_due')->name('upgrade-plan-due')->middleware('admin.user')->middleware('member');
Route::get('upgrade-plan-business/{id}/{type?}','HomeController@upgrade_plan_business')->name('upgrade-plan-business')->middleware('admin.user')->middleware('member');

Route::get('upgrade-plan','HomeController@upgrade_plan')->name('upgrade-plan')->middleware('admin.user')->middleware('member');
Route::get('renew-plan','HomeController@renew_plan')->name('renew-plan')->middleware('admin.user')->middleware('member');

Route::post('update-profile-store','HomeController@update_profile_store')->name('update-profile-store')->middleware('admin.user');
Route::get('update-profile/{planid?}/{refferralcode?}','HomeController@update_profile')->name('update-profile')->middleware('admin.user');
Route::post('admin/update-bank-details-store','HomeController@update_bank_details_store')->name('update-bank-details-store')->middleware('admin.user');
Route::get('admin/update-bank-details','HomeController@update_bank_details')->name('update-bank-details')->middleware('admin.user');
Route::get('edit-profile/{userId}','HomeController@edit_profile')->name('edit-profile')->middleware('admin.user');
Route::post('edit-profile-store/{userId}','HomeController@edit_profile_store')->name('edit-profile-store')->middleware('admin.user');

Route::get('user-update','HomeController@user_update')->name('user-update')->middleware('admin.user');
Route::get('corporate-plan','HomeController@corporate_plan')->name('corporate-plan')->middleware('admin.user');
Route::get('upgrade-corporate-plan','HomeController@upgrade_corporate_plan')->name('upgrade-corporate-plan')->middleware('admin.user');
Route::get('invoice','HomeController@invoice')->name('invoice');
Route::get('sendmail','HomeController@sendmail')->name('sendmail')->middleware('admin.user');
Route::get('register-pricing-plan','HomeController@register_pricing_plan')->name('register-pricing-plan')->middleware('admin.user');
Route::post('membership-payment/payment-callback/{id?}/{type?}/{due_type?}', 'HomeController@membershipPaymentCallback')->name('membership-payment-callback');
Route::get('upgrade-pricing-plan/{id?}/{type?}/{due_type?}','HomeController@register_pricing_plan')->name('upgrade-pricing-plan')->middleware('admin.user');
Route::get('renew-pricing-plan','HomeController@register_pricing_plan')->name('renew-pricing-plan')->middleware('admin.user');
Route::get('auto-renew-membership-plans','CronController@auto_renew_membership_plans')->name('auto-renew-membership-plans');
Route::get('auto-notifications-membership-plans/{days}','CronController@auto_notifications_membership_plans')->name('auto-notifications-membership-plans');

//Route::post('logingout', 'Front\AuthController@logout')->name('individual.logout')->middleware('individual.redirect.if.authenticated');

Route::post('register/otp','Auth\RegisterController@getRegisterOTP')->name('register.getotp');
Route::post('register/verifyotp','Auth\RegisterController@verifyRegister')->name('register.verifyotp');
Route::post('register/checkmobile','Auth\RegisterController@checkMobile')->name('register.checkmobile');
Route::post('register/verifyemaiid','Auth\RegisterController@EmailVerification')->name('register.verifyemaiid');

// Route::post('register/businessname_validate','Auth\RegisterController@businessname_validate')->name('register.businessname_validate');

/* auto login individual check my report */
Route::get('checkmyreport/individual/{token}', 'Auth\CheckmyReportController@authenticate_individual_checkmyreport_login');

/* auto login business check my report */
Route::get('checkmyreport/business/{token}', 'Auth\CheckmyReportController@authenticate_business_checkmyreport_login');

/* Individual */
Route::group(['namespace'=>'Front'],function(){

	/*Individual Login*/
	Route::post('fetch-customer-otp', 'AuthController@register')->name('fetch-customer-otp')->middleware('individual.redirect.if.authenticated');
	Route::post('individual/login', 'AuthController@login')->name('individual.login')->middleware('individual.redirect.if.authenticated');

	/*Business Login*/
	Route::post('fetch-business-customer-otp', 'AuthController@business_register')->name('fetch-business-customer-otp')->middleware('individual.redirect.if.authenticated');
	Route::post('business/login', 'AuthController@business_login')->name('business.login')->middleware('individual.redirect.if.authenticated');

	Route::post('logingout', 'AuthController@logout')->name('individual.logout')->middleware('individual.auth');


});
	Route::group(['namespace'=>'Front\Individual'],function(){
		Route::group(['as'=>'front-individual.','middleware'=>['individual.auth','onlyIndividual']],function(){


			Route::get('individual/dashboard','DashboardController@index')->name('dashboard');
			Route::get('individual/records','MyRecordsController@index')->middleware('member')->name('my-records');
			Route::get('get-individual-report', 'DashboardController@getIndividualReport')->name('get-individual-report');
			Route::get('individual/records/{studentid}/view', 'MyRecordsController@studentData')->middleware('member')->name('my-records-view');
			Route::post('individual/payment-history', 'MyRecordsController@paymentHistory')->name('my-payment-history');
			Route::get('individual/profile', 'ProfileController@index')->name('profile');
			Route::get('individual/profile/{id}/edit', 'ProfileController@edit')->name('profile-edit');
			Route::post('individual/profile/update', 'ProfileController@update')->name('profile-update');

			//payment
			Route::post('individual/records/payment','MyRecordsController@payment')->name('my-records-make-payment');
			Route::post('individual/records/payment/payment-callback','MyRecordsController@paymentCallback')->name('my-records-payment-callback');
			Route::get('individual/records/donwload-report', 'MyRecordsController@myReportDownload')->name('report.download');
			Route::get('individual/records/{dueId}/raise-dispute', 'MyRecordsController@raiseDispute')->name('raise-dispute');
			Route::post('individual/records/{dueId}/raise-dispute', 'MyRecordsController@raiseDisputeStore')->name('store-raise-dispute');

		});

	});
	Route::group(['namespace'=>'Front\Business'],function(){
		Route::group(['as'=>'front-business.','middleware'=>['individual.auth','onlyBusiness']],function(){
			Route::get('business/dashboard','DashboardController@index')->name('dashboard');
			Route::get('business/records','MyRecordsController@index')->name('business-records');
			Route::get('business/records/{studentid}/view', 'MyRecordsController@businessData')->name('my-records-view');
			Route::post('business/payment-history', 'MyRecordsController@paymentHistory')->name('my-payment-history');
			Route::get('business/profile', 'ProfileController@index')->name('profile');
			Route::post('business/profile/update', 'ProfileController@update')->name('profile-update');

			Route::post('business/records/payment','MyRecordsController@payment')->name('my-records-make-payment');
			Route::post('business/records/payment/payment-callback','MyRecordsController@paymentCallback')->name('my-records-payment-callback');

			Route::get('business/records/donwload-report', 'MyRecordsController@myReportDownload')->name('report.download');
			Route::get('business/records/{dueId}/raise-dispute', 'MyRecordsController@raiseDispute')->name('raise-dispute');
			Route::post('business/records/{dueId}/raise-dispute', 'MyRecordsController@raiseDisputeStore')->name('store-raise-dispute');

		});
	});



Route::group(['prefix' => 'admin'], function () {
	/* cron */
	Route::get('notification/store','CronController@storeNotification')->name('admin.notification-store');
	Route::get('cron/send-dispute-notification-count','CronController@sendDisputeNotificationCount')->name('admin.send-dispute-notification-count');
	Route::get('cron/send-dispute-reminder','CronController@sendDisputeReminder')->name('admin.send-dispute-reminder');
	Route::get('cron/send-reminder-to-registered-users','CronController@sendRemindertoUsers')->name('send-reminder-to-registered-users');
	Route::get('cron/send-email-for-member-services','CronController@sendEmailforMemberServices')->name('send-email-for-member-services');
	Route::get('cron/send-email-to-educate-users','CronController@sendEmailtoEducateUsers')->name('send-email-to-educate-users');

	Route::get('verify-india-payment','CronController@verifyIndiaPayment')->name('admin.verify-india-payment');
	Route::get('verify-global-payment','CronController@verifyGlobalPayment')->name('admin.verify-global-payment');
	Route::get('cron-send-campaign-emails','CronController@cronSendCampaignEmails')->name('admin.cron-send-campaign-emails');

	/*PayU APIs*/
	Route::get('cron-check-payu-refund-status','CronController@checkOrUpdatePayuRefundStatus')->name('admin.cron-check-payu-refund-status');

    Voyager::routes();
    Route::get('menu-frame', function(){
        return View('layouts_front.iframe-header-menu');
    })->name('menu-frame');


    Route::get('campaigns','SendEmailController@showCampaignEmailForm')->name('campaigns')->middleware('onlyAdmin');
	Route::post('sendemail/sendcampaignmails', 'SendEmailController@sendCampaignMails')->name('admin.send-campaign-emails');

    Route::group(['middleware' => 'admin.user'], function () {

    	Route::get('profile/edit/mobile','ProfileController@editMobile')->name('admin.profile.edit-mobile');
    	Route::post('profile/edit/mobile/get-otp','ProfileController@editMobileGetOtp')->name('admin.profile.edit-mobile-get-otp');
    	Route::post('profile/edit/mobile','ProfileController@updateMobile')->name('admin.profile.update-mobile');

    	Route::get('profile/edit/email','ProfileController@editEmail')->name('admin.profile.edit-email');
    	Route::post('profile/edit/email/get-otp','ProfileController@editEmailGetOtp')->name('admin.profile.edit-email-get-otp');
    	Route::post('profile/edit/email','ProfileController@updateEmail')->name('admin.profile.update-email');
    	Route::get('profile/change/password','ProfileController@editPassword')->name('admin.profile.edit-password');
    	Route::post('profile/change/password','ProfileController@changePassword')->name('admin.profile.change-password');


    	/*Route::get('encrypt/students','EncryptController@students');
    	Route::get('encrypt/users','EncryptController@users');
    	Route::get('encrypt/business','EncryptController@business');
    	Route::get('encrypt/admin-notifications','EncryptController@adminNotifications');
    	Route::get('encrypt/customer-kyc','EncryptController@customerKyc');
    	Route::get('encrypt/individual-business-front','EncryptController@individualBusinessFront');
    	Route::get('encrypt/dues-sms-log','EncryptController@duesSmsLog');*/


		Route::get('auth/verify', 'Auth\VerificationController@authVerify')->name('auth.verify');
		Route::get('auth/email-resend', 'Auth\VerificationController@emailResend')->name('verification.email.resend');

		Route::get('import-excel', 'StudentController@importExcelView')->middleware('member')->name('import-excel-view');
		Route::post('import-excel', 'StudentController@importExcel')->middleware('custom.verified')->name('import');
		Route::get('import-excel-super/{userId}', 'StudentController@importSuperExcel')->middleware('onlyAdmin')->name('super-excel');
		Route::post('import-excel-super/{userId}', 'StudentController@importExcelSuper')->middleware('custom.verified')->name('super');
		Route::get('import-excel/issues/{unique_url_code}/{userId?}', 'StudentController@importExcelIssues')->middleware('custom.verified')->name('import-excel.issues');
		Route::get('download', 'StudentController@export')->middleware('custom.verified')->name('export');
		Route::get('download-payment', 'StudentController@export_updatePayments')->middleware('custom.verified')->name('export.payment');
		Route::get('report', 'StudentController@individualReport')->middleware('custom.verified')->name('admin.individual.report');
		Route::get('us-report-view', 'UsBusinessReportDownloadController@usReportView')->middleware('custom.verified')->name('admin.us.report');

		Route::get('download-us-report-pdf', 'UsBusinessReportDownloadController@usBusinessReportDowloadPdf')->middleware('custom.verified')->name('admin.us.business.download.pdf');

		Route::get('donwload-report-pdf', 'StudentController@individualReportDowloadPdf')->name('admin.individual.download.pdf');
		Route::get('view-report-pdf', 'StudentController@individualReportViewPdf')->name('admin.individual.view.pdf');

		Route::get('donwload-report', 'StudentController@individualReportDowload')->middleware('custom.verified')->name('admin.individual.report.download');

		Route::post('import-due-payment', 'StudentController@importDuePayment')->middleware('custom.verified')->name('import-due-payment');
		Route::get('import-due-payment/issues/{unique_url_code}', 'StudentController@importDuePaymentIssues')->middleware('custom.verified')->name('import-due-payment.issues');

		Route::post('import-update-profile/{userId}', 'StudentController@importUpdateProfile')->middleware('custom.verified')->name('import-update-profile');
		Route::get('import-excel-profile/issues/{unique_url_code}/{userId?}', 'StudentController@importExcelProfileIssues')->middleware('custom.verified')->name('import-excel-profile.issues');

		//all sttudent records//
		Route::get('us-creditreport', 'AllStudentController@uscreditreport')->middleware('member')->name('us-creditreport');
		Route::get('us-creditreportresponse', 'AllStudentController@uscreditreportresponse')->middleware('member')->name('us-creditreportresponse');

		//Route::get('users/all-records',function(){dd(123);});
		Route::get('member-users-individual-records', 'AllUserRecordsController@getStudentRecords')->name('individual-records-for-member');
		Route::get('user/records/{studentid}/{userId}/view', 'AllUserRecordsController@studentData')->middleware('custom.verified')->name('user-records-view');
		Route::get('users-records', 'AllUserRecordsController@studentRecords')->name('user-records');
		Route::get('user/records/{studentid}/{userId}/{dueId}/view', 'AllUserRecordsController@studentData')->middleware('custom.verified')->name('user-records-view');
		Route::post('users/student/payment-history', 'AllUserRecordsController@paymentHistory')->middleware('custom.verified')->name('user-student-payment-history');

		Route::get('users-business-records', 'AllBusinessRecordsController@index')->name('business-records-for-admin');
		Route::get('member-users-business-records', 'AllBusinessRecordsController@getMemberCustomers')->name('business-records-for-member');
		Route::get('user/business/{business_id}/{userId}/view', 'AllBusinessRecordsController@businessData')->middleware('custom.verified')->name('business-records-view-for-admin');
		Route::get('user/business/{business_id}/{userId}/{dueId}/view', 'AllBusinessRecordsController@businessData')->middleware('custom.verified')->name('business-records-view-for-admin');
		Route::post('users/business/payment-history', 'AllBusinessRecordsController@paymentHistory')->middleware('custom.verified')->name('user-business-payment-history-for-admin');
		//Notification listing
		Route::get('notifications', 'NotificationController@index')->middleware('admin.notification')->name('admin.notification-list');
		Route::get('payments', 'PaymentController@index')->name('admin.due-payments');
    Route::get('membershippayments', 'PaymentController@membershipPaymentsListing')->name('admin.membershippayments-listing');
    Route::get('consentpayments', 'PaymentController@consentPaymentsListing')->name('admin.consentpayments-listing');

		//reporting organization only for Non admin  users----
		Route::get('organizations', 'OrganizationController@index')->name('organizations');

		//request consent
		Route::post('consent-request', 'RequestConsentController@store')->name('admin.request-consent-store');
		Route::get('all-records/consent/check-status/{consentId}', 'AllStudentController@checkConsentStatus')->name('admin.check-consent-status');
		Route::post('consent-request-business', 'RequestConsentController@storeBusiness')->name('admin.request-consent-store-business');
		Route::post('admin.get-gstin-api-data/{report}', 'ApiController@getGstinData')->name('admin.get-gstin-api-data');
		Route::post('admin.get-city-api-data', 'ApiController@getCityData')->name('admin.get-city-api-data');

		Route::get('all-records/consent/payment/{id}','AllStudentController@consentPayment')->name('admin.consent.payment');
		Route::post('all-records/consent/payment-callback','AllStudentController@consentPaymentCallback')->name('admin.consent.payment-callback');
		Route::post('all-records/consent/uspayment-callback','AllStudentController@usCreditReportPaymentCallback')->name('admin.consent.uspayment-callback');

		Route::get('all-records/consent/creditreport/status/{business_name}/{consent_payment_value}', 'UsBusinessReportController@usCreditReportNoHitResponse')->name('admin.consent.us-b2b-creditreport-no-hit-status');
		Route::get('all-records/consent/creditreport/{business_name}/{consent_payment_value}', 'UsBusinessReportController@usCreditReportSucessResponse')->name('admin.consent.us-b2b-creditreport-success-status');

		Route::get('get-us-b2b-report-refund-status', 'UsBusinessReportController@getUsB2BReportRefundStatus')->name('admin.get-us-b2b-report-refund-status');
		/*Route::post('for_record/uscreditreport/payment-callback','AllStudentController@usCreditReportPaymentCallback')->name('admin.uscreditreport.payment-callback');*/

		Route::get('add-records/payment/{id}','AddRecordController@makePaymentForDues')->name('admin.due.payment');
		Route::post('add-records-due/payment-callback/{id}','AddRecordController@makePaymentForDuesCallback')->name('admin.due.payment-callback');

		Route::get('add-records/payment/{id}/{type?}','AddRecordController@makePaymentForDues')->name('admin.due.payment');
		Route::post('add-records-due/payment-callback/{id}','AddRecordController@makePaymentForDuesCallback')->name('admin.due.payment-callback');
		Route::post('add-records-due-import/payment-callback/{id}','AddRecordController@makePaymentForDuesCallbackImport')->name('admin.due.payment-callback.import');

		Route::get('add-records/postpaid/{id}/{type?}','AddRecordController@postPaidForDues')->name('admin.due.postpaid');
		Route::get('add-business-records/postpaid/{id}/{type?}','AddRecordController@postPaidForBusinessDues')->name('admin.business.due.postpaid');

		Route::get('add-business-records/payment/{id}/{type?}','AddRecordController@makePaymentForBusinessDues')->name('admin.business.due.payment');
		Route::post('add-business-records-due/payment-callback/{id}','AddRecordController@makePaymentForBusinessDuesCallback')->name('admin.business.due.payment-callback');
		Route::post('add-business-records-due-import/payment-callback/{id}','AddRecordController@makePaymentForBusinessDuesCallbackImport')->name('admin.business.due.payment-callback.import');
		//Superadmin report//
		Route::post('super-admin-reports/{userId}', 'StudentController@importReportSuper')->middleware('onlyAdmin')->name('super-admin-reports');
		//all sttudent records//
		Route::get('all-records', 'AllStudentController@studentRecords')->middleware('member')->name('all-records');
		Route::get('us-records', 'UsBusinessReportController@usPaymentRecords')->middleware('member')->name('us-records');
		Route::get('all-records/{id}/view', 'AllStudentController@studentData')->middleware('member')->middleware('custom.verified')->name('all-records-view');
		Route::get('reported/{id}', 'AllStudentController@reportedBy')->middleware('custom.verified')->name('reported');
		Route::post('search_request_consent', 'AllStudentController@searchRequestConsent')->middleware('member')->name('admin.search_request_consent');

		Route::get('all-records/send-sms', 'AllStudentController@studentRecordsForSms')->name('admin.all-records-for-sms');
		Route::post('all-records/send-sms', 'AllStudentController@studentRecordsSendSms')->name('admin.all-records-send-sms');
		//Route::post('all-records/send-sms-local', 'AllStudentController@studentRecordsSendSmsLocal')->name('admin.all-records-send-sms-local');
		Route::get('all-records/sent-sms', 'AllStudentController@studentRecordsSentSms')->name('admin.all-records-sent-sms');
		Route::get('sample-notifications', 'SampleNotifcationsController@index')->name('admin.sample-notifications');

		Route::get('my-records', 'StudentController@studentRecords')->middleware('member')->name('my-records');
		Route::get('my-individual-records/{student_id}/{due_id}', 'StudentController@myStudentRecords')->middleware('member')->name('my-individual-records');
		Route::get('my-records/send-sms', 'StudentController@studentRecordsForSms')->middleware('member')->name('my-records-for-sms');
		Route::post('my-records/send-sms', 'StudentController@studentRecordsSendSms')->middleware('member')->name('my-records-send-sms');
		//Route::post('my-records/send-sms-local', 'StudentController@studentRecordsSendSmsLocal')->name('my-records-send-sms-local');
		Route::get('my-records/sent-sms', 'StudentController@studentRecordsSentSms')->middleware('member')->name('my-records-sent-sms');

		Route::get('students/edit/{id}', 'StudentController@editStudent')->name('edit-student');
		Route::post('students/update', 'StudentController@updateStudent')->name('update-student');

		Route::get('students/{id}/view', 'StudentController@studentData')->middleware('custom.verified')->name('student-data');
		Route::post('students/{id}/due', 'StudentController@storeDueAmount')->middleware('custom.verified')->name('student-store-due');

		Route::get('students-edit-due-data', 'StudentController@dueDataByDueID')->name('edit-due-data');
		Route::put('students/{id}/edit-due-data', 'StudentController@editDueAmount')->name('student-edit-due');

		Route::get('get-total-Due-For-Student-By-CustomId', 'StudentController@getTotalDueForStudentByCustomId')->name('get-total-Due-For-Student-By-CustomId');
		Route::post('student-store-pay-customer-level', 'StudentController@storePayAmountCustomerLevel')->middleware('custom.verified')->name('student-store-pay-customer-level');
		Route::post('student-due-payment-callback-customer-level/{paid_date}/{paid_note}/{orderArr}/{payment_options}/{skipandupdatepayment}','StudentController@duePaymentCallbackCustomerLevel')->middleware('custom.verified')->name('student-due-payment-callback-customer-level');
		Route::get('get-student-dues-customer-level', 'StudentController@getStudentDuesCustomerLevel')->middleware('custom.verified')->name('get-student-dues-customer-level');


		Route::post('student-store-pay-customer-invoice-level', 'StudentController@storePayAmountCustomerInvoiceLevel')->middleware('custom.verified')->name('student-store-pay-customer-invoice-level');

		Route::post('students/due/proof-of-due/delete', 'StudentController@deleteProofOfDue')->middleware('custom.verified')->name('student-proof-of-due-delete');

		Route::post('students/student-list-of-dues', 'StudentController@getProofOfDueList')->middleware('custom.verified')->name('student-listof-dues');

		Route::post('students/assigned-file-check-isexist', 'StudentController@IsAssigneProofdDue')->middleware('custom.verified')->name('assigned-file-check-isexist');
		Route::post('students/due/delete', 'StudentController@deleteDue')->middleware('custom.verified')->name('student-delete-due');
		Route::post('students/{id}/pay', 'StudentController@storePayAmount')->middleware('custom.verified')->name('student-store-pay');
		Route::post('students/due-payment-callback','StudentController@duePaymentCallback')->middleware('custom.verified')->name('student-due-payment-callback');
		Route::post('student/payment-history', 'StudentController@paymentHistory')->middleware('custom.verified')->name('student-payment-history');

		Route::post('student/payment-history-delete', 'StudentController@paymentHistoryDelete')->middleware('custom.verified')->name('student-payment-history-delete');
		Route::post('customer-kyc/rating/store', 'CustomerkycController@storeRating')->middleware('custom.verified')->name('customer-kyc-rating-store');

		/* Customer KYC */
		/*Route::get('customers/kyc/create', 'CustomerkycController@create')->name('customer-kyc-create');
		Route::post('customers/kyc/store', 'CustomerkycController@store')->name('customer-kyc-store');
	  	*/


	  	/* Membership Upgrade Routes */
	  	// Route::get('upgrade-membership','HomeController@showAvailablePlansToMember')->name('upgrade-membership')->middleware('admin.user')->middleware('member');
	  	Route::get('get-membership-invoice/{membership_payment_id}','HomeController@downloadInvoiceByMembershipPaymentId')->name('get-membership-invoice');

			/*upload proof of due custm level*/
		  Route::post('upload-proof-due-customlevel/proofduestore', 'AddRecordController@proofduestore')->middleware('custom.verified')->name('upload-proof-due-customlevel');
		  Route::post('assign-proof-duelevel', 'AddRecordController@assignproofduestore')->middleware('custom.verified')->name('assign-proof-duelevel');


		Route::get('add-record', 'AddRecordController@index')->middleware('member')->name('add-record');
		Route::post('add-record/store', 'AddRecordController@store')->middleware('custom.verified')->name('add-record-store');
		Route::post('add-record/storereference', 'AddRecordController@storereference')->middleware('custom.verified')->name('add-record-storereference');

	  	/*Help and Support Routes*/
	  	Route::get('helpandsupport', function () { return view('admin/support/helpandsupport'); })->middleware('member')->name('admin.helpandsupport');
		Route::post('sendemail/sendsupportmsg', 'SendEmailController@sendsupportmsg')->name('admin.send-help-and-support-mail');


	  	Route::group(['namespace'=>'Business'],function(){


			Route::get('business/add-record', 'AddRecordController@index')->middleware('member')->name('business.add-record');
			Route::post('business/add-record/store', 'AddRecordController@store')->middleware('custom.verified')->name('business.add-record-store');


			Route::post('business/business-upload-proof-due-customlevel/proofduestore', 'AddRecordController@proofduestore')->middleware('custom.verified')->name('business-upload-proof-due-customlevel');
			Route::post('business/business-assign-proof-duelevel', 'AddRecordController@BusinessAssignProofDuestore')->middleware('custom.verified')->name('business-assign-proof-duelevel');


			/**** my Business records ****/
			Route::get('business/my-business-records/{business_id}/{due_id}', 'MyRecordController@MyBusinessRecords')->middleware('member')->name('business.my-business-records');
			Route::get('business/my-records', 'MyRecordController@MyRecords')->name('business.my-records');

			Route::get('business/my-records/send-sms', 'MyRecordController@recordsForSms')->middleware('member')->name('business.my-records-for-sms');
			Route::post('business/my-records/send-sms', 'MyRecordController@recordsSendSms')->middleware('member')->name('business.my-records-send-sms');
			//Route::post('business/my-records/send-sms-local', 'MyRecordController@recordsSendSmsLocal')->name('business.my-records-send-sms-local');
			Route::get('business/my-records/sent-sms', 'MyRecordController@recordsSentSms')->middleware('member')->name('business.my-records-sent-sms');

			Route::post('business/business-list-of-dues', 'MyRecordController@getProofOfDueList')->middleware('custom.verified')->name('business-listof-dues');
			Route::post('business/business-assigned-file-check-isexist', 'MyRecordController@BusinessIsAssigneProofdDue')->middleware('custom.verified')->name('business-assigned-file-check-isexist');
			Route::get('business/edit/{id}', 'MyRecordController@editBusiness')->name('business.edit-business');
			Route::post('business/update', 'MyRecordController@updateBusiness')->name('business.update-business');
			Route::get('business/{id}/view', 'MyRecordController@businessData')->middleware('custom.verified')->name('business.business-data');
			Route::post('business/{id}/due', 'MyRecordController@storeDueAmount')->middleware('custom.verified')->name('business.store-due');
			Route::get('business/edit-due-data', 'MyRecordController@dueDataByDueID')->name('business.edit-due-data');
			Route::put('business/{id}/edit-due-data', 'MyRecordController@editDueAmount')->name('business.business-edit-due');
			Route::post('business/due/proof-of-due/delete', 'MyRecordController@deleteProofOfDue')->middleware('custom.verified')->name('business.business-proof-of-due-delete');
			Route::post('business/due/delete', 'MyRecordController@deleteDue')->middleware('custom.verified')->name('business.business-delete-due');
			Route::post('business/{id}/pay', 'MyRecordController@storePayAmount')->middleware('custom.verified')->name('business.business-store-pay');
			Route::post('business/due-payment-callback','MyRecordController@duePaymentCallback')->middleware('custom.verified')->name('business.business-due-payment-callback');

			Route::post('business/payment-history', 'MyRecordController@paymentHistory')->middleware('custom.verified')->name('business.business-payment-history1');
			Route::post('business/payment-history-delete', 'MyRecordController@paymentHistoryDelete')->middleware('custom.verified')->name('business.business-payment-history-delete');

			Route::post('business/get-customer-deatils', 'AllRecordController@GetPopulateBasicDetails')->middleware('member')->name('get-customer-deatils');
			//Business Update Payments Customer Level
			Route::get('get-total-Due-For-Business-By-CustomId', 'MyRecordController@getTotalDueForBusinessByCustomId')->name('get-total-Due-For-Business-By-CustomId');
			Route::post('business-store-pay-customer-level', 'MyRecordController@storePayAmountCustomerLevel')->middleware('custom.verified')->name('business-store-pay-customer-level');
			Route::post('business/business-due-payment-callback-customer-level/{paid_date}/{paid_note}/{checkbox}/{payment_options}/{skipandupdatepayment}','MyRecordController@duePaymentCallbackCustomerLevel')->middleware('custom.verified')->name('business.business-due-payment-callback-customer-level');
			Route::get('get-business-dues-customer-level', 'MyRecordController@getBusinessDuesCustomerLevel')->middleware('custom.verified')->name('get-business-dues-customer-level');
		//Business Update Payments Customer Level Ends

			/**** All Business records ****/
			Route::get('business/all-records', 'AllRecordController@allRecords')->middleware('member')->name('business.all-records');
			Route::get('business/all-records/{id}/view', 'AllRecordController@businessData')->middleware('member')->middleware('custom.verified')->name('business.all-records-view');
			Route::get('business/reported/{id}', 'AllRecordController@reportedBy')->middleware('custom.verified')->name('business.reported');
			Route::post('search_request_consent_business', 'AllRecordController@searchRequestConsent')->middleware('member')->name('admin.search_request_consent_business');

			Route::get('business/all-records/send-sms', 'AllRecordController@recordsForSms')->middleware('member')->name('admin.business.all-records-for-sms');
			Route::post('business/all-records/send-sms', 'AllRecordController@recordsSendSms')->middleware('member')->name('admin.business.all-records-send-sms');
			//Route::post('business/all-records/send-sms-local', 'AllRecordController@recordsSendSmsLocal')->name('admin.business.all-records-send-sms-local');
			Route::get('business/all-records/sent-sms', 'AllRecordController@recordsSentSms')->middleware('member')->name('admin.business.all-records-sent-sms');

			Route::get('business/import-excel', 'AllRecordController@importExcelView')->middleware('member')->name('import-excel-view-business');
			Route::post('business/import-excel', 'AllRecordController@importExcel')->middleware('custom.verified')->name('import-business');
			Route::post('import-excel-super-business/{userId}', 'AllRecordController@importExcelSuper')->middleware('custom.verified')->name('import-business-super');
			Route::post('import-business-report-super/{userId}', 'AllRecordController@importReportSuper')->middleware('onlyAdmin')->name('import-business-report-super');
			Route::get('business/import-excel/issues/{unique_url_code}/{userId?}', 'AllRecordController@importExcelIssues')->middleware('custom.verified')->name('import-excel-business.issues');
			Route::get('download-business', 'AllRecordController@export')->middleware('member')->middleware('custom.verified')->name('export-business');
			Route::get('download-business-payments', 'AllRecordController@export_updatePayments')->middleware('member')->middleware('custom.verified')->name('export-business-payments');
			Route::get('business/report', 'MyRecordController@report')->middleware('member')->middleware('custom.verified')->name('admin.business.report');
			Route::get('business/download/report', 'MyRecordController@DownloadReport')->middleware('member')->middleware('custom.verified')->name('admin.business.download.report');

			Route::post('business/import-due-payment', 'AllRecordController@importDuePayment')->middleware('custom.verified')->name('import-business-due-payment');
			Route::get('business/import-due-payment/issues/{unique_url_code}', 'AllRecordController@importDuePaymentIssues')->middleware('custom.verified')->name('import-business-due-payment.issues');
			Route::post('business/import-business-update-profile/{userId}', 'AllRecordController@importUpdateProfile')->middleware('custom.verified')->name('import-business-update-profile');
			Route::get('business/import-excel-profile/issues/{unique_url_code}/{userId?}', 'AllRecordController@importExcelProfileIssues')->middleware('custom.verified')->name('import-excel-business-profile.issues');


			Route::get('business/all-records/consent/payment/{id}','AllRecordController@consentPayment')->name('admin.business.consent.payment');
			Route::post('business/all-records/consent/payment-callback','AllRecordController@consentPaymentCallback')->name('admin.business.consent.payment-callback');
			Route::get('business/all-records/consent/check-status/{consentId}', 'AllRecordController@checkConsentStatus')->name('admin.business.check-consent-status');

			Route::get('download-india-b2b-report-pdf', 'MyRecordController@indiaB2BPDFReport')->middleware('custom.verified')->name('admin.india-b2b.business.report.download.pdf');
	  	});

		Route::get('dispute','DisputeController@index')->middleware('member')->name('admin.dispute-list');
		Route::post('dispute/{disputeId}/due-delete','DisputeController@deleteDue')->name('admin.dispute-due-delete');
		Route::put('dispute/{disputeId}/due-edit', 'DisputeController@editDue')->name('admin.dispute-edit-due');
		Route::get('dispute/{disputeId}','DisputeController@view')->name('admin.dispute-view');
		Route::get('dispute-reject/{disputeId}','DisputeController@reject')->name('admin.dispute-reject');
		Route::get('download-disputes', 'DisputeController@export')->middleware('custom.verified')->name('export-dispute');

		Route::group(['namespace'=>'Superadmin'],function(){


            Route::get('download-members-reports','UserController@MembersReportsExport')->name('superadmin.download-members-reports');
			Route::get('download-creditreport-records','UserController@creditReport_export')->name('superadmin.download-creditreport-records');
			Route::get('download-consentlog-records','UserController@ConsentLog_export')->name('superadmin.download-consentlog-records');
			Route::get('download-all-members','UserController@export')->name('superadmin.download-all-members');
			Route::get('sms', 'DueSmsController@index')->middleware('onlyAdmin')->name('superadmin.due-sms-list');
			Route::get('users/userid')->middleware('onlyAdmin')->name('superadmin.user.membership');
			Route::post('sms/approve-reject', 'DueSmsController@approveReject')->middleware('onlyAdmin')->name('superadmin.due-sms-approve-reject');
			Route::post('sms/approve-reject-bulk', 'DueSmsController@approveRejectBulk')->middleware('onlyAdmin')->name('superadmin.due-sms-approve-reject-bulk');
			Route::get('customer-credit-report-analysis-filter', 'UserController@customer_credit_report_analysis')->middleware('onlyAdmin')->name('customer-credit-report-analysis-filter');
			Route::get('customer-credit-report-analysis', 'UserController@customer_credit_report_analysis')->middleware('onlyAdmin')->name('customer-credit-report-analysis');
			Route::get('users/{id}/membership', 'UserController@membershipIndex')->middleware('onlyAdmin')->name('superadmin.user.membership');
			Route::get('users/{id}/invoice', 'UserController@invoiceList')->middleware('onlyAdmin')->name('superadmin.user.invoice');
			Route::get('users/{id}/membership/edit', 'UserController@getUserMembershipDetails')->middleware('onlyAdmin')->name('superadmin.user-edit.membership');
			Route::post('users/{id}/update_membership', 'UserController@updateMembershipDetails')->middleware('onlyAdmin')->name('superadmin.user.update_membership');
			//Route::post('users/generate_invoice', 'UserController@generateInvoice')->middleware('onlyAdmin')->name('superadmin.user.generate_invoice');
			// Route::get('users/{id}/send-payment-link', 'UserController@sendMakePaymentLink')->middleware('onlyAdmin')->name('superadmin.user.send-payment-link');
			Route::post('users/{id}/save-send-payment-link', 'UserController@saveAndSendMakePaymentLink')->middleware('onlyAdmin')->name('superadmin.user.save.send-payment-link');
			Route::get('download/invoice/{member_id}', 'UserController@downloadMembershipInvoice')->middleware('onlyAdmin')->name('superadmin.user.membership.invoice');
			Route::get('consent-log', 'UserController@ConsentLog')->middleware('onlyAdmin')->name('consent-log');
			Route::get('member-reports', 'UserController@Member_Reports')->middleware('onlyAdmin')->name('member-reports');
		});

		Route::get('creditreports','CreditReportController@index')->middleware('member')->name('admin.credit-report');
    Route::get('individual-custom-creditreports','CreditReportController@IndividualCustomCreditReport')->middleware('member')->name('admin.individual-custom-credit-report');
	});
});

Route::get('myconsent/thankyou','Front\MyConsentController@thankyou')->name('myconsent.thankyou');
Route::get('myconsent/{unique_url}', 'Front\MyConsentController@index')->name('myconsent');
Route::post('myconsent/{unique_url}/send-otp', 'Front\MyConsentController@sendOtp')->name('myconsent.sendOtp');
Route::post('myconsent/{unique_url}/accept', 'Front\MyConsentController@accept')->name('myconsent.accept');
Route::post('myconsent/{unique_url}/deny', 'Front\MyConsentController@deny')->name('myconsent.deny');
Route::post('store-individual-custom-creditreport', 'Front\MyConsentController@storeIndividualCustomCreditReport')->name('store-individual-custom-creditreport');


// customer payment page
Route::get('make-payment/{unique_url}', 'Front\MakePaymentController@index')->name('customer.payment-page');
Route::get('make-payment/{unique_url}/payment', 'Front\MakePaymentController@makePayment')->name('customer.make-payment');
Route::post('make-payment/payment-callback', 'Front\MakePaymentController@customerPaymentCallback')->name('customer.payment-callback');
Route::get('make-payment/{order_id}/status', 'Front\MakePaymentController@paymentStatus')->name('customer.payment-status');


Route::get('logout', 'HomeController@logout');
Route::get('getgstinapidata', 'ApiController@getGstinData');
Route::get('checkemailexists', 'HomeController@checkEmailExists');
Route::post('businessname_validate','Auth\RegisterController@businessname_validate');
Route::post('businessname_validation','HomeController@business_namevalidation');
Route::get('verifyoffercode', 'ApiController@verifyOfferCode');
Route::get('getallrecords', 'HomeController@getAllRecords');
Route::get('getallcustomers', 'HomeController@getAllCustomers');
//Route::get('emailverification', 'HomeController@emailVerification');
//Route::get('emailverification/{emlink}', 'HomeController@verify')->name('verification.email.verify');
Route::get('emailverifymail', 'HomeController@emailVerifyMail');

Route::post('user_prepaid_postpaid', 'HomeController@userPrepaidPostpaid')->middleware('auth');


Route::view('admin/configuration', 'admin.configuration');
Route::view('admin/scheduler', 'admin.scheduler');
Route::view('admin/reports', 'admin.reports');

//notification module

// Route::view('admin/notificationScheduler', 'admin.notificationScheduler');
Route::get('admin/notificationScheduler', 'NotificationSchedulerController@index')->middleware('onlyAdmin');
Route::get('admin/getTemplateByType', 'NotificationSchedulerController@getTemplateByType')->middleware('onlyAdmin');
Route::get('admin/saveEmailTemplate', 'NotificationSchedulerController@saveEmailTemplate')->middleware('onlyAdmin');
Route::post('admin/add_notificationScheduler', 'NotificationSchedulerController@addNotification')->name('add-notification')->middleware('onlyAdmin');
Route::post('admin/seach_notificationScheduler', 'NotificationSchedulerController@searchNotification')->name('search-notification')->middleware('onlyAdmin');
Route::get('/admin/stop_notificationScheduler/{id}/{value}', 'NotificationSchedulerController@stopNotification')->middleware('onlyAdmin');
Route::get('admin/delete_notificationScheduler/{id}', 'NotificationSchedulerController@deleteNotification')->middleware('onlyAdmin');
Route::get('admin/customer_notificationScheduler/{customer_type_id?}/{member?}', 'NotificationSchedulerController@customerNotification')->middleware('onlyAdmin');
Route::get('admin/view_exclusions_members/{id}', 'NotificationSchedulerController@viewExclusionsMembers')->middleware('onlyAdmin');

Route::get('admin/sendSms', 'NotificationSchedulerController@sendsms');
Route::get('cron/notificationCron', 'NotificationSchedulerController@notificationCron');
Route::get('cron/introductoryOverdueStudentsCron', 'NotificationSchedulerController@introductoryOverdueStudentsCron');
Route::get('cron/introductoryOverdueBusinessCron', 'NotificationSchedulerController@introductoryOverdueBusinessCron');
