  <div class="table-responsive">
    <table id="dataTable" class="table table-hover fixed_headerss">
       <thead>
         <tr>
            <th>Reported Organization</th>
            <th>Person's Name</th>
            <th>Contact Phone</th>
            <th>Aadhaar Number</th>
            <th>Reported Date</th>
            <th>Due Date</th>
            <th>Reported Due</th>
            <th>Balance Due</th>
        </tr>
    </thead>
    <tbody>
      @forelse($dueRecords as $data)                  
      <tr>
        <td>
          {{$data->addedBy->business_name}}@if(!empty($data->addedBy->userType)) ({{$data->addedBy->userType->name }}) @endif
      </td>
      <td>{{$data->profile->person_name}}</td>
      <td>{{$data->profile->contact_phone}}</td>
      <td>{{$data->profile->aadhar_number}}</td>
      <td>{{date('d/m/Y', strtotime($data->created_at))}}</td>
      <td>{{date('d/m/Y',strtotime($data->due_date))}}</td>
      <td>{{General::ind_money_format($data->due_amount)}}</td>
      <td class="balance">{{General::ind_money_format($data->due_amount - General::getPaidForDue($data->id))}}</td>
  </tr>
  @empty
  <tr><td colspan="10" align="center">No Record Found</td></tr>
  @endforelse
  
</tbody>
</table>
</div> 