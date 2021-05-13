<div class="table-responsive">
<table id="dataTable" class="table table-hover fixed_headerss">
<thead>
<tr>
    <th>Member Name</th>
    <th>GSTIN / Business PAN</th>
    <th>Reported Date</th>
    <th>Due Date</th>
    <th>Outstanding Days</th>
    <th>Reported Due</th>
    <th>Balance Due</th>
    <th>Custom ID</th>
    
</tr>
</thead>
<tbody>
    @if(empty($data))
        <tr>
            <td colspan="10">No Data Found</td>
        </tr>
    @else
    <tr>
        <td>{{$data->profile->company_name}}
              @if(!empty($data->profile->concerned_person_phone))
                <br>{{$data->profile->concerned_person_phone}}
              @endif  
        </td>
        
        <td>{{$data->profile->unique_identification_number}}</td>
        <td>{{date('d/m/Y', strtotime($data->created_at))}}</td>
        <td>{{date('d/m/Y', strtotime($data->due_date))}}</td>
        <td>{{General::diffInDays($data->due_date)}}</td>
        <td>
            {{General::ind_money_format($data->due_amount)}}
            {{-- General::ind_money_format(General::getTotalDueForStudent($data->profile->id,Auth::id()) - General::getTotalPaidForStudent($data->profile->id,Auth::id())) --}}
            
        </td>
        <td class="balance">{{General::ind_money_format($data->due_amount - $data->totalPaid)}}</td>
        <td>{{$data->external_business_id}}</td>
        {{--<td>{{General::ind_money_format(General::getNumberOfDues($data->id,Auth::id()))}}</td>--}}
        <td class="no-sort no-click bread-actions">
           {{-- @can('delete', $data)
                <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $data->{$data->getKeyName()} }}">
                    <i class="voyager-trash"></i> 
                </div>
            @endcan --}}
            
                {{--<a href="{{ route('business.business-data', $data->profile->id) }}" class="btn btn-sm btn-warning view">
                    <i class="voyager-eye"></i> 
                </a>--}}
                
               
        </td>

    </tr>
    @endif
</tbody>
</table>
</div>
<div class="row ">
    <div class="col-12 pull-right">
        {{-- @if(Auth::id()==$data->profile->added_by) --}}
         @php $query_string = app('request')->input('query_string') ? '?'.app('request')->input('query_string') : ''; @endphp
         <a href="{{ route('business.edit-business', $data->profile->id) }}{{htmlspecialchars_decode($query_string)}}" class="btn btn-sm btn-warning view" title="Update Profile">
            Update Profile
        </a>
        {{--@endif --}}
        
    </div>
</div>