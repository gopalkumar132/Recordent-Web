<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/admin/all-records/consent/payment-callback',
        '/admin/add-records-due/payment-callback/*',
        '/admin/add-business-records-due/payment-callback/*',
        '/admin/add-records-due-import/payment-callback/*',
        '/admin/add-business-records-due-import/payment-callback/*',
        '/admin/business/all-records/consent/payment-callback',
        'individual/records/payment/payment-callback',
        'business/records/payment/payment-callback',
        'admin/students/due-payment-callback',
        'admin/business/due-payment-callback',
        'membership-payment/payment-callback/*/*/*',
        'membership-payment/payment-callback',
        'make-payment/payment-callback',
        'multiple-invoice-payment-callback',
        'admin/student-due-payment-callback-customer-level/*/*/*/*/*',
        'admin/business/business-due-payment-callback-customer-level/*/*/*/*/*'
    ];
}
