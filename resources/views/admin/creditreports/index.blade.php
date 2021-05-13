@extends('voyager::master')

@section('page_title', __('Recordent - Check Credit & Payment History'))

@section('page_header')

@stop
@section('content')
<meta name="description" content="Check Credit & Payment History.">
<link rel="stylesheet" href="https://www.stage.recordent.com/admin/voyager-assets?path=css%2Fapp.css">
        <link rel="stylesheet" type="text/css" href="https://www.stage.recordent.com/css/custom.css">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.2.4.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<style type="text/css">
        .nav-tabs1 {
            border-bottom: 1px solid #ddd;
        }
        .nav-tabs1 > li.active > a, .nav-tabs1 > li.active > a:focus, .nav-tabs1 > li.active > a:hover {
            color: #202020;
            cursor: default;
            background-color: #fff;
            border: 1px solid #ddd;
            border-bottom-color: rgb(221, 221, 221);
            border-bottom-color: transparent;
            box-shadow: 0px -11px 10px 5px #e3e3e3;
        }
        .nav-tabs1 > li{
            float: left;
            margin-bottom: -1px;
            display: flex;
            width: 33.1%;
        }
        .nav-tabs1 > span {
            float: left;
            display: flex;
        }
        .nav-tabs1 > li.active > a{
            background-color: #fff;
            color: #000;
            width: 100%;
            border-radius: 10px 10px 0px 0px;
            height: 130px;
        }
        .nav1 > li > a {
            overflow: hidden;
            width: 100% !important;
            border: 1px solid transparent;
            font-size: 18px;
            line-height: 20px;
            text-align: center;
            padding: 12px;
            color: #252525;
            font-weight: 600;
            height: 130px;
        }
        .tab-content{
            border-width: 0 1px 1px 1px;
            border-style: solid;
            border-color: #ddd !important;
            background-color: #fff;
            border-radius: 0px 0px 10px 10px;
            color: #000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .nav1 > li > a:focus, .nav1 > li > a:hover {
            text-decoration: none;
            background-color: transparent;
        }
        .nav1 > li > a > img {
            max-width: none;
            width: 45px;
            padding-bottom: 5px;
            height: 36px;
        }
        .nav1 > li > a > span{
            display: block;
            color: red;
            font-size: 15px;
            font-weight: 600;
            height: 28px;
            padding: 5px 0px;
        }
        .nav1{
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }
        .nav1::after {
            clear: both;
        }
        .nav1::after, .nav1::before {
            display: table;
            content: " ";
        }
        .border-tab{
            border-right: 3px solid #e3e3e3;
            height:50px;
            top:32px;
            position:relative;
        }
        .viewsamplebtn{
            border: 2px solid #e1e1e1;
            background-color: transparent;
            height: 47px;
            border-radius: 10px;
            line-height: 33px;
            font-size: 15px;
            font-weight: 600;
            color: #3a3a3a;
            margin: 0px auto;
            text-align: center;
            padding: 0px 25px;
        }
        .formtext{
            font-family: 'Open Sans', sans-serif;
            color: #252525;
            text-align: center;
            border-bottom: 1px solid #e8e8e8;
        }
        .formtext h3{
            font-size: 25px;
            padding: 0px;
            margin: 0px;
        }
        .recordent_report .form-check-label {
            font-weight: 400;
            margin-right: 40px;
            color: #686767 !important;
            font-size: 16px;
        }
        .requestbtn{
            background-color: #273581;
            border: 1px solid #273581;
            border-radius: 8px;
            color: #fff;
            padding: 0px;
            font-weight: 700;
            line-height: 40px;
            width: 300px;
            height: 45px;
            margin-top: 32px;
        }
        .requestbtn:hover{
            background-color: #fff;
            border: 1px solid #273581;
            border-radius: 8px;
            color: #273581;
            padding: 0px;
            font-weight: 700;
            line-height: 40px;
            width: 300px;
            height: 45px;
            margin-top: 32px;
        }
        .page-title1{
            display: inline-block;
            height: auto;
            font-size: 18px;
            margin-top: 3px;
            padding-top: 12px;
            padding-left: 50px;
            margin-bottom: 10px;
            color: #555;
            font-weight:800;
            line-height: 30px;
        }
        .page-title1 > img {
            font-size: 25px;
            position: absolute;
            left: 25px;
            margin-right: 10px;
        }
        .page-title2{
            display: inline-block;
            height: auto;
            font-size: 11px;
            margin-top: 3px;
            padding-top: 12px;
            padding-left: 179px;
            margin-bottom: 10px;
            color: #555;
            font-weight:400;
            line-height: 23px;
        }
        .page-title2 > img {
            height: 26px;
        }
        .page-title3{
            font-size: 18px;
            margin-top: 10px;
            /*margin-bottom: 70px;*/
            color: #5f94c4;
            font-weight:600;
            line-height: 23px;
            text-align: center;
        }
        .samplereport-tab{
            text-align:center;
            font-size:14px;
            color:blue;
            background-color: transparent;
            border: none;
            pointer-events:fill;
            text-decoration-line: underline;
        }
       .disabledTab{
            pointer-events: none;
            background-color: transparent;
            color: #000;
            width: 100%;
            border-radius: 10px 10px 0px 0px;
            height: 130px;
        }
        .advanced-search{
            margin:75px 0px;
        }
        div#dataTable_filter label.error, label.error, #add_store_record label.error {
            color: red !important;
            position: absolute;
            bottom: -29px;
            font-size: 13px;
        }

        .pdf-logo{
           width: 200px;
           height: 60px;
           align-items: center;
        }
        .pdf-logo img{
            width: 200px;
        }
        .pdf-downloadbtn{
            color: #202f7d;
            text-align: center;
            font-weight: 800;
            font-size: 25px;
            line-height: 28px;
            margin: 12px;
        }
        .pdf-date{
            text-align: right;
            font-size: 14px;
            /*font-style: italic;*/
            font-weight: 400;
            color: #fff;
            background-color: #1e2c76;
            width: max-content;
            padding: 5px 30px;
            float: right;
            margin-top: 5px;
            border-radius: 20px 10px;
            position: relative;
            top: 7px;
        }
        .pie-title-center {
        margin: auto;
        position: relative;
        text-align: center;
        }
        .pie-title-center p{
            display: block;
            position: absolute;
            height: 40px;
            top: 63%;
            left: 0;
            right: 0;
            margin-top: -20px;
            line-height: 22px;
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }
        .pie-value-txt{
            display: block;
            position: absolute;
            font-size: 20px;
            height: 40px;
            top: 46px;
            margin: 0px auto;
            /*line-height: 25px;*/
            font-weight: 600;
            color: #000;
            width:200px;
            text-align:center;
            padding:10px;
        }

        .rc_progress {​​
            position: relative;
            overflow: visible;
            border-radius: 10px;
            margin: 0px auto 40px auto;
            max-width: 900px;
            }​​
            .rc_block .left_top p{
                font-size:15px;
                color:#727481
            }
            .clr-green,.right_top a.clr-green,.right_top h5.clr-green,.right_top h5.clr-green a,a.clr-green{
                color:#4cb826;
                font-weight:600!important;
                font-size:16px;
                text-transform:uppercase
            }
           .right_top a.clr-green:hover,.right_top h5.clr-green a:hover,a.clr-green:hover{
            color:#273581
           }
           .rc_bottom{
             padding:50px 0 0 0
           }
         .rc_bottom .left_bottom p{
            font-size:18px;
            line-height:24px;
            color:#575c71;
            font-weight:600!important;
            margin-bottom:0
         }
         .rc_bottom .left_bottom p span{
            color:#202f7d;
            font-weight:700
         }
         .not_link i{
            font-size:20px;
            color:#f5d13d;
            font-weight:700
         }
         .not_link:hover i{
            color:#273581
         }
         .ac_inline{
            display:inline-block
         }
         .ac_lm{
            margin-left:20px
         }
         .address_span{
            max-width:50%
         }
         .rc_progress{
            position:relative;
            overflow:visible;
            border-radius:10px;
            margin:0px auto 70px auto;
            max-width:900px
         }
        .rc_progress .progress-bar-danger{
            border-top-left-radius:10px;
            border-bottom-left-radius:10px;
            background:#ff6c6c!important;
            width:30%;
            position:relative
        }
         .rc_progress .progress-bar-danger::before,.rc_progress .progress-bar-info::before,.rc_progress .progress-bar-warning::before{
            content:"";
            width:1px;
            height:100%;
            position:absolute;
            right:0;
            top:0;
            background:#fff
         }
         .rc_progress .progress-bar-warning{
            background:#ffb36c!important;
            width:30%;
            position:relative
         }
         .rc_progress .progress-bar-info{
            background:#f5d13d!important;
            width:20%;
            position:relative
         }
         .rc_progress .progress-bar-success{
            background:#82e360!important;
            border-top-right-radius:10px;
            border-bottom-right-radius:10px;
            width:20%;
            position:relative
         }
         .rc_progress .progress-bar.active{
            position:absolute;
            background:#82e360;
            width:12px;
            height:50px;
            left: 40%;
            top:-18px;
            border-radius:10px;
            border:solid 1px #fff;
            z-index:99
         }
         .progress-bar {
           line-height: 115px;
           box-shadow: none;
         }
        .pie-value {
            font-size: 40px;
            font-weight: 800;
        }
        .title_imporve {
            text-align: center;
            font-size: 28px;
            padding: 5px 0;
            font-weight: 500;
            color:#000;
        }
        .progress{
            border-radius: 5px;
            overflow: inherit;
        }
        .redpb{
            background-color: #ff6c6c !important;
            border-right: solid 2px #fff;
        }
        .orangepb{
            background-color: #ffb36c !important;
            border-right: solid 2px #fff;
        }
        .greenpb{
            background-color: #82e360 !important;
            border-right: solid 2px #fff;
        }
        .bluepb{
            background-color: #1483f2 !important;
        }
        .yellowpb{
            background-color: #f5d13d !important;
        }
        .donutchart{
            width: 200px;
            margin: 0px auto;
            height:200px;
        }
        .donutchart h3{
            font-size: 18px;
            font-weight: 600;
            padding: 0px 0px 20px;
            color: black;
        }
        .progress-bar span{
            color: #262626;
            top: -16px;
            position: relative;
            z-index: 4;
            font-weight: 600;
            font-size: 13px;
        }

        .rc_progress .progress-bar-act{​​​​​

            left: 0;
            position: absolute !important;
            /*background: #82e360;*/
            width: 12px;
            height: 50px;
            top: -18px;
            border-radius: 10px;
            border: solid 1px #fff;
            z-index: 99;
        }​​​​​
        .progress-meter {
            min-height: 5px;
        }

        .progress-meter > .meter {
            position: relative;
            float: left;
            min-height: 5px;
        }

        .progress-meter > .meter-left {
            border-left-width: 2px;
        }

        .progress-meter > .meter-right {
            float: right;
            border-right-width: 2px;
        }

        .progress-meter > .meter-right:last-child {
            border-left-width: 2px;
        }

        .progress-meter > .meter > .meter-text {
            position: absolute;
            display: inline-block;
            bottom: -5px;
            width: 100%;
            font-weight: 700;
            font-size: 0.85em;
            color: rgb(0, 0, 0);
            text-align: right;
        }

        .progress-meter > .meter.meter-right > .meter-text {
            text-align: right;
        }
        .mt-mb{
            margin: 25px auto;
        }
        .customerdata{
            margin: 45px auto;
        }

        #customers {
            border-collapse: separate;
            color: #424242;
            font-size: 15px;
            width: 100%;
            border: 1px solid #bbbbbb;
            border-radius: 10px 10px 0px 0px;
            overflow: hidden;
        }
        #customers td{
            border-bottom: 1px solid #bbbbbb;
            padding: 10px;
            color: #000000;
            font-weight: 500;
            border-right: 1px solid #bbbbbb;
        }
        #customers td:last-child{
            border-right: none;
        }
        #customers th {
            background-color: #f3b90f;
            color: #424242;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            text-align: center;
        }
        .reportdata{
            margin: 45px auto;
        }
        #reportdata {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #reportdata td{
            padding: 10px 50px;
            background-color: #e8e8e8;
            font-weight: 600;
        }
        #reportdata th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            text-align: center;
        }
        .non-headtxt{
            position: relative;
            margin: 25px auto;
            text-align: center;
            display: block;
        }
        .non-headtxt h2{
            font-size: 18px;
            font-weight: 600;
            background-color: #f3b90f;
            width: max-content;
            padding: 10px 30px;
            border-radius: 20px;
            color: black;
            text-align: center;
            z-index: 99;
            position: relative;
            text-align: center;
            margin: 0px auto;
        }
        .non-headtxt span{
            border-bottom: solid 1px #424242;
            display: block;
            top: -19px;
            position: relative;
            z-index: 1;
        }
        .statistics_item {
            background-color: #273581;
            text-align: center;
            padding: 25px 15px;
            border-bottom: solid 4px #0b1130;
            border-radius: 20px;
            box-shadow: 3px 3px 15px #d3d3d3;
            -moz-box-shadow: 3px 3px 15px #d3d3d3;
            -webkit-box-shadow: 3px 3px 15px #d3d3d3;
            -o-box-shadow: 3px 3px 15px #d3d3d3;
            width: 300px;
            height:120px;
            margin: 15px auto;
        }
        .statistics_item .counter{
            font-size: 35px;
            font-weight: 700;
            margin-top:20px;
            height:30px;
            color: #fff;
        }
        .statistics_item p{
            color: #d1d1d1;
        }
        .statistics_item .counter span{
            font-size: 25px;
            font-weight: 700;
        }

        .pb-hr{
            border-bottom: solid 1px #424242;
            display: block;
            position: relative;
            margin: 25px auto;
        }
        .publicdeeds{
            margin: 45px auto;
        }
        .donutchart1{
            width: 150px;
            margin: 0px auto;
        }
        .donutchart1 h3{
            font-size: 18px;
            font-weight: 600;
            padding: 0px 0px 20px;
            color: black;
        }
        .pie-value-txt1{
            display: block;
            position: absolute;
            height: 40px;
            top: 56%;
            left: 0;
            right: 0;
            line-height: 20px;
            font-weight: 600;
            color: #000;
            text-align: center;
            width: 150px;
            margin: 0px auto;
        }
        .pie-value1 {
            font-size: 18px;
            font-weight: bold;
        }
        #publicdeeds {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #publicdeeds td{
            padding: 10px 5px;
            border: 1px solid #bbbbbb;
            font-weight: 600;
            text-align: center;
            border-radius: 15px 15px 0px 0px;
        }
        #publicdeeds th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            text-align: center;
            border-radius: 15px 15px 0px 0px;
        }
        .publicdeeds2{
            margin: 45px auto;
        }
        #publicdeeds2 {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #publicdeeds2 td{
            padding: 10px 25px;
            font-weight: 600;
        }
        #publicdeeds2 th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            padding: 10px 25px;
            border-radius: 15px 15px 0px 0px;
            text-align: center;
        }
        .creditage{
            margin: 45px auto;
        }
        #creditage {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #creditage td{
            padding: 10px 25px;
            font-weight: 600;
            text-align: center;
        }
        #creditage th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            padding: 10px 25px;
            border-radius: 15px 15px 0px 0px;
            text-align: center;
        }
        .totalaccount{
            margin: 45px auto;
        }
        #totalaccount {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #totalaccount td{
            padding: 10px 5px;
            border: 1px solid #bbbbbb;
            font-weight: 600;
            text-align: center;
        }
        #totalaccount th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            padding: 10px 25px;
            border-radius: 15px 15px 0px 0px;
            text-align: center;
        }
        .openacdetails{
            margin: 45px auto;
        }
        #openacdetails {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #openacdetails td{
            padding: 10px 25px;
            font-weight: 600;
            text-align: left;
        }
        #openacdetails th {
            background-color: #e8e8e8;
            /*color: #fff;*/
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            padding: 10px 25px;
            border-radius: 15px 15px 0px 0px;
            text-align: center;
        }
        .paymenthistory{
            margin: 45px auto;
        }
        #paymenthistory {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
            table-layout: fixed;
        }
        #paymenthistory td{
            padding: 0px;
            border: 1px solid #bbbbbb;
            font-weight: 600;
            text-align: center;
            width: calc(100%/13);
        }
        #paymenthistory th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            text-align: center;
            border-radius: 15px 15px 0px 0px;
        }
        .red-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #f22a2a;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .pur-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #db22cd;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .lblue-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #79d2de;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .green-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #1da727;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .blue-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #147ad6;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .bri-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #7849c4;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .black-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #000;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .orange-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #ff9d00;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .tab-legends{
            display: flex;
            position: relative;
            flex-direction: row;
            margin: 10px auto;
            flex-wrap: wrap;
            justify-content:space-between;
        }
        .tab-legends li{
            list-style: none;
            margin-right: 10px;
            font-size: 13px;
            color: #000;
            font-weight: 300;
            line-height: 15px;
        }
        .tab-legends li > div{
            display: inline-flex;
            margin-right: 5px;
            top: 3px;
            position: relative;
        }
        .tab-blue{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #147ad6;
        }
        .tab-lblue{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #79d2de;
        }
        .tab-green{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #1da727;
        }
        .tab-red{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #f22a2a;
        }
        .tab-orange{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #ff9d00;
        }
        .tab-bri{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #7849c4;
        }
        .tab-pur{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #db22cd;
        }
        .tab-black{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #000;
        }
        #averagedaystb {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
            table-layout: fixed;
            margin: 0px -15px;
        }
        #averagedaystb td{
            padding: 10px 5px;
            border: 1px solid #bbbbbb;
            font-weight: 600;
            text-align: center;
        }
        #averagedaystb th {
            color: #000;
            font-size: 16px;
            font-weight: 800;
            height: 45px;
            text-align: center;
            border: 1px solid #bbbbbb;
        }
        .media-break{
            display: none;
        }
        .order-number{
          margin-right: 4px;
        }
        .payment-history{
            padding-left: 357px;
        }
        .payment-time{
            padding-left: 52px;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
               -webkit-appearance: none;
               margin: 0;
        }

        input[type=number] {
         -moz-appearance: textfield;
        }


        @media (max-width: 767px) {
            .container-fluid{
                padding:0px !important;
            }
            .xs-p0{
                padding:0px !important;
            }
            .nav1 > li > a {
                font-size: 13px;
                line-height: 17px;
                height:150px;
            }
            .nav1 > li > a > span {
                font-size: 12px;
                font-weight: 400;
            }
            .nav1 > li > a > img {
                width: 35px;
            }
            .nav-tabs1 > li.active > a {
                height: 120px;
            }
            .disabledTab {
                height: 120px;
            }
            .advanced-search{
                margin:25px 0px;
            }
            .samplereport-tab{
                font-size:12px;
            }
            .xs-pad{
                margin-top:55px;
            }
            .page-title1 {
                font-size: 20px;
                line-height: 23px;
            }
            .page-title2{
                display: inline-block;
                height: auto;
                font-size: 11px;
                margin-top: -4px;
                padding-top: 12px;
                padding-left: 179px;
                margin-bottom: 10px;
                color: #555;
                font-weight:400;
                line-height: 23px;
            }
            .modal-lg{
                width: auto !important;
            }
            .navbar1 {
                padding: 10px 0px !important;
                height:70px;
            }
            .nav > li > a:focus {
                outline: 0;
                background-color: transparent;
            }
            .nav .open > a, .nav .open > a:focus, .nav .open > a:hover {
                border-color: transparent !important;
                background-color: transparent !important;
            }
            .navbar-right1{
                right: 20px !important;
                top: -8px;
            }
            .modal-header .close {
                margin-top: -14px !important;
                margin-right: -10px !important;
            }
            .modal-dialog {
                margin: 30px 10px 10px !important;
            }
            .m-lr-0 {
                margin: 0px !important;
                background-color:transparent !important;
                padding: 0px !important;
                box-shadow: none !important;
            }
            .rc_progress .progress-bar {
                height: 15px;
            }
            .rc_progress .progress-bar-red{
                width:65% !important;
            }
            .rc_progress .progress-bar-or{
                width:10% !important;
            }
            .rc_progress .progress-bar-ye{
                width:10% !important;
            }
            .rc_progress .progress-bar-gr{
                width: 15% !important;
            }
            .rc_progress .progress-bar.active {
                left: 75.99% !important;
            }
            .bg-ash{
                background-position: right top !important;
                background-size: 204px !important;
            }
            .modal-title {
                display: block ruby !important;
                font-size:15px !important;
            }
            .mobile-mr{
            margin: 70px -15px 0px -15px !important;
            padding: 0px !important;
        }
        .voyager .panel{
            padding: 20px 0px;
        }
        .panel-bordered > .panel-body {
            padding: 10px 0px 0px;
        }
        .profress-scroll {
            width: 100%;
            overflow: visible;
        }
        .rc_progress .progress-bar-danger{
            height:15px;
        }
        .rc_progress .progress-bar-warning{
            height:15px;
        }
        .rc_progress .progress-bar-info{
            height:15px;
        }
        .rc_progress .progress-bar-success{
            height:15px;
        }
        .pdf-logo{
            width:100%;
            height:50px;
        }
        .pdf-logo img {
            width: 125px;
        }
        .download-btn {
            border: solid 1px #202f7d;
            color: #202f7d;
            border-radius: 15px;
            display: inline-block;
            padding: 10px 60px 10px 30px;
            text-decoration: none;
            font-size: 20px;
            line-height: 20px;
            font-weight: 700;
            position:relative;
            right: 0px;
            top: 0px;
            width:100%;
        }
        .pdf-date {
            text-align: right;
            font-size: 9.5px;
            /*font-style: italic;*/
            font-weight: 400;
            color: #fff;
            background-color: #1e2c76;
            width: max-content;
            padding: 5px 10px;
            float: right;
            border-radius: 20px 10px;
            position: relative;
            top: -133px;
            left: 17px;
            margin-bottom:0px 0px 10px 0px;
        }
        .mt-mb{
            margin-bottom: -104px;
        }
        .donutchart {
           width: 200px;
            margin: -25px auto;
            height: 200px;
        }

        .mb-0{
            margin-bottom:0px !important;
        }
        .page-title2 {
            display: block;
            height: auto;
            font-size: 11px;
            margin-top: -14px;
            padding-top: 0px;
            padding-left: 0px;
            margin-bottom: 10px;
            color: #555;
            font-weight: 400;
            line-height: 23px;
            text-align: center;
        }
        .statistics_item {
            width: 273px !important;
        }
        #customers{
            font-size:12px;
        }
        #reportdata {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
        }
        #reportdata td {
            padding: 10px 10px;
            background-color: #e8e8e8;
            font-weight: 600;
            white-space: nowrap;
            border: 1px solid #fff;
        }
        #publicdeeds {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: table;
            overflow-y: scroll;
        }
        #publicdeeds2 {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        #publicdeeds2 td{
            border: solid 1px #eee;
        }
        #totalaccount {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        #totalaccount td {
            white-space: nowrap;
        }
        #openacdetails {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
            overflow-x: scroll;
            /*display: inline-block;*/
            overflow-y: scroll;
            padding:2px;
        }
        #openacdetails td {
            padding: 10px 10px;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
            border: solid 1px #eee;
        }
        #paymenthistory {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            table-layout: fixed;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        .ten{
         margin-left: 3px !important;
        }
        .nine{
           margin-right: -54px !important;
        }
        .eight{
          margin-right: -44px !important;
        }
        .seven{
          left: -8px !important;
        }
        .six{
          margin-left: -43px !important;
        }
        .five{
          margin-left: -41px !important;
        }
        .four{
           left: -7px !important;
        }
        .three{
           left: -7px !important;
        }
        .two{
            left: -7px !important;
        }
        .one{
          left: -5px !important;
        }
        .order-number{
             margin-right: -140px !important;
                 top: -121px !important;
        }
        .media-break{
            display: block;
        }

        /*.dpd-text span {
          display: none;
        }

        .dpd-text:after {
            content: "DPD";
        }*/
        .over-due-text span {
          display: none;
        }
         .over-due-text:after {
            content: "Due";
        }
        .payment-history{
            font-size: 10.9px;
            padding-left: 0px;
        }
        .payment-time{
            padding-left: 8px;
           font-size: 9px;
        }
        #paymenthistory td{
            padding: 12px !important;

        }
        .customers-data{
            padding-right: 0px !important;
        }
        .customers-id{
            padding-left: 0px !important;
            padding-top: 0px !important;
        }

        }
        @media (max-width: 990px) and (min-width: 768px) {
            .hidden-sm{
                display:none;
            }
            .hidden-sm{
                display:block;
            }
            .modal-lg{
                width: auto !important;
                margin: 20px 20px;
            }
            .rc_progress .progress-bar {
                height: 15px;
            }
            .rc_progress .progress-bar-red{
                width:65% !important;
            }
            .rc_progress .progress-bar-or{
                width:10% !important;
            }
            .rc_progress .progress-bar-ye{
                width:10% !important;
            }
            .rc_progress .progress-bar-gr{
                width: 15% !important;
            }
            .rc_progress .progress-bar.active {
                left: 75.99% !important;
            }
            .mobile-mr{
            margin: 70px -15px 0px -15px !important;
            padding: 0px !important;
        }
        .voyager .panel{
            padding: 20px 0px;
        }
        .statistics_item {
            width: 273px !important;
        }
        .panel-bordered > .panel-body {
            padding: 10px 0px 0px;
        }
        .profress-scroll {
            width: 100%;
            overflow: visible;
        }
        .rc_progress .progress-bar-danger{
            height:15px;
        }
        .rc_progress .progress-bar-warning{
            height:15px;
        }
        .rc_progress .progress-bar-info{
            height:15px;
        }
        .rc_progress .progress-bar-success{
            height:15px;
        }
        .pdf-logo{
            width:100%;
            height:50px;
        }
        .pdf-logo img {
            width: 125px;
        }
        .download-btn {
            border: solid 1px #202f7d;
            color: #202f7d;
            border-radius: 15px;
            display: inline-block;
            padding: 10px 60px 10px 30px;
            text-decoration: none;
            font-size: 20px;
            line-height: 20px;
            font-weight: 700;
            position:relative;
            right: 0px;
            top: 0px;
            width:100%;
        }
        .pdf-date {
            text-align: right;
            font-size: 9.5px;
            /*font-style: italic;*/
            font-weight: 400;
            color: #fff;
            background-color: #1e2c76;
            width: max-content;
            padding: 5px 10px;
            float: right;
            border-radius: 20px 10px;
            position: relative;
            top: -133px;
            left: 17px;
            margin-bottom:0px 0px 10px 0px;
        }
        .mt-mb{
            margin-bottom: -104px;
        }
        .donutchart {
           width: 200px;
            margin: -25px auto;
            height: 200px;
        }

        .mb-0{
            margin-bottom:0px !important;
        }
        .page-title2 {
            display: block;
            height: auto;
            font-size: 11px;
            margin-top: -25px;
            padding-top: 0px;
            padding-left: 0px;
            margin-bottom: 10px;
            color: #555;
            font-weight: 400;
            line-height: 23px;
            text-align: center;
        }
        #customers{
            font-size:12px;
        }
        #reportdata {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
        }
        #reportdata td {
            padding: 10px 10px;
            background-color: #e8e8e8;
            font-weight: 600;
            white-space: nowrap;
            border: 1px solid #fff;
        }
        #publicdeeds {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: table;
            overflow-y: scroll;
        }
        #publicdeeds2 {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        #publicdeeds2 td{
            border: solid 1px #eee;
        }
        #totalaccount {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        #totalaccount td {
            white-space: nowrap;
        }
        #openacdetails {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
            overflow-x: scroll;
            /*display: inline-block;*/
            overflow-y: scroll;
            padding:2px;
        }
        #openacdetails td {
            padding: 10px 10px;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
            border: solid 1px #eee;
        }
        #paymenthistory {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            table-layout: fixed;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        .ten{
         margin-left: 3px !important;
        }
        .nine{
           margin-right: -54px !important;
        }
        .eight{
          margin-right: -44px !important;
        }
        .seven{
          left: -8px !important;
        }
        .six{
          margin-left: -43px !important;
        }
        .five{
          margin-left: -41px !important;
        }
        .four{
           left: -7px !important;
        }
        .three{
           left: -7px !important;
        }
        .two{
            left: -7px !important;
        }
        .one{
          left: -5px !important;
        }
        .order-number{
             margin-right: -140px !important;
                 top: -121px !important;
        }
        .media-break{
            display: block;
        }

        /*.dpd-text span {
          display: none;
        }

        .dpd-text:after {
            content: "DPD";
        }*/
        .over-due-text span {
          display: none;
        }
         .over-due-text:after {
            content: "Due";
        }
        .payment-history{
            font-size: 10.9px;
            padding-left: 0px;
        }
        .payment-time{
            padding-left: 8px;
           font-size: 9px;
        }
        #paymenthistory td{
            padding: 12px !important;

        }
        .customers-data{
            padding-right: 0px !important;
        }
        .customers-id{
            padding-left: 0px !important;
            padding-top: 0px !important;
        }

        }
        .modal-lg {
            width: 1049px;
        }
        .close {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
        }
        .modal-header .close {
            padding: 10px;
            position: absolute;
            z-index: 999;
            width: 40px;
            height: 40px;
            background-color: #eaeaea;
            opacity: 1;
            border-radius: 30px;
            float: right;
            right: 0px;
            margin-top: -20px;
            margin-right: -20px;
        }
        .modal-header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 0rem;
        }
        .modal-title{
            color: #555;
            position: relative;
            width: 100%;
            font-weight: 700;
            text-align:center;
            font-size:18px;
            line-height: 40px;
            margin: 15px auto;
        }
        .bg-ash{
            background-color:#f9fafb;
            border: 1px solid #ebebeb;
            background-image: url("{{asset('front_new/images/team/report-dec.png')}}");
            display: inline-block;
            background-position: right;
            background-repeat: no-repeat;
            background-size: contain;
            padding:20px 15px;
            border-radius:10px;
        }
        .bg-ash h3{
            font-size:15px;
            font-weight:400;
            color:#555;
        }
        .bigtext{
            text-align: center;
        }
        .bigtext h1{
            font-weight: 700 !important;
            text-align: center;
            font-size: 90px;
            color: #0d1332;
            margin: 0px;
            padding: 5px 0px;
            line-height:80px;
        }
        .bigtext h1 span{
            font-size: 60px;
            position: relative;
            top: -19px;
        }
        .bigtext h2{
            text-align: center;
            font-size: 35px;
            color: #ffba00;
            font-weight: 700;
            margin: 0px 0px 50px 0px;
        }
        .bigtext h2 span{
            font-size: 20px;
            position: relative;
            top: -14px;
        }
        .rc_progress {
            margin: 0px auto 50px auto !important;
        }
        .rc_progress .progress-bar-red{
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            background: #EF4C47 !important;
            width:650px;
        }
        .rc_progress .progress-bar-or{
            background: #FF741D !important;
            width:50px;
        }
        .rc_progress .progress-bar-ye{
            background: #FFBA00 !important;
            width:50px;
        }
        .rc_progress .progress-bar-ye1{
            background: #FFBA00 !important;
            width:150px;
        }
        .rc_progress .progress-bar-red::before, .rc_progress .progress-bar-or::before, .rc_progress .progress-bar-ye::before{
            content: "";
            width: 5px;
            height: 100%;
            position: absolute;
            right: 0;
            top: 0;
            background: #fff;
        }
        .rc_progress .progress-bar-gr{
            background: #89C65A !important;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            width: 150px;
            position: relative;
        }
        .m-lr-0{
            margin: 0px !important;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(36, 32, 32, 0.2);
        }
        .report-blocks{
            background-color: #EFEFEF;
            border: 1px solid #CCCCCC;
            border-radius: 10px;
        }
        .report-blocks h2{
            color:#000;
            font-size:18px;
            margin-left:20px;
            font-weight:700;
        }
        .report-blocks p{
            color:#666666;
            font-size:14px;
            margin-left:20px;
            font-weight:400;
        }
        .in-blocks{
            background-color: #F9FAFB;
            border-radius: 10px;
            padding: 5px 20px !important;
            height: 114px;
            overflow: hidden;
        }
        .in-blocks h3{
            color:#666666;
            font-size:16px;
            font-weight:400;
            margin: 20px 0px;
        }
        .in-blocks h3 span{
            color:#363D72;
            font-size:18px;
            font-weight:600;
            left: 100px;
            position: absolute;
        }
        .in-blocks h4{
            color:#363D72;
            font-size:18px;
            font-weight:600;
        }
        .navbar1{
            box-shadow: 0 2px 4px rgba(36, 32, 32, 0.2);
            background-color: #fff;
            padding: 10px 20px;
            min-height:60px;
            z-index:999;
        }
        .navbar-brand1{
            padding:0px;
        }
        .modal-body{
            padding: 20px;
            background-color: #F6F5FC;
        }
        .reportSum1 span {
            border: solid 1px #363d72;
            padding: 15px 20px;
            background-color: #363d72;
            border-radius: 10px;
            display: block;
            color:#fff;
            text-align:center;
            font-size: 14px;
            height: 50px;
            font-weight: 400;
            cursor:default;
        }
        .download_btn1 {
            text-align: right;
            position: relative;
            margin: 10px 0 10px 0;
        }
        .download_btn1::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #202f7d;
            z-index: 1;
        }
        .togle_buttons1{
            position: absolute;
            border-radius: 30px;
            z-index: 2;
            font-size: 14px;
            line-height: 18px;
            color: #0d1332;
            font-weight: 400;
            border: solid 1px #363d72;
            background: #f9f9f9;
            top: 0;
            overflow: hidden;
            height:50px;
        }
        .togle_buttons1 a.active {
            background: #363d72;
            color: #fff;
        }
        .togle_buttons1 a:last-child {
            margin-left: -15px;
        }
        .togle_buttons1 a {
            padding: 15px 15px;
            display: inline-block;
            color: #000;
            cursor:default;
        }
        .btn_d{
            background: #363d72;
            width: 50px;
            height: 50px;
            position: relative;
            display: inline-block;
            z-index: 1;
            border-radius: 5px;
            cursor:default;
        }
        .btn_d img{
            color: #fff;
            padding: 10px;
            width: 100%;
            height: 96%;
        }
        .nav .open > a, .nav .open > a:focus, .nav .open > a:hover {
            border-color: transparent !important;
        }
        .nav .open > a{
            border-color: transparent !important;
        }
        .navbar-right1{
            position: relative;
            z-index: 9999;
            float: right !important;
            margin-right: -15px;
        }
        li.user_membership{
            line-height:50px !important;
        }
        .rc_progress .progress-bar.active {
            position: absolute;
            background: #82e360;
            width: 12px;
            height: 50px;
            left: 0;
            top: -18px;
            border-radius: 10px;
            border: solid 1px #fff;
            z-index: 99;
            left: 77.99%;
        }
        .mb-0{
            margin-bottom:0px !important;
        }
        .nav > li > a:focus, .nav > li > a:hover {
            background-color: transparent !important;
        }
        .navbar-nav > li > a {
            cursor: default !important;
        }
        @media only screen and (max-width: 600px) {
            #usbtn{
            width: 265px !important;
        }
        }


      </style>
    <body class="voyager users">
      <!-- Plz Copy below code only  -->
        <div class="col-md-12">
            <div class="container-fluid xs-pad">
              <div class="row">
                    <div class="col-md-12 col-sm-12 pull-right">
                        <h2 class="page-title2 float-right"><img src="{{asset('front_new/images/team/equifaxlogo.svg')}}" border="0" height="50px"/></h2>
                    </div>
              </div>
            </div>
        </div>

        <div class="col-md-12 xs-p0">
            <div class="container-fluid">
                <ul class="nav1 nav-tabs1" role="tablist">

                    <li class="tabActive  active " id="business_tab">
                        <a data-toggle="tab"  href="#individualcreditreport"><img src="{{asset('front_new/images/team/individualreportflagicon.svg')}}" border="0" />
                                                                             <br/>Individual Credit Report<span class="tab-alert-txt"> </span>
                                                                             </a></li>
                    <span class="border-tab hidden-xs hidden-sm"> </span>
                    <!-- <li class="disabled disabledTab"> -->

                      <li class="businessactive business_tab_2">
                        <a data-toggle="tab" href="#businesscreditreport"><img src="{{asset('front_new/images/team/BusinessReportflagicon.svg')}}" border="0" /><br/>Business Credit Report</a></li>

                    <span class="border-tab hidden-xs hidden-sm"> </span>
                    <li class="tabActive " id="usbusiness_tab">
                        <a data-toggle="tab"  href="#usbusinesscreditreport"><img src="{{asset('front_new/images/team/USbusinessreportflagicon.svg')}}" border="0" /><br/>US Business Credit Report</a></li>
                </ul>
                <!-- <a href="us-creditreport"> -->
                <!-- Tab panes -->
                <div class="tab-content" style="border-color: #ddd !important;">
                    <!-- individualcreditreport -->
                    <div id="individualcreditreport" class="tab-pane fade in active">
					<div class="alert alert-success hide" role="alert"></div>
					<div class="alert alert-danger hide" role="alert"></div>
                   <!--    <div class="row">
                          <div class="col-md-12">
                            <button type="button" class="viewsamplebtn float-right">View Sample Report</button>
                           </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2"></div>
                          <div class="col-md-8 formtext">
                            <h3>Select a type of Individual Report</h3>
                            <div class="recordent_report">
                                <div class="form-check form-check-inline">
                                    <div class="inline">
                                    <input class="form-check-input" type="radio" name="report" id="recordentReport" value="Recordent Report">
                                    <label class="form-check-label" for="mobe">Recordent Report <span class="tool-tip tool-tip-adv"  data-toggle="tooltip" data-placement="top" title="Recordent report comprises details of any dues reported for a customer by a Recordent member. These dues will provide history of non-banking credit payment behavior for the customer"><i class="fa fa-info-circle"></i></span></label>
                                    </div>
                                    <div class="inline">
                                    <input class="form-check-input" type="radio" name="report" id="recordentComprehensive" value="Recordent Comprehensive Report">
                                    <label class="form-check-label" for="emaile">Recordent Comprehensive Report <span class="tool-tip tool-tip-adv"  data-toggle="tooltip" data-placement="top" title="Recordent comprehensive report not only provides payment behavior for a customer for non-banking credit but also any banking credit reported to the bureau partner. The comprehensive report is powered by Equifax"><i class="fa fa-info-circle"></i></span></label>
                                    <p class="comingsoon" style="margin-left: 180px !important;">Data powered by Equifax</p>
                                    </div>
                                </div>
                                <p class="report-error">Please select a product</p>
                            </div>
                          </div>
                          <div class="col-md-2"></div>
                      </div> -->

                      <div class="row">

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12" style="text-align: center;">
                                    <button type="button" class="viewsamplebtn" data-toggle="modal" data-target=".bd-example-modal-lg">View Sample Report</button>
                              </div>
                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <h2  id="title-report" class="page-title3">Complex Credit Information simplified only for you to make better credit decision and reduce risk</h2>
                                </div>
                            </div>
                        </div>
                          <div class="col-md-4 hidden-xs hidden-sm" style="text-align: center; display:none;">
                                <a href="{{route('home')}}" target="_blank"><button type="button" class="viewsamplebtn">View Sample Report</button></a>
                          </div>

                          <div class="col-md-4 hidden-xs hidden-sm" style="text-align: center; display:none;">
                                <a href="{{route('home')}}" target="_blank"><button type="button" class="viewsamplebtn">View Sample Report</button></a>
                          </div>
                      </div>

                        <form action="{{route('all-records')}}" method="get" id="individualConsent">
                        <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="row new_width">
                                    <div class="advanced-search">
                                        <div class="d-flex justify-content-center flex-wrap">
                                            <div class="col-md-6 col-xs-10 business_form">
                                                <label> Full Name:</label>
                                                <input type="text" name="student_first_name" class="form-control" style="border: 1px solid #676767;" placeholder="Enter Customer's Name" aria-controls="dataTable" value="" id="student_first_name" >
                                            </div>
                                            <div class="col-md-6 col-xs-10 business_form">
                                                <label>Mobile Number:</label>
                                                <input type="number" name="contact_phone" class="form-control" style="border: 1px solid #676767;" aria-controls="dataTable" value="" id="contact_phone"  required onblur="trimIt(this);" maxlength="10" onkeypress="return numbersonly(this,event)" placeholder="Enter Customer's Mobile Number">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center flex-wrap ">
                                            <button type="button" class="requestbtn requestConsent business_form" aria-controls="dataTable">Search</button>
                                        </div>
                                        <p class="consent_button_desc business_form">By clicking search, a consent request will be sent to the customers mobile number</p>

                                    </div>

                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                        </div>
                        </form>

                    </div>
                    <!-- individualcreditreport Ends -->

                    <!-- businesscreditreport -->
                    <div id="businesscreditreport" class="tab-pane fade in businessactive"><br>
                    <div class="alert alert-success hide" role="alert"></div>
                    <div class="alert alert-danger hide" role="alert"></div>

                      <div class="row">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12" style="text-align: center;">
                                    <button type="button" class="viewsamplebtn" data-toggle="modal" data-target=".bb-example-modal-lg">View Sample Report</button>
                              </div>
                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <h2  id="title-report" class="page-title3">Complex Credit Information simplified only for you to make better credit decision and reduce risk</h2>
                                </div>
                            </div>

                          <div class="col-md-4 hidden-xs hidden-sm" style="text-align: center; display:none;">
                                <a href="{{route('home')}}" target="_blank"><button type="button" class="viewsamplebtn">View Sample Report</button></a>
                          </div>

                          <div class="col-md-4 hidden-xs hidden-sm" style="text-align: center; display:none;">
                                <a href="{{route('home')}}" target="_blank"><button type="button" class="viewsamplebtn">View Sample Report</button></a>
                          </div>
                      </div>

                        <form action="{{route('business.all-records')}}" method="get" id="businessConsent">
                        <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="row new_width">
                                    <div class="advanced-search">
                                        <div class="d-flex justify-content-center flex-wrap">
                                            <div class="col-md-6 col-xs-10">
                                                <label> {{General::getLabelName('unique_identification_number')}}:</label>
                                                <input type="text" name="unique_identification_number"class="form-control" id="UDISE_NO" style="border: 1px solid #676767;"  aria-controls="dataTable" value="{{!empty(app('request')->input('unique_identification_number')) ? app('request')->input('unique_identification_number') : '' }}">
                                                 <div id=div2 style="color: red;"></div>
                                            </div>
                                            <div class="col-md-6 col-xs-10">
                                                <label>Concerned Person Phone:</label>
                                                <input type="number" name="concerned_person_phone"class="form-control "  style="border: 1px solid #676767;"  id="contact_phone_business" aria-controls="dataTable" value="{{!empty(app('request')->input('concerned_person_phone')) ? app('request')->input('concerned_person_phone') : '' }}" required>
                                            </div>

                                        </div>
                                        <div class="d-flex justify-content-center flex-wrap">
                                                    <button type="button" class="requestbtn requestConsentBusiness"  aria-controls="dataTable"> Search</button>

                                            </div>
                                        <p class="consent_button_desc">By clicking search, a consent request will be sent to the customers mobile number</p>

                                    </div>

                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                        </div>
                        </form>

                    <!-- <h3>Coming Soon</h3>
                    <p>Recordent report also comprises payment history data from one of the leading cedit bureau to provide complete risk analysis.</p> -->
                    </div>
                    <!-- businesscreditreport Ends -->

                    <!-- usbusinesscreditreport -->
                    <div id="usbusinesscreditreport" class="tab-pane fade">
                        <div class="row">
                                <div class="col-md-9">
                                    <h2 id="ustitle-report" class="page-title3">Reduce risk by checking US business's credit report for Rs. {{$total_us_b2b_credit_report_price}} only <small style="color: #5f94c4;">(inclusive of taxes)</small></h2>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12" style="display: none;">
                                    <button type="button" class="viewsamplebtn" data-toggle="modal" data-target=".bd-example-modal-lg">View Sample Report</button>
                                </div>
                            </div>

						<form action="{{route('add-record-storereference')}}" name="add_store_record" id="add_store_record" method="POST" enctype="multipart/form-data">
							@csrf

                            <div class="submitdues-mainbody">
                            <div class="col-md-12">
								<div class="form-group">
									<label for="contact_phone">Business Name*</label>
									<input type="text" class="form-control" name="business_name" value="{{old('person_name')}}" placeholder="Business Name" required onblur="trimIt(this);">
								</div>
                            </div>
							<div class="clearfix"></div>
                            <div class="col-md-12">
                                <div class="form-group">
									<label for="contact_phone">Address*</label>
									<input type="text" class="form-control" name="address" value="{{old('father_name')}}" placeholder="address details" required onblur="trimIt(this);">
								</div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-12">
	                        	<div class="form-group">
	                        		<label for="contact_phone">State*</label>
									<select name="state" id="state"  placeholder="Select State" class="form-control" required>
							            <option value="">Select</option>
							            @if($states->count())
								            @foreach($states as $state)
								            	<option value="{{$state->id}}" {{old('state')==$state->id ? 'selected' : '' }}>{{$state->name}}</option>
								            @endforeach
							            @endif
							        </select>
					        	</div>
					        </div>
                            <div class="col-md-12">
	                        	<div class="form-group">
	                        		<label for="contact_phone">City*</label>
									<select name="city" id="city"  placeholder="Select city" class="form-control" required >
							            <option value="">Select</option>
							        </select>
					        	</div>
					        </div>
                            <div class="clearfix"></div>
							 <div class="col-md-12">
	                            <div class="form-group">
									<label for="contact_phone">Zip Code*</label>
									<input type="tel" class="form-control number" name="zip_code" value="{{old('contact_phone')}}" placeholder="Zip code" required onblur="trimIt(this);" maxlength="12" onkeypress="return numbersonly(this,event)">
								</div>
							</div>

                            <div class="col-md-12">
								<div class="col-md-12">
								<div class="form-action ">
									<label for="contact_phone">By Clicking on 'Continue' I Agree Recordent`s <a target="_blank" href="{{config('app.url')}}terms-and-conditions">Terms & Conditions</a> & <a target="_blank" href="{{config('app.url')}}end-user-license-agreement">End User License Agreement</a></label>
								</div>
								</div>
							</div>
                            <div class="col-md-12">
								<div class="col-md-12">
								<div class="form-action ">
									<!-- <button type="submit" class="requestbtn">Continue</button> -->
									<button type="submit" id="usbtn" class="requestbtn btn btn-info  btn-blue">Continue</button>
								</div>
								</div>
                                </div>
                                <div class="clearfix"></div>
							</div>

						</form>

                    </div>
                                <select id="maincity" style="display: none">
                @if($cities->count())
                    @foreach($cities as $city)
                        <option data-state-id="{{$city->state_id}}" value="{{$city->id}}">{{$city->name}}</option>
                    @endforeach
                @endif
            </select>
                    <!-- usbusinesscreditreport Ends -->
                </div>
            </div>

        </div>

        <!-- Modal Starts Here -->
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <nav class="navbar1" style="width:100%;">

                            <img src="{{asset('front_new/images/team/cVbtEvZRz9zp2WRgMMJA.jpg')}}" style="width:140px">

                        <ul class="nav navbar-nav  navbar-right1">
                            <li class="user_membership">BASIC</li>

                            <li class="notify-count"> </li>

                            <li class="dropdown profile">
                                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button" aria-expanded="false"><img src="{{asset('front_new/images/team/defaultuser.svg')}}" class="profile-img"> <span class="caret"></span></a>
                            </li>
                        </ul>
                    </nav>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <h4 class="modal-title" id="myLargeModalLabel">Individual Credit Report - Preview</h4>
                        </div>
                        <div class="row m-lr-0">
                            <!-- Current Score Starts Here -->
                            <div class="col-md-12 col-sm-12 bg-ash">
                                    <div class="col-md-6 col-xs-6 col-sm-6"><h3>CURRENT SCORE</h3></div>
                                    <div class="col-md-6 col-xs-6 col-sm-6">
                                        <h2 class="page-title2 float-right">Data Powered by &nbsp; <img src="{{asset('front_new/images/equifax_logo.png')}}" height="50px" border="0"></h2>
                                    </div>

                                    <div class="col-md-12 bigtext">
                                        <h1>710<span>*</span></h1>
                                        <h2>Good<span>*</span></h2>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="progress rc_progress">
                                            <div id="progress-bar-active-score" class="progress-bar active progress-bar-ye1" role="progressbar"></div>
                                            <div class="progress-bar progress-bar-red" role="progressbar">
                                                <span class="lp">300</span> <span class="rp">650</span>
                                            </div>
                                            <div class="progress-bar progress-bar-or" role="progressbar">
                                                <span class="rp">700</span>
                                            </div>
                                            <div class="progress-bar progress-bar-ye" role="progressbar">
                                                <span class="rp">750</span>
                                            </div>
                                            <div class="progress-bar progress-bar-gr" role="progressbar">
                                                <span class="rp">900</span>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <!-- Current Score Ends Here -->
                            <!-- 2nd Half Part Starts Here -->
                            <div class="row">
                                <div class="col-md-12 col-sm-12 mb-0">
                                    <div class="download_btn1 active_none">
                                        <div class="togle_buttons1">
                                            <a href="#" class="equifax-active active">Equifax</a>
                                            <a href="#" class="recordent-active">Recordent</a>
                                        </div>
                                            <a class="btn_d" href="#"><img src="{{asset('front_new/images/team/downloadicon.svg')}}" border="0"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 mb-0">
                                    <h4 class="reportSum1 active_none"><span>Report Summary</span></h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="report-blocks">
                                        <h2>Profile</h2>
                                        <p>Personal Details</p>
                                        <div class="in-blocks">
                                            <h3>Phone<span>9********9</span></h3>
                                            <h3>Pan<span>A**********D</span></h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="report-blocks">
                                        <h2>Credit Age</h2>
                                        <p>Age of Credit Accounts</p>
                                        <div class="in-blocks">
                                            <h3>Since 1st account</h3>
                                            <h4>18 years 7 months</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="report-blocks">
                                        <h2>Payment History</h2>
                                        <p>On-time & delayed payments</p>
                                        <div class="in-blocks">
                                            <h3>Payments on time</h3>
                                            <h4>512/558</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="report-blocks">
                                        <h2>Accounts</h2>
                                        <p>Type & Status of Credit Accounts</p>
                                        <div class="in-blocks">
                                            <div class="col-md-4 col-xs-6 col-sm-6">
                                                <h3>Open</h3>
                                                <h4>6</h4>
                                            </div>
                                            <div class="col-md-8 col-xs-6 col-sm-6">
                                                <h3>Close</h3>
                                                <h4>17</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="report-blocks">
                                        <h2>Limits</h2>
                                        <p>Remaining limit of open credit cards</p>
                                        <div class="in-blocks">
                                            <h3>Credit Available</h3>
                                            <h4>87%</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="report-blocks">
                                        <h2>Enquiries</h2>
                                        <p>Loan / Credit Card applications</p>
                                        <div class="in-blocks">
                                            <h3>Last 30 days</h3>
                                            <h4>0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2nd Half Part Ends Here -->

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- India B2B Sample report starts -->
         <div class="modal fade bb-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <nav class="navbar1" style="width:100%;">

                            <img src="{{asset('front_new/images/team/cVbtEvZRz9zp2WRgMMJA.jpg')}}" style="width:140px">

                        <ul class="nav navbar-nav  navbar-right1">
                            <li class="user_membership">BASIC</li>

                            <li class="notify-count"> </li>

                            <li class="dropdown profile">
                                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button" aria-expanded="false"><img src="{{asset('front_new/images/team/defaultuser.svg')}}" class="profile-img"> <span class="caret"></span></a>
                            </li>
                        </ul>
                    </nav>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <h4 class="modal-title" id="myLargeModalLabel">Business Credit Report - Preview</h4>
                        </div>

                        <div class="row m-lr-0">
                            <!-- Current Score Starts Here -->
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-3 ">
                                        <div class="pdf-logo"><img src="https://www.stage.recordent.com/main_logo.jpg" alt="Logo" data-default="placeholder" data-max-width="300" data-max-height="100"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pdf-downloadbtn">
                                        <?php
                                          echo "Business Name";
                                        ?>

                                    <span style="color: #1e2c76; text-align:center !important;font-weight: 400; font-size: 18px;line-height: 28px;margin: 0px; padding-top:50px !important;"></span>

                                        <span style="color: #000000; text-align:center !important;font-weight: 400; font-size: 18px;line-height: 28px;margin: 0px; padding-top:50px !important;">
                                            <?php

                                            ?>
                                        </span>
                                        </div>
                                    </div>


                                    <div class="col-md-3">

                                        <p class="pdf-date">
                                        <?php
                                        echo "Date of Report :24/03/2021";?>
                                     </p>
                                     <br class="media-break">
                                     <p  class="pdf-date order-number">
                                        <?php
                                        echo "Order Number : 123456 &nbsp;&nbsp;&nbsp;&nbsp";?></p>

                                    </div>
                                </div>

                                <div class="row">
                                  <div class="col-md-12 mt-mb">
                                        <div class="donutchart">
                                        <!-- <span style="color: #1e2c76; text-align:center !important;font-weight: 400; font-size: 18px;line-height: 28px;margin: 0px; padding-top:50px !important;">Business Name B2B</span> -->
                                            <canvas id="chDonut" style="display: block; width: 200px; height: 200px;"></canvas>
                                            <div class="pie-value-txt" style="color: #000000;font-size: 20px;line-height: 28px; font-weight: 400 !important;padding-top: 20px;">
                                                <span class="pie-value"><?php echo "4";?></span><br/>
                                                Good
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8 mt-mb">
                                    <div class="rc_mid">
                                          <h5 class="title_imporve" style="color: #000000;font-weight: 400;font-size: 18px;line-height: 28px;">
                                              <?php


                                                    echo '<span style"color: #000000;font-size: 16px; font-weight: 600;"><strong>Score</strong></span>';
                                              ?>
                                          </h5>
                                    </div>

                                    <div class="profress-scroll">
                                        <div class="progress rc_progress">
                                            <?php

                                            ?>
                                                <div id="progress-bar-active-score" class="progress-bar-act" role="progressbar" style="right:40%; background-color:#f5d13d"></div>
                                                <?php
                                            // }
                                            ?>

                                            <div class="progress-bar progress-bar-danger" role="progressbar">
                                                <span class="lp ten" style="left: -5px;">10</span>
                                                <span class="rp nine" style="left: -84px;">9</span><span class="rp eight" style="right: 76px;" >8</span>
                                            </div>

                                            <div class="progress-bar progress-bar-warning" role="progressbar">
                                                <span class="lp seven" style="left: -4px;">7</span><span class="lp six" style="left: 64px;">6</span><span class="rp five" style="left: 64px;">5</span></div>

                                            <div class="progress-bar progress-bar-info" role="progressbar">
                                                <span class="lp four" style="left: -10px;">4</span> <span class="rp three" style="left: -8px;" >3</span></div>
                                            <div class="progress-bar progress-bar-success" role="progressbar">
                                                <span class="lp two" style="left: -4px;">2</span> <span class="rp one" style="left: -10px;" >1</span></div>
                                        </div>
                                        <p style="text-align:right;">
                                            <h2 class="page-title2"><img src="https://www.test.recordent.com/front_new/images/team/equifaxlogo.svg" border="0" height="50px" width="250px"></h2>
                                        </p>
                                    </div>

                                    </div>
                                </div>

                                <div class="row">
                                   <div class="col-md-12 publicdeeds">
                                      <table id="publicdeeds">
                                          <tr>
                                              <th>Business Details</th>
                                          </tr>
                                      </table>
                                      <!-- </div> -->
                                      <div class="col-md-6 customers customers-data" style="padding-left: 0px !important;">
                                         <table id="publicdeeds">
                                           <!--  <tr>
                                            <th colspan="2">Enquiry Match
                                            </th>
                                            </tr> -->
                                            <tr>
                                                <td style="width: 55%;">Business Name</td>
                                                <td style="width: 45%;">
                                                  <?php
                                                   echo ' - ';
                                                  ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Business Short Name</td>
                                                <td>
                                                <?php  echo ' - ';?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Business Category</td>
                                                <td>
                                                <?php
                                                echo ' - ';
                                                ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Business Industry Type</td>
                                                <td>
                                                    <?php
                                                    echo ' - ';
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Date of Incorporation</td>
                                                <td>
                                                    <?php
                                                    echo ' - ';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                              <td>Legal Constitution:</td>
                                              <td>
                                               <?php
                                               echo ' - ';
                                               ?>
                                              </td>
                                            </tr>

                                            <tr>
                                              <td>Sales Figure:</td>
                                              <td><?php
                                                echo ' - ';
                                                ?>
                                              </td>
                                            </tr>

                                            <tr>
                                                <td>Class of Activity:</td>
                                                <td><?php
                                                echo  ' - ';?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Employee count:</td>
                                                <td><?php
                                                echo  ' - ';?>
                                                </td>
                                            </tr>
                                         </table>
                                      </div>

                                       <div class="col-md-6 customers customers-id" style="padding-right: 0px !important;">
                                            <table id="publicdeeds">
                                              <!-- <tr>
                                                <th colspan="2" >Headquarters Site</th>
                                              </tr> -->
                                                  <td style="width: 55%;border-top:none;">CIN:</td>
                                                  <td style="width: 45%;border-top: none;"><?php echo  ' - '; ?></td>

                                                  <tr>
                                                    <td>TIN: </td>
                                                    <td><?php echo  ' - '; ?></td>
                                                  </tr>
                                                  <tr>
                                                    <td>PAN:</td>
                                                    <td> <?php echo  ' - '; ?>
                                                     </td>
                                                  </tr>
                                                  <tr>
                                                    <td>Service Tax Number:</td>
                                                    <td> <?php echo  ' - '; ?> </td>
                                                  </tr>
                                                  <tr>
                                                    <td>Business Registration Date:</td>
                                                    <td><?php echo  ' - '; ?>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td>Company Registration Number:</td>
                                                    <td><?php echo  ' - '; ?>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                   <td>Phone :</td>
                                                   <td><?php echo  ' - '; ?></td>
                                                  </tr>
                                                  <tr>
                                                    <td>Mobile :</td>
                                                    <td><?php echo  ' - '; ?></td>
                                                  </tr>
                                                  <tr>
                                                    <td>Fax :</td>
                                                    <td><?php echo  ' - '; ?> </td>
                                                  </tr>
                                            </table>
                                       </div>
                                   </div>
                                </div>

                                 <div class="row">
                                <div class="col-md-12 publicdeeds2">
                                    <table id="publicdeeds2">
                                    <tr>
                                        <th colspan="7">Related Entities</th>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Name</td>

                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Address</td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Incorporation Date</td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">CIN </td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">TIN </td>
                                        <td style="background-color:#f2c50c; text-align: left; font-weight: 600;">PAN</td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Relationship</td>
                                    </tr>

                                     <tr>
                                        <td colspan="7" style="text-align: center;color: red;font-size: 17px;">
                                            <?php echo "No Related Entities Reported to Equifax"?>
                                        </td>

                                    </tr>

                                </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 publicdeeds2">
                                    <table id="publicdeeds2">
                                    <tr>
                                        <th colspan="5">Related Individuals</th>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Name </td>

                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Address</td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">ID </td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Phone</td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Relationship </td>
                                    </tr>

                                     <tr>
                                        <td colspan="5" style="text-align: center;color: red;font-size: 17px;">
                                           <?php echo "No Related Individuals Reported to Equifax"?>
                                        </td>
                                    </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 reportdata">
                                    <table id="reportdata">
                                        <tr>
                                           <th colspan="4">Report Highlights (Last 3 years)</th>
                                        </tr>
                                        <tr>

                                            <th colspan="4" style="width: 15%; background-color:#f2c50c; text-align: center; font-weight: 600;color: #222222">Availed by your company</th>
                                        </tr>
                                         <!-- </table> -->
                                            <!-- <div class="col-md-12 reportdata"> -->
                                            <!-- <table id="reportdata"> -->

                                        <tr style=" border-bottom: 1px solid #000;">
                                            <td style="font-size: 17px !important;font-weight: 700;">Details</td>

                                            <td style="font-size: 17px !important;font-weight: 700;">
                                               <?php echo "FY 2020-2021";?>
                                            </td>
                                           <td style="font-size: 17px !important;font-weight: 700;">
                                               <?php echo "FY 2019-2020";?>
                                            </td>
                                            <td style="font-size: 17px !important;font-weight: 700;">
                                               <?php echo "FY 2018-2019";?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Accounts :</td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                        </tr>
                                        <tr>
                                            <td>New Accounts Opened : </td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                        </tr>
                                        <tr>
                                            <td>Term Loans Closed :</td>
                                            <td><?php echo '-';?></td>
                                            <td><?php echo '-';?></td>
                                            <td><?php echo '-';?></td>
                                        </tr>
                                        <tr>
                                            <td>Credit Utilization (Open Accounts)</td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                        </tr>
                                        <tr>
                                            <td>Accounts Overdue</td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                        </tr>
                                        <tr>
                                            <td>Most Severe Status</td>
                                            <td><?php echo '-';?></td>
                                            <td><?php echo '-';?></td>
                                            <td><?php echo '-';?></td>
                                        </tr>
                                        <tr>
                                            <td>Highest Overdue Amount</td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                            <td><?php echo ' - ';?></td>
                                        </tr>
                                    </table>
                                    <br>


                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 publicdeeds2">
                                   <table id="publicdeeds2">
                                    <tr>
                                        <th colspan="8">Overall Report Summary</th>
                                    </tr>
                                    <tr>
                                        <td style="width: 16%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Credit Facilities availed by your company</td>
                                      </tr>
                                    </table>
                                  </div>
                            </div>

                            <div class="row justify-content-lg-start justify-content-center">

                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter">
                                    <?php echo "-";?></h3>
                                    <p>Credit Age</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter">
                                        <?php echo "-"; ?>
                                    </h3>
                                    <p>Credit Usage</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter">
                                     <?php echo "-"; ?>
                                    </h3>
                                    <p>Enquires</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-lg-start justify-content-center">
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter">
                                        <?php echo '-';?>
                                    </h3>
                                    <p>Payment Score</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter"> <?php echo '-';?></h3>
                                    <p>Total Accounts</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                               <div class="col-md-12 reportdata">
                                    <table id="reportdata">
                                        <tr>
                                            <th colspan="4">Details of Credit Facilities</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" style="width: 15%; background-color:#f2c50c; text-align: center; font-weight: 600;color: #222222">Availed by your company</th>
                                        </tr>
                                         <!-- </table> -->
                                            <!-- <div class="col-md-12 reportdata"> -->
                                            <!-- <table id="reportdata"> -->
                                                <div class="col-md-4">
                                                      <tr>
                                                         <td>Lender Name: <?php echo '-';?></td>
                                                         <td>Account Number: <?php echo '-';?>
                                                         </td>
                                                         <td>Account Type:<?php echo '-';?></td>
                                                     </tr>
                                                </div>
                                                <div class="col-md-4">
                                                      <tr>
                                                        <td>Sanctioned Amount :  <?php echo '-';?></td>
                                                        <td>Drawing Power : <?php echo '-';?></td>
                                                        <td>Current Balance : <?php echo '-';?></td>
                                                    </tr>
                                                </div>
                                                <div class="col-md-4">
                                                     <tr>
                                                       <td>High Credit :<?php echo '-';?></td>
                                                       <td>Gurantee Coverage :<?php echo '-';?></td>
                                                       <td>Tenure : <?php echo '-';?></td>
                                                     </tr>
                                                </div>
                                                <div class="col-md-4">
                                                      <tr>
                                                        <td>Date Opened : <?php echo '-';?></td>
                                                        <td>Loan Renewal Date : <?php echo '-';?></td>
                                                        <td>Loan End Date : <?php echo '-';?></td>
                                                      </tr>
                                                </div>
                                                <div class="col-md-4">
                                                      <tr>
                                                        <td>Last Payment Date : <?php echo '-';?></td>
                                                        <td>Date Reported: <?php echo '-';?></td>
                                                        <td>Dispute Code : <?php echo '-';?></td>
                                                     </tr>
                                                </div>
                                                <div class="col-md-4">
                                                      <tr>
                                                        <td>Account Status : <?php echo '-';?></td>
                                                        <td>Suit Filed Status :
                                                        <?php echo '-';?></td>
                                                        <td>Wilful Default Status : <?php echo '-';?></td>
                                                      </tr>
                                                </div>
                                                <div class="col-md-4">
                                                      <tr>
                                                        <td>Status Date :<?php echo '-';?></td>
                                                        <td>Suit Filed Date:
                                                         <?php echo '-';?></td>
                                                        <td>Wilful Default Date : <?php echo '-';?></td>
                                                     </tr>
                                                </div>
                                                <div class="col-md-4">
                                                      <tr>
                                                        <td>Past Due Amount: <?php echo '-';?></td>
                                                        <td>Settlement Amount:<?php echo '-';?></td>
                                                        <td>Written Off Amount: <?php echo '-';?></td>
                                                     </tr>
                                                </div>
                                                <div class="col-md-4">
                                                      <tr>
                                                        <td>Monthly Payment Amount :
                                                         <?php echo '-';?>
                                                        </td>
                                                        <td>Repayment Frequency :
                                                            <?php echo '-';?></td>
                                                        <td>Restructuring Reason :
                                                       <?php echo '-';?></td>
                                                     </tr>
                                                </div>
                                                <div class="col-md-4">
                                                      <tr>
                                                        <td>Amount of NPA Contracts: <?php echo '-';?></td>
                                                        <td>NOARC: <?php echo '-';?></td>
                                                        <td>Asset Based Security Coverage:<?php echo '-';?></td>
                                                     </tr>
                                                </div>

                                            </table>
                                    <!-- </div>
                                    </div> -->





                                   <!-- <div class="row"> -->


                                    <!-- <div class="col-md-12 openacdetails"> -->
                                        <?php


                                                $str = '<a class="anc_active oneitme" href="javascript:void(0)"></a>';

                                     ?>


                                        <table id="openacdetails">
                                            <tr>
                                                <th colspan="7"><h class="payment-history"> Payment History </h><br class="media-break"><h class="payment-time"> <div class="anc_active oneitme" style="width: 12px;height: 12px;margin-bottom: 2px;"></div>&nbsp;&nbsp;On-time&nbsp;&nbsp;<div class="anc_active miditme" style="width: 12px; height: 12px;margin-bottom: 2px;"></div>&nbsp;&nbsp;1-89 days late&nbsp;&nbsp;<div class="anc_active latetime" style="height: 12px;width: 12px;margin-bottom: 2px;"></div>&nbsp;&nbsp;90+ days late</h>
                                                  </th>
                                            </tr>
                                            <div style="margin-top: 10px">

                                                            <table id="paymenthistory">

                                                                <tr>
                                                                    <td></td>
                                                                    <td>Year</td>
                                                                    <td> Dec </td>
                                                                    <td> Nov </td>
                                                                    <td> Oct </td>
                                                                    <td> Sep </td>
                                                                    <td> Aug </td>
                                                                    <td> Jul </td>
                                                                    <td> Jun </td>
                                                                    <td> May </td>
                                                                    <td> Apr </td>
                                                                    <td> Mar </td>
                                                                    <td> Feb </td>
                                                                    <td> Jan </td>
                                                                </tr>

                                    <tr>
                                        <td style="font-size: 12px;"><div class="dpd-text"><span>Status</span></div>
                                            <hr style="margin-bottom: 0px;margin-top: 0px;border-bottom: 1px solid #bbbbbb">

                                           <div class="over-due-text"><span>Overdue Amount</span></div>
                                        </td>



                                        <td>2020</td>

                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                        <td><?php echo $str;?><br>
                                        <?php
                                          echo '₹0';
                                        ?></td>
                                    </tr>
                                   </table>
                                 </div>
                               </table>
                            </div>


                                <div class="col-md-12 publicdeeds2">
                                    <table id="publicdeeds2">
                                    <tr>
                                        <th colspan="4">Details of Enquiries</th>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Lender</td>

                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Date</td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Purpose</td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Amount</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;"><?php echo  ' - ';?></td>
                                        <td style="text-align: center;"><?php echo  ' - ';?></td>
                                        <td style="text-align: center;"><?php echo '-';?></td>
                                        <td style="text-align: center;"><?php echo  ' - ';?></td>
                                    </tr>

                                    </table>
                                </div>


                            <!-- 2nd Half Part Ends Here -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- India B2B Sample reports ends -->

        <!-- Modal END -->
      <div class="modal fade commap-team-popup" id="comprehensive_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <p style="text-align: center;font-size: 20px;">Business Credit Report Request</p>
          </div>
          <div class="modal-body">
            <p>Please confirm the below details to continue</p>
            <form action="" name="send_comprehensive_request" id="send_comprehensive_request" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="contact_phone">Business Name</label>
                <input type="text" class="form-control" name="business_name" id="business_name" maxlength="{{General::maxlength('name')}}" required onblur="trimIt(this);" readonly>
              </div>
              <div class="form-group">
                <label for="contact_phone">Address</label>
                  <textarea class="form-control" id="address" name="address" rows="4" cols="50" onkeypress="return blockSpecialChar(this,event)" onblur="trimIt(this);" readonly>
                  </textarea>
              </div>
              <div class="form-group">
                <label for="contact_phone">State</label>
                  <input type="text" class="form-control" name="state_b2b" id="state_b2b" readonly>
              </div>
              <div class="form-group">
                <label for="contact_phone">City</label>
                <input type="text" class="form-control" name="city_b2b" id="city_b2b">
              </div>
              <div class="form-group">
                <label for="contact_phone">Pincode</label>
                  <input type="text" class="form-control" name="pincode" id="pincode" maxlength="6" onblur="trimIt(this);" onkeypress="return numbersonly(this,event)" readonly>
              </div>
              <input type="hidden" class="form-control" name="contact_phone_comprehensive" id="contact_phone_comprehensive" readonly>
              <input type="hidden" class="form-control" name="unique_identification_number" id="unique_identification_number" readonly>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary BusinessComprehensiveReport"  aria-controls="dataTable">Continue</button>
             </div>
           </form>
          </div>
        </div>
      </div>
    </div>

      <!--  End -->
    </body>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script language="javascript" type="text/javascript">
 function trimIt(currentElement){
      $(currentElement).val(currentElement.value.trim());
  }
  function numbersonly(myfield, e,maxlength=null)
    {
        var key;
        var keychar;
        if (window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

//        alert(1);
        keychar = String.fromCharCode(key);
        // control keys
        if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
            return true;
        // numbers
        else if ((("0123456789").indexOf(keychar) > -1)){
          return true;
        }
        else{
          return false;
        }
    }

    $.validator.addMethod("check_gstin", function(value, element) {
      if(value.toString().length == 10) {
        var valueToString = value.toString().toUpperCase();
        // var fourthChar = valueToString.charAt(3);
        // var allowedCharsAtFourthPosition = ["C","H","A","B","G","J","L","F","T"];
        if(valueToString) {
          return this.optional(element) || /^[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}$/i.test(value);
        } else {
          return false;
        }
      } else {
          return this.optional(element) || /^[0-3|9]{1}[0-9]{1}[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(value);
      }
    }, "Please enter a valid GSTIN/Business PAN.");

	$.validator.addMethod("mobile_number_india", function(value, element) {
        return this.optional(element) || /^[6789]\d{9}$/i.test(value);
    }, "Please enter a valid number.");
  var individualConsent = $('#individualConsent');
  individualConsent.validate({
      ignore: '',
      rules: {
          contact_phone:{
              maxlength:10,
              mobile_number_india:true
          },

      }
  });
  var businessConsent = $('#businessConsent');
  businessConsent.validate({
      ignore: '',
      rules: {
          concerned_person_phone:{
            required:true,
              maxlength:10,
              mobile_number_india:true
          },
          unique_identification_number: {
              maxlength: 15,
              minlength:2,
              required:true,
              check_gstin:true
            },

      }
  });
  var sendcomprehensiverequest = $('#send_comprehensive_request');
  sendcomprehensiverequest.validate({
      ignore: '',
      rules: {
          city_b2b:{
            required:true
          },
      }
  });

	 $('.report-error').hide();
        $("input[name='report']").click(function(){
          $('.report-error').hide();
            $(document).find('.alert.alert-danger').addClass('hide');
            $(document).find('.alert.alert-success').addClass('hide');
            $(document).find('label.error').addClass('hide');
        });

	 $(".requestConsent").on('click',function(){
              var thisButton = $(this);
			  var report = 2;
              /*if($("input:radio[name='report']").is(":checked")) {
              if($('input:radio[name=report]:checked').val()=="Recordent Report"){
                var report = 1;
              }else{
                var report = 2;
              }*/
              // var report = $('input:radio[name=report]:checked').val();
              var name=$('#student_first_name').val();
              var contact_phone = $('#contact_phone').val();
              $(document).find('label.error').removeClass('hide');

              if(individualConsent.valid()==true){
                $.ajax({
                 method: 'post',
                 url: "{{route('admin.search_request_consent')}}",
                 data: {
                      name:name,
                      customer_type:'INDIVIDUAL',
                      contact_phone:contact_phone,
                     _token: $('meta[name="csrf-token"]').attr('content')
                 },
                 success:function(data) {
                    setTimeout(function(){
                      document.getElementById("individualConsent").reset();
                    }, 3000);
                    thisButton.attr('disabled','disabled');
                    $(document).find('.alert').addClass('hide');
                    $(document).find('.alert').html('');
                    var dueId = data;
                    $.ajax({
                       method: 'post',
                       url: "{{route('admin.request-consent-store')}}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                            name:name,
                            customer_type:'INDIVIDUAL',
                            contact_phone:contact_phone,
                            report: report,
                            due_id: dueId,
                           _token: $('meta[name="csrf-token"]').attr('content')
                       }
                    }).then(function (response) {
                        $(document).find('.alert.alert-success').html(response.message);
                        $(document).find('.alert.alert-success').removeClass('hide');
                        $(document).find('.alert.alert-danger').addClass('hide');
                        // window.location.reload();
                        thisButton.removeAttr('disabled');
                        // $(".requestConsent").attr('disabled','disabled');
                        // if(typeof response.lastRequestAccepted!='undefined'){
                        //     $(".checkRequestConsentStatus").removeAttr('disabled');
                        //     return false;
                        // }
                        if($("tr.consentRequestStatusTr").length){
                            clearInterval(xx);
                            $("tr.consentRequestStatusTr").removeClass("hide");
                            $("tr.consentRequestStatusTr").find("td").html('');
                            $("tr.consentRequestStatusTr").find("td").html("<a href='' class='btn btn-primary checkRequestConsentStatus'>Check Status</a> <label>Your consent request is pending.</label>");
                            setTimeout(function(){
                                $("tr.consentRequestStatusTr").find("td").find("label").fadeOut(1000,function(){
                                    $(this).remove();
                                });
                            },5000);

                        }
                        if(response.canRequestConsentAgain24Hour){
                            if(response.startCountDownTimer){
                                //start the timer
                                $(".checkRequestConsentStatus").attr('disabled','disabled');
                                var countDownDate = new Date(response.next3MinForCounDown).getTime();
                                var now = new Date(response.currentTimeInMilli).getTime();
                                // Update the count down every 1 second
                                var x = setInterval(function() {
                                      // Get today's date and time

                                      // Find the distance between now and the count down date
                                      var distance = countDownDate - now;
                                      if(distance < 0){
                                        clearInterval(x);
                                      }
                                      now = now + 1000;
                                      // Time calculations for days, hours, minutes and seconds
                                      if(distance>=0){
                                          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                          var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                          // Display the result in the element with id="demo"
                                          if(seconds<10){
                                            seconds = "0" + seconds;
                                          }
                                          document.getElementById("nextConsentRequestCountDown").innerHTML = 'Request in 0'+minutes + ":" + seconds + " Min";
                                        }

                                          // If the count down is finished, write some text
                                          if (distance < 0) {
                                             $(".checkRequestConsentStatus").removeAttr('disabled');
                                            clearInterval(x);
                                            document.getElementById("nextConsentRequestCountDown").innerHTML='';
                                            $(".requestConsent").removeAttr('disabled');
                                          }
                                    }, 1000);
                            }
                        }else{
                            //$("tr.consentRequestStatusTr").addClass("hide");
                            //You have already raised consent for this user. You can raise consent maximum two times in last 24 hours.
                        }
                    }).fail(function (data) {
                        $(document).find('.alert.alert-danger').html(data.responseJSON.message);
                        $(document).find('.alert.alert-danger').removeClass('hide');
                        $(document).find('.alert.alert-success').addClass('hide');
                        thisButton.removeAttr('disabled');

                    });
                 }
              });
              }
            /*}else{
              $('.report-error').show();
            }*/
        });


$(".requestConsentBusiness").on('click',function(){
    if(businessConsent.valid()==true){
          $("#div2").html('').show();
            var thisButton = $(this);


                   var report = 3;
                   var unique_identification_number=$('#UDISE_NO').val();
                   var contactPhone = $('#contact_phone_business').val();
                   if(unique_identification_number.length>=15){
                   $.ajax({
                   method: 'post',
                   url: "{{route('admin.get-gstin-api-data',$report='3')}}",
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   data: {
                        unique_identification_number:unique_identification_number,
                       _token: $('meta[name="csrf-token"]').attr('content')
                   },
                   success:function(data){
                    var response= data.response;
                    var address = data.address;
                    var state = data.state;

                    var business_name = data.business_name;
                    var pincode = data.pincode;
                    if(!(response)){
                    // $("#div2").html('').show();

                    $("#div2").html('Please enter valid data').show();
                   //  setTimeout(function() {
                   // $('#div2').fadeOut('slow');
                   // }, 3000);

                    }
                    else {
                    $("#address").val(address);
                    $("#state_b2b").val(state);
                    $("#business_name").val(business_name);
                    $("#pincode").val(pincode);
                    $("#contact_phone_comprehensive").val($('#contact_phone_business').val());
                    $("#unique_identification_number").val($('#UDISE_NO').val());
                     // console.log(response);
                     $.ajax({
                       method: 'post',
                       url: "{{route('admin.get-city-api-data')}}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                            pincode:data.pincode,
                           _token: $('meta[name="csrf-token"]').attr('content')
                       },
                       success:function(data){
                        var city = data.city;
                         if(typeof city !== 'undefined') {
                          $("#city_b2b").val(city).attr('readonly','true');
                          $("#comprehensive_popup").modal('show');
                         } else {
                          $("#comprehensive_popup").modal('show');
                         }
                       },
                       error:function(error){
                       $("#comprehensive_popup").modal('show');
                       }
                      });
                  }
                }
              });
                 }
              var unique_identification_number=$('#UDISE_NO').val();
                var contactPhone = $('#contact_phone_business').val();
                if(unique_identification_number.length<=10){

                if(businessConsent.valid()==true){
                $.ajax({
                   method: 'post',
                   url: "{{route('admin.search_request_consent_business')}}",
                   data: {
                        unique_identification_number:unique_identification_number,
                        customer_type:'BUSINESS',
                        contact_phone:contactPhone,
                       _token: $('meta[name="csrf-token"]').attr('content')
                   },
                   success:function(data) {
                    setTimeout(function(){
                      document.getElementById("businessConsent").reset();
                    }, 3000);
                $.ajax({
                   method: 'post',
                   url: "{{route('admin.request-consent-store-business')}}",
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   data: {
                        unique_identification_number:unique_identification_number,
                        customer_type:'BUSINESS',
                        contact_phone:contactPhone,
                        report: report,
                        due_id: data,
                       _token: $('meta[name="csrf-token"]').attr('content')
                   }
                }).then(function (response) {
                    $(document).find('.alert.alert-success').html(response.message);
                    $(document).find('.alert.alert-success').removeClass('hide');
                    $(document).find('.alert.alert-danger').addClass('hide');
                    // window.location.reload();
                    thisButton.removeAttr('disabled');
                    // $(".requestConsent").attr('disabled','disabled');
                    // if(typeof response.lastRequestAccepted!='undefined'){
                    //     $(".checkRequestConsentStatus").removeAttr('disabled');
                    //     return false;
                    // }
                    if($("tr.consentRequestStatusTr").length){
                        clearInterval(xx);
                        $("tr.consentRequestStatusTr").removeClass("hide");
                        $("tr.consentRequestStatusTr").find("td").html('');
                        $("tr.consentRequestStatusTr").find("td").html("<a href='' class='btn btn-primary btn-blue checkRequestConsentStatus'>Check Status</a> <label>Your consent request is pending.</label>");
                        setTimeout(function(){
                            $("tr.consentRequestStatusTr").find("td").find("label").fadeOut(1000,function(){
                                $(this).remove();
                            });
                        },5000);

                    }
                    if(response.canRequestConsentAgain24Hour){
                        if(response.startCountDownTimer){
                            $(".checkRequestConsentStatus").attr('disabled','disabled');
                            //start the timer
                            var now = new Date(response.currentTimeInMilli).getTime();
                            var countDownDate = new Date(response.next3MinForCounDown).getTime();
                            // Update the count down every 1 second
                            var x = setInterval(function() {
                                  // Get today's date and time
                                  var now = new Date().getTime();
                                  // Find the distance between now and the count down date
                                  var distance = countDownDate - now;
                                  if(distance < 0){
                                    clearInterval(x);
                                  }
                                  now = now + 1000;
                                  // Time calculations for days, hours, minutes and seconds
                                  if(distance>=0){
                                      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                      // Display the result in the element with id="demo"
                                      if(seconds<10){
                                        seconds = "0" + seconds;
                                      }
                                      document.getElementById("nextConsentRequestCountDown").innerHTML = 'Request in 0'+minutes + ":" + seconds + " Min";
                                    }

                                      // If the count down is finished, write some text
                                      if (distance < 0) {
                                        $(".checkRequestConsentStatus").removeAttr('disabled');
                                        clearInterval(x);
                                        document.getElementById("nextConsentRequestCountDown").innerHTML='';
                                        $(".requestConsent").removeAttr('disabled');
                                      }
                                }, 1000);
                        }
                    }
                }).fail(function (data) {
                    $(document).find('.alert.alert-danger').html(data.responseJSON.message);
                    $(document).find('.alert.alert-danger').removeClass('hide');
                    $(document).find('.alert.alert-success').addClass('hide');
                    thisButton.removeAttr('disabled');

                });
                   }
                });

         }
      } else{
              $('.report-error').show();
            }
            }
        });
	 $(".BusinessComprehensiveReport").on('click',function(){
                var thisButton = $(this);
                var unique_identification_number=$('#UDISE_NO').val();
                var contactPhone = $('#contact_phone_business').val();
                var report = 3;
                var business_name=$('#business_name').val();
                var address=$('#address').val();
                var state=$('#state_b2b').val();
                var city=$('#city_b2b').val();
                var pincode=$('#pincode').val();
                if(sendcomprehensiverequest.valid()==true){

                $.ajax({
                   method: 'post',
                   url: "{{route('admin.search_request_consent_business')}}",
                   data: {
                        unique_identification_number:unique_identification_number,
                        customer_type:'BUSINESS',
                        contact_phone:contactPhone,
                       _token: $('meta[name="csrf-token"]').attr('content')
                   },
                   success:function(data) {
                    $('#comprehensive_popup').modal('hide');
                    $.ajax({
                    method: 'post',
                    url: "{{route('admin.request-consent-store-business')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        unique_identification_number:unique_identification_number,
                        customer_type:'BUSINESS',
                        contact_phone:contactPhone,
                        report: report,
                        due_id: data,
                        business_name: business_name,
                        address: address,
                        state: state,
                        city: city,
                        pincode: pincode,
                       _token: $('meta[name="csrf-token"]').attr('content')
                     }
                    }).then(function (response) {
                    $(document).find('.alert.alert-success').html(response.message);
                    $(document).find('.alert.alert-success').removeClass('hide');
                    $(document).find('.alert.alert-danger').addClass('hide');
                    // window.location.reload();
                    thisButton.removeAttr('disabled');
                    // $(".requestConsent").attr('disabled','disabled');
                    // if(typeof response.lastRequestAccepted!='undefined'){
                    //     $(".checkRequestConsentStatus").removeAttr('disabled');
                    //     return false;
                    // }
                    if($("tr.consentRequestStatusTr").length){
                        clearInterval(xx);
                        $("tr.consentRequestStatusTr").removeClass("hide");
                        $("tr.consentRequestStatusTr").find("td").html('');
                        $("tr.consentRequestStatusTr").find("td").html("<a href='' class='btn btn-primary btn-blue checkRequestConsentStatus'>Check Status</a> <label>Your consent request is pending.</label>");
                        setTimeout(function(){
                            $("tr.consentRequestStatusTr").find("td").find("label").fadeOut(1000,function(){
                                $(this).remove();
                            });
                        },5000);

                    }
                    if(response.canRequestConsentAgain24Hour){
                        if(response.startCountDownTimer){
                            $(".checkRequestConsentStatus").attr('disabled','disabled');
                            //start the timer
                            var now = new Date(response.currentTimeInMilli).getTime();
                            var countDownDate = new Date(response.next3MinForCounDown).getTime();
                            // Update the count down every 1 second
                            var x = setInterval(function() {
                                  // Get today's date and time
                                  var now = new Date().getTime();
                                  // Find the distance between now and the count down date
                                  var distance = countDownDate - now;
                                  if(distance < 0){
                                    clearInterval(x);
                                  }
                                  now = now + 1000;
                                  // Time calculations for days, hours, minutes and seconds
                                  if(distance>=0){
                                      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                      // Display the result in the element with id="demo"
                                      if(seconds<10){
                                        seconds = "0" + seconds;
                                      }
                                      document.getElementById("nextConsentRequestCountDown").innerHTML = 'Request in 0'+minutes + ":" + seconds + " Min";
                                    }

                                      // If the count down is finished, write some text
                                      if (distance < 0) {
                                        $(".checkRequestConsentStatus").removeAttr('disabled');
                                        clearInterval(x);
                                        document.getElementById("nextConsentRequestCountDown").innerHTML='';
                                        $(".requestConsent").removeAttr('disabled');
                                      }
                                }, 1000);
                        }
                    }
                }).fail(function (data) {
                    $(document).find('.alert.alert-danger').html(data.responseJSON.message);
                    $(document).find('.alert.alert-danger').removeClass('hide');
                    $(document).find('.alert.alert-success').addClass('hide');
                    thisButton.removeAttr('disabled');

                });
                setTimeout(function(){
                      document.getElementById("businessConsent").reset();
                    }, 2000);

              }
              });
             }
            });

    $(document).ready(function(){

		$("#student_first_name").attr('placeholder',"Enter Customer's Full Name");
		$("#contact_phone").attr('placeholder',"Enter Customer's Mobile Number");
        $("#UDISE_NO").attr('placeholder',"Enter GSTIN/Business Pan");
        $("#contact_phone_business").attr('placeholder',"Enter Customer's Mobile Number");
       /*  $('.collection_date_info').tooltip('toggle')
        $('.tool-tip').tooltip('toggle');

        $('.collection_date_info').tooltip('hide')
        $('.tool-tip').tooltip('hide');
        $('.collection_date_block').show();
        $("input[name=due_date]").on('dp.change',function(){
             set_collection_date();
        });
        $('.grace_period').on('change',function(){
            set_collection_date();
        });*/

     });

    </script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
    <script type="text/javascript">


        //Anonymous sely-executing function
(function (root, factory) {
  factory(root.jQuery);
}(this, function ($) {

  var CanvasRenderer = function (element, options) {
    var cachedBackground;
    var canvas = document.createElement('canvas');

    element.appendChild(canvas);

    var ctx = canvas.getContext('2d');

    canvas.width = canvas.height = options.size;

    // move 0,0 coordinates to the center
    ctx.translate(options.size / 2, options.size / 2);

    // rotate canvas -90deg
    ctx.rotate((-1 / 2 + options.rotate / 180) * Math.PI);

    var radius = (options.size - options.lineWidth) / 2;

    Date.now = Date.now || function () {

          //convert to milliseconds
          return +(new Date());
        };

    var drawCircle = function (color, lineWidth, percent) {
      percent = Math.min(Math.max(-1, percent || 0), 1);
      var isNegative = percent <= 0 ? true : false;

      ctx.beginPath();
      ctx.arc(0, 0, radius, 0, Math.PI * 2 * percent, isNegative);

      ctx.strokeStyle = color;
      ctx.lineWidth = lineWidth;

      ctx.stroke();
    };

    /**
     * Return function request animation frame method or timeout fallback
     */
    var reqAnimationFrame = (function () {
      return window.requestAnimationFrame ||
          window.webkitRequestAnimationFrame ||
          window.mozRequestAnimationFrame ||
          function (callback) {
            window.setTimeout(callback, 1000 / 60);
          };
    }());

    /**
     * Draw the background of the plugin track
     */
    var drawBackground = function () {
      if (options.trackColor) drawCircle(options.trackColor, options.lineWidth, 1);
    };

    /**
     * Clear the complete canvas
     */
    this.clear = function () {
      ctx.clearRect(options.size / -2, options.size / -2, options.size, options.size);
    };

    /**
     * Draw the complete chart
     * param percent Percent shown by the chart between -100 and 100
     */
    this.draw = function (percent) {
      if (!!options.trackColor) {
        // getImageData and putImageData are supported
        if (ctx.getImageData && ctx.putImageData) {
          if (!cachedBackground) {
            drawBackground();
            cachedBackground = ctx.getImageData(0, 0, options.size, options.size);
          } else {
            ctx.putImageData(cachedBackground, 0, 0);
          }
        } else {
          this.clear();
          drawBackground();
        }
      } else {
        this.clear();
      }

      ctx.lineCap = options.lineCap;

      // draw bar
      drawCircle(options.barColor, options.lineWidth, percent / 687);
    }.bind(this);

    this.animate = function (from, to) {
      var startTime = Date.now();

      var animation = function () {
        var process = Math.min(Date.now() - startTime, options.animate.duration);
        var currentValue = options.easing(this, process, from, to - from, options.animate.duration);
        this.draw(currentValue);

        //Show the number at the center of the circle
        options.onStep(from, to, currentValue);

        reqAnimationFrame(animation);

      }.bind(this);

      reqAnimationFrame(animation);
    }.bind(this);
  };

  var pieChart = function (element, userOptions) {
    var defaultOptions = {
      barColor: '#ef1e25',
      trackColor: '#f9f9f9',
      lineCap: 'round',
      lineWidth: 4,
      size: 180,
      rotate: 0,
      animate: {
        duration: 1000,
        enabled: true
      },
      easing: function (x, t, b, c, d) {//copy from jQuery easing animate
        t = t / (d / 2);
        if (t < 1) {
          return c / 2 * t * t + b;
        }
        return -c / 2 * ((--t) * (t - 2) - 1) + b;
      },
      onStep: function (from, to, currentValue) {
        return;
      },
      renderer: CanvasRenderer//Maybe SVGRenderer more later
    };

    var options = {};
    var currentValue = 0;

    var init = function () {
      this.element = element;
      this.options = options;

      // merge user options into default options
      for (var i in defaultOptions) {
        if (defaultOptions.hasOwnProperty(i)) {
          options[i] = userOptions && typeof(userOptions[i]) !== 'undefined' ? userOptions[i] : defaultOptions[i];
          if (typeof(options[i]) === 'function') {
            options[i] = options[i].bind(this);
          }
        }
      }

      // check for jQuery easing, use jQuery easing first
      if (typeof(options.easing) === 'string' && typeof(jQuery) !== 'undefined' && jQuery.isFunction(jQuery.easing[options.easing])) {
        options.easing = jQuery.easing[options.easing];
      } else {
        options.easing = defaultOptions.easing;
      }

      // create renderer
      this.renderer = new options.renderer(element, options);

      // initial draw
      this.renderer.draw(currentValue);

      if (element.getAttribute && element.getAttribute('data-percent')) {
        var newValue = parseFloat(element.getAttribute('data-percent'));

        if (options.animate.enabled) {
          this.renderer.animate(currentValue, newValue);
        } else {
          this.renderer.draw(newValue);
        }

        currentValue = newValue;
      }
    }.bind(this)();
  };

  $.fn.pieChart = function (options) {

    //Iterate all the dom to draw the pie-charts
    return this.each(function () {
      if (!$.data(this, 'pieChart')) {
        var userOptions = $.extend({}, options, $(this).data());
        $.data(this, 'pieChart', new pieChart(this, userOptions));
      }
    });
  };

}));


    </script>
    <script type="text/javascript">
        var colors = ['#ff6c6c','#ffb36c','#82e360','#1483f2','#f5d13d','#333333'];
          var donutOptions = {
            cutoutPercentage: 85,
            legend: {
              display: false
            }
            };
           var score_value = 4;
                var donutBackground = '#ff6c6c';
                if(score_value==""){
                    var donutBackground = '#147ad6';
                }else{
                    if (score_value > 0 && score_value <=2) {
                        donutBackground = '#82e360';
                    } else if (score_value >= 3 && score_value <= 4) {
                        donutBackground = '#f5d13d';
                    } else if (score_value >= 5 && score_value <= 7) {
                        donutBackground = '#ffb36c';
                    }
                    else  {
                        donutBackground = '#ff6c6c';
                    }
                }
              var chDonutData = {
                    //labels: ['Public Deeds'],
                    datasets: [
                        {
                            //var cur_color = '';
                            backgroundColor: donutBackground,
                            borderWidth: 0,
                            data: [100],
                            opacity:10
                        }
                    ]
                };
            var chDonut = document.getElementById("chDonut");
            if (chDonut) {
            new Chart(chDonut, {
                type: 'pie',
                data: chDonutData,
                options: donutOptions

            });
        }

		if($("#state").val()!=''){
			@if(old('city'))
		    var oldCity = "{{old('city')}}";
			var selected ='';
	    	$("#city").find('option').remove();
	    	//$("#city").append('<option value="">Select</option>');
	     	var stateId =  $("#state").val();
	        $("#maincity option").each(function(){
	        	if($(this).data('state-id')==stateId){
					var cityId = $(this).val();
					if(oldCity==cityId) { selected= 'selected';}else{selected= ''}
	        		$("#city").append('<option value="'+$(this).val()+'" '+selected+'>'+$(this).text()+'</option>');
	        	}
	        });
	        @endif
	      }
	    $("#state").on('change',function(){
	    	$("#city").find('option').remove();
	    	$("#city").append('<option value="">Select</option>');

	     if($("#state").val()!=''){
	     	var stateId =  $("#state").val();
	        $("#maincity option").each(function(){
	        	if($(this).data('state-id')==stateId){
	        		$("#city").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
	        	}
	        });
	      }
	    });

$("#ustitle-report").css("display","none");
$("#business_tab").on("click",function(){

    $("#title-report").css("display","");
    $("#ustitle-report").css("display","none");
});
$("#usbusiness_tab").on("click",function(){

$("#title-report").css("display","none");
$("#ustitle-report").css("display","");
});
$(".business_tab_2").on("click",function(){

$("#title-report").css("display","");
$("#ustitle-report").css("display","none");
});


    </script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{setting('site.google_analytics_tracking_id')}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{setting('site.google_analytics_tracking_id')}}');
</script>


@endsection
