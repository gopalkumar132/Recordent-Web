@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' invoice')

@section('page_header')

	<h1 class="page-title">
        <i class="voyager-person"></i> <?php  if(isset($user_name)){
                                                echo $user_name;
                                                }else{
                                                    echo $user_name="";  
                                                }?> Invoice List &nbsp;
    </h1>

@stop

@section('content')
<style>
    .errors
    {
        text-align: left;
        position: relative;
        margin-left: -30%;
    }
    .page-content {
    	margin-top: 15px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
<style type="text/css">input,textarea{text-transform: uppercase};</style>
	<div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
				<table class="table table-striped">
    <thead>
      <tr>
        <th>Invoice Id</th>
        <th>Invoice Type</th>
        <th>Payment Status</th>
		<th>Download</th>
      </tr>
    </thead>
    <tbody>
	@foreach($invoices as $invoice)
                    <tr>
                        <td>{{$invoice->invoice_id}}</td>
                        <td>{{$invoice->particular}}</td>
                        <td>{{$invoice->postpaid==1?'Unpaid':'Paid'}}</td>
						<td><a href="#" data-id="{{$invoice->id}}"  class="downloadInvoice">Download</a></td>
						
                    </tr>
                        
                    @endforeach
    </tbody>
  </table>
             
            </div>
			{{ $invoices->links() }}
         </div>
    </div>
	<form action="{{url('superadmin_invoice_download')}}" method="post" id="download_invoice">
    	{{ csrf_field() }}
        <div class="invoice_list"></div>
    </form>
	<script>

$('.downloadInvoice').on('click',function(){
	var Invoice_id=$(this).data("id");
    var html="";
        html+=" <input type='hidden' name='invoice_id[]' value='"+Invoice_id+"'>"; 
    $('.invoice_list').html(html);
		if(Invoice_id){
				$('#download_invoice').submit();
			 }
            
        

    });
	</script>
@endsection