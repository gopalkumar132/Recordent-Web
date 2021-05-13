@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)
@section('page_header')
<style type="text/css">
    .download-class{
        padding-top: 15px;
        margin-left: 3px;
        font-weight: bold;
        font-family: var(--font-rubik);    
    }
    .add_new{
        padding-top: 15px;
    }
    .br{
        display: none;
    }
    .pull-left {
    float: left!important;
    padding-top: 26px;
    font-weight: bold;
    font-family: var(--font-rubik);
    padding-left: 22px !important;
}
input[type=date]{
    text-transform: uppercase;
}

@media screen and (max-width:500px){
    .page-title{
        margin-top: 52px !important;
    }
    .download-class{
        margin-top: 117px !important;
        margin-left: -274px !important;
        font-weight: bold;
        font-family: var(--font-rubik);    
    }
    .add_new{
        margin-top: 48px !important;
        margin-left: -4px !important;
    }
    .br{
    display: block;
    }
    .pull-left {
    float: left!important;
    padding-top: 144px;
    font-weight: bold;
    font-family: var(--font-rubik);
    /*padding-left: 22px !important;*/
    margin-right: 282px;
    margin-left: -233px;
}
}
</style>
    <!-- <div class="container-fluid padding-20"> -->
    <div class="d-flex"> 
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> Members
        </h1>
        <br class="br">
        <div class="add_new">
        @can('add', app($dataType->model_name))
            <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success btn-add-new" style="height: 38px;">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
        @endcan
    </div>

      &nbsp&nbsp
      @if(Auth::user()->role_id != setting('admin.hide_export_download'))
      <div class="pull-left">
        <p>From:</p>
      </div>
    <div  class="download-class">
        <form action="{{route('superadmin.download-all-members')}}">
            <input type="date" name="date" id="date">
            <button class="btn btn-success btn-add-new">
              <i class="voyager-download"></i> <span>Download All Members</span>
            </button> 
        </form>
    </div>
    @endif
        @can('delete', app($dataType->model_name))
            {{--@include('voyager::partials.bulk-delete')--}}
        @endcan
        @can('edit', app($dataType->model_name))
            @if(isset($dataType->order_column) && isset($dataType->order_display_column))
                <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary btn-add-new">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>
            @endif
        @endcan
        @can('delete', app($dataType->model_name))
            @if($usesSoftDeletes)
                <input type="checkbox" @if ($showSoftDeleted) checked @endif id="show_soft_deletes" data-toggle="toggle" data-on="{{ __('voyager::bread.soft_deletes_off') }}" data-off="{{ __('voyager::bread.soft_deletes_on') }}">
            @endif
        @endcan
        @foreach(Voyager::actions() as $action)
            @if (method_exists($action, 'massAction'))
                @include('voyager::bread.partials.actions', ['action' => $action, 'data' => null])
            @endif
        @endforeach
        @include('voyager::multilingual.language-selector')
        
         
    </div>
@stop

@section('content')

    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        @if ($isServerSide)
                            <form method="get" class="form-search">
                                <div id="search-input">
                                    <select id="search_key" name="key">
                                        @foreach($searchable as $key)
                                          @php $fields = "name,email,mobile_number,business_name,address"; @endphp
                                            @if(str_contains($fields,$key)) 
                                                <option value="{{ $key }}" @if($search->key == $key || (empty($search->key) && $key == $defaultSearchKey)){{ 'selected' }}@endif>{{ ucwords(str_replace('_', ' ', $key)) }}</option>
                                            @endif
                                            
                                        @endforeach
                                            <option value="roles.name" @if($search->key == "roles.name" || (empty($search->key) && $key == $defaultSearchKey)){{ 'selected' }}@endif>Business Type</option>

                                             <option value="pricing_plans.name" @if($search->key == "pricing_plans.name" || (empty($search->key) && $key == $defaultSearchKey)){{ 'selected' }}@endif>Membership Type</option>
                                            
                                            <option value="total_reported_dues" @if($search->key == "total_reported_dues" || (empty($search->key) && $key == $defaultSearchKey)){{ 'selected' }}@endif># of Customers</option>
                                    </select>
                                    <select id="filter" name="filter">
                                        <option value="equals" @if($search->filter == "equals"){{ 'selected' }}@endif>=</option>
                                        <option value="lessthan" @if($search->filter == "lessthan"){{ 'selected' }}@endif><</option>
                                        <option value="greaterthan" @if($search->filter == "greaterthan"){{ 'selected' }}@endif>></option>
                                    </select>
                                    <div class="input-group col-md-12">
                                        <input type="text" class="form-control" placeholder="{{ __('voyager::generic.search') }}" name="s" value="{{ $search->value }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info btn-lg" type="submit">
                                                <i class="voyager-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                @if (Request::has('sort_order') && Request::has('order_by'))
                                    <input type="hidden" name="sort_order" value="{{ Request::get('sort_order') }}">
                                    <input type="hidden" name="order_by" value="{{ Request::get('order_by') }}">
                                @endif
                            </form>
                        @endif
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                                <thead>
                                    <tr>
                                        {{-- @can('delete',app($dataType->model_name))
                                            <th style="text-align: center !important;">
                                                <input type="checkbox" class="select_all">
                                            </th>
                                        @endcan
                                        --}}
                                    
                                        @foreach($dataType->browseRows as $row)

                                        <th style="text-align: center !important;">
                                            @if($isServerSide)
                                                <a href="javascript:void(0)"> {{-- $row->sortByUrl($orderBy, $sortOrder) --}}
                                            @endif
                                            @if($row->display_name == "Role") 
                                                Business Type
                                            @else
                                            {{ $row->display_name }}
                                               
                                            @endif
                                            @if($isServerSide)
                                                @if ($row->isCurrentSortField($orderBy))
                                                    @if ($sortOrder == 'asc')
                                                        <i class="voyager-angle-up pull-right"></i>
                                                    @else
                                                        <i class="voyager-angle-down pull-right"></i>
                                                    @endif
                                                @endif
                                                </a>
                                            @endif
                                        </th>
                                        @endforeach
                                      

                                         <th style="text-align: center !important;color:#538cc6;"># of Customers</th>
                                        <th style="text-align: center !important;" class="actions text-right"><a href="#">{{ __('voyager::generic.actions') }}</a></th>
                                        
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach($dataTypeContent as $data)
                                    <tr>
                                        {{--@can('delete',app($dataType->model_name))

                                            <td style="text-align: center !important;">
                                                <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                                            </td>
                                        @endcan
                                        --}}
                                        @foreach($dataType->browseRows as $row)
                                            @php
                                            if ($data->{$row->field.'_browse'}) {
                                                $data->{$row->field} = $data->{$row->field.'_browse'};
                                            }
                                            @endphp

                                            <td style="text-align: center !important;">
                                                @if (isset($row->details->view))
                                                    @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse'])
                                                @elseif($row->type == 'image')
                                                    <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                                                @elseif($row->type == 'relationship')
                                                    @include('voyager::formfields.relationship', ['view' => 'browse','options' => $row->details])
                                                @elseif($row->type == 'select_multiple')
                                                    @if(property_exists($row->details, 'relationship'))

                                                        @foreach($data->{$row->field} as $item)
                                                            {{ $item->{$row->field} }}
                                                        @endforeach

                                                    @elseif(property_exists($row->details, 'options'))
                                                        @if (!empty(json_decode($data->{$row->field})))
                                                            @foreach(json_decode($data->{$row->field}) as $item)
                                                                @if (@$row->details->options->{$item})
                                                                    {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ __('voyager::generic.none') }}
                                                        @endif
                                                    @endif

                                                    @elseif($row->type == 'multiple_checkbox' && property_exists($row->details, 'options'))
                                                        @if (@count(json_decode($data->{$row->field})) > 0)
                                                            @foreach(json_decode($data->{$row->field}) as $item)
                                                                @if (@$row->details->options->{$item})
                                                                    {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ __('voyager::generic.none') }}
                                                        @endif

                                                @elseif(($row->type == 'select_dropdown' || $row->type == 'radio_btn') && property_exists($row->details, 'options'))

                                                    {!! $row->details->options->{$data->{$row->field}} ?? '' !!}

                                                @elseif($row->type == 'date' || $row->type == 'timestamp')
                                                    {{ property_exists($row->details, 'format') ? \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format) : $data->{$row->field} }}
                                                @elseif($row->type == 'checkbox')
                                                    @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                                        @if($data->{$row->field})
                                                            <span class="label label-info">{{ $row->details->on }}</span>
                                                        @else
                                                            <span class="label label-primary">{{ $row->details->off }}</span>
                                                        @endif
                                                    @else
                                                    {{ $data->{$row->field} }}
                                                    @endif
                                                @elseif($row->type == 'color')
                                                    <span class="badge badge-lg" style="background-color: {{ $data->{$row->field} }}">{{ $data->{$row->field} }}</span>
                                                @elseif($row->type == 'text')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                @elseif($row->type == 'text_area')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                @elseif($row->type == 'file' && !empty($data->{$row->field}) )
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    @if(json_decode($data->{$row->field}) !== null)
                                                        @foreach(json_decode($data->{$row->field}) as $file)
                                                            <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank">
                                                                {{ $file->original_name ?: '' }}
                                                            </a>
                                                            <br/>
                                                        @endforeach
                                                    @else
                                                        <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($data->{$row->field}) }}" target="_blank">
                                                            Download
                                                        </a>
                                                    @endif
                                                @elseif($row->type == 'rich_text_box')
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <div>{{ mb_strlen( strip_tags($data->{$row->field}, '<b><i><u>') ) > 200 ? mb_substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200) . ' ...' : strip_tags($data->{$row->field}, '<b><i><u>') }}</div>
                                                @elseif($row->type == 'coordinates')
                                                    @include('voyager::partials.coordinates-static-image')
                                                @elseif($row->type == 'multiple_images')
                                                    @php $images = json_decode($data->{$row->field}); @endphp
                                                    @if($images)
                                                        @php $images = array_slice($images, 0, 3); @endphp
                                                        @foreach($images as $image)
                                                            <img src="@if( !filter_var($image, FILTER_VALIDATE_URL)){{ Voyager::image( $image ) }}@else{{ $image }}@endif" style="width:50px">
                                                        @endforeach
                                                    @endif
                                                @elseif($row->type == 'media_picker')
                                                    @php
                                                        if (is_array($data->{$row->field})) {
                                                            $files = $data->{$row->field};
                                                        } else {
                                                            $files = json_decode($data->{$row->field});
                                                        }
                                                    @endphp
                                                    @if ($files)
                                                        @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                            @foreach (array_slice($files, 0, 3) as $file)
                                                            <img src="@if( !filter_var($file, FILTER_VALIDATE_URL)){{ Voyager::image( $file ) }}@else{{ $file }}@endif" style="width:50px">
                                                            @endforeach
                                                        @else
                                                            <ul>
                                                            @foreach (array_slice($files, 0, 3) as $file)
                                                                <li>{{ $file }}</li>
                                                            @endforeach
                                                            </ul>
                                                        @endif
                                                        @if (count($files) > 3)
                                                            {{ __('voyager::media.files_more', ['count' => (count($files) - 3)]) }}
                                                        @endif
                                                    @elseif (is_array($files) && count($files) == 0)
                                                        {{ trans_choice('voyager::media.files', 0) }}
                                                    @elseif ($data->{$row->field} != '')
                                                        @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                            <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:50px">
                                                        @else
                                                            {{ $data->{$row->field} }}
                                                        @endif
                                                    @else
                                                        {{ trans_choice('voyager::media.files', 0) }}
                                                    @endif
                                                @else
                                                    @include('voyager::multilingual.input-hidden-bread-browse')
                                                    <span>{{ $data->{$row->field} }}</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        

                                        <td style="text-align: center !important;">{{General::totalNumberDueReportedForIndividualBusiness($data->id)}}</td>
                                        <td style="text-align: center !important;" class="no-sort no-click" id="bread-actions">
                                            @foreach(Voyager::actions() as $action)
                                                @if (!method_exists($action, 'massAction'))
                                                    @include('voyager::bread.partials.actions', ['action' => $action])
                                                @endif
                                            @endforeach
                                            
                                            <a href="{{route('business-records-for-admin')}}?userId={{$data->id}}" class="btn btn-sm btn-primary pull-right margin-right-5" title="View Business Records"><i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Business Records {{--General::getUserBusinessRecordsCount($data->id)--}}
                                            {{General::getRecordsLevelBusinessDuesCount($data->id)}}</span></a>

                                            <a href="{{route('user-records')}}?userId={{$data->id}}" class="btn btn-sm btn-primary pull-right margin-right-5" title="View Individual Records"><i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Individual Records {{--General::getUserRecordsCount($data->id)--}}
                                            {{General::getRecordsLevelIndividualDuesCount($data->id)}}</span></a>
                                            <a href="{{route('super-excel',$data->id)}}" value="{{$data->id}}" class="btn btn-sm btn-primary pull-right margin-right-5" title="View Individual Records"><i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Bulk upload</span></a>
                                            <!-- <a href="{{route('edit-profile',$data->id)}}" value="{{$data->id}}" class="btn btn-sm btn-primary pull-right margin-right-5" title="View Individual Records"><i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Edit Profile</span></a> -->
                                         </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($isServerSide)
                            <div class="pull-left">
                                <div role="status" class="show-res" aria-live="polite">{{ trans_choice(
                                    'voyager::generic.showing_entries', $dataTypeContent->total(), [
                                        'from' => $dataTypeContent->firstItem(),
                                        'to' => $dataTypeContent->lastItem(),
                                        'all' => $dataTypeContent->total()
                                    ]) }}</div>
                            </div>
                            <div class="pull-right">
                                {{ $dataTypeContent->appends([
                                    's' => $search->value,
                                    'filter' => $search->filter,
                                    'key' => $search->key,
                                    'order_by' => $orderBy,
                                    'sort_order' => $sortOrder,
                                    'showSoftDeleted' => $showSoftDeleted,
                                ])->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->display_name_singular) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('css')
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
    @endif
@stop

@section('javascript')
    <!-- DataTables -->
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    @endif
    <script>
        function numbersonly(myfield, e,maxlength=null)
        {
            var key;
            var keychar;
            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;

    //        alert(1);
            keychar = String.fromCharCode(key);
            // control keys
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
                return true;
            // numbers
            else if ((("0123456789").indexOf(keychar) > -1)){
                return true;
            }
            else{
                return false;
            }
        }
        $('#search_key').on('select2:selecting', function(e) {
            try{
               $('.form-search').find('input[name=s]').datetimepicker('destroy');
            }catch(err){} 
            $('.form-search').find('input[name=s]').off('keypress');
            if(e.params.args.data.id=='mobile_verified_at'){
                $('.form-search').find('input[name=s]').datetimepicker({
                    'format':'DD/MM/YYYY'
                });
            }else if(e.params.args.data.id=='total_reported_dues'){
                $('.form-search').find('input[name=s]').val('');
                $('.form-search').find('input[name=s]').on('keypress',function(e){
                   return numbersonly(this,e); 
                });

            }
        });
        $(document).ready(function () {
            
            @if (!$dataType->server_side)
                var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => $orderColumn,
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [['targets' => -1, 'searchable' =>  false, 'orderable' => false]],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!});
            @else
                $('#search-input select').select2({
                    minimumResultsForSearch: Infinity
                });
            @endif

            @if ($isModelTranslatable)
                $('.side-body').multilingual();
                //Reinitialise the multilingual features when they change tab
                $('#dataTable').on('draw.dt', function(){
                    $('.side-body').data('multilingual').init();
                })
            @endif
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
            });
                if($('#search_key').find(':selected').val()=='mobile_verified_at'){
                   
                    $('.form-search').find('input[name=s]').datetimepicker({
                        'format':'DD/MM/YYYY'
                    });
                }else if($('#search_key').find(':selected').val()=='total_reported_dues'){
                    $('.form-search').find('input[name=s]').on('keypress',function(e){
                       return numbersonly(this,e); 
                    });
                }
        });


        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        @if($usesSoftDeletes)
            @php
                $params = [
                    's' => $search->value,
                    'filter' => $search->filter,
                    'key' => $search->key,
                    'order_by' => $orderBy,
                    'sort_order' => $sortOrder,
                ];
            @endphp
            $(function() {
                $('#show_soft_deletes').change(function() {
                    if ($(this).prop('checked')) {
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 1]), true)) }}"></a>');
                    }else{
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 0]), true)) }}"></a>');
                    }

                    $('#redir')[0].click();
                })
            })
        @endif
        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });

        
        
    </script>

@stop
