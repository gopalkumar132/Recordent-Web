<head>     
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{csrf_token()}}" />
  <link rel="manifest" href="{{config('app.url')}}manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>@yield('meta-title', 'Recordent - Credit Report')</title>
	<meta name="description" content="Check Credit & Payment History.">
	<link rel="canonical" href="{{config('app.url')}}creditreport" />
    <?php $site_favicon = Voyager::setting('site.favicon', '');?>
    @if($site_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($site_favicon) }}" type="image/png">
    @endif
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    @yield('canonical-url')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{asset('front_new/images/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('front_new/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('front_new/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('front_new/css/owl.theme.default.min.css')}}"> 
    <link rel="stylesheet" href="{{asset('front_new/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('front_new/css/style.css')}}">  
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet"/>
    <script src="{{asset('front_new/js/jquery-3.js')}}"></script>     
    <script src="{{asset('front_new/js/bootstrap.js')}}"></script>
    <script src="{{asset('front_new/js/owl.carousel.min.js')}}"></script>
     <link rel="stylesheet" type="text/css" href="{{asset('css/report.css')}}">       
</head>


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style type="text/css" media="screen">

    html, body{
      width: 100%;
      overflow-x: hidden;
    }
    .progress{
      width:67%;
      margin: 0px auto;
    }
    .container{
        max-width: 900px !important;
    }
    .header {
      padding: 25px 0px 10px 0px !important;
    }
    @media  screen and (min-width:1200px) {
      .logo{
        top: 0px;
        position: absolute;
        height:60px;
      }
      .text3
      {
        top: 0;
        /* padding-left:270px; */
        padding-left: 10%;
        font-size: 20px;
        text-align: center;
      }
      .bluebtn
      {
        text-align: center;
        font-size: 20px !important;
        width: auto;
        min-width: 150px;
        height: auto;
        margin: 15px 100px 18px -175px;
        /* margin: 15px 0px 78px -175px; */
        /* padding: 2px 8.3px 5px 8.2px; */
        /* padding: 17px 8.3px 5px 8.2px; */
        padding: 5px;
        border-radius: 3px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #363d72;
        font-family:Segoe UI;
      }
      .redbtn
      {
        font-size: 20px !important;
        text-align: center;
        width: auto;
        min-width: 100px;
        height: auto;
        margin: 15px -5px 18px -102px;
        /* margin: 15px -5px 78px -45px; */
        /* padding: 2px 11.8px 5px 42.2px; */
        padding: 5px;
        border-radius: 3px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #e42c2c;
        font-family:Segoe UI;
        font-family: 14px;
      }
    }
    @media  screen and (min-width:767px) and (max-width:800px) {

      #layer2 {
      /* margin-top: 70px; */
      /* margin-top: -5px !important; */
      margin-top: -117px !important;
      /* left: 22px; */
      }
      #layer2 {
      position: absolute;
      z-index: 2;
      height: 1250px;
      width: 350px;
      top: 130px;
      left: 20px;

      }
      .comman_cls{
        margin-left:0px !important;
      }
      .text3
      {
        margin-top: 100px;
        font-size: 20px;
        padding-left:60px;
      }
      .text4
      {
        font-family:Segoe UI;
      }
      .checknowbtn
      {
        margin-top: 340px;
        margin-left: -230px;
      }
      .bluebtn
      {
        width: 131.5px;
        height: 29px;
        margin: 15px 6px 8px 15px;
        padding: 5px 6px 15px 15px;
        border-radius: 3px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #363d72;
        font-family:Segoe UI;
        font-family: 14px;
       
      }
      .redbtn
      {
        width: 72px;
        height: 29px;
        margin: 15px 0px 8px 15px;
        padding: 5px 6px 15px 5px;
        border-radius: 3px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #e42c2c;
        font-family:Segoe UI;
        font-family: 14px;
      }
    }
    @media  screen and (min-width:1024px) and (max-width:1050px) {

      .comman_cls{
        margin-left:0px !important;
      }

      .logo
      {
        margin: 0 114.8px 58.5px 0;
        width: 132.8px;
        height: 49px;
      }
      .text3
      {
        left:75px !important;                                          
        font-size: 20px;
      }
      .bluebtn
      {
       width: 131.5px;
        height: 29px;
        margin: 15px 160px 78px -175px;
        padding: 2px 8.3px 5px 8.2px;
        border-radius: 3px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #363d72;
        font-family:Segoe UI;
        font-family: 14px;
       
      }
      .redbtn
      {
        width: 72px;
        height: 29px;
        margin: 15px 160px 78px -155px;
        padding: 2px 11.8px 5px 42.2px;
        border-radius: 3px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #e42c2c;
        font-family:Segoe UI;
        font-family: 14px;
      }
      .text4
      {
        font-family:Segoe UI;
      }
      .text3
      {
        padding-left:100px;
      }
      #layer2{
      left: 100px;
      }
      .checknowbtn
      { top:1070px !important;
        margin-left: -175px;
      }
    }

    @media  screen and (min-width:360px) and (max-width:640px) {
      .progress{
        width:100% !important;
      }

        .checknow
      {
        width:258px !important;height:59px !important;
      }
        .cards {
          font-family: Segoe UI;
          font-size: 22px !important;
          text-align: center;
          margin: 0px auto 50px 0px;
          padding: 10px 0px;
          color: #273581;
          top: 35px;
          position: relative;
          }

        .final-layer{
        padding-bottom:30px !important;
      }
        .check-now-div{
        height: 415px !important;
      }
        .text
      {
        color:white;font-size: 22px;line-height: 30px;margin: 120px 0px 0px 0px;font-family:Segoe UI;
      }
        .cust-report{font-size: 22px !important;
          margin-left: 0px !important;}

        .arrow-mark{
         font-size: 18px !important;

      }
      .middle-last {
          color: #273581;
          font-size: 22px !important;
          line-height: 33px;
      }
        .equifax-div
      {
        font-size:26px !important;
      }
        #layer2{width: 319px !important;}
        .mid-txt{
        color:#273581;font-size: 22px !important;line-height: 35px !important;
      }
      .middle-txt{
        font-size: 22px !important;
      }
        .img_logo{
        width:0px !important;
      }
      .txt-size{
        font-size:32px !important;
      }
      .comman_cls{
        margin-left:0px !important;
      }

        .flag_div{
        padding-left:0px !important;

      }

        .text_critical{margin-top: -84px!important;}
      #layer3 {
      position: relative;
      z-index: 2;
      /* height: 280px; */
      height: 329px;
      width: 250px;
      top: -70px;
      margin-top: 120px;
      left: 50px;
      }
      #layer2 {
      /* margin-top: 70px; */
      /* margin-top: -5px !important; */
      margin-top: -117px !important;
      /* left: 22px; */
      left: -242px;
      }
      .text
      {
        color:white;font-size: 20px;line-height: 30px;margin: 120px 0px 0px 0px;padding-left:110px;font-family: 'Lato', sans-serif;
      }
      .text1
      {
      position: relative;
      z-index: 2;
      height: 280px;
      width: 250px;
      top: -240px;
      margin-top: 1400px;
      color: #5a5454;
      font-family:Segoe UI;
      }
      .text4
      {
        font-family:Segoe UI;
      }
      .text3
      {
      font-size: 20px;
      }


      .bluebtn
      {
        width: 131.5px;
        height: 29px;
        text-align:center;
        margin: -53px 26px -25px 15px;
        /* margin: 25px 26px -25px 15px; */
        padding: 5px 6px 15px 15px;
        border-radius: 3px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #363d72;
        font-family:Segoe UI;
        font-family: 14px;
       
      }
      .redbtn
      {
        text-align:center;
        margin: -23px 10px -25px 15px;
        width: 72px;
        height: 29px;
        /* margin: 15px 10px -25px 15px; */
        padding: 5px 6px 15px 5px;
        border-radius: 3px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #e42c2c;
        font-family:Segoe UI;
        font-family: 14px;
      }
      .checknowbtn
      {
        left:249px !important;
        top: 1114px !important;
          margin-left: -225px !important;
      }
    }

    @media (min-width:1150px){
        html, body{
            width: 100%;
        }  
        div#navbarSupportedContent {
            max-width: 75%;
            flex: 1 0 70%;
            margin-top: 15px;
        }

      nav.navbar.navbar-expand-lg {
          flex-flow: row wrap;
          justify-content: space-between;
      }

      body .container-fluid {
          max-width: 1140px;
          margin: 0 auto;
      }

      p.arrow-mark,.middle-last {
        font-weight: 400;
        font-size: 25px;
      }


      p.arrow-mark + br, p.arrow-mark + br +br, p.arrow-mark + br +br + br,p.arrow-mark + br +br + br + br {
          display: none;
      }

      .comman_cls.middle-txt br {
          display: none;
      }

      .comman_cls.middle-txt {
          border-bottom: 1px solid #d6d3d3;
          border-bottom-style: dashed;
          padding-bottom: 40px;
          margin-bottom: 40px;
      }

      .text4 br {
          display: none;
      }

      .equifax-div br {
          display: none;
      }

      .final-layer{
        padding-bottom: 100px;
      }
      .check-now-div{
        height: 600px;
      }
      .equifax-div
      {
        font-size: 26px;
      }
      .cust-report{
        /* font-size: 25px !important;
          margin-left: 177px !important; */
        font-size: 25px !important;
        margin-left: 10px !important;
        }

        .comman_cls_1
        {
          margin-left: 0px !important;
        }
      .middle-txt{
        font-size: 30px;
        /* line-height: 66p
        /* line-height: 66px; */
        /* line-height: 35px; */
        /* font-size:28px; 28 26 */
        font-weight:400;
      }
      .arrow-mark{
        /* font-size: 23px; */
       font-size: 30px;
      }
      .middle-last{
        /* color:#273581;font-size: 22px;line-height: 35px; */
         color:#273581;line-height:33px;
      }
      .subtxt{
        font-size: 40px;
      }
      .mid-txt{
        color:#273581;
        font-size: 25px;
        line-height: 33px;
        /* line-height: 35px; */
        font-weight: 400;
      }

      .credit_img{width: 100% !important;height: auto !important;}

      .txt-size{font-size: 45px;}
      .reduce_txt{
        font-size: 30px;
        font-weight: 400;
        margin-top: 35px;
        margin-bottom: 35px;
        width:80%;
        line-height:50px;
      }
      .check_nowbtn_cls{
        margin-left: 215px !important;
      }

      .comman_cls{
        margin-left: 0px !important;
      }


      .color_bar{padding-top: 62px;}



      .right_top_menu{
        margin-right: 0px !important;
      }
      .checknowbtn{top: 1170px !important;/* margin-left: 220px !important; */margin-left: 0px !important;left: 29%;}


      .flag_div{
        padding-left:63px !important;

      }

      div.header {
        background-color: white;
        color: white;
        padding: 10px;
        font-size: 40px;
      }

      div.container {
        padding: 10px;
      }
      .fa-arrow-right
      {
        color: #273581;
        font-size: 12px;
        line-height: 45px;
       
      }

      div.polaroid {
        width: 250px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        text-align: center;
        background-color: white;
        height: 220px;
      }
      #layer2 {
        position: absolute;
        z-index: 2;
        height: 1055px;
        width: 100%;
        max-width:100% !important;
        top: 40;
      }

      #layer3 {
          position: absolute;
          z-index: 2;
          height: 350px;
          width: 340px;
          top: -50px;
      }


      .checknow
      {
        width: 273px;
        height: 57px;
        margin: 33px 242px 0 0;
        object-fit: contain;
      }

      .credit p
      {
       font-weight: 500;
      }
      .credit
      {
       font-weight: 500;
      }
      .cards {
          font-family: Segoe UI;
          font-size: 30px !important;
          text-align: center;
          margin: 0px auto 30px 0px;
          padding: 10px 0px;
          color: #273581;
          top: -20px;
          position: relative;
      }
      div h6
      {
        padding: 12px 16px 1px 10px;
      }
      .text4
      {
        font-family:Segoe UI;
      }
      .text
      {
        color:white;font-size: 30px;line-height: 30px;margin: 149px 0px 0px 0px;font-family:Segoe UI;
      }
      .text1
      {
        font-family:Segoe UI;
      }
    }

    @media (max-width:1024px)
    {
      .text3 {
        margin-top: 0px;
      }
        #layer2 {
        top: 0 !important;
        position: relative;
        left: 0 !important;
        width: 100% !important;
        margin: 10px 0px!important;
        height: auto;
      }

      .text1 {
          margin-top: 0;
          top: 0;
          width: 100%;
          height: auto;
      }

      #layer3 {
          height: auto !important;
          top: 0;
          margin-top: 0;
          width: 100%;
          left: 10px;
      }

      #layer3 br {
          display: none;
      }

      #layer3 .container-fluid h4 {
          margin:10px 0px;
      }
      div.polaroid {
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
          text-align: center;
          background-color: white;
      padding:20px 0px;
      margin:20px 0px;
      }

      img.checknow {
          margin: 20px 0px;
      }
      .checknowbtn.check_nowbtn_cls{
          height: auto !important;
          max-width: 258px !important;
          padding-left: 10px;
      }
      .checknow
      {
        width:258px !important;height:59px !important;
      }
    }


    @media only screen and (min-width:568px) and (max-width:990px)
    {

      ul .dropdown:hover > .dropdown-menu {
        display:block;
      }
      ul .dropdown > .dropdown-menu {
        display:none;
      }
          .container-fluid .row [class*='col-sm'] {
          max-width: 100%;
          flex: 1 0 100%;
      }
      .img_logo.comman_cls_1.credit_img {
          width: 100%;
      }
      .text_critical.comman_cls.middle-last {
          color: #273581;
          font-size: 25px;
      }
      .text1 br {
          display: none;
      }
      .text1 {
          padding: 40px 20px;
      }
      .text1 .comman_cls.middle-txt {
          margin: 20px 0px;
          font-size: 30px;
      }
      .text1 p {
          margin: 10px 0px;
      }

      .comman_cls.equifax-div, .comman_cls.equifax-div,.equifax-div span {
          font-size: 30px;
      }
      .container.flag_div .row {
          align-items: center;
          padding: 20px 0px;
      }
    }
    @media (max-width:567px)
    {
      .equifax-div br {
        display:none
      }
      .equifax-div
      {
          font-size:25px !important;
      }
    }

    @media (min-width:1150px)
    {
      .image-class {
        position: absolute;
        width: 350;px
        right: 0;
        height: 1200px;
      }
      .comman_cls_1.credit_img.img_logo {
          width: auto !important;
          height: 100% !important;
      }
      .credit_img {
          top: 10px !important;
      }
      .cust-report {
          text-align: right;
      }
      .checknowbtn.check_nowbtn_cls {
          left: auto;
      }
      .checknowbtn.check_nowbtn_cls {
          width: 310px !important;
          object-fit: cover;
          border-radius: 5px;
      }
      header .container-fluid {
          max-width:1140px !important;
      }
      body .container-fluid
      {
          max-width:70%;
      }
      .header #navbarSupportedContent .cards {
          margin-right: 230px;
          padding: 5px 20px;
      }
      .image-class {
          right: 15px;
      }
      .checknowbtn.check_nowbtn_cls {
          width: 304px !important;
          margin-right: 5px;
      }
    }
    @media (min-width:1150px)
    {
      .checknowbtn.check_nowbtn_cls{
          right:auto;
          left: 10px;
      }
      .equifax-div,.reduce_txt,.comman_cls.middle-txt
      {
          letter-spacing: 0.3px;
      }
      .comman_cls_1.credit_img.img_logo
      {
          right:auto;
          left:0;
      	max-height: 100%;
          width: 100% !important;
          height: auto !important;
      }
      .cust-report {
          text-align:center;
          padding:0px !important;
          margin: 0px !important;
          font-size: 20px !important;
          letter-spacing: 0.2px;
      }
      .checknowbtn.check_nowbtn_cls
      {
          width:270px !important;
          left: 12px;
      }

      .comman_cls.txt-size {
          letter-spacing: 0.2px;
      }
    }

    .header .navbar-nav li .bluebtn,.header .navbar-nav li .redbtn {
          background-color: #273581;
          border: 1px solid #273581;
          border-radius: 8px;
          padding: 4px;
          font-weight: 700;
          font-size: 16px !important;
          text-align: center;
          line-height:25px;
      }
    .header .navbar-nav li .redbtn {
        background-color: #c1191b;
        border: 1px solid #c1191b;
    }
    .header .navbar-nav li .bluebtn {
        background-color: #273581;
        border: 1px solid #273581;
    }
    .header .navbar-nav li .redbtn:hover
    {
        color:#c1191b !important;
        background-color:#fff;
        border: 1px solid #c1191b;
    }
    .header .navbar-nav li .bluebtn:hover
    {
        color:#273581 !important;
        background-color:#fff;
        border: 1px solid #273581;
    }
    @media (min-width:1150px)
    {
      .customer-report-div a {
        width: 100%;
        max-width: 100%;
        display: block;
        height: 59px;
        position: absolute;
        top: 1270px !important;
        /* background-color: #273581; */
        border-radius: 10px;
        /* left: 0px !important; */
      }  

      .checknowbtn.check_nowbtn_cls {
          position: static !important;
          width: 100% !important;
          margin-right: 0;
          object-fit: initial;
      }
    }
    @media (min-width:1150px)
    {
        .header #navbarSupportedContent .cards {
        /* font-size: 16px !important; */
    }

    .final-layer .text {
        text-align: left;
        margin-top: 0;
        height: 100%;
        display: flex;
        flex-flow: row;
        align-items: center;
    	white-space:nowrap;
    }

    .final-layer .flag_div {
        padding: 0 !important;
        max-width: 90%;
        flex: 1 0 90%;
        min-height: 200px;
    }

    .container.flag_div .row {
        max-width: 100%;
        margin: 0 auto;
        justify-content: center;
        height: 100%;
    }

    .container.flag_div .row [class*='col'] {
        max-width: 30.5%;
        flex: 1 0 30.5%;
        text-align: center;
    }

    .final-layer .flag_div .container-fluid {
        max-width: 100%;
        display: flex;
        flex-flow: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 50px 10px !important;
    }

    .container.flag_div .row [class*='col'] #layer3 {
        width: 100%;
        left: 0;
        /* min-height: 250px; */
        height: 300px;
    }

    .container.flag_div .row [class*='col'] #layer3 h4 {
        font-size: 18px !important;
        margin: 30px 0px;
    }

    .final-layer .flag_div .container-fluid br {
        display: none;
    }

    .container.flag_div .row [class*='col']:first-child {
        margin: 0;
    }

    .container.flag_div .row [class*='col']:nth-child(2) {
        margin: 0px 10px;
    }
    }

    @media (max-width:990px)
    {
      .customer-report-div .cust-report {
        text-align: center;
    }
    .comman_cls.middle-txt {
        text-align: center;
    }

    .final-layer .flag_div {
        padding: 0 10px !important;
    }

    .final-layer .flag_div .text {
        padding: 50px 0px;
        text-align: center;
        margin-top: 0;
    }

    .final-layer .flag_div .row [class*='col'] {
        padding: 0;
    }

    .final-layer .flag_div .row [class*='col'] br {
        display: none;
    }

    .final-layer .flag_div .row [class*='col'] #layer3 {
        left: 0;
    }

    .final-layer .flag_div .row {
        padding: 0;
        margin: 0;
        max-width: 100%;
        flex: 1 0 100%;
    }
    }

    @media only screen and (min-width:568px) and (max-width:1024px)
    {
    .final-layer .flag_div .row [class*='col'] {
      max-width: 32%;
      flex: 1 0 32%;
      }

    .final-layer .flag_div .row {
        justify-content: space-between;
        padding: 25px 0px;
    }
    }

    @media (max-width:990px)
    {
        header.header
    {
        background-color: #fff !important;
    }

    #navbarSupportedContent .cards {
        margin: 10px 0px;
        box-shadow: none;
        padding: 0;
        text-transform: uppercase;
        width: 100%;
        height: auto !important;
        font-size: 13px !important;
    }

    .header .navbar-nav li .bluebtn {
        color: #273581 !important;
    }

    .header .navbar-nav li .redbtn,.header .navbar-nav li .bluebtn {
        border: 0;
        box-shadow: none;
        padding: 0;
        background: none;
        margin: 0;
        font-size: 13px !important;
        width: auto;
        text-align: left;
    }

    .header .navbar-nav li .redbtn {
        color: #c1191b !important;
    }
    .header .navbar-nav li .bluebtn:hover,.header .navbar-nav li .redbtn:hover
    {
        border:none !important;
    }

    .header .navbar-nav .nav-item {
        width: 100%;
    }
    }

    .get-report{
        color:#fff;
        background-color:#273581; 
        border:1px solid #273581; 
        font-weight:700;
        text-align:center; 
        border-radius:12px; 
        padding:12px 40px 15px; 
        display:inline-block;
        line-height:1;
        font-size:18px;
    }

    @media (max-width: 991px){
        .dropbtn {
            text-transform: uppercase;
            color: #000;
            font-weight: 700;
            padding: 6px 8px 6px 0px;
            text-decoration: none;
            display: block;
            border-radius: 5%;
            width: 100%;
        }

        .dropdown-content {
            padding-left: 8px;
        }

        .dropdown-content a {
            padding: 12px 0px;
            text-align: left;
            text-decoration: none;
            display: block;
        }
        
        .dropbtn.active:after {
            content: '\25B2';
        }
        
        .dropbtn:after {
            content: '\25BC';
            color: black;
            font-weight: bold;
            float: right;
            margin-left: 5px;
        }
    }

    @media (min-width: 992px) {
        .dropbtn {
            padding: 0.6rem .5rem 0rem .5rem;
        }

        .dropdown-content {
            margin-left: 0px;
        }
    }
</style>

<header class="w-100 header position-relative" style="background-color: #f8f8f8;">
<div class="fixed-this">
<div id="main-content">
        <div class="container">
            <nav class="navbar navbar-expand-lg p-0">
                <a href="{{route('home')}}" class="navbar-brand">
                <img src="{{asset('front_new/images/team/Logo.svg')}}" class="logo comman_cls" style="width: 180px;height: 67px;" alt="Recordent">  
                
                </a>
                <button class="navbar-toggler p-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <img src="{{asset('front_new/images/menu.jpg')}}"></button>
                <div class="collapse navbar-collapse justify-content-end align-items-left" id="navbarSupportedContent">
                  <br>
                  <ul class="navbar-nav align-items-left">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('aboutus')}}" title="">About Us</a>
                        </li>
                        <li class="sepre"></li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('pricing-plan')}}" title="">Pricing</a>
                        </li>
                        <li class="sepre"></li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <p class="dropbtn">Check My Credit Report</p>
                                <div class="dropdown-content">
                                    <a href="{{route('your.reported.dues')}}">Individual Report</a>
                                    <a href="{{route('your.reported.bussinesdues')}}">Business Report</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item btn-member-log">
                            <a class="nav-link blue-btn" href="{{config('app.url')}}admin/login" title="">Member Log In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link red-btn" href="{{config('app.url')}}register" title="">Sign Up</a>
                        </li>                       
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    </div>
</header>
<body>

<div  class="customer-credit-report-div" style="background-color: #f8f8f8;">
 <div class="container">
  <div class="row">
    <div class="col-md-12"><p class="cards"><b>Check Credit & Payment History</b></p> </div>
    <div class="col-lg-7 col-md-7 col-xs-12 text4">
      <h1 class="comman_cls txt-size"><b>Taking Risk?</b></h1>

      <h2 class="comman_cls reduce_txt">Reduce it by checking your<br> customer's credit report.</h2>
       <a href="{{route('admin.credit-report')}}?credit_report_type=1" class="comman_cls"><img src="{{asset('front_new/images/team/Group 420.svg')}}" class="checknow"></a>
    </div>
    <div class="col-lg-5 col-md-5 col-xs-12 customer-report-div">
    <h4 class="text3 cust-report"><b><span>Customers Credit Report.</span></b></h4>
    <div class="image-class">
    <img src="{{asset('front_new/images/team/Group 419_2.svg')}}" id="layer2" class="img_logo comman_cls_1 credit_img">
   <a href="{{route('admin.credit-report')}}?credit_report_type=1"><img src="{{asset('front_new/images/team/Group 420.svg')}}" id="layer2" class="checknowbtn check_nowbtn_cls" style="width:258px;height:59px;"></a>
   </div>
</div>
</div>
</div>
</div>
<div id="layer1" class="color_bar" style="background-color: #f8f8f8;">
  <div class="progress">
    <div class="progress-bar " style="width:366.9px;background-color:#ff8080 !important;">
     
    </div>
    <div class="progress-bar" style="width:673px;background-color:#ff8080 !important;">
     
    </div>
    <div class="progress-bar " style="width:692px;background-color:#ffb36c ;">
     
    </div>
    <div class="progress-bar " style="width:445px;background-color:#f5d13d ;">
     
    </div>
    <div class="progress-bar" style="width:672px;background-color:#82e360">
     
    </div>
  </div>
</div>
<div style="clear:both"></div>
 <div class="container credit-report-complex-div" style="padding-top: 0px;">
  <div class="row">
      <div class="col-lg-7 col-md-7 col-xs-12 text1">
          <br><br>
          <h3  class="comman_cls middle-txt" style="font-family:Segoe UI !important;">Complex credit information<br> 
          <span class="subtxt">simplified</span >  <span >only for you. </span> 
          <br><br><br></h3>
        <h1  class="comman_cls mid-txt"><b>You can now easily find:</b></h1><br>
          <p style="color: #273581;font-size: 25px" class="arrow-mark">
          <span class="fa fa-arrow-right comman_cls " aria-hidden="true" ></span> On-time payments
          </p>
          <p style="color: #273581;font-size: 25px" class="arrow-mark">
          <span class="fa fa-arrow-right comman_cls "  aria-hidden="true"></span> Available Credit
          </p>
          <br><br><br><br>
          <p  class="text_critical comman_cls middle-last">And all critical information to make<br>better credit decision.</p>
          <br><br><br>
    </div>
	
	<div class="col-lg-7 col-md-7 col-xs-12 credit-report-text1">
          
    </div>

</div>

</div>
<div style="clear:both"></div>
    <div style="background-color:#f8f8f8;">
        <div class="container check-now-div">
            <div class="row">
                <div class="col-lg-7 col-md-7 col-xs-12 credit">
                    <br>
                    <br>
                    <br>
                    <span class="equifax-div">
                        <p style="font-family: 'Lato', sans-serif;" class="comman_cls equifax-div">This Credit Report</p>
                        <span style="color:#273581;font-family: 'Lato', sans-serif;" class="comman_cls equifax-div">provides</span>
                        <span style="font-family: 'Lato', sans-serif;" class="equifax-div"> an</span>
                        <span class="equifax-div" style="color:#273581;font-family: 'Lato', sans-serif;"> easy to understand</span>
                        <span style="font-family: 'Lato', sans-serif;" class="equifax-div">summary</span>
                        <br>
                        <span style="font-family: 'Lato', sans-serif;" class="comman_cls equifax-div">of the entire</span>
                        <span style="color:#273581;font-family: 'Lato', sans-serif;" class="equifax-div"> credit history information</span>
                        <br> 
                        <span style="font-family: 'Lato', sans-serif;" class="comman_cls equifax-div">which is powered by Equifax.</span>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </span>
                </div>
                <div class="col-sm-5 credit-text1">
                </div>
            </div>
        </div>
    </div>

<div style="background-color:#4652b3;" class="final-layer">    
    <div class="container flag_div">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-xs-12">
          <div class="polaroid"  id="layer3">
            <div class="container-fluid">
              <br><br><br>
              <img src="{{asset('front_new/images/team/india.png')}}" alt="Recordent" width="102px;" height="72px;">
              <br><br><br><br>
              <h4 style="color:#4652b3;font-family: 'Lato', sans-serif;line-height: 30px;font-size:20px;font-family: 'Lato', sans-serif;"><b>INDIVIDUAL CUSTOMER <br>CREDIT REPORT</b></h4>
              <br>
              <a href="{{route('admin.credit-report')}}?credit_report_type=1" class="get-report">Get Report</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12">
          <div class="polaroid"  id="layer3">
            <div class="container-fluid">
              <br><br><br>
              <img src="{{asset('front_new/images/team/india_b.png')}}" alt="Recordent" width="102px;" height="72px;">
              <br><br><br><br>
              <h4 style="color:#4652b3;font-family: 'Lato', sans-serif;line-height: 30px;font-size:20px;font-family: 'Lato', sans-serif;"><b>BUSINESS CUSTOMER <br>CREDIT REPORT</b></h4>
              <br>
              <a href="{{route('admin.credit-report')}}?credit_report_type=1" class="get-report">Get Report</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12"> 
          <div class="polaroid" id="layer3" style="background: linear-gradient(to bottom, #9c7fdf 0%, #463864 100%)">
            <div class="container-fluid">
              <br><br><br>
              <img src="{{asset('front_new/images/team/us.png')}}" alt="Recordent" width="106px;" height="75px;">
              <br><br><br><br>
              <h4 style="color:#ffffff;font-family: 'Lato', sans-serif;line-height: 30px;font-size:20px;font-family: 'Lato', sans-serif;"><b>US BUSINESS CUSTOMER <br>CREDIT REPORT</b></h4>
              <br>
              <a href="{{route('admin.credit-report')}}?credit_report_type=1" class="get-report">Get Report</a>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</div>
<div style="background-color:#273581;">
<footer>
    <div class="container" style="max-width:1120px !important;">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-4 col-xl-5">
                {{--<div class="join-our-mailer">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Email ID or Mobile Number" aria-label="Email ID or Mobile Number" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-info" type="button" id="button-addon2">Submit</button>
                        </div>
                    </div>
                    <p>Follow us on</p>
                </div>
                --}}
                @include('layouts_front_new/join-newsletter-email')
            </div>
            <div class="col-12 col-md-12 col-lg-8 col-xl-7">
                <div class="d-flex justify-content-between csd-flex-wrap">
                    <div class="footer-linking">
          <!--About Us section--->
                        <h3>About us</h3>
                        <ul>
                            <li><a href="{{route('aboutus')}}">About Us</a></li>
                            <li><a href="{{config('app.url')}}pricing-plan">Pricing</a></li>
                            <li><a href="{{route('aboutus')}}#our-team">Our Team</a></li>
                            <li><a href="{{route('aboutus')}}#contact-us">Contact Us</a></li>
                            <li><a href="{{route('careers')}}">Careers</a></li>
                        </ul>
                    </div>
                    <div class="footer-linking">
                        <h3>Solutions</h3>
                        <ul>
                            <li><a href="{{route('solutions')}}#report-payments">Submit Payments</a></li>
                            <li><a href="{{route('solutions')}}#messaging">Messaging</a></li>
                            <li><a href="{{route('solutions')}}#payment-options">Payment Options</a></li>
                            <li><a href="{{route('solutions')}}#payment-plans">Payment Plans</a></li>                               
                            <li><a href="{{route('solutions')}}#finance-options">Finance Options</a></li>
                            <li><a href="{{route('solutions')}}#customer-reports">Customer Reports</a></li>
                        </ul>
                    </div>
                    <div class="footer-linking">
                        <h3>Quick Links</h3>
                        <ul>
                            <li><a href="{{config('app.url')}}register">Sign Up</a></li>
                            <li><a href="{{config('app.url')}}admin/login">Member Login</a></li>
                            <li><a href="{{route('faq')}}">FAQs</a></li>
                            {{--<li><a href="javascript:void(0)">White Papers</a></li>--}}
                            <li><a href="{{route('your.reported.dues')}}">Check My Report</a></li>
                        </ul>
                    </div>
                    <div class="footer-linking">
                        <h3>Legal</h3>
                        <ul>
                            {{--<li><a href="javascript:void(0)" data-toggle="modal" data-target="#PrivacyPolicyModal">Privacy Policy</a></li>--}}
                            <li><a target="_blank" href="{{config('app.url')}}privacy-policy">Privacy Policy</a></li>
                            <li><a target="_blank" href="{{config('app.url')}}terms-and-conditions">Terms &amp; Conditions</a></li>
                            {{--<li><a href="javascript:void(0)" data-toggle="modal" data-target="#TermConditionModal">Terms &amp; Conditions</a></li>--}}
                            {{--<li><a href="javascript:void(0)">Dispute Resolution</a></li>--}}
                            <li><a href="{{route('security')}}">Data Security &amp; Privacy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
</body>
<script>
// $(function() {
//   $(document).click(function (event) {
//     $('.navbar-collapse').collapse('hide');
//   });
// })
</script>
<!-- check my report drop down  -->
<script>
    var dropdown = document.getElementsByClassName("dropbtn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{setting('site.google_analytics_tracking_id')}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{setting('site.google_analytics_tracking_id')}}');
</script>
