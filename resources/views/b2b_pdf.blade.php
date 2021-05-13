<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;800&display=swap');
</style>
<style type="text/css">
    body, html {
        font-family: 'Open Sans', sans-serif;
        font-size: 15px;
        line-height: 1.57142857;
        color: #76838f;
    }
    table, tr, td{
        font-family: 'Open Sans', sans-serif;
    }
    .customers_equ{color:#424242; font-size:15px; width:100%; border:1px solid #bbb; border-radius:10px 10px 0 0;}
    .customers_equ td{border-bottom:1px solid #bbb;padding:10px; border-right:1px solid #bbb;}
    .customers_equ td:last-child{border-right:none}
    .customers_equ th{background-color:#f3b90f;color:#424242;font-size:16px;font-weight:600;height:45px;text-align:center}
    .row{margin-right:5px;margin-left:5px;}
    .pdf-logo{width:200px;height:60px;align-items:center}
    .pdf-logo img{width:200px}
    .pdf-downloadbtn{color:#202f7d;text-align:center;font-weight:800;font-size:25px;line-height:28px;margin:0}
    .pdf-date{
        text-align:right;
        font-size:15px;
        font-weight:400;
        color:#fff;
        background-color:#1e2c76;
        padding:5px 25px;
        float:right;
        border-radius:15px 0px;
        position:relative;
        width: 220px;
    }
    .pie-title-center{margin:auto;position:relative;text-align:center}
    .pie-title-center p{
        display:block;position:absolute;height:40px;top:63%;left:0;right:0;margin-top:-20px;
        line-height:22px;font-size:18px;color:#333;font-weight:600;
    }
    .pie-value-txt{display:block;position:absolute;font-size:16px;height:40px;top:46px;margin:0 auto;font-weight:600;color:#000;width:200px;text-align:center;padding:10px 0px}
    .rc_progress{overflow:visible;border-radius:10px;margin:0 auto 40px auto;max-width:900px}​​ 
    .pie-value{font-size:40px;font-weight:800}
    .title_imporve{text-align:center;font-size:28px;padding:5px 0;font-weight:500;color:#000}
    .progress{border-radius:5px;overflow:inherit}
    .redpb{background-color:#ff6c6c!important;border-right:solid 2px #fff}
    .orangepb{background-color:#ffb36c!important;border-right:solid 2px #fff}
    .greenpb{background-color:#82e360!important;border-right:solid 2px #fff}
    .bluepb{background-color:#1483f2!important}
    .yellowpb{background-color:#f5d13d!important}
    .donutchart{width:200px;margin:0 auto;height:200px}
    .donutchart h3{font-size:18px;font-weight:600;padding:0 0 20px;color:#000}
    .progress-bar span{color:#262626;top:-25px;position:relative;z-index:4;font-weight:600;font-size:13px}
    .rc_progress .progress-bar-act{
        position:absolute!important;
        width:12px;
        height:50px;
        top:-18px;
        border-radius:6px;
        border:solid 1px #fff;
        z-index:999;
        }​​​​​
    .progress-meter{min-height:5px}
    .progress-meter>.meter{position:relative;float:left;min-height:5px}
    .progress-meter>.meter-left{border-left-width:2px}
    .progress-meter>.meter-right{float:right;border-right-width:2px}
    .progress-meter>.meter-right:last-child{border-left-width:2px}
    .progress-meter>.meter>.meter-text{position:absolute;display:inline-block;bottom:-5px;width:100%;font-weight:700;font-size:.85em;color:#000;text-align:right}
    .progress-meter>.meter.meter-right>.meter-text{text-align:right}
    .mt-mb{margin:25px auto}
    .customerdata{margin:45px auto}
    #customers{
        font-family: 'Open Sans', sans-serif;
        border-collapse:separate;
        color:#424242;font-size:15px;
        width:100%;border:1px solid #bbb;
        border-radius:10px 10px 0 0;
        overflow:hidden;
    }
    #customers td{
        font-family: 'Open Sans', sans-serif; border-bottom:1px solid #bbb;
        padding:12px 10px;color:#000;font-weight:500;border-right:1px solid #bbb;
    }
    #customers td:last-child{border-right:none}
    #customers th{background-color:#f3b90f;color:#424242;font-size:16px;font-weight:600;height:45px;text-align:center}
    .reportdata{
        margin:45px auto;
    }
    #reportdata{
        border-collapse:collapse;
        color:#222;
        font-size:15px;
        width:100%;
        margin: 0px 15px;
    }
    #reportdata td{
        padding:5px 50px;
        background-color:#e8e8e8;
        font-weight:600;
    }
    #reportdata th{
        background-color:#273581;
        color:#fff;
        font-size:16px;
        font-weight:600;
        height:45px;
        text-align:center;
    }
    .non-headtxt{position:relative;margin:25px auto;text-align:center;display:block}
    .non-headtxt h2{
        font-size:18px;font-weight:600;background-color:#f3b90f;width:200px;
        padding:10px 30px;border-radius:20px;color:#000;text-align:center;z-index:99;position:relative;text-align:center;margin:0 auto;
    }
    .non-headtxt span{border-bottom:solid 1px #424242;display:block;top:-19px;position:relative;z-index:1}
    .statistics_item{
        background-color:#273581;
        text-align:center;
        padding:15px;
        border-bottom:solid 4px #0b1130;
        border-radius:20px;
        box-shadow:3px 3px 15px #d3d3d3;
        -moz-box-shadow:3px 3px 15px #d3d3d3;
        -webkit-box-shadow:3px 3px 15px #d3d3d3;
        -o-box-shadow:3px 3px 15px #d3d3d3;width:90%;
        height:130px;
        margin:15px auto;
    }
    .statistics_item .counter{font-size:35px;font-weight:700;margin-top:10px;height:50px;color:#fff}
    .statistics_item p{color:#d1d1d1; padding-top:20px;}
    .statistics_item .counter span{font-size:25px;font-weight:700}
    .pb-hr{border-bottom:solid 1px #424242;display:block;position:relative;margin:25px auto}
    .publicdeeds{margin:45px auto}
    .donutchart1{width:150px;margin:0 auto}
    .donutchart1 h3{font-size:18px;font-weight:600;padding:0 0 20px;color:#000}
    .pie-value-txt1{
        display:block;
        position:absolute;
        height:40px;top:56%;
        left:0;right:0;
        line-height:20px;
        font-weight:600;
        color:#000;
        text-align:center;
        width:150px;margin:0 auto;
    }
    .pie-value1{font-size:18px;font-weight:700}

    .creditage{margin:45px auto}
    #creditage{border-collapse:collapse;color:#222;font-size:15px;width:100%}
    #creditage td{padding:10px 25px;font-weight:600;text-align:center}
    #creditage th{
        background-color:#273581;color:#fff;font-size:16px;
        font-weight:600;height:45px;padding:10px 25px;border-radius:15px 15px 0 0;text-align:center;
    }
    .totalaccount{margin:45px auto}
    #totalaccount{border-collapse:collapse;color:#222;font-size:15px;width:100%}
    #totalaccount td{padding:10px 5px;border:1px solid #bbb;font-weight:600;text-align:center}
    #totalaccount th{
        background-color:#273581;color:#fff;font-size:16px;font-weight:600;
        height:45px;padding:10px 25px;border-radius:15px 15px 0 0;text-align:center;
    }
    .openacdetails{margin:45px auto}
    #openacdetails{
        border-collapse:collapse;
        color:#222;
        font-size:15px;
        width:100%
    }
    #openacdetails td{
        padding:10px 25px;
        font-weight:600;
        text-align:left;
    }
    #openacdetails th{
        background-color:#273581;color:#fff;
        font-size:16px;font-weight:600;
        height:45px;padding:10px 25px;
        border-radius:15px 15px 0 0;
        text-align:center;
    }
    .paymenthistory{margin:45px auto}
    #paymenthistory{
        border-collapse:collapse;
        color:#222;
        font-size:15px;
        width:100%;
        table-layout:fixed;
    }
    #paymenthistory td{
        overflow:hidden; 
        padding:5px 5px;
        border:1px solid #bbb;
        font-weight:600;
        text-align:center;
        width:calc(100%/13);
        height:50px;
    }
    #paymenthistory th{
        background-color:#273581;
        color:#fff;
        font-size:16px;
        font-weight:600;
        height:45px;
        text-align:center;
        border-radius:15px 15px 0 0;
    }
    .red-roundbg{
        width:34px;height:34px;position:relative;background-color:#f22a2a;
        border-radius:17px;line-height:17px;margin:4px auto;color:#fff;font-size:12px;font-weight:600; border: 1px solid #f22a2a;
    }
    .pur-roundbg{width:34px;height:34px;position:relative;
        background-color:#db22cd;border-radius:17px;line-height:17px;margin:4px auto;color:#fff;font-size:12px;font-weight:600;
    }
    .lblue-roundbg{width:34px;height:34px;position:relative;background-color:#79d2de;border-radius:17px;line-height:17px;margin:4px auto;color:#fff;font-size:12px;font-weight:600}
    .green-roundbg{width:34px;height:34px;position:relative;background-color:#1da727;border-radius:17px;line-height:17px;margin:4px auto;color:#fff;font-size:12px;font-weight:600}
    .blue-roundbg{width:34px;height:34px;position:relative;background-color:#147ad6;border-radius:17px;line-height:17px;margin:4px auto;color:#fff;font-size:12px;font-weight:600}
    .bri-roundbg{width:34px;height:34px;position:relative;background-color:#7849c4;border-radius:17px;line-height:17px;margin:4px auto;color:#fff;font-size:12px;font-weight:600}
    .black-roundbg{width:34px;height:34px;position:relative;background-color:#000;border-radius:17px;line-height:17px;margin:4px auto;color:#fff;font-size:12px;font-weight:600}
    .orange-roundbg{width:34px;height:34px;position:relative;background-color:#ff9d00;border-radius:17px;line-height:17px;margin:4px auto;color:#fff;font-size:12px;font-weight:600;}
    .tab-legends{display:flex;position:relative;flex-direction:row;margin:10px auto;flex-wrap:wrap;justify-content:space-between; width:100%;}
    .tab-legends li{list-style:none;margin-right:10px;font-size:13px;color:#000;font-weight:300;line-height:15px}
    .tab-legends li>div{display:inline-flex;margin-right:5px;top:3px;position:relative}
    .tab-blue{width:15px;height:15px;box-sizing:border-box;border-radius:3px;background-color:#147ad6}
    .tab-lblue{width:15px;height:15px;box-sizing:border-box;border-radius:3px;background-color:#79d2de}
    .tab-green{width:15px;height:15px;box-sizing:border-box;border-radius:3px;background-color:#1da727}
    .tab-red{width:15px;height:15px;box-sizing:border-box;border-radius:3px;background-color:#f22a2a}
    .tab-orange{width:15px;height:15px;box-sizing:border-box;border-radius:3px;background-color:#ff9d00}
    .tab-bri{width:15px;height:15px;box-sizing:border-box;border-radius:3px;background-color:#7849c4}
    .tab-pur{width:15px;height:15px;box-sizing:border-box;border-radius:3px;background-color:#db22cd}
    .tab-black{width:15px;height:15px;box-sizing:border-box;border-radius:3px;background-color:#000}
    #averagedaystb{border-collapse:collapse;color:#222;font-size:15px;width:100%;table-layout:fixed;margin:0 -15px}
    #averagedaystb td{padding:10px 5px;border:1px solid #bbb;font-weight:600;text-align:center}
    #averagedaystb th{color:#000;font-size:16px;font-weight:800;height:45px;text-align:center;border:1px solid #bbb}
    .wapper{width: 100%; overflow: scroll; position:absolute; display:inline-block; margin-bottom:20px; top:50px}
    @page {
        margin: 100px 60px;
    }
    
   
    header {
        position: fixed;
        top: -60px;
        left: 0px;
        right: 0px;
        color: #273581;
        text-align: center;
        display:block !important;
        height:50px;

    }

    .title_imporve.progress_bar_green{color:#82e360!important; background: none !important;}
    .title_imporve.progress_bar_yellow{color:#f5d13d!important;  background: none !important;}
    .title_imporve.progress_bar_orange{color:#ffb36c!important;  background: none !important;}
    .title_imporve.progress_bar_red{color:#ff6c6c!important;  background: none !important;}
    .progress_bar_green{background:#82e360!important}
    .progress_bar_yellow{background:#f5d13d!important}
    .progress_bar_orange{background:#ffb36c!important}
    .progress_bar_red{background:#ff6c6c!important}
    .right-block{float:right}
    .pdf_block{
        border:solid 1px #e9e9e9;
        padding:15px 20px;
        background:#fff;
        box-shadow:3px 3px 15px #eee;
        -moz-box-shadow:3px 3px 15px #eee;
        -webkit-box-shadow:3px 3px 15px #eee;
        -o-box-shadow:3px 3px 15px #eee;
        margin-bottom:30px;
    }
    .item{
        border:solid 1px #e9e9e9;
        padding:15px 15px;
        background:#fff;
        box-shadow:3px 3px 15px #eee;
        -moz-box-shadow:3px 3px 15px #eee;
        -webkit-box-shadow:3px 3px 15px #eee;
        -o-box-shadow:3px 3px 15px #eee;
        margin-bottom:30px;
    }
    .item p,.item span{font-size:14px;line-height:22px}
    .pdf_progress{height:350px;text-align:center}
    .left_top{float:left;width:50%;text-align:left}
    .right_top{float:right;text-align:right;width:50%}
    .clear{clear:both;width:100%;height:1px;display:block}h1,h2,h3,h4,h5,h6,li,p{padding:0;margin:0}
    .rc_mid{
        padding:10px 0;
    }
    .rc_mid h2{
        font-weight:700!important;
        text-align:center;
        font-size:20px;
        line-height:20px;
        color:#0d1332;
    }
    .last-update,.rc_age .rc_block p.last-update{font-size:16px;color:#000;font-weight:600;text-align:left}
    .right_top h5{font-size:28px;line-height:30px;font-weight:400!important;color:#202f7d}
    .pdf_progress .right_top{text-align:right}
    .right_top p{color:#000;font-size:16px;line-height:20px}
    .rc_progress{width:900px;position:relative;margin:10px auto 70px auto;height:15px;border-radius:10px;background:#eee}
    .right_top h5{font-size:28px;line-height:30px;font-weight:400!important;color:#202f7d}
    .pdf_progress .right_top{text-align:right}
    .right_top p{color:#000;font-size:16px;line-height:20px}
    .rc_progress{
        width:900px;
        position:relative;
        margin:40px auto 90px auto;
        height:15px;
        background:#eee}
    .rc_progress .progress-bar-danger{
        background:#ff6c6c!important;
        height:15px;
        border-top-left-radius:4px;
        border-bottom-left-radius:4px;
    }

    .rc_progress .progress-bar span.scale-numbers {
        font-size:16px;
        color:#000;
        font-weight:400;
        height:15px;
        width:30px;
        float:left;
    }
    .rc_progress .progress-bar span.lp,.rc_progress .progress-bar span.rp{
        font-size:16px;
        color:#000;
        font-weight:400;
        height:15px;
        width:30px;
        float:left;
        margin-top:45px;
    }
    .rc_progress .progress-bar span.rp{float:right}
    .rc_progress .progress-bar-warning{background:#ffb36c!important}
    .rc_progress .progress-bar{position:relative;height:15px;float:left}
    .rc_progress .progress-bar-info{background:#f5d13d!important}
    .rc_progress .progress-bar-success{
        background:#82e360!important;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .rc_progress .progress-bar-active{
        position:absolute!important;
        background:#82e360;
        width:12px;
        height:50px;
        top:-18px;
        border:solid 1px #fff;
        z-index:99;
    }
    .rc_progress .progress-bar-danger::before, .rc_progress .progress-bar-info::before, .rc_progress .progress-bar-warning::before{
        content:"";
        width:5px;
        height:100%;
        position:absolute;
        right:0;
        top:0;
        background:#fff;
    }
    .rc-pdf-block{width:358px;float:left;height:150px}
    .marginl{margin-left:30px}
    .sub{font-size:20px;line-height:24px;color:#0d1332;font-weight:400;padding:0 0 10px 0;margin:0}
    .rc_bottom .left_bottom p{font-size:18px;line-height:24px;color:#575c71;font-weight:600!important;margin-bottom:0}
    .rc_bottom .left_bottom p{font-size:16px}
    .rc_bottom i{display:inline-block;font-style:normal;width:80px;font-weight:400!important}
    .rc_bottom .left_bottom p span{color:#202f7d}
    .pdf-title{font-size:50px;line-height:56px;padding:0;font-weight:700;margin:0 0 30px 0;color:#202f7d}
    .sub-title{color:#000;font-size:26px;line-height:28px;font-weight:600;padding-bottom:20px;margin:0}

    .left-block{float:left}
    .grid_table{display:block;text-align:center; }
    .payment_history .item h4{float:left;text-align:left;line-height:40px}
    .payment_history .item h4 span{display:block;font-size:20px;font-weight:400;color:#000}
    .payment_history .item p{float:right;font-size:22px;line-height:24px;color:#000;font-weight:600;text-align:right;line-height:40px}
    .payment_history .item p span{color:#4cb826}
    .payment_history .item p em{font-style:normal;display:block;font-weight:400}
    .grid_table .grid-block{margin-top:15px;}
    .red-icon{width:15px;height:15px;background:#2dac00;border-radius:7px}
    .green-icon{width:15px;height:15px;background:#f5821f;border-radius:7px}
    .orange-icon{width:15px;height:15px;background:#ef4023;border-radius:7px}
    .anc_active{width:15px;height:15px;border-radius:7px}
    .oneitme{background:#2dac00; width:15px;height:15px;border-radius:7px}
    .miditme{background:#f5821f; width:15px;height:15px;border-radius:7px}
    .latetime{background:#ef4023; width:15px;height:15px;border-radius:7px}
    .grid_table table td,.grid_table table th{
        text-align:center;padding:1px 3px;
        font-weight:400!important;position:relative;color:#000!important;font-size:15px;
        line-height:18px;background:#fff!important;
    }
    .anc_active{
        display:inline-block;
        position:relative;
        left:25px;
        top:3px;
        line-height:30px; 
        margin: 3px 0 0 35px;
    }
    table{border-collapse:collapse;  }
    .enquires_section{page-break-inside: avoid;}

    .height_block{height:500px; overflow: hidden; }
    .col-md-1 {
        width:8.33333333%
    }
    .col-md-2 {
        width:16.66666667%
    }
    .col-md-3 {
        width:25%
    }
    .col-md-4 {
        width:33.33333333%
    }
    .col-md-5 {
        width:41.66666667%
    }
    .col-md-6 {
        width:50%
    }
    .col-md-7 {
        width:58.33333333%
    }
    .col-md-8 {
        width:66.66666667%
    }
    .col-md-9 {
        width:75%
    }
    .col-md-10 {
        width:83.33333333%
    }
    .col-md-11 {
        width:91.66666667%
    }
    .col-md-12 {
        width:100%
    }
    .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
    .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
        float: left;
    }
    .row > [class*="col-"] {
        margin-bottom: 25px;
    }
    *, ::after, ::before {
        box-sizing: border-box;
    }

    * {
         outline: none;
    }
    .row::after {
          clear: both;
    }

    .row::after, .row::before {
        display: table;
        content: " ";
    }

    .row {
        margin-right: 5px;
        margin-left: 5px;
    }

    .justify-content-center {
         -ms-flex-pack: center !important;
         justify-content: center !important;
    }

    .donutcolor{
        width:200px;
        height:200px;
        border-radius:100px;
        background-color:#147ad6;
        font-size:16px;

    }
    .pie-value-txt{
        width: 170px;
        height: 170px;
        background-color: white;
        border-radius: 85px;
        margin: -30px 15px;
        padding:0px !important;
    }
    .noscore{
        line-height:70px;
    }

    /*report summary*/
    .reportSum {
        text-align: center;
        padding: 0;
        margin: -1px 0 30px 0;
        font-size: 28px;
        line-height: 30px;
        color: #fff;
        font-weight: bold;
    }
    .reportSum span {
        border: solid 1px #ccc;
        padding: 15px 20px;
        background: #202f7d;
        box-shadow: 3px 3px 15px #eee;
        -moz-box-shadow: 3px 3px 15px #eee;
        -webkit-box-shadow: 3px 3px 15px #eee;
        -o-box-shadow: 3px 3px 15px #eee;
        border-radius: 10px;
        display: block;
    }

    .publicdeeds {
        border-collapse: collapse;
        color: #222222;
        font-size: 15px;
        width: 100%;
        margin-left: 15px;
        margin-right: 15px;
        margin-top: -10px;
    }
    .publicdeeds th{
        background-color: #273581;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        height: 45px;
        text-align: center;
        border-radius: 15px 15px 0px 0px;
    }
    .publicdeeds td{
        padding: 10px 5px;
        border: 1px solid #bbbbbb;
        font-weight: 600;
        text-align: center;
        border-radius: 15px 15px 0px 0px;
    }

    #publicdeeds2 {
        border-collapse: collapse;
        color: #222222;
        font-size: 15px;
        width: 100%;
        margin: 0px 15px;
    }
    #publicdeeds2 td{
        padding: 5px 25px;
        font-weight: 600;
    }
    #publicdeeds2 th {
        background-color: #273581;
        color: #fff;
        font-size: 18px;
        font-weight: 600;
        height: 35px;
        padding: 5px 25px;
        border-radius: 15px 15px 0px 0px;
        text-align: center;
    }

    .pt-4 {
        padding-top: 1.5rem!important;
    }

    .mt-4, .my-4 {
        margin-top: 1.5rem!important;
    }

    .payment-history{
        padding-left: 0px;
    }
    .payment-time{
        padding-left: 8px;
    }
    #paymenthistory td{
        padding: 12px !important;
    }

    .media-break{
        display: block;
    }

    .dot {
      height: 12px;
      width: 12px;
      border-radius: 6px;
      display: inline-block;
    }

    /*Recordent Report CSS*/
    .sub_mainBlock.recordent_sub_mainBlock .block ul li{font-size:18px;line-height:26px;}
    .sub_mainBlock h2{color:#000120;font-size:30px;line-height:34px;font-weight:600;padding-bottom:20px;margin:0}
    .pdf_screens{font-family:Arial,Helvetica,sans-serif!important;width:1260px;margin:0 auto 0 auto}
    .eq-title{color:#202f7d; background:linear-gradient(90deg, #fff 18%,#2a3883 100%)}
    .sub_mainBlock .block{width:610px;float:left;border-left:solid 1px #202f7d;padding:0 0 0 30px;margin:0 0 10px 0}
    .sub_mainBlock .block ul{list-style:none;margin:0;padding:0}
    .sub_mainBlock .block ul li{font-size:16px;line-height:22px;padding:0;margin:0 0 3px 0;color:#000;font-weight:400}
    .sub_mainBlock .block ul li span{color:#202f7d;font-weight:700;display:inline-block;vertical-align:top; font-size: 18px; max-width: 50%;}
    .sub_mainBlock.ntb .block ul li span{font-size: 13px;}
    .sub_mainBlock .block ul li span:first-child{width:40%;color:#000;font-weight:400}




    .sub_mainBlock .block01{border-left:solid 1px #202f7d;padding:0 0 0 30px;margin:0 0 10px 0}
    .sub_mainBlock .block01 ul{list-style:none;margin:0;padding:0}
    .sub_mainBlock .block01 ul li{font-size:16px;line-height:22px;padding:0;margin:0 0 3px 0;color:#000;font-weight:400}
    .sub_mainBlock .block01 ul li span{color:#202f7d;font-weight:700;display:inline-block;vertical-align:top; font-size: 18px; max-width: 50%;}
    .sub_mainBlock.ntb .block01 ul li span{font-size: 13px;}
    .sub_mainBlock .block01 ul li span:first-child{width:40%;color:#000;font-weight:400}

</style>

<body>
    <!-- Section 1 - Logo, Business Name, Dates -->
    <header>
        <table width="100%" allign="center" style="margin-top: -15px;">
            <tr>
                <td valign="top" style="width: 20%" class="pdf-logo">
                    <img src="https://www.stage.recordent.com/main_logo.jpg" alt="Logo" data-default="placeholder" data-max-width="300" data-max-height="100">
                </td>
                <td valign="top" style="width: 60%;" class="pdf-downloadbtn">
                    {{!empty($business_details->BusinessName) ? $business_details->BusinessName : ' - '}}<br/>
                </td>
                <td valign="top" style="width: 20%;">
                    <span class="pdf-date">Date of Report : {{date('d/m/Y',strtotime($report_date))}}</span>
                    <br>
                    <br>
                    <span class="pdf-date">Report Number :     {{$report_no}}</span>
                </td>
            </tr>
        </table>
    </header>
    <!-- End of Section 1 - Logo, Business Name, Dates -->
    <div class="wapper" >
        <table width="100%" allign="center">
            <tr>
                <td colspan="3" style="height:50px;"></td>
            </tr>
            <!--Section 2 - donutchart-->
            <tr>
                <td colspan="3" allign="center">
    				<div class="donutchart donutcolor" style="background-color:<?php echo $needle_color;?>;">
                        <div class="pie-value-txt <?php echo empty($score_value) ? ' noscore ' : '';  ?>"  valign="center">
                            <span style="margin-top:30px; font-size:20px; display:block;"><?php echo $score_value;?></span>
                            {{$scoreText}}
                        </div>
                    </div>
                </td>
            </tr>
            <!-- End of Section 2 - donutchart-->
            <!-- progress bar with score indicator -->
            <tr>
                <td colspan="3" allign="center">
                    <div class="inner">
                        <div class="rc_mid">
                            <h2>{{$scoreText}}</h2>
                        </div>
                        <div class="profress-scroll">
    						<div class="progress rc_progress">
                                @if(!empty($score_value))
                                    <div id="progress-bar-active-score" class="progress-bar-act" role="progressbar" style="right:{{$score_percentage}}%; background-color:{{$needle_color}}"></div>
                                @endif
    							<div class="progress-bar progress-bar-danger" role="progressbar" style="width:30%">
    								<span class="lp">10<span class="scale-numbers" style=" display: inline-block; margin-left: 80px;">9</span><span class="scale-numbers" style=" display: inline-block; margin-left: 169px;">8</span></span>
                                    <span class="rp"><span class="scale-numbers" style=" display: inline-block; margin-left: 18px;">7</span></span>
                                </div>
    							<div class="progress-bar progress-bar-warning" role="progressbar" style="width:30%">
                                    <span class="lp" style="margin-left: 80px;">6 <span class="scale-numbers" style=" display: inline-block; margin-left: 88px;">5</span></span>
                                    <span class="rp"><span class="scale-numbers" style=" display: inline-block; margin-left: 20px;">4</span></span>
                                </div>
    							<div class="progress-bar progress-bar-info" role="progressbar" style="width:20%">
                                    <span class="lp" style="margin-left: 80px;">3</span>
                                    <span class="rp"><span class="scale-numbers" style=" display: inline-block; margin-left: 21px;">2</span></span>
                                </div>
    							<div class="progress-bar progress-bar-success" role="progressbar" style="width:20%">
    								<span class="lp" style="margin-left: 80px;">1</span>
                                </div>
    						</div>
                        </div>
                    </div>
                </td>
            </tr>
            <!-- End of progress bar with score indicator -->
            <!-- equifax logo -->
            <tr>
                <td colspan="3">
                    <table width="100%">
                        <tr>
                            <td style="text-align:center; margin:15px auto;">
                                <h2 class="page-title2">
                                    <img src="https://www.recordent.com/front_new/images/team/equifaxlogo.svg"  height="30px" width="220px">
                                </h2>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- End of equifax logo -->
            <tr>
                <td colspan="3" style="height:50px;">&nbsp;</td>
            </tr>
            <!-- Report Summary Section heading -->
            <tr>
                <td colspan="3">
                    <h4 class="reportSum">
                        <span>Report Summary</span>
                    </h4>
                </td>
            </tr>
            <!-- End of Report Summary Section heading -->
            <!-- Enquiry Match & Head Quarter -->
            <tr>
                <td colspan="3">
                    <table class="publicdeeds">
                        <tr>
                            <th colspan="2" allign="center"><span style="font-size: 18px;"><strong>Business Details</strong></span></th>
                        </tr>
                        <td style="border: none; width: 50%;">
                            <table class="publicdeeds" style="padding-left: -2px; margin-left: -2px;">
                                <tr>
                                    <td style="width: 55%; border-top:none;">Business Name</td>
                                    <td style="width: 45%;border-top:none;"> {{!empty($business_details->BusinessName) ? $business_details->BusinessName : ' - '}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Business Short Name</td>
                                    <td>{{!empty($business_details->BusinessShortName) ? $business_details->BusinessShortName : ' - '}}</td>
                                </tr>
                                <tr>
                                    <td>Business Category</td>
                                    <td> {{!empty($business_details->BusinessCategory) ? $business_details->BusinessCategory : ' - '}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Business Industry Type</td>
                                    <td>{{!empty($business_details->BusinessIndustryType) ? $business_details->BusinessIndustryType : ' - '}}
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>Date of Incorporation</td>
                                    <td> {{!empty($business_details->DateIncorporation) ? 
                                        date('d-m-Y',strtotime($business_details->DateIncorporation))  : ' - '}}
                                    </td>
                                </tr>
                                <tr>
                                  <td>Legal Constitution:</td>
                                  <td> {{!empty($business_details->BusinessLegalConstitution) ? $business_details->BusinessLegalConstitution : ' - '}}
                                  </td>
                                </tr>
                                    
                                <tr>                                                
                                  <td>Sales Figure:</td>
                                  <td>{{!empty($business_details->SalesFigure) ? $business_details->SalesFigure : ' - '}}
                                  </td>
                                </tr>
                                
                                <tr>
                                    <td>Class of Activity:</td>
                                    <td>{{!empty($business_details->ClassActivity) ? $business_details->ClassActivity : ' - '}}
                                    </td>
                                </tr>                                           
                                <tr>
                                    <td>Employee count:</td>
                                    <td>{{!empty($business_details->EmployeeCount) ? $business_details->EmployeeCount : ' - '}}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="border: none;">
                            <table class="publicdeeds" style="padding-right: -2px; margin-right: -2px;">
                                <tr>
                                    <td style="width: 55%;border-top:none;">CIN:</td>
                                    <td style="width: 45%;border-top: none;">{{!empty($cin_details->IdNumber) ? $cin_details->IdNumber : ' - '}}</td>
                                </tr>
                            
                                <tr>
                                    <td>TIN: </td>
                                    <td>{{!empty($tin_details->IdNumber) ? $tin_details->IdNumber : ' - '}}</td>
                                </tr>
                                <tr>
                                    <td>PAN:</td>
                                    <td>{{!empty($pan_details->IdNumber) ? $pan_details->IdNumber : ' - '}}</td>
                                </tr>
                                <tr>
                                    <td>Service Tax Number:</td>
                                    <td>{{!empty($service_tax_details->IdNumber) ? $service_tax_details->IdNumber : ' - '}} </td>
                                </tr>
                                <tr>
                                    <td>Business Registration Date:</td>
                                    <td>{{!empty($business_details->DateIncorporation) ? 
                                        date('d-m-Y',strtotime($business_details->DateIncorporation))  : ' - '}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Company Registration Number:</td>
                                    <td>{{!empty($business_registration_no->IdNumber) ? $business_registration_no->IdNumber : ' - '}}
                                    </td>
                                </tr>
                                <tr>
                                   <td>Phone :</td>
                                   <td>
                                        @php
                                            $checkCountContacts = [];
                                        @endphp

                                        @foreach ($contact_details as $key => $value)
                                                @if($value['typeCode']=='L' || $value['typeCode']=='O')
                                                    $checkCountContacts[] = $value['typeCode']; 
                                                @endif

                                                @if($value['typeCode']=='L')
                                                    {{!empty($value['Number']) ? $value['Number'] : ' - '}}
                                                @endif

                                                @php
                                                    $addComma = count($checkCountContacts)>1 ? ",":""; 
                                                    $addOthers = !empty($value['Number']) ? $value['Number'] : ' - ';
                                                @endphp
                                                
                                                @if ($value['typeCode']=='O')
                                                    {{$addComma.$addOthers}}
                                                @endif
                                        @endforeach

                                        @if(count($checkCountContacts)==0)
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mobile :</td>
                                    <td>
                                        @php
                                            $checkCountContacts = [];
                                        @endphp

                                        @foreach($contact_details as $key => $value)
                                            @if($value['typeCode']=='M')
                                                @php $checkCountContacts[] = $value['typeCode']; @endphp
                                                {{!empty($value['Number']) ? $value['Number'] : ' - '}}
                                                @php break; @endphp
                                            @endif
                                        @endforeach
                                        @if(count($checkCountContacts)==0)
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Fax :</td>
                                    <td>
                                        @php $checkCountContacts = []; @endphp
                                        @foreach($contact_details as $key => $value)
                                            @if($value['typeCode']=='F')
                                                @php $checkCountContacts[] = $value['typeCode']; @endphp
                                                {{!empty($value['Number']) ? $value['Number'] : ' - '}}
                                            @endif
                                        @endforeach
                                        @if(count($checkCountContacts)==0)
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </table>
     
                </td>
            </tr>
            <!-- END of Enquiry Match & Head Quarter -->
            <!-- Related Entities -->
            @php $re_or_ri_page_break_count = 0; @endphp
            <tr>
                <table id="publicdeeds2" style="padding-top: -60px;">
                    <tr>
                        <th colspan="7"><strong>Related Entities</strong></th>                                  
                    </tr>
                    <tr>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Name</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Address</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Incorporation Date</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">CIN</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">TIN</td>
                        <td style="background-color:#f2c50c; text-align: left; font-weight: 600;">PAN</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Relationship</td>
                    </tr>
                    
                    @if(isset($RelationshipDetails))
                        @foreach($RelationshipDetails as $key => $value)
                            <tr>
                                <td style="text-align: center;">{{!empty($value['business_entity_name']) ? $value['business_entity_name']:'-'}}</td>
                                 <td style="text-align: center;">{{!empty($value['CommercialAddressInfo'][0]['Address']) ? $value['CommercialAddressInfo'][0]['Address'] : '-'}}</td>
                                 <td style="text-align: center;">{{!empty($value['date_of_incorporation']) ? date('d-m-Y',strtotime($value['date_of_incorporation'])):'-'}}</td>
                                 <td style="text-align: center;">{{!empty($value['IdentityInfo']['CIN'][0])? $value['IdentityInfo']['CIN'][0] :'-'}}</td> 
                                 <td style="text-align: center;">{{!empty($value['IdentityInfo']['TIN'][0])? $value['IdentityInfo']['CIN'][0] :'-'}}</td> 
                                 <td style="text-align: center;">{{!empty($value['IdentityInfo']['PANId'][0])? $value['IdentityInfo']['CIN'][0] :'-'}}</td> 
                                 <td style="text-align: center;">Proprietor</td>
                            </tr>
                            @php $re_or_ri_page_break_count++; @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" style="text-align: center;color: red;font-size: 17px;">
                                No Related Entities Reported to Equifax
                            </td>
                        </tr>
                    @endif 
                </table>
            </tr>
            <!--End of Related Entities -->
            <!-- Related Individuals -->
            <tr>
                <table id="publicdeeds2" style="margin-top: 20px;">
                    <tr>
                        <th colspan="5"><strong>Related Individuals</strong></th>                                  
                    </tr>
                    <tr>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Name</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Address</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">ID</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Phone</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Relationship</td>
                    </tr>
                     <tr>
                        <td colspan="5" style="text-align: center;color: red;font-size: 17px;">
                            No Related Individuals Reported to Equifax
                        </td>
                    </tr>
                </table>
            </tr>
            <!-- End of Related Individuals -->
            @if($re_or_ri_page_break_count > 1)
                <!-- page break -->
                <tr>
                    <td colspan="3">
                        <!-- HR Line-->
                        <div class="row">
                            <div class="col-md-12"><div style="page-break-after: always"></div></div>
                        </div>
                    </td>
                </tr>
                <!-- end of page break -->
            @endif
            <!-- Report High lights -->
            <tr>
                <table id="reportdata" style=" border-collapse: collapse; margin-top: 15px;">
                    <tr>
                       <th colspan="4"><strong>Report Highlights (Last 3 years)</strong></th>
                    </tr>
                    <tr>
                        <th colspan="4" style="width: 15%; background-color:#f2c50c; text-align: center; font-weight: 600;color: #222222">Availed by {{!empty($business_details->BusinessName) ? $business_details->BusinessName : ' '}}
                        </th>
                    </tr>
                    <tr style="border-bottom: 1px solid #a99f9f;">
                        <td style="font-size: 17px !important;font-weight: 700;  border-right: solid 1px #a99f9f;text-align: center;">Details</td>
                        <td style="font-size: 17px !important;font-weight: 700;border-right: solid 1px #a99f9f;text-align: center;"><!-- Most Recent Year Value -->
                           {{!empty($overallcreditsummary_keys[0])? $overallcreditsummary_keys[0] : '-'}}
                        </td>
                        <td style="font-size: 17px !important;font-weight: 700;border-right: solid 1px #a99f9f;text-align: center;">{{!empty($overallcreditsummary_keys[1])? $overallcreditsummary_keys[1] : '-'}} <!-- Previous Year Value -->
                        </td>
                        <td style="font-size: 17px !important;font-weight: 700;text-align: center;">{{!empty($overallcreditsummary_keys[2])? $overallcreditsummary_keys[2] : '-'}}<!-- Most Latest Year Value -->
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: solid 1px #a99f9f;text-align: center;">Total Accounts :</td>
                        <td style="border-right: solid 1px #a99f9f;text-align: center;">{{!empty($overallcreditsummary_borrower->a->CF_Count) ? $overallcreditsummary_borrower->a->CF_Count : ' - '}}</td>
                        <td style="border-right: solid 1px #a99f9f;text-align:center;">{{!empty($overallcreditsummary_borrower->b->CF_Count) ? $overallcreditsummary_borrower->b->CF_Count : ' - '}}</td>
                        <td style="text-align:center;">{{!empty($overallcreditsummary_borrower->c->CF_Count) ? $overallcreditsummary_borrower->c->CF_Count : '-'}}</td>
                    </tr>
                    <tr>
                        <td style="border-right: solid 1px #a99f9f;text-align: center;">New Accounts Opened : </td>
                        <td style="border-right: solid 1px #a99f9f;text-align:center;">{{!empty($overallcreditsummary_borrower->a->OpenCF_Count) ? $overallcreditsummary_borrower->a->OpenCF_Count : ' - '}}</td>
                        <td style="border-right: solid 1px #a99f9f;text-align:center;">{{!empty($overallcreditsummary_borrower->b->OpenCF_Count) ? $overallcreditsummary_borrower->b->OpenCF_Count : ' - '}}</td>
                        <td style="text-align:center;">{{!empty($overallcreditsummary_borrower->c->OpenCF_Count) ? $overallcreditsummary_borrower->c->OpenCF_Count : ' - '}}</td>
                    </tr>
                    <tr>
                        <td style="border-right: solid 1px #a99f9f;text-align: center;">Term Loans Closed :</td>
                        <td style="border-right: solid 1px #a99f9f;text-align:center;">-</td>
                        <td style="border-right: solid 1px #a99f9f;text-align:center;">-</td>
                        <td style="text-align:center;">-</td>
                    </tr>
                    <tr>
                        <td style="border-right: solid 1px #a99f9f;text-align: center;">Credit Utilization (Open Accounts)</td>
                        <td style="border-right: solid 1px #a99f9f;text-align:center;">
                            @if(!empty($overallcreditsummary_borrower->a->CurrentBalanceOpenCF_Sum))
                                @if(($overallcreditsummary_borrower->a->CurrentBalanceOpenCF_Sum != '0' && $overallcreditsummary_borrower->a->SanctionedAmtOpenCF_Sum != '0'))
                                    {{round((($overallcreditsummary_borrower->a->CurrentBalanceOpenCF_Sum)/($overallcreditsummary_borrower->a->SanctionedAmtOpenCF_Sum)) * 100,0,PHP_ROUND_HALF_UP)}}<span>%</span>
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td style="border-right: solid 1px #a99f9f;text-align:center;">
                            @if(!empty($overallcreditsummary_borrower->b->CurrentBalanceOpenCF_Sum))
                                @if($overallcreditsummary_borrower->b->CurrentBalanceOpenCF_Sum != '0' && $overallcreditsummary_borrower->b->SanctionedAmtOpenCF_Sum != '0')
                                    {{round((($overallcreditsummary_borrower->b->CurrentBalanceOpenCF_Sum)/($overallcreditsummary_borrower->b->SanctionedAmtOpenCF_Sum)) * 100,0,PHP_ROUND_HALF_UP)}}<span>%</span>
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if(!empty($overallcreditsummary_borrower->c->CurrentBalanceOpenCF_Sum))
                                @if($overallcreditsummary_borrower->c->CurrentBalanceOpenCF_Sum != '0' && $overallcreditsummary_borrower->c->SanctionedAmtOpenCF_Sum != '0')
                                    {{round((($overallcreditsummary_borrower->c->CurrentBalanceOpenCF_Sum)/($overallcreditsummary_borrower->c->SanctionedAmtOpenCF_Sum)) * 100,0,PHP_ROUND_HALF_UP)}}<span>%</span>
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                         <td style="border-right: solid 1px #a99f9f;text-align: center;">Accounts Overdue</td>
                         <td style="border-right: solid 1px #a99f9f;text-align:center;">{{!empty($overallcreditsummary_borrower->a->OverdueCFInFY_Count) ? $overallcreditsummary_borrower->a->OverdueCFInFY_Count : ' - '}}</td>
                         <td style="border-right: solid 1px #a99f9f;text-align:center;">{{!empty($overallcreditsummary_borrower->b->OverdueCFInFY_Count) ? $overallcreditsummary_borrower->b->OverdueCFInFY_Count : ' - '}}</td>
                         <td style="text-align:center;">{{!empty($overallcreditsummary_borrower->c->OverdueCFInFY_Count) ? $overallcreditsummary_borrower->c->OverdueCFInFY_Count : ' - '}}</td>
                    </tr>
                    <tr>
                         <td style="border-right: solid 1px #a99f9f;text-align: center;">Most Severe Status</td>
                         <td style="border-right: solid 1px #a99f9f;text-align:center;">-</td>
                         <td style="border-right: solid 1px #a99f9f;text-align:center;">-</td>
                         <td style="text-align:center;">-</td>
                    </tr>
                    <tr>
                         <td style="border-right: solid 1px #a99f9f;text-align: center;">Highest Overdue Amount</td>
                         <td style="border-right: solid 1px #a99f9f;text-align:center;">{{!empty($overallcreditsummary_borrower->a->HighestOverdueAmt) ? "₹".$overallcreditsummary_borrower->a->HighestOverdueAmt : ' - '}}</td>
                         <td style="border-right: solid 1px #a99f9f;text-align:center;">{{!empty($overallcreditsummary_borrower->b->HighestOverdueAmt) ? "₹".$overallcreditsummary_borrower->b->HighestOverdueAmt : ' - '}}</td>
                         <td style="text-align:center;">{{!empty($overallcreditsummary_borrower->c->HighestOverdueAmt) ? "₹".$overallcreditsummary_borrower->c->HighestOverdueAmt : ' - '}}</td>
                    </tr>
                </table>
            </tr>
            <!-- End of Report High lights -->
            @if($re_or_ri_page_break_count <= 1)
                <!-- page break -->
                <tr>
                    <td colspan="3">
                        <!-- HR Line-->
                        <div class="row">
                            <div class="col-md-12"><div style="page-break-after: always"></div></div>
                        </div>
                    </td>
                </tr>
                <!-- end of page break -->
            @endif
            <!-- Overall Report Summary -->
            <tr>
                <table id="publicdeeds2" style="margin-top: 30px;">
                    <tr>
                        <th colspan="8">Overall Report Summary</th>                                  
                    </tr>
                    <tr>
                        <th colspan="8" style="width: 16%; background-color:#f2c50c; text-align: center; font-weight: 600;color: #222222">Credit Facilities availed by {{!empty($business_details->BusinessName) ? $business_details->BusinessName : ' '}}
                        </th>
                    </tr>
                </table>
            </tr>
            <!-- End of Overall Report Summary -->
            <?php 
                if(!empty($arrayAccOpenDate)){
                    
                    $date_arr =$arrayAccOpenDate;
                    for ($i = 0; $i < count($date_arr); $i++){
                        if ($i == 0){
                            $max_date = date('Y-m-d H:i:s', strtotime($date_arr[$i]));
                            $min_date = date('Y-m-d H:i:s', strtotime($date_arr[$i]));
                        } else if ($i != 0) {
                            $new_date = date('Y-m-d H:i:s', strtotime($date_arr[$i]));
                            if ($new_date > $max_date) {
                                $max_date = $new_date;
                            } else if ($new_date < $min_date) {
                                $min_date = $new_date;
                            }
                        }
                    }
                }
            ?>
            <!-- 5 boxes -->
            <tr>
                <td colspan="3">
                    <table width="100%" style="margin: 15px 120px;">
                        <tr>
                            <td valign="top" width="33.3%">
                                <div class="statistics_item">
                                    <h3 class="counter">
                                         @if(!empty($credit_age))
                                            {{$credit_age}}
                                            @if($credit_age == 1)
                                                yr
                                            @else
                                                yrs
                                            @endif
                                        @else
                                            0
                                        @endif
                                    </h3>
                                    <p>Credit Age</p>
                                </div>
                            </td>
                            <td valign="top" width="33.3%">
                                <div class="statistics_item">
                                    <h3 class="counter">
                                        @if(!empty($credit_usage))
                                            {{round($credit_usage,0,PHP_ROUND_HALF_UP)}}<span>%</span>
                                        @else
                                            -
                                        @endif
                                    </h3>
                                    <p>Credit Usage</p>
                                </div>
                            </td>
                            <td valign="top" width="33.3%">
                                <div class="statistics_item">
                                    <h3 class="counter">
                                        {{!empty($total_enquiries) ? $total_enquiries : ' 0 '}}
                                    </h3>
                                    <p>Enquires</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" width="33.3%">
                                <div class="statistics_item">
                                    <h3 class="counter">
                                        @php $avgPayCnt = ""; @endphp
                                        @if(!empty($payment_score))
                                            {{round($payment_score,0,PHP_ROUND_HALF_UP)}}<span>%</span>
                                        @else
                                            0
                                        @endif
                                    </h3>
                                    <p>Payment Score</p>
                                </div>
                            </td>
                            <td valign="top" width="33.3%">
                                <div class="statistics_item">
                                    <h3 class="counter">{{!empty($total_account) ? $total_account : ' 0 '}}</h3>
                                    <p>Total Accounts</p>
                                </div>
                            </td>
                            <td valign="top" width="33.3%">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- End of 5 boxes -->
            <!-- credit_facility sections -->
            <tr>
                @php
                    $paymentStatusOnTimeArray = ['000', '*', 'STD', 'NEW', 'CLSD', 'OPEN', 'RES','NS','1000'];
                    $paymentStatusLateArray = ['01+', '31+', '61+','SUB','SMA','SMA 0','SMA 1','SMA 2','1001','1002-1089','FPD'];
                    $paymentStatusVeryLateTimeArray = ['91+','121+','181+', '360+', '540+', '720+','DBT','LOS','DBT 1','DBT 2','DBT 3','NPA','1090-1999', 'SET', 'WOF', 'POWS', 'INV', 'DEV', 'RNC','RGM','RNC','SF','WDF','SFR','SFWD','SFWO','SWDW','TP','DI','ED'];
                    $openHistoryAccountFlag = false;
                    $closedHistoryAccountFlag = false;
                    $counter = 1;
                @endphp
                @foreach($credit_facility as  $value)

                    @if($counter%2 == 0)
                        <!-- page break -->
                        <tr>
                            <td colspan="3">
                                <!-- HR Line-->
                                <div class="row">
                                    <div class="col-md-12"><div style="page-break-after: always"></div></div>
                                </div>
                            </td>
                        </tr>
                        <!-- end of page break -->
                    @endif
                    <table id="reportdata" style="margin-top: 30px;">
                        <tr>
                            <th colspan="3">Details of Credit Facilities</th>  
                        </tr>
                        <tr>
                            <th colspan="3" style="width: 15%; background-color:#f2c50c; text-align: center; font-weight: 600;color: #222222">Availed by {{!empty($business_details->BusinessName) ? $business_details->BusinessName : ' '}}</th>
                        </tr>   
                        <tr>
                            <td style="border-right: solid 1px #a99f9f;">Lender Name : ****</td>
                            <td style="border-right: solid 1px #a99f9f;">Account Number : ****</td>
                            <td>Account Type : {{!empty($value['credit_type']) ? $value['credit_type'] : ' - '}}</td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #a99f9f;">Sanctioned Amount :  {{!empty($value['sanctioned_amount_notional_amountofcontract']) ? "Rs. ".number_format($value['sanctioned_amount_notional_amountofcontract']) : ' - '}}</td>
                            <td style="border-right: solid 1px #a99f9f;">Drawing Power : {{!empty($value['drawing_power']) ? "Rs. ".number_format($value['drawing_power']) : ' - '}}</td>
                            <td>Current Balance : {{!empty($value['current_balance_limit_utilized_marktomarket']) ? "Rs. ".number_format($value['current_balance_limit_utilized_marktomarket']) : ' - '}}</td>
                       </tr>
                       <tr>
                          <td style="border-right: solid 1px #a99f9f;">High Credit : {{!empty($value['high_credit']) ? "₹".number_format($value['high_credit']) : ' - '}}</td>
                          <td style="border-right: solid 1px #a99f9f;">Gurantee Coverage : {{!empty($value['guarantee_coverage']) ? $value['guarantee_coverage'] : ' - '}}</td>
                          <td>Tenure : {{!empty($value['tenure_weighted_avg_maturityperiod']) ? $value['tenure_weighted_avg_maturityperiod'].' months' : ' - '}}</td>
                       </tr>
                        <tr>
                           <td style="border-right: solid 1px #a99f9f;">Date Opened : {{!empty($value['sanctiondate_loanactivation']) ? 
                                date('d-m-Y',strtotime($value['sanctiondate_loanactivation'])) : ' - '}}</td>
                           <td style="border-right: solid 1px #a99f9f;">Loan Renewal Date : {{!empty($value['loan_renewal_date']) ?
                                date('d-m-Y',strtotime($value['loan_renewal_date'])) : ' - '}}</td>
                           <td>Loan End Date : {{!empty($value['loan_expiry_maturity_date']) ? date('d-m-Y',strtotime($value['loan_expiry_maturity_date'])): ' - '}}</td>
                        </tr>
                         <tr>
                            <td style="border-right: solid 1px #a99f9f;">Last Payment Date : {{!empty($value['dt_reported_lst']) ? 
                                date('d-m-Y',strtotime($value['dt_reported_lst'])): ' - '}}</td>
                            <td style="border-right: solid 1px #a99f9f;">Date Reported : {{!empty($value['dt_reported_lst']) ? 
                                date('d-m-Y',strtotime($value['dt_reported_lst'])): ' - '}}</td>
                            <td>Dispute Code : -</td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #a99f9f;">Account Status : {{!empty($value['account_status']) ? $value['account_status'] : ' - '}}</td>
                            <td style="border-right: solid 1px #a99f9f;">Suit Filed Status : {{!empty($value['suit_filed_status']) ? $value['suit_filed_status'] : ' - '}}</td>
                            <td>Wilful Default Status : {{!empty($value['wilful_default_status']) ? $value['wilful_default_status'] : ' - '}}</td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #a99f9f;">Status Date : {{!empty($value['account_status_dt']) ? 
                                date('d-m-Y',strtotime($value['account_status_dt'])): ' - '}}</td>
                            <td style="border-right: solid 1px #a99f9f;">Suit Filed Date : {{!empty($value['date_of_suit']) ? date('d-m-Y',strtotime($value['date_of_suit'])): ' - '}}</td>
                            <td>Wilful Default Date : -</td>
                        </tr>
                         <tr>
                            <td style="border-right: solid 1px #a99f9f;">Past Due Amount : -</td>
                            <td style="border-right: solid 1px #a99f9f;">Settlement Amount : {{!empty($value['settled_amount']) ? "₹".number_format($value['settled_amount']) : '-'}}</td>
                            <td>Written Off Amount : {{!empty($value['written_off_amount']) ? "₹".number_format($value['written_off_amount']) : '-'}}</td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #a99f9f;">Monthly Payment Amount : {{!empty($value['installment_amount']) ? "₹".number_format($value['installment_amount']) : '-'}}</td>
                            <td style="border-right: solid 1px #a99f9f;">Repayment Frequency : 
                                @if(!empty($value['repayment_frequency']))
                                    @if($value['repayment_frequency'] == 1)
                                        Weekly
                                    @elseif($value['repayment_frequency'] == 2)
                                        Fortnightly
                                    @elseif($value['repayment_frequency'] == 3)
                                        Monthly
                                    @elseif($value['repayment_frequency'] == 4)
                                        Quarterly
                                    @else
                                        -
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>Restructuring Reason :
                                @if(!empty($value['major_reasons_for_restructuring']))
                                    @if($value['major_reasons_for_restructuring'] == 01)
                                        Restructured due to Non- Performance
                                    @elseif($value['major_reasons_for_restructuring'] == 02)
                                        Restructured due to Natural Calamity
                                    @elseif($value['major_reasons_for_restructuring']==99)
                                        Others
                                    @else
                                        -
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #a99f9f;">Amount of NPA Contracts : {{!empty($value['amount_of_contracts_classified_npa']) ? "₹".$value['amount_of_contracts_classified_npa'] : ' - '}}</td>
                            <td style="border-right: solid 1px #a99f9f;">NOARC : {{!empty($value['notional_amount_outstanding_restructured_contracts']) ? $value['notional_amount_outstanding_restructured_contracts'] : ' - '}}</td>
                            <td>Asset Based Security Coverage : {{!empty($value['asset_based_security_coverage']) ? $value['asset_based_security_coverage'] : ' - '}}</td>
                        </tr>
                    </table>
                    <?php 
                        $tempYears = array();
                        $onTimePaymentCount = 0;
                    
                        foreach ($value['History48Months'] as $k => $v) {
                            $date = DateTime::createFromFormat("Y-m", $v['yyyymm']);
                            
                            $str = '';
                            if (in_array($v['assetclassification_dayspastdue'], $paymentStatusOnTimeArray)) {
                                $str = '<a class="anc_active oneitme" href="javascript:void(0)"></a>';
                                $onTimePaymentCount++;
                               
                            } else if (in_array($v['assetclassification_dayspastdue'], $paymentStatusLateArray)) {
                                $str = '<a class="anc_active miditme" href="javascript:void(0)"></a>';
                               
                            } else if (in_array($v['assetclassification_dayspastdue'], $paymentStatusVeryLateTimeArray)) {
                                $str = '<a class="anc_active latetime" href="javascript:void(0)"></a>';
                               
                            } else {
                               list($days_past_due) = explode(' ', trim($v['assetclassification_dayspastdue']));
                                if($days_past_due >=1 && $days_past_due<=89){
                                   $str = '<a class="anc_active miditme" href="javascript:void(0)"></a>';   
                                 } elseif ($days_past_due >=90) {
                                    $str = '<a class="anc_active latetime" href="javascript:void(0)"></a>';
                                 } else {
                                   $str = '<a class="anc_active latetime" href="javascript:void(0)"></a>';
                                 }
                            
                            }

                            if (isset($tempYears[$date->format("Y")])) {
                                $tempYears[$date->format("Y")][$date->format("M")] = $str;
                                
                            } else {
                                $tempYears[$date->format("Y")] = array();
                                $tempYears[$date->format("Y")][$date->format("M")] = $str;
                            }
                        }

                        $history_percentage = number_format(($onTimePaymentCount * 100) / count($value['History48Months']), 2);

                        if($value['account_status'] == 'OPN'){
                            $openHistoryAccountFlag = true;
                        } else {
                            $closedHistoryAccountFlag = true;
                        }

                        $class = $value['account_status'] == 'OPN' ? 'open_account' : 'closed_account display_none';
                        $fromToCount = count($value['History48Months']);
                        
                        if(isset($value['History48Months'][0]['yyyymm'])){
                            $from_date = DateTime::createFromFormat("Y-m", $value['History48Months'][0]['yyyymm']);
                            $from_date = $from_date->format('m/Y');
                        } else {
                            $from_date ='';
                        }

                        $paymentHeadingFrom = isset($value['History48Months'][0]) ? " to ".$from_date : "";
                        if(isset($value['History48Months'][$fromToCount-1]['yyyymm'])){
                            $to_date = DateTime::createFromFormat("Y-m", $value['History48Months'][$fromToCount-1]['yyyymm']);
                            $to_date = $to_date->format('m/Y');
                        } else {
                            $to_date ='';
                        }

                        $paymentHeadingTo = isset($value['History48Months'][$fromToCount-1]) ? $to_date : "";
                        if(count($value['History48Months'])==1){
                            $paymentHeadingFrom = '';
                        }

                        $forOrFrom = $paymentHeadingFrom != "" ? 'from ':'for ';
                    ?>
                    <table id="openacdetails" class="mt-4" style="margin-left: 15px; margin-right: 15px;">
                        <tr>
                            <th colspan="7" style="background-color: #e8e8e8; color: #222222; height: 45px;">
                                <div class="col-md-6" style="text-align: right;">
                                    <h class="payment-history"> Payment History</h>
                                </div>
                                
                                <!-- <br class="media-break"> -->
                                <div class="col-md-6">
                                    <table style="width: 100%; text-align: right;" >
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="margin: 0; padding: 0;">
                                                <span class="dot" style="background-color: #2dac00;"></span>&nbsp;&nbsp;On-time
                                            </td>
                                            <td style=" margin: 0; padding: 0;">
                                                <span class="dot" style="background-color: #f5821f;"></span>&nbsp;&nbsp;1-89 days late
                                            </td>
                                            <td style=" margin: 0; padding: 0;">
                                                <span class="dot" style="background-color: #ef4023;"></span>&nbsp;&nbsp;90+ days late
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </th>                                  
                        </tr>
                        <div style="margin-top: 10px">
                            <table id="paymenthistory" style="margin: 0px 15px 10px 15px;">
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
                                @foreach($tempYears as $tempYears_key => $tempYears_value)
                                    <tr> 
                                        <td style="font-size: 12px;"><div class="dpd-text"><span>Status</span></div>
                                            <hr style="margin-bottom: 0px;margin-top: 0px;border-bottom: 1px solid #bbbbbb; width: 60px;">
                                          
                                           <div class="over-due-text"><span>Overdue Amount</span></div>
                                        </td>
                                        <td>{{$tempYears_key}}</td>

                                        <td>{!! isset($tempYears_value['Dec']) ? $tempYears_value['Dec'] : '' !!}<br>
                                            <?php foreach ($value['History48Months'] as $k => $v){
                                                $month =    date('m',strtotime('Dec')); 
                                                if($tempYears_key.'-'.$month == $v['yyyymm']){
                                                  // echo "₹". $v['amount_overdue_limit_overdue'];
                                                  echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 '; 
                                                }
                                            }?>
                                        </td>
                                        <td>{!! isset($tempYears_value['Nov']) ? $tempYears_value['Nov'] : '' !!}<br>
                                            <?php foreach ($value['History48Months'] as $k => $v){
                                              $month =    date('m',strtotime('Nov')); 
                                            
                                                if($tempYears_key.'-'.$month == $v['yyyymm']){
                                                 echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>{!! isset($tempYears_value['Oct']) ? $tempYears_value['Oct'] : '' !!}<br>
                                            <?php foreach ($value['History48Months'] as $k => $v) { 
                                                $month =    date('m',strtotime('Oct')); 
                                                if($tempYears_key.'-'.$month == $v['yyyymm']){
                                                 echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>{!! isset($tempYears_value['Sep']) ? $tempYears_value['Sep'] : '' !!}<br>
                                            <?php foreach ($value['History48Months'] as $k => $v) {
                                                $month =    date('m',strtotime('Sep')); 
                                                if($tempYears_key.'-'.$month == $v['yyyymm']){
                                                 echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>{!! isset($tempYears_value['Aug']) ? $tempYears_value['Aug'] : '' !!}<br>
                                            <?php foreach ($value['History48Months'] as $k => $v) {
                                                $month =    date('m',strtotime('Aug')); 
                                                if($tempYears_key.'-'.$month == $v['yyyymm']){
                                                  echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 '; 
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>{!! isset($tempYears_value['Jul']) ? $tempYears_value['Jul'] : '' !!}<br>
                                            <?php foreach ($value['History48Months'] as $k => $v) {
                                                $month =    date('m',strtotime('Jul')); 
                                                if($tempYears_key.'-'.$month == $v['yyyymm']){
                                                 echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>{!! isset($tempYears_value['Jun']) ? $tempYears_value['Jun'] : '' !!}<br>
                                            <?php foreach ($value['History48Months'] as $k => $v) { 
                                                $month =    date('m',strtotime('Jun')); 
                                                if($tempYears_key.'-'.$month == $v['yyyymm']){
                                                 echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
                                                }
                                            }?>
                                        </td>
                                        <td>{!! isset($tempYears_value['May']) ? $tempYears_value['May'] : '' !!}<br>
                                        <?php foreach ($value['History48Months'] as $k => $v) { 
                                        $month =    date('m',strtotime('May')); 
                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
                                          echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0';
                                        } }?></td>
                                        <td>{!! isset($tempYears_value['Apr']) ? $tempYears_value['Apr'] : '' !!}<br>
                                        <?php foreach ($value['History48Months'] as $k => $v) {
                                        $month =    date('m',strtotime('Apr')); 
                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
                                        }
                                        }
                                      
                                        ?></td>
                                        <td>{!! isset($tempYears_value['Mar']) ? $tempYears_value['Mar'] : '' !!}<br>
                                        <?php foreach ($value['History48Months'] as $k => $v) { 
                                        $month =    date('m',strtotime('Mar')); 
                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
                                        }
                                        }
                                      
                                        ?></td>
                                        <td>{!! isset($tempYears_value['Feb']) ? $tempYears_value['Feb'] : '' !!}<br>
                                        <?php foreach ($value['History48Months'] as $k => $v) {
                                        $month =    date('m',strtotime('Feb')); 
                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
                                          }
                                         }
                                      
                                        ?></td>
                                        <td>{!! isset($tempYears_value['Jan']) ? $tempYears_value['Jan'] : '' !!}<br>
                                        <?php foreach ($value['History48Months'] as $k => $v) {
                                        $month =    date('m',strtotime('Jan')); 
                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
                                        } 
                                        }
                                      
                                        ?></td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </table>
                    @php $counter++; @endphp;
                @endforeach
            </tr>
            <!-- End of credit_facility sections -->
            <!--Details of Enquiries  -->
            <tr>
                <table id="publicdeeds2" style="margin-top: 30px; ">
                    <tr>
                        <th colspan="4">Details of Enquiries</th>                                  
                    </tr>
                    <tr>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Lender</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Date</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Purpose</td>
                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Amount</td>
                    </tr>
                        @if($recent_enquiries != 0)
                            @foreach($recent_enquiries as $key => $value)
                                 <tr>
                                    <td style="text-align: center;"><?php echo !empty($value['Institution']) ? 'XXXXXXXXXXXX' : ' - ';?></td>
                                    <td style="text-align: center;"><?php echo !empty($value['Date']) ? $value['Date'] : ' - ';?></td>
                                    <td style="text-align: center;"><?php echo !empty($value['RequestPurpose']) ? $value['RequestPurpose'] : ' - ';?></td>
                                    <td style="text-align: center;"><?php echo !empty($value['Amount']) ? "₹".$value['Amount'] : ' - ';?></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td style="text-align: center;">-</td>
                                <td style="text-align: center;">-</td>
                                <td style="text-align: center;">-</td>
                                <td style="text-align: center;">-</td>
                            </tr>
                        @endif
                </table>
            </tr>
            <!-- Details of Enquiries -->

            <!-- Recordent report starts here -->
            <div class="pdf_screens recordent-pdf" style="margin-top: 30px;">
                <h1 class="pdf-title eq-title recordent-eq-title">Recordent</h1>

                @if(count($records) > 0) 
                    <!-- Score section -->
                    <div class="pdf_block pdf_progress" style="height:220px; " >
                      <div class="inner">
                         <div class="rc_top">
                            <div class="left_top">
                               <h4 style="color: #000;">Recordent score is:</h4>
                            </div>
                           
                            <div class="clear"></div>
                         </div>
                         <div class="rc_mid">
                            <h2 style="font-size:50px; padding: 70px 0px 30px 0px;">Coming soon !</h2>
                         </div>
                      
                         <p class="last-update">Report Date: {{General::getFormatedDate($dateTime)}}</p>
                      </div>
                    </div>
                    <!-- End of Score Section -->

                    <!-- profile, members, invoice, total dues cards -->
                    <div class="pdf-row" >

                        <div class="pdf_block bottom_block" style="width:575px; height:110px; float:left; border-left: 10px solid #202f7d; ">
                         <div class="inner">
                         <h4 class="sub">Profile information</h4>  
                         <p style="margin-top:50px; color: #000;"><i style="font-style:normal">Phone: </i><span>{{$user['number']}}</span></p>       
                         </div>
                        </div>
                      
                        <div class="pdf_block bottom_block" style="width:575px; height:110px; float:left; margin-left:28px; border-left: 10px solid #202f7d; ">
                            <div class="inner">
                                <h4 class="sub">Members</h4>    
                                <p style="margin-top:50px; color: #000;">Total Members Reporting Dues: <span>{{$user['recordent']['total_members']}}</span></p>            
                            </div>
                        </div>

                        <div class="clear" style="clear:both; width:100%; height:1px; display:block;"></div>
                      
                        <div class="pdf_block bottom_block" style="width:575px; height:110px; float:left ; border-left: 10px solid #202f7d; ">
                            <div class="inner" >
                                <h4 class="sub">Invoices</h4>
                                <p  style="color: #000;">No.of records: {{$user['recordent']['summary_overDueStatus0To89Days']+$user['recordent']['summary_overDueStatus90To179Days']+$user['recordent']['summary_overDueStatus180PlusDays']}}</p>
                                <p  style="margin-top:15px; color: #000;">Overdue status</p>
                                <p style="color: #000;">
                                    <span>1-89 days : {{$user['recordent']['summary_overDueStatus0To89Days']}}</span>  <span>90-180 days : {{$user['recordent']['summary_overDueStatus90To179Days']}}</span>  <span>180+ days : {{$user['recordent']['summary_overDueStatus180PlusDays']}}</span>
                                </p>        
                            </div>
                        </div>

                        <div class="pdf_block bottom_block" style="width:575px; height:110px; float:left; margin-left:28px; border-left: 10px solid #202f7d; ">
                            <div class="inner">
                                <h4 class="sub">Total dues</h4>
                                  <p  style="margin:20px 0 10px 0px; color: #000;">Total Due Amount: Rs {{number_format($user['recordent']['total_dues_paid']+$user['recordent']['total_dues_unpaid'])}}</p>
                                  <p class="ac_inline" style="display: inline-block; width: 200px; margin-left:5px; color: #000;">Paid: <span>Rs {{number_format($user['recordent']['total_dues_paid'])}}</span></p>
                                  <p class="ac_inline ac_lm" style="display: inline-block; width: 200px; color: #000;">Unpaid: <span>Rs {{number_format($user['recordent']['total_dues_unpaid'])}}</span></p>
                            </div>
                        </div> 
                   
                        <div class="clear" style="clear:both; width:100%; height:1px; display:block;"></div>
                    </div>
                    <!-- End of profile, members, invoice, total dues cards -->

                    <!-- Profile Section -->
                    <div class="sub_mainBlock ntb recordent_sub_mainBlock" style="border: none;">
                        <h2>Profile</h2>
                        <div class="block">
                            <ul>
                                <li style="font-size:18px; line-height:26px">
                                    <span style="font-size:18px; line-height:26px">Business Name: </span>
                                    <span style="font-size:16px; line-height:24px">{{$user['business_name_rec'] }}</span>
                                </li>
                                <li style="font-size:18px; line-height:26px">
                                    <span style="font-size:18px; line-height:26px">GSTIN / Business PAN:</span>
                                    <span style="font-size:18px; line-height:26px">{{$user['unique_identification_number'] }}</span>
                                </li>
                                <li style="font-size:18px; line-height:26px">
                                    <span style="font-size:18px; line-height:26px">Business Type:</span>
                                    <span style="font-size:18px; line-height:26px">{{$user['business_type_rec'] }}</span>
                                </li>
                                <li style="font-size:18px; line-height:26px">
                                    <span style="font-size:18px; line-height:26px">Business Sector:</span>
                                    <span style="font-size:18px; line-height:26px">{{$user['business_sector_rec'] }}</span>
                                </li>
                            </ul>
                            <div class="clear" style="clear:both; width:100%; height:1px; display:block;"></div>
                        </div>
                        <div class="block" style="margin-left:18px;">
                            <ul>
                                <li style="font-size:18px; line-height:26px">
                                    <span style="font-size:18px; line-height:26px; width: 50%;">Concerned Person Name:</span>
                                    <span style="font-size:18px; line-height:26px">{{$user['business_concerned_name_rec'] }}</span>
                                </li>
                                <li style="font-size:18px; line-height:26px">
                                    <span style="font-size:18px; line-height:26px; width: 50%;">Concerned Person Mobile:</span>
                                    <span style="font-size:18px; line-height:26px">{{$user['number'] }}</span>
                                </li>
                                <li style="font-size:18px; line-height:26px">
                                    <span style="font-size:18px; line-height:26px; width: 50%;">Concerned Person Email:</span>
                                    <span style="font-size:18px; line-height:26px">{{$user['business_email_rec'] }}</span>
                                </li>
                                <li style="font-size:18px; line-height:26px">
                                    <span style="font-size:18px; line-height:26px; width: 50%;">Concerned Person Designation:</span>
                                    <span style="font-size:18px; line-height:26px;">{{$user['business_designation_rec'] }}</span>
                                </li>
                            </ul>
                        
                            <div class="clear" style="clear:both; width:100%; height:1px; display:block;"></div>
                        </div>
                        <div class="clear" style="clear:both; width:100%; height:1px; display:block;"></div>
                    </div>
                    <!-- End of Profile Section -->


                    <div class="clear" style="clear:both; width:100%; height:30px; display:block;"></div>
                    <!-- Invoice Section -->
                    <div class="recdodent_block">
                        @php $invoice_count = 0; @endphp
                        @forelse($records as $data)
                            <div class="sub_mainBlock recd_sub_mainBlock">
                                <h2>Inovice for member:   {{General::getMaskedCharacterAndNumber($data->person_name)}}</h2>
                                @foreach($data->dues as $due_k => $due_v)
                                    <?php
                                        $invoice_count++;
                                        $now = time(); // or your date as well
                                        $your_date = strtotime($due_v->due_date);
                                        $datediff = $now - $your_date;
                                        $days = round($datediff / (60 * 60 * 24));

                                        $temp_paid_amount = 0;
                                        foreach ($due_v->paid as $temp_r_due_paid_key => $temp_r_due_paid_value) {
                                            $temp_paid_amount += $temp_r_due_paid_value->paid_amount;
                                        }
                                    ?>
                                    <!-- invoice section data -->
                                    <div class="innner01 mb30" style="border-bottom: solid 1px #ccc; margin-top: 15px;"> 
                                        <div id="summary" style="width:100%; padding-bottom:10px;">
                                            <table width="100%">
                                                <tr>
                                                    <td width="50%">
                                                        
                                                        <div class="block01" style="height:80px;">
                                                            <ul>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Invoice no:</span>
                                                                    <span style="font-size:16px;">{{$due_v->id}}</span>
                                                                </li>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Status:</span>
                                                                    <span style="font-size:18px;">unpaid</span>
                                                                </li>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Overdue status:</span>
                                                                    <span style="font-size:18px;">{{$days > 0 ? $days. ' days' : '-'}}</span>
                                                                </li>
                                                            </ul>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </td>
                                                    <td width="50%">
                                                       
                                                        <div class="block01" style="height:80px;">
                                                            <ul>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Due date:</span>
                                                                    <span style="font-size:16px;">{{General::getFormatedDate($due_v->due_date)}}</span>
                                                                </li>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Date Reported:</span>
                                                                    <span style="font-size:18px;">{{General::getFormatedDate($due_v->created_at)}}</span>
                                                                </li>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Last Payment Date:</span>
                                                                    <span style="font-size:18px;">{{count($due_v->paid) > 0 ? General::getFormatedDate($due_v->paid[count($due_v->paid) - 1]->paid_date) : '-'}}</span>
                                                                </li>
                                                            </ul>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                   <td width="50%">
                                                       <div class="block01" style="height:80px;">
                                                            <ul>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Opening balance:</span>
                                                                    <span style="font-size:16px;">{{number_format($due_v->due_amount)}}</span>
                                                                </li>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Closing balance:</span>
                                                                    <span style="font-size:18px;">{{number_format($due_v->due_amount - $temp_paid_amount)}}</span>
                                                                </li>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Last payment:</span>
                                                                    <span style="font-size:18px;">{{count($due_v->paid) > 0 ? 'Rs '.$due_v->paid[count($due_v->paid) - 1]->paid_amount : '-'}}</span>
                                                                </li>
                                                            </ul>
                                                            <div class="clear"></div>
                                                        </div>
                                                   </td>
                                                   <td width="50%">
                                                       <div class="block01" style="height:80px;">
                                                            <ul>
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Proof of dues:</span>
                                                                    <span style="font-size:16px;">{{!empty($due_v->proof_of_due)? 'Yes' : 'No'}}</span>
                                                                </li>
                                                               
                                                                @php
                                                                    $disputeDetail = $due_v->dispute();
                                                                    $disputeStatus = 'No';
                                                                    $disputeComment = 'N/A';
                                                                    if(isset($disputeDetail->is_open)){
                                                                        $disputeStatus = $disputeDetail->is_open == 1 ? 'Open' : 'Closed';
                                                                    }
                                                                @endphp
                                                                <li style="font-size:18px;">
                                                                    <span style="font-size:18px;">Dispute:</span>
                                                                    <span style="font-size:18px;">{{$disputeStatus}}</span>
                                                                </li>
                                                                  
                                                               
                                                            </ul>
                                                            <div class="clear"></div>
                                                        </div>
                                                   </td>
                                               </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- End of invoice section data -->
                                    <!-- last invoice data -->
                                    @foreach($due_v->paid as $r_due_paid_key => $r_due_paid_value)
                                        @php $invoice_count++; @endphp

                                        <div class="innner02 mb30"> 

                                            <div id="summary" style="width:100%; border-bottom: solid 1px #eee; padding:10px 0 10px 0;">
                                                <table width="100%">
                                                    <tr>
                                                        <td width="50%">
                                                            <div class="block01" style="height:100px;">         
                                                                <ul>
                                                                    <li><span>Invoice no:</span> <span> {{$r_due_paid_value->due_id}}</span></h4>
                                                                    <li><span>Status</span><span> Paid</span></li>
                                                                    <li><span>Paid amount:</span><span>Rs {{$r_due_paid_value->paid_amount}}</span></li>
                                                                    <li><span>Due amount:</span><span>Rs {{$due_v->due_amount}}</span></li>
                                                                </ul>
                                                                <div class="clear"></div>
                                                            </div>
                                                        </td>
                                                        <td width="50%">
                                                            <div class="block01" style="height:100px; ">         
                                                                <ul>
                                                                    <li><span>Due date:</span><span> {{General::getFormatedDate($due_v->due_date)}}</span></li>
                                                                    <li><span>Paid date:</span><span> {{General::getFormatedDate($r_due_paid_value->paid_date)}}</span></li>  
                                                                    <li><span>Date reported:</span><span>{{General::getFormatedDate($due_v->created_at)}}</span></li>           
                                                                </ul>
                                                                <div class="clear"></div>
                                                            </div>  
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="clear" style="clear:both; width:100%; height:1px; display:block;"></div>
                                        </div>
                                        <!-- End of last invoice data -->
                                    @endforeach
                                @endforeach
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <!-- End of Invoice Section -->
                @else
                    <!-- Report Not found section -->
                    <div class="pdf_block pdf_progress" style="height:220px; " >
                      <div class="inner">
                         <div class="rc_mid">
                            <h2 style="font-size:50px; padding: 70px 0px 30px 0px;">No report found!</h2>
                         </div>
                      </div>
                    </div>
                    <!-- End of Report not found Section -->
                @endif

            </div>
            <!-- End of recordent report -->
          
        </table>
    </div>
</body>
