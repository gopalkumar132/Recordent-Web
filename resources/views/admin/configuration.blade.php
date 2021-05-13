@extends('voyager::master')


@section('page_header')
<h1 class="page-title" style="display: none">
<i class="voyager-upload"></i> API Screens 
</h1>
@stop

@section('content')



<div class="nofication_module">
<h1>Configuration</h1>

<div class="config_block">

<div class="block">
<h3>SMS</h3>

<form>
<div class="row">

<div class="form-group col-md-3">
  <label for="sms_01">Gateway</label>
  <input type="text" id="sms_01" class="form-control"   placeholder="Gateway">  
</div>


<div class="form-group col-md-3">
  <label for="sms_02">API</label>
  <input type="text" id="sms_02" class="form-control"   placeholder="API">  
</div>

<div class="form-group col-md-3">
  <label for="sms_03">Username</label>
  <input type="text" id="sms_03" class="form-control"   placeholder="Username">  
</div>

<div class="form-group col-md-3">
  <label for="sms_04">Password</label>
  <input type="password" id="sms_04" class="form-control"   placeholder="Password">  
</div>



<div class="form-group col-md-3">
  <label for="sms_05">Token key</label>
  <input type="text" id="sms_05" class="form-control"   placeholder="Token key">  
</div>

<div class="form-group col-md-3">
  <label for="sms_06">Account Type</label>
  <input type="text" id="sms_06" class="form-control"   placeholder="Account Type">  
</div>

<div class="form-group col-md-3">
  <label for="sms_07">Sender Id</label>
  <input type="text" id="sms_07" class="form-control"   placeholder="Sender Id">  
</div>


<div class="form-group col-md-12">
<button type="submit" class="btn btn-primary">Configure</button>
</div>

</div>
</form>


</div>

<div class="block">
<h3>IVR</h3>

<form>
<div class="row">


<div class="form-group col-md-3">
  <label for="ivr_01">MP3 file</label>
  <input type="file" id="ivr_01" class="form-control"   placeholder="MP3 file">  
</div>



<div class="form-group col-md-3">
  <label for="ivr_02">Gateway</label>
  <input type="text" id="ivr_02" class="form-control"   placeholder="Gateway">  
</div>

<div class="form-group col-md-3">
  <label for="ivr_03">API</label>
  <input type="text" id="ivr_03" class="form-control"   placeholder="API">  
</div>

<div class="form-group col-md-3">
  <label for="ivr_04">Username</label>
  <input type="text" id="ivr_04" class="form-control"   placeholder="Password">  
</div>

<div class="form-group col-md-3">
  <label for="ivr_05">Password</label>
  <input type="password" id="ivr_05" class="form-control"   placeholder="Password">  
</div>



<div class="form-group col-md-3">
  <label for="ivr_06">Token key</label>
  <input type="text" id="ivr_06" class="form-control"   placeholder="Token key">  
</div>

<div class="form-group col-md-3">
  <label for="ivr_07">Account Type</label>
  <input type="text" id="ivr_07" class="form-control"   placeholder="Account Type">  
</div>

<div class="form-group col-md-3">
  <label for="ivr_08">Sender Id</label>
  <input type="text" id="ivr_08" class="form-control"   placeholder="Sender Id">  
</div>


<div class="form-group col-md-12">
<button type="submit" class="btn btn-primary">Configure</button>
</div>

</div>
</form>

</div>

<div class="block">
<h3>Email</h3>

<form>
<div class="row">


<div class="form-group col-md-3">
  <label for="email_01">Port</label>
  <input type="text" id="email_01" class="form-control"   placeholder="Port">  
</div>



<div class="form-group col-md-3">
  <label for="email_02">Gateway</label>
  <input type="text" id="email_02" class="form-control"   placeholder="Gateway">  
</div>

<div class="form-group col-md-3">
  <label for="email_03">API</label>
  <input type="text" id="email_03" class="form-control"   placeholder="API">  
</div>

<div class="form-group col-md-3">
  <label for="email_04">Username</label>
  <input type="text" id="email_04" class="form-control"   placeholder="Username">  
</div>

<div class="form-group col-md-3">
  <label for="email_05">Password</label>
  <input type="password" id="email_05" class="form-control"   placeholder="Password">  
</div>



<div class="form-group col-md-3">
  <label for="email_06">Token key</label>
  <input type="text" id="email_06" class="form-control"   placeholder="Token key">  
</div>

<div class="form-group col-md-3">
  <label for="email_07">Account Type</label>
  <input type="text" id="email_07" class="form-control"   placeholder="Account Type">  
</div>

<div class="form-group col-md-3">
  <label for="email_08">Sender Id</label>
  <input type="text" id="email_08" class="form-control"   placeholder="Sender Id">  
</div>


<div class="form-group col-md-12">
<button type="submit" class="btn btn-primary">Configure</button>
</div>

</div>
</form>

</div>

</div>









</div>










@endsection