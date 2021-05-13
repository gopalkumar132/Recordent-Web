@extends('voyager::master')


@section('page_header')
<h1 class="page-title" style="display: none">
<i class="voyager-upload"></i> API Screens 
</h1>
@stop

@section('content')



<div class="nofication_module">
<h1 class="ftopleft">Templates</h1>
<button type="submit" class="btn btn-primary ftopright">Add New Template</button>
<div class="clear"></div>




<div class="reports_scheduler">


<div class="table_gird notificaion_table">
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col" >Template Description</th>
      <th scope="col">Notification Type</th>   
 
         <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr>
    <td >HARVE has submitted your overdue payment on Recordent and is overdue by < days> days. To view report, click here http://localhost:8000/check-my-report</td>
      
      <td>Email</td>
          
      <td><a href="#" class="btn btn-sm btn-primary view"><i class="voyager-edit"></i></a>
      <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a></td>
    </tr>

   
    <tr>
    <td >HARVE has submitted your overdue payment on Recordent and is overdue by < days> days. To view report, click here http://localhost:8000/check-my-report</td>
      <td>SMS
</td>
    
         
      <td><a href="#" class="btn btn-sm btn-primary view"><i class="voyager-edit"></i></a>
      <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a></td>
    </tr>

    <tr>
    <td >HARVE has submitted your overdue payment on Recordent and is overdue by < days> days. To view report, click here http://localhost:8000/check-my-report</td>
      <td>SMS
</td>
    
         
      <td><a href="#" class="btn btn-sm btn-primary view"><i class="voyager-edit"></i></a>
      <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a></td>
    </tr>

    <tr>
    <td >HARVE has submitted your overdue payment on Recordent and is overdue by < days> days. To view report, click here http://localhost:8000/check-my-report</td>
      <td>SMS
</td>
    
         
      <td><a href="#" class="btn btn-sm btn-primary view"><i class="voyager-edit"></i></a>
      <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a></td>
    </tr>


    <tr>
    <td >HARVE has submitted your overdue payment on Recordent and is overdue by < days> days. To view report, click here http://localhost:8000/check-my-report</td>
      <td>SMS
</td>
    
         
      <td><a href="#" class="btn btn-sm btn-primary view"><i class="voyager-edit"></i></a>
      <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a></td>
    </tr>


    <tr>
    <td >HARVE has submitted your overdue payment on Recordent and is overdue by < days> days. To view report, click here http://localhost:8000/check-my-report</td>
      <td>SMS
</td>
    
         
      <td><a href="#" class="btn btn-sm btn-primary view"><i class="voyager-edit"></i></a>
      <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a></td>
    </tr>

   
  </tbody>
</table>
</div>

</div>







</div>












@endsection