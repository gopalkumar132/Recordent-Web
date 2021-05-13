@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' All Records')

@section('page_header')
    <h1 class="page-title">
        
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}Reported By {{isset($studentDueData[0]->business_name) ? $studentDueData[0]->business_name .'('.$studentDueData[0]->userType .')' : ''}}
        {{--<div id="addDue" class="pull-right">
			<a href="" class="btn btn-success" data-toggle="modal" data-target="#outstanding">
				<i class="voyager-plus"></i> Add
			</a>
   		</div>
        <div id="more" class="pull-right" style="display: none; padding-left: 10px">
			<a href="" class="btn btn-danger" data-toggle="modal" data-target="#pay">
				<i class="voyager-check"></i> Pay
			</a>
   		</div>--}}
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
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
    								<tr>
    									<th>Person's Name</th>
                                        <th>Due Date</th>
                                        <th>Balance Due</th>
                                        <th>Aadhaar Number</th>
                                       
    									<th>Reported Date</th>
    									
    									<th>Outstanding Days</th>
    									<th>Reported Due</th>
    									
    									<th>Notes</th>
    									
    								</tr>
                                </thead>
                                <tbody>
                                    @foreach($studentDueData as $data)
                                    <tr>
                                    	<td>{{$data->person_name}}</td>
                                        <td>{{date('d/m/Y', strtotime($data->due_date))}}</td>
                                        <td class="balance">{{General::ind_money_format($data->due_amount - General::getPaidForDue($data->dueId))}}</td>
                                        <td>{{$data->aadhar_number}}</td>
                                        
                                        <td>{{date('d/m/Y', strtotime($data->ReportedAt))}}</td>
                                        
                                        <td>{{General::diffInDays($data->due_date)}}</td>
                                        <td>{{General::ind_money_format($data->due_amount)}}</td>
                                        
                                        <td><div class="wrap-table-text">{{$data->due_note}}</div></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection