@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Send SMS')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}Send SMS
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
@stop

@section('content')
 <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">

                        <div id="dataTable_filter" class="dataTables_filter">
                            <form action="{{route('admin.all-records-for-sms')}}" method="get">
                            <div class="row">
                            	<div class="col-md-6">
                                 <div class="row new_width"> 
                                    <div class="col-md-4">
                                        <label>Organizations:</label>
                                        <select name="organization_id"class="form-control input-sm select2" placeholder="" aria-controls="dataTable">
                                            <option value="">ALL</option>   
                                            @foreach($organizations as $organization) 
                                                <option value="{{$organization->id}}" {{app('request')->input('organization_id')== $organization->id ? 'selected' : '' }} data-mobile_number="{{$organization->mobile_number}}">{{$organization->business_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Due Amount (in INR):</label>
                                        <select name="due_amount"class="form-control input-sm" placeholder="" aria-controls="dataTable">
                                            <option value="">ALL</option>    
                                            <option value="less than 1000" {{app('request')->input('due_amount')=='less than 1000' ? 'selected' : '' }}>less than 1000</option>
                                            <option value="1000 to 5000" {{app('request')->input('due_amount')=='1000 to 5000' ? 'selected' : '' }}>1000 to 5000</option>
                                            <option value="5001 to 10000" {{app('request')->input('due_amount')=='5001 to 10000' ? 'selected' : '' }}>5001 to 10000</option>
                                            <option value="10001 to 25000" {{app('request')->input('due_amount')=='10001 to 25000' ? 'selected' : '' }}>10001 to 25000</option>
                                            <option value="25001 to 50000" {{app('request')->input('due_amount')=='25001 to 50000' ? 'selected' : '' }}>25001 to 50000</option>
                                            <option value="more than 50000" {{app('request')->input('due_amount')=='more than 50000' ? 'selected' : '' }}>more than 50000</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Due Date Period:</label>
                                        <select name="due_date_period" class="form-control input-sm" placeholder="" aria-controls="dataTable">
                                            <option value="">ALL</option>    
                                            <option value="less than 30days" {{app('request')->input('due_date_period')=='less than 30days' ? 'selected' : '' }}>less than 30days</option>
                                            <option value="30days to 90days" {{app('request')->input('due_date_period')=='30days to 90days' ? 'selected' : '' }}>30days to 90days</option>
                                            <option value="91days to 180days" {{app('request')->input('due_date_period')=='91days to 180days' ? 'selected' : '' }}>91days to 180days</option>
                                            <option value="181days to 1year" {{app('request')->input('due_date_period')=='181days to 1year' ? 'selected' : '' }}>181days to 1year</option>
                                            <option value="more than 1year" {{app('request')->input('due_date_period')=='more than 1year' ? 'selected' : '' }}>more than 1year</option>
                                            
                                        </select>
                                    </div>   
                                   
                                </div>
                                </div>
                                <div class="col-md-6 text-right text-md-right mt_form">
                                    <button type="submit" class="btn btn-primary btn-blue"  aria-controls="dataTable">Search</button>
                                    <a href="{{route('admin.all-records-for-sms')}}" class="btn btn-primary btn-red"  aria-controls="dataTable">Reset</a>
                               </div>
                            </div>
                           </form>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    </br>
                    <div class="col-md-12">
                        <a href="javascript:void(0)" class="btn btn-primary continue margin-tb-zero" title="Send English SMS" data-mymodel-id="continue">Send English SMS</a>
                        <a href="javascript:void(0)" class="btn btn-primary continue margin-tb-zero" title="Send local language SMS" data-mymodel-id="continueLocalLanguage">Send Local language SMS</a>
                        <a href="javascript:void(0)" class="btn btn-primary continue margin-tb-zero" title="Send Hindi language SMS" data-mymodel-id="continueHindiLanguage">Send Hindi language SMS</a>
                    </div>
                    </br>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                                <thead>
                                <tr>
                                    <th style="text-align: center !important;">
                                        <input type="checkbox" class="select_all">
                                    </th>
                                    <th style="text-align: center !important;">Reported Organization</th>
                                    <th style="text-align: center !important;">Person's Name</th>
                                    <th style="text-align: center !important;">Due Date</th>
                                    <th style="text-align: center !important;">Outstanding Days</th>
                                    <th style="text-align: center !important;">Reported Due</th>
                                    <th style="text-align: center !important;">Balance Due</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $data)
                                    <tr>
                                        <td style="text-align: center !important;">
                                            <input type="checkbox" name="due_id" id="checkbox_{{ $data->id }}" value="{{ $data->id }}">
                                        </td>
                                        <td style="text-align: center !important;">{{$data->addedBy->business_name}}</td>
                                        <td style="text-align: center !important;">{{$data->profile->person_name}}
                                              @if(!empty($data->profile->contact_phone))
                                                <br>{{$data->profile->contact_phone}}
                                              @endif  
                                         </td>
                                        <td style="text-align: center !important;">{{date('d/m/Y', strtotime($data->due_date))}}</td>
                                        <td style="text-align: center !important;">{{General::diffInDays($data->due_date)}}</td>
                                        <td style="text-align: center !important;">{{number_format($data->due_amount)}}</td>
                                        <td class="balance" style="text-align: center !important;">{{number_format($data->due_amount - $data->totalPaid)}}</td>
                                    </tr>
                                    @empty
                                        <tr><td colspan="10" align="center">No Record Found</td></tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        @if($records->count())
                            <div class="pull-right">
                                {{$records->links()}}
                            </div>   
                        @endif
                    </div>
                </div>
            </div>
           
        </div>
    </div>
<div class="modal" id="continue" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Send SMS</h3>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.all-records-send-sms')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="ids" value="">  
             <div class="form-group">
                <label>Choose Template</label>
            </div>                                       
            @foreach($smsTemplates as $template_id=>$template_text)
                @if($template_text['language'] =='ENGLISH') 
                <div class="form-group">
                    <input type="radio" class="form-radio-input" name="template_id" value="{{$template_id}}">
                    
                    @if(empty(request()->organization_id))
                       @php $text = General::replaceTextInSmsTemplate($template_id,'INDIVIDUAL'); @endphp
                    @else    
                       @php $text = General::replaceTextInSmsTemplate($template_id,'INDIVIDUAL',$authUser); @endphp
                    @endif

                    @php
                        
                    $withinDate = strpos($text,"<Date>");
                    if($withinDate!==false){
                        $replacetext='<input disabled type="date" name="within_date" class="input-sm withinDate" aria-controls="dataTable" value="" style="max-width: 200px;">';
                        $text = str_replace("<Date>",$replacetext,$text);
                    }
                    @endphp
                    @if(empty(request()->organization_id))
                        @php
                            $text = str_replace("<Member name, city>","&lt;Member name, city&gt;",$text);
                        @endphp
                    @endif   
                    <span class="template_text" style="color:#000;font-weight: 300">{!!$text!!}</span>
                </div>
                @endif
                  
            @endforeach
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="custom_message_checkbox" id="custom_message_checkbox">
                <label class="form-check-label" for="custom_message_checkbox">Custom Message</label>
            </div>
            <div class="form-group">
                <label for="due_note">Message</label>
                <textarea class="form-control" name="message" disabled="disabled" maxlength="145"></textarea>
            </div>
            <div class="form-action pull-right">
                <button type="submit" class="btn btn-primary btn-blue">SEND</button>
                <button type="reset" class="btn btn-secondary btn-red" data-dismiss="modal">CANCEL</button>
            </div>                      
            
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal" id="continueLocalLanguage" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Send SMS</h3>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.all-records-send-sms')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="ids" value="">  
             <div class="form-group">
                <label>Choose Template</label>
            </div>                                       
            @foreach($smsTemplates as $template_id=>$template_text)
                @if($template_text['language'] =='LOCAL') 
                <div class="form-group">
                    <input type="radio" class="form-radio-input" name="template_id" value="{{$template_id}}">
                    @if(empty(request()->organization_id))
                       @php $text = General::replaceTextInSmsTemplate($template_id,'INDIVIDUAL'); @endphp
                    @else    
                       @php $text = General::replaceTextInSmsTemplate($template_id,'INDIVIDUAL',$authUser); @endphp
                    @endif

                    @php
                        
                    $withinDate = strpos($text,"<Date>");
                    if($withinDate!==false){
                        $replacetext='<input disabled type="date" name="within_date" class="input-sm withinDate" aria-controls="dataTable" value="" style="max-width: 200px;">';
                        $text = str_replace("<Date>",$replacetext,$text);
                    }
                    @endphp
                    @if(empty(request()->organization_id))
                        @php
                            $text = str_replace("<Member name, city>","&lt;Member name, city&gt;",$text);
                        @endphp
                    @endif   
                    <span class="template_text" style="color:#000;font-weight: 300">{!!$text!!}</span>
                </div>
                @endif
                  
            @endforeach
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="custom_message_checkbox" id="custom_message_checkbox">
                <label class="form-check-label" for="custom_message_checkbox">Custom Message</label>
            </div>
            <div class="form-group">
                <label for="due_note">Message</label>
                <textarea class="form-control" name="message" disabled="disabled" maxlength="145"></textarea>
            </div>
            <div class="form-action pull-right">
                <button type="submit" class="btn btn-primary btn-blue">SEND</button>
                <button type="reset" class="btn btn-secondary btn-red" data-dismiss="modal">CANCEL</button>
            </div>                      
            
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal" id="continueHindiLanguage" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Send SMS</h3>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.all-records-send-sms')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="ids" value="">  
             <div class="form-group">
                <label>Choose Template</label>
            </div>                                       
            @foreach($smsTemplates as $template_id=>$template_text)
                @if($template_text['language'] =='HINDI') 
                <div class="form-group">
                    <input type="radio" class="form-radio-input" name="template_id" value="{{$template_id}}">
                    @if(empty(request()->organization_id))
                       @php $text = General::replaceTextInSmsTemplate($template_id,'INDIVIDUAL'); @endphp
                    @else    
                       @php $text = General::replaceTextInSmsTemplate($template_id,'INDIVIDUAL',$authUser); @endphp
                    @endif

                    @php
                        
                    $withinDate = strpos($text,"<Date>");
                    if($withinDate!==false){
                        $replacetext='<input disabled type="date" name="within_date" class="input-sm withinDate" aria-controls="dataTable" value="" style="max-width: 200px;">';
                        $text = str_replace("<Date>",$replacetext,$text);
                    }
                    @endphp
                    @if(empty(request()->organization_id))
                        @php
                            $text = str_replace("<Member name, city>","&lt;Member name, city&gt;",$text);
                        @endphp
                    @endif 
                   <span class="template_text" style="color:#000;font-weight: 300">{!!$text!!}</span>
                        

                </div>
                @endif
                  
            @endforeach
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="custom_message_checkbox" id="custom_message_checkbox">
                <label class="form-check-label" for="custom_message_checkbox">Custom Message</label>
            </div>
            <div class="form-group">
                <label for="due_note">Message</label>
                <textarea class="form-control" name="message" disabled="disabled" maxlength="145"></textarea>
            </div>
            <div class="form-action pull-right">
                <button type="submit" class="btn btn-primary btn-blue">SEND</button>
                <button type="reset" class="btn btn-secondary btn-red" data-dismiss="modal">CANCEL</button>
            </div>                      
            
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>    
<script>
$(document).ready(function(){
    
    $("#ids").select2({
        placeholder:'Select records',
        allowClear : true
    });
    $("input.select_all").on('change',function(){
        if($(this).prop('checked')){
            $("input[name=due_id]").prop('checked',true);
        }else{
            $("input[name=due_id]").prop('checked',false);
        }
    });

    $(".modal").find("input[name=custom_message_checkbox]").on('change',function(){
        var modal = $(this).parents('.modal');
        if($(this).prop('checked')){
            //textarea
            modal.find("textarea[name=message]").removeAttr('disabled');
            modal.find("textarea[name=message]").attr('required','required');

            //radio
            modal.find('input[name=template_id]').prop('checked',false);
            modal.find('input[name=template_id]').removeAttr('required');
            modal.find('input[name=template_id]').attr('disabled','disabled');
            
            //input date
            modal.find('input[name=within_date]').attr('disabled','disabled');
            modal.find('input[name=within_date]').removeAttr('required');

        }else{
            //textarea    
            modal.find('textarea[name=message]').attr('disabled','disabled');
            modal.find('textarea[name=message]').removeAttr('required');
            modal.find('textarea[name=message]').val('');

            //radio
            modal.find('input[name=template_id]').removeAttr('disabled');
        }
    });

    $('.continue').on('click', function () {
        var element = $(this);
        var modalId = element.data('mymodel-id');
        var modal  = $("#"+modalId);
        var arrValue= $('input[name=due_id]:checked').map(function(){
            return this.value;
        }).get(); 
        
        if(!arrValue.length){
            alert('Please select atleast one record');
            return false;
        }
        modal.find(".modal-body").find('input[name="ids"]').val(arrValue);           
        modal.find(".modal-body").find('input[name=agree_terms]').prop('checked',false);
        //$("#continue").find("button[type=submit]").attr('disabled',true); 
        modal.modal();
    });

    $("#continue, #continueLocalLanguage, #continueHindiLanguage").find(".modal-body").find("input[name=template_id]").on('change',function(){
        //within date
        $(this).parent().siblings().find('input.withinDate').attr('disabled','disabled');
        $(this).parent().siblings().find('input.withinDate').removeAttr('required');
        $(this).parent().siblings().find('input.withinDate').val('');
        $(this).parent().find('input.withinDate').removeAttr('disabled');
        $(this).parent().find('input.withinDate').attr('required','required');
    });

}); 
</script>
@endsection