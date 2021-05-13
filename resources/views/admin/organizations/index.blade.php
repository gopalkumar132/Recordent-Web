@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Organizations')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}Organizations
    </h1>
   
    
@stop

@section('content')
 
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">

                         <div id="dataTable_filter" class="dataTables_filter">
                            <form action="{{route('organizations')}}" method="get">
                            <div class="row">
                                <div class="col-md-12">
                                 <div class="row new_width"> 
                                    
                                    <div class="col-md-2">
                                        <label> Business Name:</label>
                                        <input type="text" name="business_name"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('business_name')) ? app('request')->input('business_name') : '' }}">
                                    </div> 
                                      
                                    
                                        
                                        <div class="col-md-8 text-right text-md-right mt_form">
                                            <button type="submit" class="btn btn-primary"  aria-controls="dataTable">Search</button>
                                            <a href="{{route('organizations')}}" class="btn btn-primary"  aria-controls="dataTable">Reset</a>
                                       </div>
                                       
                                 </div>
                                </div>

                                <div class="col-md-12 text-left text-md-right mt_form" style="vertical-align: bottom">
                                    <div class="row new_width">
                                        
                                    </div>
                                </div>        
                                
                               
                            </div>
                           </form>
                        </div>
                    </div>
                </div>    


            </div>

   
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                         <table id="dataTable" class="table table-hover fixed_headerss">
                                <thead>
                                <tr>
                                    
                                    <th>Business Name</th>
                                    <th>Location</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $data)
                                        
                                    <tr>
                                        
                                        <td>{{$data->business_name}}</td>
                                        @php
                                            $location = '';
                                            if(!empty($data->cityName)){
                                                $location.=$data->cityName.', ';
                                            }

                                            $location.= $data->stateName;

                                        @endphp
                                        
                                       <td>@if(!empty($data->address)){{$data->address}}<br>@endif {{$location}}</td>
                                       
                                    </tr>
                                    @empty
                                        <tr><td colspan="10" align="center">No Record Found</td></tr>
                                        
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @if($records->count())
                        <div class="pull-left">
                            <div role="status" class="show-res" aria-live="polite">Showing
                                {{($records->perPage() * $records->currentPage()) - ($records->perPage() - 1)}} to
        
                                {{(($records->perPage() * $records->currentPage()) - ($records->perPage() - 1)) + ($records->count() - 1)}}</strong> of <strong>{{$records->total()}} </strong> entries
                                
                            </div>
                        </div>
                        <div class="pull-right">
                            {{$records->links()}}
                        </div> 
                    @endif

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