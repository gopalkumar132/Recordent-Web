@extends('layouts_front_ib.master')
@section('content')
<!-- BEGIN CONTENT -->
<div class="container-fluid" data-select2-id="13">

      <div class="side-body padding-top" data-select2-id="12">
        <div class="container-fluid padding-20">
          <h1 class="page-title"> <i class="voyager-person"></i> Profile </h1>
        </div>
        <div id="voyager-notifications"></div>
        
        <div class="page-content browse container-fluid" data-select2-id="11">
          @include('layouts_front_ib.error')
           @if (\Session::get('message'))
           <div class="alert alert-success">
                <span class="font-weight-semibold">{{ \Session::get('message') }}</span> 
           </div>
        @endif 
          <div class="row" data-select2-id="10">
            <div class="col-md-12" data-select2-id="9">
              @forelse($profiles as $profile)
              <div class="panel panel-bordered" data-select2-id="8">
                <div class="panel-body" data-select2-id="7">
                    <div class="pull-left"><h3>{{!empty($profile->aadhar_number) ? $profile->aadhar_number : $profile->person_name}}</h3></div>
                    <div class="pull-right"><a href="{{route('front-individual.profile-edit',$profile->id)}}">EDIT</a></div>
                </div>
              </div>
              @empty
              <div class="panel panel-bordered" data-select2-id="8">
                <div class="panel-body" data-select2-id="7"><h3 class="text-center">No record found</h3></div>
              </div>  
              @endforelse

             
            </div>
          </div>
        </div>
      </div>
    </div>
<!-- END CONTAINER --> 
 

@endsection