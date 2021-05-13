<?php
	return [
		'e_arbitration_cc_emails' => [
			'e_arbitration_support_mail1' => env('E_ARBITRATION_MAIL_CC'),
			'e_arbitration_support_mail2' => env('E_ARBITRATION_MAIL_CC_SUPPORT'),
		],
		'gstinLookUpConfig' => [
			'username' => env('GSTIN_USERNAME'),
			'password' => env('GSTIN_PASSWORD'),
			'client_id' => env('GSTIN_CLIENTID'),
			'client_secret' => env('GSTIN_CLIENT_SECRET'),
			'grant_type' => env('GSTIN_GRANT_TYPE'),
		],
		'oneCodeConfig' => [
			'clientId' => env('ONECODE_CLIENTID'),
			'clientSecret' => env('ONECODE_CLIENTSECRET'),
		],
		'email_campaign_limit_per_hour' => env('EMAIL_CAMPAIGN_LIMIT_PER_HOUR', 10),
		'email_for_support' => env('MAIL_SUPPORT'),
		'cc_emails' => [
			'support_mail1' => env('MAIL_CC_SUPPORT'),
			'support_mail2' => env('MAIL_CC_ACCOUNTING'),
		],
		
		'email_for_corporate' => env('MAIL_CORPORATE'),
		'B2B_SMS_Number' => env('B2B_SMS_Number'),
		'free_limit_b2c' => env('Free_Limit_B2C'),
		'free_limit_b2b' => env('Free_Limit_B2B'),
		'total_free_reports' => env('Total_Free_Reports'),
	];

?>