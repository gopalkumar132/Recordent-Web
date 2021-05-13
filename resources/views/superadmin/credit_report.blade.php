@extends('voyager::master')

@section('page_header')

	<h1 class="page-title">
        <i class="icon voyager-file-text"></i> Customer Reports &nbsp;
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
        <div class="" style="margin-top: -28px;">
                
                <div class="pull" style="margin-left: 35px;">
                     <form action="{{route('customer-credit-report-analysis-filter')}}">
                     From:
                     &nbsp
                    <input type="date" name="fromdate" id="fromdate" value="<?php if(isset($fdate)){echo $fdate;}else{$fdate="";}?>">
                    <span id="error"></span>
                     To:
                     &nbsp
                    <input type="date" name="todate" id="todate" value="<?php if(isset($tdate)){echo $tdate;}else{$tdate="";}?>">
                     <button type="submit" class="btn btn-info btn-blue"  aria-controls="dataTable">Search
                    </button>
                    </form> 
                 </div>
                 <div class="pull-left" style="    margin-left: 520px; margin-top: -45px;">
                    <button class="btn btn-info  btn-red reset_btn" aria-controls="dataTable">Reset
                    </button>
                </div>
                <div class="pull-right" style="margin-top: -44px;">
            <form action="{{route('superadmin.download-creditreport-records')}}">
            {{ csrf_field() }}
                <button class="btn btn-info download-mem-data btn-blue" id="downloadbtn">Credit Reports Records <i class="voyager-download"></i></button>
                 <input type="hidden" id="from_date" name="from_date" value="" >
                 <input type="hidden" id="to_date" name="to_date" value="" >
                </form> 
    </div>
         </div>
    </div>

         <!-- <br><br> -->
    

    <div class="row">
            <div class="col-md-5" style="bottom: -47px;">
                <div class="panel panel-bordered" style="padding-bottom:5px;height: 150px;padding: 76px;    width: 79%; background: #c1191b;right: -159px;">
                <div class="row">
                   <div style="text-align:center;margin-top: -50px;font-size:17px;font-weight:800;color: white;"> Total number of individual reports viewed on  <?php  

            $date = Carbon\Carbon::now();
            $formatedDate = $date->format('Y-m-d');
            $originalDate = $formatedDate;
            if($flag==1){
                echo $newDate = date("d-m-Y", strtotime($originalDate));
            }?>
            </div>
             <div style="text-align:center;font-size:27px;font-weight:800;color: white;">
                <?php 
                echo  "" .$total_viewed_individual;
           ?></div>
                </div>
             </div>
            </div>
          <!--   <div class="col-md-2">
            </div> -->
            <div class="col-md-5" style="float: right;">
                <div class="panel panel-bordered" style="padding-bottom:5px;height: 150px;padding: 76px;    width: 79%;    background: #273581;bottom:-47px;left: -104px;">
                    <div class="" style="text-align:center;margin-top: -50px;font-size:17px;font-weight:800;color: white;">Total number of business reports viewed on  
                    
                    <?php 
                    $date = Carbon\Carbon::now();
                    $formatedDate = $date->format('Y-m-d');
                    $originalDate = $formatedDate;

                    if($flag==1){
                        echo $newDate = date("d-m-Y", strtotime($originalDate));
                    }?>
                </div>
                <div style="text-align:center;font-size:27px;font-weight:800;color: white;">
                <?php 
                    
                        echo " " .$total_viewed_business;
                    

                  ?></div> 
             </div>
            </div>
         </div>
    </div>

    <div class="col-md-12" style="padding-top:100px;display:none;">
            <div class="col-md-12 md-footer">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                            <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Type of Customer</th>
                                <th>Date</th>
                                <th>Number of  Views</th>
                                <th>Number of Customers Report Views</th>
                                <th>total Number of Customers viewed</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($credit_reportrecords as $data)
                                <tr>
                                    <td><?php echo $data['srno'] ?></td>
                                    <td><?php echo $data['type'] ?></td>
                                    <td><?php echo $data['date'] ?></td>
                                    <td><?php echo $data['count'] ?></td>
                                    <td><?php echo $data['no_of_view_count'] ?></td>
                                    <td><?php echo $data['total_customers_count'] ?></td>
                                    
                                </tr>
                                @empty
                                <tr><td colspan="10" align="center">No Record Found</td></tr>
                                   
                                @endforelse
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-md-12" style="padding-top:100px;">
            <div class="col-md-12 md-footer">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                            <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Type of Customer</th>
                                <th>Date</th>
                                <th>Number of  Views</th>
                                <th>Total Number of Customers viewed</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            @forelse($credit_reportrecords_latest as $data)
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $data['type'] ?></td>
                                    <td><?php echo $data['viewedDate'] ?></td>
                                    <td><?php echo $data['count'] ?></td>
                                    <td><?php echo $data['total_customers_count'] ?></td>
                                </tr>
                                @empty
                                <tr><td colspan="10" align="center">No Record Found</td></tr>
                                   
                                @endforelse
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>
           <script>

    $("#fromdate").on("change",function(){
   var from_date =$("#fromdate").val();
   $("#from_date").val(from_date);
});
$("#todate").on("change",function(){
   var to_date =$("#todate").val();
   $("#to_date").val(to_date);
   
});

$(".reset_btn").on('click',function(){
    $("#fromdate").val("");
    $("#todate").val("");
});
$("#downloadbtn").on("click",function(){

    var from_date =$("#fromdate").val();
    $("#error").html('');
    if(from_date == "")
    {
        // $("#fromdate").prop('required',true);
        return true;
    }else{
        $("#fromdate").prop('required',false);
        return true;
    }
});
           </script>
@endsection