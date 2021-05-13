<head>
<meta charset="utf-8">
<title>{{setting('site.title')}}</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
<meta name="csrf-token" content="{{csrf_token()}}" />
<meta name="description" content="{{setting('site.description')}}">
<link rel="manifest" href="{{config('app.url')}}manifest.json">
<meta name="keywords" content="">
<meta name="author" content="">  
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Custom fonts for this theme -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

  <!-- Theme CSS -->
  <link href="{{asset('front/css/style.css')}}" rel="stylesheet">
</head>