<table class="table table-bordered" id="notification" >
        <thead>
            <tr>
                <th scope="col">Scheduled Date</th>
                <th scope="col">Notification Type</th>
                <th scope="col">Customer Type</th>
                <th scope="col">Conditions</th>
                <!-- <th scope="col">Exclusions</th> -->
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifications as $notification)
            <tr>
                <td>{{date("d-M-Y", strtotime($notification['notification_date']))}} {{$notification['notification_start_time']}}:00</td>
                <td>{{$notification['notification']['name']}}</td>
                <td>{{$notification['customer']['name']}}</td>
                {{-- <td> <a href="#">View Details</a></td> --}}
                <td><span style="cursor: pointer;" class = "view_exclusions" data-id = "{{$notification['id']}}">View Details</span></td>
                <td>
                    @if($notification['is_pause'] == 0)
                        <span class="btn btn-sm btn-success">Active</span>
                    @elseif($notification['is_pause'] == 1)
                        <span class="btn btn-sm btn-warning">Paused</span>
                    @elseif($notification['is_pause'] == 3)
                        <span class="btn btn-sm btn-primary">Completed</span>
                    @else
                        <span class="btn btn-sm btn-warning" style="background:red">Stopped</span>
                    @endif
                </td>
                <td class="button_section">
                @if($notification['is_pause'] != 3)
                    @if($notification['is_pause'] != 2)
                        <span class="btn btn-sm btn-warning stop" data-id="{{$notification['id']}}" data-value="2" data-toggle="tooltip" data-placement="top" title="Stop Notification" style="background:red"><i class="glyphicon glyphicon-stop" ></i></span>
                    @endif
                    @if($notification['is_pause'] == 0)
                        <span class="btn btn-sm btn-warning stop" data-id="{{$notification['id']}}" data-value="1"  data-toggle="tooltip" data-placement="top" title="Pause Notification"><i class="glyphicon glyphicon-pause"></i></span>
                    @elseif($notification['is_pause'] == 1)
                        <span class="btn btn-sm btn-success stop" data-id="{{$notification['id']}}" data-value="0" data-toggle="tooltip" data-placement="top" title="Start Notification"><i class="glyphicon glyphicon-play"></i></span>
                    @endif

                    <a href="#" class="btn btn-sm btn-danger delete" data-id="{{$notification['id']}}"><i class="voyager-trash"></i></a>
                @else
                ---
                @endif
                </td>
            </tr>

            @endforeach
            @if(count($notifications) == 0)
               <tr>
                   <td colspan="6" style="text-align:center;">No Data Found</td>
                </tr>
            @endif

        </tbody>
    </table>

