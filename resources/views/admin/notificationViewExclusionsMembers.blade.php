<div class="modal-dialog view_details_section" role="document" id="exclusions_member">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">View Excluded Member</h5>
            <button type="button" class="close close_view_exclusions_member" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">


<div class="view_details">

<ul class="top_block">
<li><span>Customer Type:</span><span>{{$notifications['customer']['name']}}</span><div class="clear"></div></li>
<li><span>Created At:</span><span>{{date("d-M-Y h:m", strtotime($notifications['created_at']))}} </span></li>
<li><span>Notification Date:</span><span>


{{date("d-M-Y", strtotime($notifications['notification_date']))}} {{$notifications['notification_start_time']}}:00</span><div class="clear"></div></li>




</ul>

<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <i class="fa fa-plus"></i>
        Customer Level
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
      <ul class="view_list">
      @foreach($notifications['customer_exclusion'] as $customer_exclusion)
                        <li class="header"><span>{{$customer_exclusion['member']['name']}}</span> </li>
                        @foreach($customer_exclusion['users'] as $customer_exclusion_users)

                            {{-- <li><span>280000500</span><span>Prasad</span> <a href="#" class="btn btn-sm btn-danger view"><i class="voyager-trash"></i></a> --}}
                            <li><span>{{isset($customer_exclusion_users['person_name']) ? $customer_exclusion_users['person_name'] : (isset($customer_exclusion_users['company_name']) ? $customer_exclusion_users['company_name'] : '')}}</span></a>
                            </li>
                        @endforeach
                    @endforeach
</ul>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        <i class="fa fa-plus"></i>
        Member Level
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
      <ul class="view_list">
                    @foreach($notifications['members'] as $member)
                            <li><span>{{$member['name']}}</span></a>
                            </li>
                    @endforeach
                </ul>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        <i class="fa fa-plus"></i>
        Business Level
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
      <ul class="view_list">
                    @foreach($notifications['sectors'] as $sector)
                            <li><span>{{$sector['name']}}</span></a>
                            </li>
                    @endforeach
                </ul>
      </div>
    </div>
  </div>
</div>

</div>











           
         
        </div>
        {{-- <div class="modal-footer" style="text-align: left;">
            <button type="button" class="btn btn-primary close_view_exclusions_member">Save</button>
            <button type="button" class="btn btn-secondary close_view_exclusions_member" data-dismiss="modal">Cancel</button>

        </div> --}}
    </div>
</div>



<script>
    $(document).ready(function(){
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
        	$(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });
        
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });
    });
</script>