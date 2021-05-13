@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Notifications')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}Notifications
    </h1>
    <ul class="name_title">
        <li>
            
            <a href="#" class="btn btn-sm btn-primary view"><i class="voyager-eye"></i> = View</a>
            
        </li> 
    </ul>
   
    
@stop

@section('content')

    <div class="page-content container-fluid">
        @include('voyager::alerts')
        
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                         <table id="dataTable" class="table table-hover fixed_headerss">
                            <thead>
                            <tr>
                                <th style="text-align:left">Title</th>
                                <th>For</th>
                                <th>Created at</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $data)
                                    
                                <tr class="@if($data->seen->count())notificationSeen @endif">
                                    <td style="text-align:left">
                                        @if(!empty($data->redirect_url))
                                           <div class="wrap-table-text"><a href="{{config('app.url').$data->redirect_url}}">{{$data->title}}</a></div>
                                        @else
                                            <div class="wrap-table-text">{{$data->title}}</div>
                                        @endif    
                                    </td>
                                    <!--  <td>@if($data->customer_type=='Individual')  Individual @else Business @endif</td> -->
                                    <td>@if($data->customer_type=='ProfileUpdated')  Profile Update @elseif($data->customer_type=='Individual') Individual @elseif($data->customer_type=='IndividualProfileUpdated') Individual Profile Update @elseif($data->customer_type=='BusinessProfileUpdated') Business Profile Update @else Business @endif</td>
                                    
                                   
                                    <td>{{date('d/m/Y H:i', strtotime($data->reported_at))}}</td>
                                   
                                </tr>
                                @empty
                                    <tr><td colspan="10" align="center">No Notification Found</td></tr>
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    </div>
                    
                </div>
            </div>
                
          

        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script language="javascript" type="application/javascript">
    {{--$(document).ready(function(){
        var newOption = new Option('RAJKOT', 23, false, false);
        // Append it to the select
        $('#city_id').append(newOption).trigger('change');
        alert($('#city_id').val());
        $('#city_id').select2("destroy");
        $("#city_id").html('')
       $('#city_id').on('change',function(){
            alert($(this).val());
        });

        //clear selection
//        $('#city_id').val(null).trigger('change');
    });
  
 --}}

    $(document).ready(function(){
        if($("#state").val()!=''){ 
            @if(!empty(app('request')->input('state_id')))        
            var oldCity = "{{app('request')->input('city_id')}}";    
            var selected = '';
            $("#city").find('option').remove();
            $("#city").append('<option value="">ALL</option>');
            var stateId =  $("#state").val();
            $("#maincity option").each(function(){
                if($(this).data('state-id')==stateId){
                    var cityId = $(this).val();
                    if(oldCity==cityId) { selected= 'selected';}else{selected= ''}
                    $("#city").append('<option value="'+$(this).val()+'" '+selected+'>'+$(this).text()+'</option>');    
                }
            });
            @endif
          } 

        $("#state").on('change',function(){
            $("#city").find('option').remove();
            $("#city").append('<option value="">ALL</option>');

         if($("#state").val()!=''){  
            var stateId =  $("#state").val();
            $("#maincity option").each(function(){
                if($(this).data('state-id')==stateId){
                    $("#city").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>'); 
                }
            });
          }  
        });

      });  

 </script> 
@endsection