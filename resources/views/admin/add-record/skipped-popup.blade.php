@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Submit Dues')

@section('page_header')
<h1 class="page-title">
    <i class="voyager-plus"></i>Submit Individual Customer Dues
</h1>
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
        text-transform: uppercase;
    }
</style>
<div class="page-content container-fluid">
    @include('voyager::alerts')
    <div class="row">

        <div id="myModal" class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Total {{count($requestData['due_date'])}} record skipped</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @include('admin.add-record.partials.skipped-popup-modal-body', ['user_plan_data' => HomeHelper::getImportOrSubmitDuesPopupData(Auth::user()), 'user' => Auth::user()])

                     <!-- Additional dues payment modal popup footer -->
                    @include('admin.add-record.partials.skipped-popup-modal-footer', [
                        'user' => Auth::user(),
                        'dues_type_prepaid_or_postpaid' => Auth::user()->reports_individual_additional_customer,
                        'dues_payment_url_prepaid' => route('admin.due.payment',['id' => $SkippedDuesRecord->id]),
                        'plan_upgrade_url' => route('upgrade-plan-due',['id' => $SkippedDuesRecord->id]),
                        'dues_import_postpaid_url' => route('admin.due.postpaid',['id' => $SkippedDuesRecord->id]),
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
            window.location.href = "{{ route('add-record') }}";
        });
    });
</script>
@endsection