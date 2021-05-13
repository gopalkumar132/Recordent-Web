<!DOCTYPE html>
<html lang="en">
<head>     
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Recordent</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{asset('landing-page/images/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('landing-page/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('landing-page/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('landing-page/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('landing-page/css/owl.theme.default.min.css')}}">     
    <link rel="stylesheet" href="{{asset('landing-page/css/style.css')}}"> 
    <!-- Pixcel code -->
    <img src="https://www.intellectadz.com/track/conversion.asp?cid=1969&conversionType=1&key=TRANSACTION_ID&opt1=&opt2=&opt3=" height="1" width="1" />
    <iframe src="https://proformics.vnative.net/pixel?adid=5f6b72bfb577894c2a4dae3f&sub1=LEADID&sub2=PHONENUMBER" scrolling="no" frameborder="0" width="1" height="1"></iframe>
	<iframe src="https://tl.tradetracker.net/?cid=33579&pid=52363&tid=LeadID&eml=email&descrMerchant=additional&descrAffiliate=LeadID" width="1" height="1" border="0" alt=""></iframe>
     <!-- Pixcel code -->
     <style>
         h1,h5{font-family: var(--font-rubik);line-height: 1.5;}
     </style>
</head>
<body>
    <header class="w-100 header position-relative">
        <div class="fixed-this">
            <div class="container">
                <nav class="navbar navbar-expand-lg p-0 justify-content-center">
                    <a href="index.html" class="navbar-brand">
                        <img src="{{asset('landing-page/images/logo.png')}}" alt="Recordent" width="320">
                    </a>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="main-slider-recordent">
            <div class="container">
                <div class="text-center">
                    <div class="row">
                        <div class="col-md-12">
                            <h1>Thank you for details.</h1>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>We will get in touch you in 48 hours.</h5>
                        </div>    
                    </div>    
                </div>               
            </div>
        </section>
               
    </main>
    
    
    <script src="{{asset('landing-page/js/jquery-3.js')}}"></script>     
    <script src="{{asset('landing-page/js/validation.js')}}"></script>   
    <script src="{{asset('landing-page/js/bootstrap.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            setTimeout(function(){
                window.location.href = "{{route('home')}}";
            },1000);
        });
    </script>
</body>
</html>