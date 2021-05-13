@extends('voyager::master')

@section('page_title', __('voyager::generic.create').'Credit Report')

@section('page_header')
    <h1 class="page-title">
        <!--<i class="voyager-plus"></i>US Business Credit Report        -->
    </h1>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
            </ul>
        </div>
    @endif
@stop
@section('content')
<style>
    .errors
    {
        text-align: left;
        position: relative;
        margin-left: -30%;
    }
</style>

<style type="text/css">       
        body, html {
           margin: 0px auto;
           padding: 30px 0px;
           background-color: #f9f9f9 !important;
           font-family: 'Open Sans', sans-serif;
        }
        .app-container.expanded .content-container .side-menu {
    width: 250px;
    top: 0px !important;
}


.app-container .content-container .side-menu {
    overflow-y: auto;
    z-index: 9999;
    position: fixed;
    width: 60px;
    top: 0px;
    height: 100%;
    transition: width .25s;
}


        .card {
            background-color: #fff;
            border-radius: 1px;
            overflow: hidden;
            position: relative;
            box-shadow: none !important;
            border: none !important;
        }
        .ico {
            width: 100%;
            position: absolute;
            height: 100%;
            background-color:#e95f49;
            opacity: 0.9;
            display: flex;
            justify-content: center;  
        }
        .action > div {
            width: 120px;
        }
        .action > div .btn {
            border-radius: 70px !important;
        }
        .img-fluid {
            max-width: 40%;
            margin: auto;
            display: block;
            position: relative;
        }
        .ml-3, .mx-3 {
            margin-left: 2.5rem !important;
        }
        .weight-light{
            font-size: 30px;
            font-weight: 600;
            color: #273581;
            border-bottom: solid 1px #e8e8e8;
            padding: 0px 0px 30px 0px;
            font-family: 'Open Sans', sans-serif;
        }
        .medium{
            color: #515151;
            font-size: 15px;
            font-weight: 400;
            margin-bottom: 30px;
        }
        .bold{
            font-weight: bold;
        }
        .col-container{
            display: flex;
            width: 100%;
        }
        .col{
            flex: 1;
            padding: 0px !important;
            background-image: url(image_bg.jpg);
            background-position: center center;
            background-size: cover;
        }
        .col1{
            padding: 16px;
        }      
      </style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
<style type="text/css">input,textarea{};</style>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
		<div class="row">
		
		<!--New code added at here -->
			<div class="col-md-12">
            <div class="panel panel-bordered p-0">
                <div class="panel-body col-container" style="padding:0px">
                    <div class="col-sm-4 col">
                        <div class="ico">
                            <img class="img-fluid" src="data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgd2lkdGg9IjI1MiIgaGVpZ2h0PSIyNTIiIHZpZXdCb3g9IjAgMCAyNTIgMjUyIj4NCiAgPGRlZnM+DQogICAgPGNsaXBQYXRoIGlkPSJjbGlwLXBhdGgiPg0KICAgICAgPGNpcmNsZSBpZD0iRWxsaXBzZV8yNyIgZGF0YS1uYW1lPSJFbGxpcHNlIDI3IiBjeD0iMTI2IiBjeT0iMTI2IiByPSIxMjYiIGZpbGw9IiMzMDQwOWIiLz4NCiAgICA8L2NsaXBQYXRoPg0KICAgIDxjbGlwUGF0aCBpZD0iY2xpcC1wYXRoLTIiPg0KICAgICAgPHBhdGggaWQ9IlBhdGhfODEwMyIgZGF0YS1uYW1lPSJQYXRoIDgxMDMiIGQ9Ik0xMzQuNDYyLDIwSDM5LjkxYy00LjgsMC03LjkxLDQuMTU0LTcuOTEsOC45NTZWMTg0LjNjMCw0LjgsMy4xMDksOC45NTYsNy45MSw4Ljk1NkgxNTAuMTM5YzQuOCwwLDcuOTEtNC4xNTQsNy45MS04Ljk1NlY0My45MDdaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMzIgLTIwKSIvPg0KICAgIDwvY2xpcFBhdGg+DQogICAgPGNsaXBQYXRoIGlkPSJjbGlwLXBhdGgtMyI+DQogICAgICA8cGF0aCBpZD0iUGF0aF84MTExIiBkYXRhLW5hbWU9IlBhdGggODExMSIgZD0iTTk0LjQ4NiwzMS4yNDEsOTEuNjM5LDU1LjYyOEExNy4xMzQsMTcuMTM0LDAsMCwxLDEwMi4yODIsNzUuNWwyMC4xOTMsOS42MzdBMS44ODksMS44ODksMCwwLDAsMTI1LjA5Miw4NCw0MS40MTUsNDEuNDE1LDAsMCwwLDk0LjQ4NiwzMS4yNDFaTTg4LjU1LDg4LjYxMkExNy4xNjYsMTcuMTY2LDAsMCwxLDY4LjgsNjcuODY3TDQ4LjYsNTguMjNhMS44OSwxLjg5LDAsMCwwLTIuNjE2LDEuMTQyLDQxLjQyNSw0MS40MjUsMCwwLDAsNzQsMzUuMzE5LDEuODksMS44OSwwLDAsMC0uNzU4LTIuNzUyTDk5LjA0LDgyLjNBMTcuMTI1LDE3LjEyNSwwLDAsMSw4OC41NSw4OC42MTJaTTc5LjgwNyw1NS40ODRhMTcuMSwxNy4xLDAsMCwxLDQuMzcxLS45MTRsMi41OTEtMjIuMmExLjg5MSwxLjg5MSwwLDAsMC0xLjkwNi0yLjEwOUE0MS40MTcsNDEuNDE3LDAsMCwwLDUxLjA4OSw0OC42NzhhMS44OTEsMS44OTEsMCwwLDAsLjc1OCwyLjc1NEw3Mi4wNCw2MS4wNjlBMTcuMTExLDE3LjExMSwwLDAsMSw3OS44MDcsNTUuNDg0WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTQ0LjEwNCAtMzAuMjY0KSIvPg0KICAgIDwvY2xpcFBhdGg+DQogIDwvZGVmcz4NCiAgPGcgaWQ9Ikdyb3VwXzY5NjQiIGRhdGEtbmFtZT0iR3JvdXAgNjk2NCI+DQogICAgPHBhdGggaWQ9IlBhdGhfODExMiIgZGF0YS1uYW1lPSJQYXRoIDgxMTIiIGQ9Ik0xMjYsMEExMjYsMTI2LDAsMSwxLDAsMTI2LDEyNiwxMjYsMCwwLDEsMTI2LDBaIiBmaWxsPSIjMjczNTgxIi8+DQogICAgPGcgaWQ9Ikdyb3VwXzY5NTgiIGRhdGEtbmFtZT0iR3JvdXAgNjk1OCI+DQogICAgICA8ZyBpZD0iR3JvdXBfNjk1NyIgZGF0YS1uYW1lPSJHcm91cCA2OTU3IiBjbGlwLXBhdGg9InVybCgjY2xpcC1wYXRoKSI+DQogICAgICAgIDxwYXRoIGlkPSJQYXRoXzgxMDAiIGRhdGEtbmFtZT0iUGF0aCA4MTAwIiBkPSJNMTUxLjkyMSwyOS42NzcsMjE5LjcsOTcuMjV2MTI2SDc0LjcyMkwzMy4zNDEsMTgxLjcyMloiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDMyLjI5OSAyOC43NSkiIGZpbGw9IiMzMDQwOWIiLz4NCiAgICAgIDwvZz4NCiAgICA8L2c+DQogICAgPHBhdGggaWQ9IlBhdGhfODEwMSIgZGF0YS1uYW1lPSJQYXRoIDgxMDEiIGQ9Ik0xMzQuNDYyLDIwSDM5LjkxYy00LjgsMC03LjkxLDQuMTU0LTcuOTEsOC45NTZWMTg0LjNjMCw0LjgsMy4xMDksOC45NTYsNy45MSw4Ljk1NkgxNTAuMTM5YzQuOCwwLDcuOTEtNC4xNTQsNy45MS04Ljk1NlY0My45MDdaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgzMSAxOS4zNzUpIiBmaWxsPSIjZjFmMWYxIi8+DQogICAgPGcgaWQ9Ikdyb3VwXzY5NjAiIGRhdGEtbmFtZT0iR3JvdXAgNjk2MCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNjMgMzkuMzc1KSI+DQogICAgICA8ZyBpZD0iR3JvdXBfNjk1OSIgZGF0YS1uYW1lPSJHcm91cCA2OTU5IiBjbGlwLXBhdGg9InVybCgjY2xpcC1wYXRoLTIpIj4NCiAgICAgICAgPHBhdGggaWQ9IlBhdGhfODEwMiIgZGF0YS1uYW1lPSJQYXRoIDgxMDIiIGQ9Ik01OC42NzYsOTkuMTE5LDg3LjQsMTI3LjUxbC00NC40MzEsMi4wNTcsMTMuNCwxMy42MS0xMy40LDIuMTQsMTIuNDE1LDEyLjczNC0xMi40MTUsMy4wMTYsMTkuOCwyMC4xMTdoODcuOTE4VjExNS4wMzhsLTEuMi00Mi42MjdMMTE3Ljk1Nyw0MC4zMjZsLTE5Ljg2MywxNC4xTDgxLjc4MSwzNS4xMDdaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjEuMzcyIC01LjM2NSkiIGZpbGw9IiNkZGUxZjEiLz4NCiAgICAgIDwvZz4NCiAgICA8L2c+DQogICAgPHBhdGggaWQ9IlBhdGhfODEwNCIgZGF0YS1uYW1lPSJQYXRoIDgxMDQiIGQ9Ik05Mi4yLDQzLjlsMTUuNDMzLjAxTDg0LjA0NCwyMFYzNS42NzFBNy44ODQsNy44ODQsMCwwLDAsOTIuMiw0My45WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoODEuNDE4IDE5LjM3NSkiIGZpbGw9IiM0ZDVmYzUiLz4NCiAgICA8cGF0aCBpZD0iUGF0aF84MTA1IiBkYXRhLW5hbWU9IlBhdGggODEwNSIgZD0iTTEyNC4yLDg2LjQwNkg0NS40NTNhMi45NTMsMi45NTMsMCwwLDEsMC01LjkwNkgxMjQuMmEyLjk1MywyLjk1MywwLDAsMSwwLDUuOTA2WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNDEuMTcyIDc3Ljk4NCkiIGZpbGw9IiM0OTUyNjAiLz4NCiAgICA8cGF0aCBpZD0iUGF0aF84MTA2IiBkYXRhLW5hbWU9IlBhdGggODEwNiIgZD0iTTEyNC4yLDk0LjQwNkg0NS40NTNhMi45NTMsMi45NTMsMCwwLDEsMC01LjkwNkgxMjQuMmEyLjk1MywyLjk1MywwLDAsMSwwLDUuOTA2WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNDEuMTcyIDg1LjczNCkiIGZpbGw9IiM0OTUyNjAiLz4NCiAgICA8cGF0aCBpZD0iUGF0aF84MTA3IiBkYXRhLW5hbWU9IlBhdGggODEwNyIgZD0iTTEyNC4yLDEwMi40MDZINDUuNDUzYTIuOTUzLDIuOTUzLDAsMCwxLDAtNS45MDZIMTI0LjJhMi45NTMsMi45NTMsMCwwLDEsMCw1LjkwNloiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDQxLjE3MiA5My40ODQpIiBmaWxsPSIjNDk1MjYwIi8+DQogICAgPGcgaWQ9Ikdyb3VwXzY5NjMiIGRhdGEtbmFtZT0iR3JvdXAgNjk2MyIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoODYuODMgNTkuNTgyKSI+DQogICAgICA8cGF0aCBpZD0iUGF0aF84MTA4IiBkYXRhLW5hbWU9IlBhdGggODEwOCIgZD0iTTcwLjg0NiwzMi42NjdsLTIuNiwyMi4yNTNBMTcuMTM0LDE3LjEzNCwwLDAsMSw3OC44OTIsNzQuOGwyMC4xOTMsOS42MzdhMS44ODksMS44ODksMCwwLDAsMi42MTYtMS4xNDRBNDEuNDEzLDQxLjQxMywwLDAsMCw3My4yMjQsMzEuMDY0LDEuODg4LDEuODg4LDAsMCwwLDcwLjg0NiwzMi42NjdaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjAuNzE0IC0yOS41NTYpIiBmaWxsPSIjMGU5Y2Q5Ii8+DQogICAgICA8cGF0aCBpZD0iUGF0aF84MTA5IiBkYXRhLW5hbWU9IlBhdGggODEwOSIgZD0iTTg4LjU1LDc0Ljk0MUExNy4xNjYsMTcuMTY2LDAsMCwxLDY4LjgsNTQuMkw0OC42LDQ0LjU2QTEuODksMS44OSwwLDAsMCw0NS45ODgsNDUuN2E0MS40MjUsNDEuNDI1LDAsMCwwLDc0LDM1LjMxOSwxLjg5LDEuODksMCwwLDAtLjc1OC0yLjc1Mkw5OS4wNCw2OC42M0ExNy4xMjUsMTcuMTI1LDAsMCwxLDg4LjU1LDc0Ljk0MVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC00NC4xMDQgLTE2LjU5MykiIGZpbGw9IiNlOTUwMzciLz4NCiAgICAgIDxwYXRoIGlkPSJQYXRoXzgxMTAiIGRhdGEtbmFtZT0iUGF0aCA4MTEwIiBkPSJNNzYuNTI2LDU1LjQ4NEExNy4xLDE3LjEsMCwwLDEsODAuOSw1NC41N2wyLjU5MS0yMi4yYTEuODkxLDEuODkxLDAsMCwwLTEuOTA2LTIuMTA5QTQxLjQxNyw0MS40MTcsMCwwLDAsNDcuODA4LDQ4LjY3OGExLjg5MSwxLjg5MSwwLDAsMCwuNzU4LDIuNzU0TDY4Ljc2LDYxLjA2OUExNy4xMTEsMTcuMTExLDAsMCwxLDc2LjUyNiw1NS40ODRaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtNDAuODI0IC0zMC4yNjQpIiBmaWxsPSIjNjliMzJkIi8+DQogICAgICA8ZyBpZD0iR3JvdXBfNjk2MiIgZGF0YS1uYW1lPSJHcm91cCA2OTYyIj4NCiAgICAgICAgPGcgaWQ9Ikdyb3VwXzY5NjEiIGRhdGEtbmFtZT0iR3JvdXAgNjk2MSIgY2xpcC1wYXRoPSJ1cmwoI2NsaXAtcGF0aC0zKSI+DQogICAgICAgICAgPGNpcmNsZSBpZD0iRWxsaXBzZV8yOCIgZGF0YS1uYW1lPSJFbGxpcHNlIDI4IiBjeD0iMjQuNjIzIiBjeT0iMjQuNjIzIiByPSIyNC42MjMiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE2LjgxMyAxNi44KSIgZmlsbD0iI2ZmZiIgb3BhY2l0eT0iMC40Ii8+DQogICAgICAgIDwvZz4NCiAgICAgIDwvZz4NCiAgICA8L2c+DQogIDwvZz4NCjwvc3ZnPg0K" />
                        </div>
                    </div>
					<?php
						$consent_id = "";
						if(!empty(Session::get('consent_request_us_id'))){
							$consent_id = Session::get('consent_request_us_id');
						}										
					?>
                    <div class="col-sm-8 col1">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between">
                                <div class="content d-flex align-items-center justify-content-between">
                                    <div class="main" style="text-align: justify;">
                                        <h1 class="weight-light h3">US Business Credit Report</h1>
                                        @if(Auth::user()->reports_us_business != 1)
                                            <p class="mb-1 medium">Thankyou for the payment of  &nbsp;<span class="bold">&#8377;{{$consent_payment_value}}</span> towards Business Credit Report of  <span class="bold"><?php if(!empty($business_name)){ echo $business_name; }?></span>.</p>
                                        @endif
                                        <p class="mb-1 medium">Unfortunately, we couldn't find a credit report for  <span class="bold"><?php if(!empty($business_name)){ echo $business_name; }?></span>.</p>
                                        @if(Auth::user()->reports_us_business != 1)
                                            <p class="mb-1 medium">We have initiated a refund of  &nbsp;<span class="bold">&#8377;{{$consent_payment_value}}</span> and will be credited with 7 working days.</p>
                                        @endif
                                        <p class="mb-1 medium"><u><a href="{{route('admin.credit-report')}}" style="font-size:18px;">Click here</a></u> to search for Business report for another US partner.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!--end of new code at here -->
	</div>	
</div>
@endsection