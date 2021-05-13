<!DOCTYPE html>
<html dir="ltr"  lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>{{env('APP_NAME')}}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="assets-path" content="js/voyager-assets">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<?php $site_favicon = Voyager::setting('site.favicon', '');?>
    @if($site_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($site_favicon) }}" type="image/png">
    @endif
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{config('app.url')}}front-ib/css/css.css" rel="stylesheet">
<link rel="stylesheet" href="{{config('app.url')}}front-ib/css/voyager-assets.css">
<link rel="stylesheet" href="{{config('app.url')}}front-ib/css/icon.css">
<link rel="stylesheet" type="text/css" href="{{config('app.url')}}front-ib/css/custom.css">
<link rel="stylesheet" type="text/css" href="{{config('app.url')}}front-ib/css/font-awesome.css">
<script src="{{config('app.url')}}front-ib/js/jquery/jquery.min.js"></script>
</head>