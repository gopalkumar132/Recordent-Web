@extends('voyager::master')

@section('page_header')
@stop
@section('content')
        <section class="membership-plans plans-price bg-white">
            <div class="container">
                <div class="the-title text-center">
                    <h2 class="text-uppercase">Invoices</h2>
                    <div class="container box">
                        
                        <form class="col-md-12" action="" method="get" id="filter_form">
                            
                            <div class="col-md-2">
                                <label>Status</label>
                                <select name="status" class="form-control">                                    
                                    <option value=1 {{isset($_GET['status'])&&$_GET['status']==1?'selected':''}}>UnPaid</option>
                                    <option value=0 {{isset($_GET['status'])&&$_GET['status']==0?'selected':''}}>Paid</option>
                                    <option value="2" {{isset($_GET['status'])&&$_GET['status']==2?'selected':''}}>All</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Invoice Type</label>
                                <select name="invoice_type" class="form-control">
                                    
                                   <option value="0" {{isset($_GET['invoice_type'])&&$_GET['invoice_type']==0?'selected':''}}>ALL</option>
                                   @foreach($invoice_types as $invoice_type)
                                   <option value="{{$invoice_type->id}}" {{isset($_GET['invoice_type'])&&$_GET['invoice_type']==$invoice_type->id?'selected':''}}>{{$invoice_type->title}}</option>
                                   @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Limit</label>
                                <select name="limit" class="form-control">
                                    <option value="10" {{isset($_GET['limit'])&&$_GET['limit']==10?'selected':''}}>10</option>
                                    <option value=25 {{isset($_GET['limit'])&&$_GET['limit']==25?'selected':''}}>25</option>
                                    <option value=50 {{isset($_GET['limit'])&&$_GET['limit']==50?'selected':''}}>50</option>
                                </select>
                            </div>
                            <div class="col-md-5 ">
                                <div class="col-md-3">
                                    <label></label>
                                    <button type="button" name="get" id="filter" class="form-control btn btn-success">Search</button>
                                </div>

                                <div class="col-md-3">
                                    <label></label>                    
                                    <button type="button" id="payment" class="form-control btn btn-success pay-multiple">Pay</button>
                                </div>
                                <div class="col-md-6">
                                    <label></label>                        
                                    <button type="button" id="download" class="form-control btn btn-info">Download Pdf</button>
                                </div>
                                
                            </div>
                            
                            
                            
                        </form>
                        <form action="{{url('multiple_invoice_download')}}" method="post" id="download_invoices">
                            {{ csrf_field() }}
                            <div class="invoice_list"></div>
                        </form>
                    </div>
                    <div class="col-md-12 text-center" id="error-block"></div>
                    
                    
                </div>
                <div class="table-responsive">
                    
                
                <table class="table table-cell membership-table">
                    <tr>
                        <th></th>
                        <th>Invoice Id</th>
                        <th>Invoice Type</th>
                        <th>Amount</th>
                        <th>Gst</th>
                        <th>Payment Status</th>                        
                        <th>Pay</th>
                    </tr>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td><input type="checkbox" class="invoice_checkbox" name="invoice" value="{{$invoice->id}}"></td>
                        <td><a href="{{url('invoice')}}/{{$invoice->id}}"  target="_blank">{{$invoice->invoice_id}}</a></td>
                        <td>{{$invoice->particular}}</td>
                        <td>₹ {{$invoice->payment_value}}</td>
                        <td>₹ {{$invoice->gst_value}}</td>
                        <td>{{$invoice->postpaid==1?'Unpaid':'Paid'}}</td>
                        <td>
                            <button name="pay" type="button" value="pay" id="invoice_{{$invoice->id}}" class="btn btn-success pay-individual {{$invoice->postpaid==0?'hidden':''}}" data-particular="{{$invoice->particular}}" data-invoice_id="{{$invoice->id}}" data-amount="{{$invoice->total_collection_value}}" data-status="{{$invoice->postpaid}}">Pay</button>
                        </td>
                    </tr>
                        
                    @endforeach
                </table>
            </div>
                {{ $invoices->links() }}
    
            </div>

                
        </section>  
        <div id="select_plan" class="popup-wrap">
          <div class="popup-overlay"></div>
           <div class="extra-wrap">
            <div class="extra-inner">
                <div class="popup-outer">
                <div class="popup-box">
                    <form action="{{url('multiple_invoice_payment')}}" method="post">
                        {{ csrf_field() }}
                <header class="popup-header"> <a class="popup-close" href="javascript:void(0);">×</a>
                <h4 class="text-left">Payment for <span class="plan_name">PREMIUM</span> Membership Plan</h4>
                </header>
                <div class="popup-scroll">
                <div class="popup-body text-left">
                    
                    <div class="form-group hidden">No Payment Method Required</div>
                    <input type="hidden" name="plan_id_val" id="plan_id_val" value=1>
                    <div class="form-group hidden">
                        <label>
                        <input type="radio" name="payment_method" class="payment_method" value="paytm" checked>
                        Paytm    
                        </label>
                        
                    </div>
                    <div class="">
                        
                    </div>
                    
                    
                        <div class="clearfix">
        

                            <table class="col-md-12 col-sm-12 col-xs-12 checkout_table">
                                <thead>
                                    <tr>
                                        <th><b>Details</b></th>
                                        <th><b class="amount">Amount</b></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    
                                </tbody>
                                <tfoot>
                                     <tr>
                                    <td>Total</td>
                                    <td><span class="total_price">₹720.95</span></td>
                                </tr>
                                </tfoot>
                               
                            </table>
                        </div>
                </div>

                </div>
                <footer class="popup-footer">
                    <div class="pull-right">
                        <input type="submit" name="pay_now" class="btn btn-success" value="Pay">
                    </div>
                    <div class="clearfix"></div>
                </footer>
                </form>
                </div>
                </div>
               </div>
              </div>
            </div>
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('front_new/css/style.css')}}">  
<style type="text/css">
    .app-footer{
        display: none;
    }
    a[disabled="disabled"] {
        pointer-events: none;
    }
    .open-popup { font-family: 'Roboto', sans-serif; }
    .popup-wrap {
        font-family: 'Roboto', sans-serif;
        display: none;
        height: 100%;
        left: 0;
        line-height: 1.5;
        margin: 0;
        outline: 0 none;
        padding: 0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 9;
    }
    .popup-outer {
        box-sizing: border-box;
        margin: 0 auto;
        max-width: 450px;
        padding: 30px 15px;
        position: relative;
        width: 100%;
        z-index: 2;
    }
    #contact_form_popup .popup-outer{
        max-width :900px;
    }
    .popup-box {
        background-color: #fff;
        border: 1px solid #ddd;
        border: 1px solid hsla(0, 0%, 0%, 0.1);
        border-radius: 4px;
        box-shadow: 0 3px 9px rgba(0, 0%, 0%, 0.5);
        background-clip: padding-box;
        width: 100%;
    }
    .popup-box .popup-header {
        border-bottom: 1px solid #ddd;
        padding: 15px 20px;
        position: relative;
    }
    .popup-box .popup-header h3 {
        padding-right: 15px;
    }
    .popup-close {
        color: #bbb;
        display: inline-block;
        font-size: 24px;
        position: absolute;
        right: 15px;
        text-decoration: none;
        top: 10px;
        transition: color 1s ease 0s;
    }
    .popup-close:hover {
        color: #222;
    }
    .popup-scroll {
        max-height: 400px;
        overflow: auto;
        position: relative;
    }
    .popup-box .popup-body {
        padding: 20px;
    }
    .popup-box .popup-footer {
        background-color: #f8f8f8;
        background-color: hsla(0, 0%, 0%, 0.02);
        border-top: 1px solid #ddd;
        padding: 1em;
    }
    .popup-overlay {
        background-color: #000;
        background-color: hsla(0, 0%, 0%, 0.3);
        filter:alpha(opacity=70);
        height: 100%;
        left: 0;
        opacity: 0.7;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1;
    }
    .popup-box p {
        margin-bottom: 25px;
    }
    .popup-box p:last-child {
        margin-bottom: 0;
    }
    .extra-wrap {
        display: table;
        height: 100%;
        width: 100%;
    }
    .extra-inner {
        display: table-cell;
        vertical-align: middle;
    }
</style>
@endsection
@section('javascript')
<script type="text/javascript">
    
    $('#download').on('click',function(){
        var arrNumber = new Array();
        var sList = "";
        var html="";
        var count=0;
        $('#error-block').html('');
       $('input[type=checkbox]').each(function () {
            // sList += "(" + $(this).val() + "-" + (this.checked ? "checked" : "not checked") + ")";
            count++;
            if(this.checked){
                var pay_id="invoice_"+$(this).val();
                html+=" <input type='hidden' name='invoice_id[]' value='"+$("#"+pay_id).data('invoice_id')+"'>";
            }
        });
        $('.invoice_list').html(html);
        if(html!=''){
            $('#download_invoices').submit();
        }
        else{

             if(count==0){
                $('#error-block').html('');
            }else
                $('#error-block').html('<div class="alert-danger">Select atleast one invoice to download</div>');
        }

        // console.log(arrNumber);
    });
    $('body').on('click','.pay-individual', function(e) {
       e.preventDefault();
       var total_price=0,html='';
       $('#error-block').html('');
       $('.checkout_table tbody').html('');
       if($(this).data('status')==1){
           html="<tr><td>"+$(this).data('particular')+"</td><td>₹"+$(this).data('amount')+" <input type='hidden' name='invoice_id[]' value='"+$(this).data('invoice_id')+"'></td></tr>";
           total_price+=$(this).data('amount');
       }
       $('.checkout_table tbody').html(html);
       $('.checkout_table tfoot span.total_price').text('₹'+total_price);
       if(total_price>0)
         $('#select_plan').fadeIn();
       else
            $('#error-block').html('<span class="alert-danger">Please select atleast one unpaid invoice to pay</span>');
     });
     $('body').on('click','.pay-multiple', function(e) {
       e.preventDefault();
       var total_price=0;
       $('.checkout_table tbody').html('');
       $('#error-block').html('');
       var html="";
       $('input[type=checkbox]').each(function () {
            // sList += "(" + $(this).val() + "-" + (this.checked ? "checked" : "not checked") + ")";
            if(this.checked){
                var pay_id="invoice_"+$(this).val();
                if($("#"+pay_id).data('status')==1){
                    html+="<tr><td>"+$("#"+pay_id).data('particular')+"</td><td>₹"+$("#"+pay_id).data('amount')+" <input type='hidden' name='invoice_id[]' value='"+$("#"+pay_id).data('invoice_id')+"'></td></tr>";
                    total_price+=$("#"+pay_id).data('amount');
                }
                
            }
        });

       
       $('.checkout_table tbody').html(html);
       // console.log( $('.checkout_table tfoot span.total_price').html());
       $('.checkout_table tfoot span.total_price').text('₹'+total_price);
       if(total_price>0)
         $('#select_plan').fadeIn();
       else
            $('#error-block').html('<span class="alert-danger">Please select atleast one unpaid invoice to pay</span>');
       // console.log(total_price);
     });
     $('#filter').on('click',function(){
        console.log('test');
        $('#filter_form').submit();
      });
     $('.popup-close').on('click', function() {
      $(this).closest('.popup-wrap').fadeOut();
      $(".payment_method").prop("checked", false);
     });
</script>

@endsection 