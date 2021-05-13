<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="http://localhost:8080/recordent/public/admin/voyager-assets?path=css%2Fapp.css">-->
    <link rel="stylesheet" href="{{asset('css/voyager-assets.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
     <!-- <link rel="stylesheet" type="text/css" href="{{asset('css/custom.css')}}"> -->
    <style>
        body,p,tr,td{
            font-weight: 100px;
            font-family: var(--font-open-sans);
        }
        .report-gen .table tbody tr td{
            border-right: 1px solid #c8c8c8;
        }
        a{
            text-decoration: underline;
        }
    </style>

</head>
<body class="voyager container @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">
<div class="row col-md-offset-1" style="padding-top:30px !important;">
                <div class="col-lg-7">
                    <img src="{{url('invoice_icons/logo.jpg')}}" style="width:50% !important;">

                </div>
                <div class="col-lg-5">
                    <p>Invoice No: {{$membership_payment->invoice_id}}</p>
                    <p>Date: {{ $dateTime}}</p>
                </div>
    </div>
    <div class="report-gen col-md-10 col-md-offset-1">
        <p>Original for Recipient</p>
        <p>To ,</p>
        <p>{{$membership_payment->user->business_name}},</p>
        <p>{{$membership_payment->user->state->name}}.</p>
        <p >GSTIN - <span style="text-transform: uppercase;">{{$membership_payment->user->gstin_udise}}</span>.</p>
        <br>
        <p>Your invoice for {{ $membership_payment->particular }}. @if($membership_payment->postpaid==1 && $membership_payment->total_collection_value>0)Due Date is {{ $membership_payment->created_at->addDays(21)->format('d-m-Y') }}@endif</p>
        <table class="table table-hover full-boder">
            <tr>
            <td>Particular</td>
            <td>SAC</td>
            <td>Amount (<span style="font-family: DejaVu Sans, sans-serif !important;">&#8377;</span>)</td>
            </tr>
            <tr>
                <td>{{ $membership_payment->particular }}</td>
                <td>998591</td>
                <td><span style="font-family: DejaVu Sans, sans-serif !important;">&#8377;</span> {{General::ind_money_format($membership_payment->payment_value)}}</td>
            </tr>
            @if($membership_payment->user->state_id==36)
            <tr>
                <td>CGST @ 9%</td>

                <td></td>
                <td><span style="font-family: DejaVu Sans, sans-serif !important;">&#8377;</span> {{ General::ind_money_format( $membership_payment->gst_value/2 ) }}</td>
            </tr>
            <tr>
                <td>SGST @ 9%</td>

                <td></td>
                <td><span style="font-family: DejaVu Sans, sans-serif !important;">&#8377;</span> {{ General::ind_money_format( $membership_payment->gst_value/2 ) }}</td>
            </tr>
            @else
                <tr>
                <td>IGST @ 18%</td>
                <td></td>
                <td><span style="font-family: DejaVu Sans, sans-serif !important;">&#8377;</span> {{ General::ind_money_format($membership_payment->gst_value) }}</td>

            </tr>
            @endif
            <tr>
                <td>Convenience Fee</td>
                <td></td>
                <td><span style="font-family: DejaVu Sans, sans-serif !important;">&#8377;</span> {{General::ind_money_format($membership_payment->collection_fee)}}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td></td>
                <td><span style="font-family: DejaVu Sans, sans-serif !important;">&#8377;</span> {{General::ind_money_format($membership_payment->discount)}}</td>
            </tr>
            <tr>
                <td>Total</td>

                <td></td>
                <td><span style="font-family: DejaVu Sans, sans-serif !important;">&#8377;</span> {{ General::ind_money_format(($membership_payment->total_collection_value)) }}</td>
            </tr>
        </table>
        <p>Amount (in words): {{General::AmountInWords($membership_payment->total_collection_value)}}</p>
        <p style="text-align: right;">E. & O.E</p>
        @if($membership_payment->postpaid==1)
        <p>You can pay by logging into your Recordent account or by Bank transfer.</p>
        <p>Bank details:</p>
        <p>Name: Recordent Private Limited</p>
        <p>HDFC Bank, Banjara Hills, Road No 3 Branch</p>
        <p>Account Number:50200036996639</p>
        <p>IFSC : HDFC0002391</p>
        @endif
	<br>
        <p>Best Regards,</p>
        <p>Recordent Private Limited</p>
        <p>Registered Office: #P-306, Sri Rams Swathi Paradise, Alkapur Township, Rd No. 16, </p>
        <p>Hyderabad, Telangana, 500089</p>
        <p>Corporate Office: Aditya Trade Center,Office No:-7-1-618/ACT/710,Seventh Floor, </p>
        <p>Ameerpet,Hyderabad, Telangana,India Pincode - 500038</p>
        <p>CIN: U74999TG2018PTC124275</p>
        <p>GSTIN: 36AAICR9328P1ZI</p>

        <p class="text-center" style="position: absolute;bottom: 10;color: #76838f;font-size: 12px;">
            We declare that this invoice shows the actual price of the Services described and that all particulars are true and correct.<br>
            SUBJECT TO TELANGANA JURISDICTION<br>
            This is a Computer Generated Invoice

        </p>
    </div>

</body>
</html>
