@extends('voyager::master')

@section('page_title', __('help and support').' Submit Form')

@section('page_header')
    <h1 class="page-title">       
    Submit Customer Query     
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
    @if ($message = Session::get('success'))
   <div class="alert alert-success alert-block" id="alertmessage">
    
           <strong>{{ $message }}</strong>
   </div>
   @endif
@stop
@section('content')
<style>
@media only screen and (max-width: 991px)
{
div#dataTable_filter label.error, label.error, #add_store_record label.error {
    bottom: -12px;
}
}
.page-title {
    display: inline-block;
    height: auto;
    font-size: 18px;
    height: 50px;
    margin-top: 3px;
    padding-top: 12px;
    padding-left: 35px !important;
    margin-bottom: 15px;
}
</style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<style type="text/css">input,textarea{text-transform: uppercase};</style>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                     
            <form action="{{route('admin.send-help-and-support-mail')}}"  id="helpandsupport" method="POST">
              @csrf 
              
                <div class="submitdues-mainbody">
                <div class="col-md-6">
                <div class="form-group">
                  <label for="query">Enter Your Query Here*</label>
                  <input type="text" minlength="3" id="query" maxlength="100" class="form-control" name="query">
                  <br>
                </div>
                </div>
                           
                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="descrive_query">Describe Your Query In Detail*</label>
                  <textarea class="form-control"  name="describe_query" rows="5" maxlength="500"  id="describe_query" placeholder="Due Note"></textarea>
               <br>
                </div>
                </div>
                
                <div class="col-md-6">
                <div class="form-action ">
                  <button type="submit" name="send" class="btn btn-primary btn-blue">SUBMIT</button>
                </div>
                </div>
            </div>
        </form>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>

<script>

$("#helpandsupport").validate({

rules: {
query : {
required: true
},
describe_query: {
required: true
}
}
});
$(function(){
       $('#alertmessage').delay(2000).fadeOut();
      });
</script>

@endsection