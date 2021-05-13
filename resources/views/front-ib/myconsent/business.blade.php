<div class="table-responsive">
    <table id="dataTable" class="table table-hover fixed_headerss">
       <thead>
         <tr>
            <th>Reported Organization</th>
            <th>Company Name</th>
            <th>{{General::getLabelName('unique_identification_number')}}</th>
            <th>Business Type</th>
            <th>Concerned Person Name</th>
            <th>Concerned Person Phone</th>
            <th>State, City</th>
            <th>Reported Date</th>
            <th>Due Date</th>
            <th>Reported Due</th>
            <th>Balance Due</th>
        </tr>
    </thead>
    <tbody>
    @forelse($dueRecords as $data)									
          @php
              $sector = General::getSector($data->profile->sector_id);
          @endphp
          <tr>

            <td>{{$data->addedBy->business_name}}@if(!empty($data->addedBy->userType)) ({{$data->addedBy->userType->name }}) @endif</td>
            <td>{{$data->profile->company_name}}</td>
            <td>{{$data->profile->unique_identification_number}}{{ $sector ? ' ('.General::getUniqueIdentificationTypeofSector($sector->unique_identification_type).')' : ''}}</td>
            <td>{{ $sector ? $sector->name : ''}}</td>
            <td>{{$data->profile->concerned_person_name}}</td>
            <td>{{$data->profile->concerned_person_phone}}</td>
            <td>{{General::getStateNameById($data->profile->state_id)}}, {{General::getCityNameById($data->profile->city_id)}}</td>
            <td>{{date('d/m/Y', strtotime($data->created_at))}}</td>
            <td>{{date('d/m/Y', strtotime($data->due_date))}}</td>
            <td class="balance">{{General::ind_money_format($data->due_amount)}}</td>
            <td class="balance">{{General::ind_money_format($data->due_amount - General::getPaidForDueOfBusiness($data->id))}}</td>

        </tr>
        @empty
            <tr><td colspan="10" align="center">No Record Found</td></tr>
    @endforelse

</tbody>
</table>
</div> 