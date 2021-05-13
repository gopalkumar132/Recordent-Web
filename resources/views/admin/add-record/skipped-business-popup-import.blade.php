@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Submit Dues')

@section('page_header')
<!-- <h1 class="page-title">
    <i class="voyager-upload"></i> Upload Master Files
</h1> -->
@stop
@section('content')
<style>
    .errors {
        text-align: left;
        position: relative;
        margin-left: -30%;
    }
</style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<style type="text/css">
    input,
    textarea {
        text-transform: uppercase
    }

    ;
</style>
<div class="page-content container-fluid">
    @include('voyager::alerts')
    <div class="row">
        @php
            if($member_id = \Session::get('member_id')){
                $user = \App\User::find($member_id);
                $popup_on_close_redirect_url = route('super-excel', [$member_id]);
            } else {
                $user = Auth::user();
                $popup_on_close_redirect_url = route('import-excel-view-business');
            }

            $user_plan_data = HomeHelper::getImportOrSubmitDuesPopupData($user);
        @endphp
        <div id="myModal" class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Total {{count($remainingRecords)}} record skipped</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @include('admin.add-record.partials.skipped-popup-modal-body', ['user_plan_data' => $user_plan_data, 'user' => $user])

                     <!-- Additional dues payment modal popup footer -->
                    @include('admin.add-record.partials.skipped-popup-modal-footer', [
                        'user' => $user,
                        'dues_type_prepaid_or_postpaid' => $user->reports_business_additional_customer,
                        'dues_payment_url_prepaid' => route('admin.business.due.payment',['id' => $SkippedDuesRecord->id, 'type' => 'import']), 'plan_upgrade_url' => route('upgrade-plan-business',['id' => $SkippedDuesRecord->id, 'type' => 'import']), 'dues_import_postpaid_url' => route('admin.business.due.postpaid',['id' => $SkippedDuesRecord->id, 'type' => 'import']),
                    ])
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/number-to-word.js')}}"></script>
<script>
    $(document).ready(function() {});

    $(window).on('load', function() {
        $('#myModal').modal('show');
        $('#myModal').on('hidden.bs.modal', function () {
            window.location.href = "{{ $popup_on_close_redirect_url }}";
        });
    });
</script>
@endsection