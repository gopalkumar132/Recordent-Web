@extends('voyager::master')


@section('page_header')
<h1 class="page-title" style="display: none">
<i class="voyager-upload"></i> API Screens 
</h1>
@stop

@section('content')






<div class="nofication_module">
<h1>Scheduler</h1>
<div class="sms_scheduler">

<div class="radio_section">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
  <label class="form-check-label" for="inlineRadio1">SMS</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
  <label class="form-check-label" for="inlineRadio2">IVR</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3">
  <label class="form-check-label" for="inlineRadio3">Email</label>
</div>
</div>

<div class="table_gird notificaion_table">
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Time</th>
      <th scope="col">Subject</th>
      <th scope="col">Status</th>
      <th scope="col">Body</th>      
      <th scope="col">Template name</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr>
    <td>12-05-2010</td>
      <td>06:40</td>
      <td>This is the test subject </td>
      <td>Pending</td>
      <td>@mdo</td>
      <td>Template 01</td>          
      <td><a href="#" class="btn btn-sm btn-primary view"><i class="voyager-edit"></i></a>
      <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a></td>
    </tr>

    <tr>
    <td>12-05-2010</td>
      <td>06:40</td>
      <td>This is the test subject </td>
      <td>Pending</td>
      <td>@mdo</td>
      <td>Template 01</td>          
      <td><a href="#" class="btn btn-sm btn-primary view"><i class="voyager-edit"></i></a>
      <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a></td>
    </tr>


    <tr>
    <td>12-05-2010</td>
      <td>06:40</td>
      <td>This is the test subject </td>
      <td>Pending</td>
      <td>@mdo</td>
      <td>Template 01</td>          
      <td><a href="#" class="btn btn-sm btn-primary view"><i class="voyager-edit"></i></a>
      <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a></td>
    </tr>
   
  </tbody>
</table>
</div>

</div>


</div>




@endsection