@php
    $style = '';
    if(Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'Sub Admin'){
        $plan_upgrade_url = 'javascript:void(0);';
        $style = 'cursor:not-allowed;';
    }
@endphp
<div class="modal-footer">
    @if($dues_type_prepaid_or_postpaid == 0)
        <a href="{{$dues_payment_url_prepaid}}" type="button" class="btn btn-primary">Continue</a>
        @if(HomeHelper::showOrHidePlanUpgradeButton())
            <a href="{{$plan_upgrade_url}}" type="button" style="{{$style}}" class="btn btn-warning">Upgrade Your Plan</a>
        @endif
    @else
        <a href="{{$dues_import_postpaid_url}}" type="button" class="btn btn-primary">Continue</a>
        @if(HomeHelper::showOrHidePlanUpgradeButton())
            <a href="{{$plan_upgrade_url}}" type="button" style="{{$style}}" class="btn btn-warning">Upgrade Your Plan</a>
        @endif
    @endif

    @if($dues_type_prepaid_or_postpaid == 1)
    <br>
    <h5>*Invoice will be generated and send to your email address.</h5>
    @endif
</div>