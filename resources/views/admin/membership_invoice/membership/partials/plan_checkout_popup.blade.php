@if(!isset($states) && !empty($states))
    @php
        $states = General::getStateList();
    @endphp
@endif
<div id="select_plan" class="popup-wrap">
    <div class="popup-overlay"></div>
    <div class="extra-wrap">
        <div class="extra-inner">
            <div class="popup-outer">
                <div class="popup-box">
                    <header class="popup-header">
                        <a class="popup-close" href="javascript:void(0);">×</a>
                        <h4 class="text-left">Payment for <span class="plan_name">BASIC</span> Membership Plan</h4>
                    </header>
                    <div class="popup-scroll">
                        <div class="popup-body text-left">
                            <div class="form-group hidden">No Payment Method Required</div>
                            <input type="hidden" name="plan_id_val" id="plan_id_val" value=1>
                            <div class="form-group hidden">
                                <label>
                                <input type="radio" name="payment_method" class="payment_method" value="paytm" checked>
                                Paytm    
                                </label>
                            </div>
                            <div class="clearfix">
                                <div class="form-group state_field">
                                    <label for="name" class="col-md-4">State</label>
                                    <div class="col-md-8">
                                        <select class="form-control select2" id="state_id" name="state_id"  >
                                            <option value="">Select</option>
                                            @foreach($states as $state)
                                                
                                                <option value="{{$state->id}}" {{Auth::user()->state_id==$state->id ? 'selected' : ''}}>{{$state->name}}</option>
                                            @endforeach
                                        </select>   
                                        <label id="state_id_error" class="error"></label>  
                                    </div>
                                </div>
                                <div class="form-group gstin_udise_field">
                                    <label for="name" class="col-md-4">GSTIN/UDISE</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="gstin_udise" name="gstin_udise" placeholder="GSTIN/UDISE" 
                                                value="{{ Auth::user()->gstin_udise ?? '' }}" maxlength="15"> 
                                        <label id="gstin_udise_error" class="error"></label>  
                                    </div>
                                </div>
                                <div class="form-group email_field">
                                    <label for="name" class="col-md-4">Email</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" 
                                                value="{{ Auth::user()->email ?? '' }}"> 
                                        <label id="email_error" class="error"></label>  
                                    </div> 
                                </div>
                                @if(isset($checkOffer))
                                    <input type="hidden" id="code_status" value="{{$checkOffer}}"/>
                                    <input type="hidden" id="is_discount" value="0"/>
                                @endif
                                <table class="col-md-12 col-sm-12 col-xs-12 checkout_table">
                                    <tr>
                                        <th><b>Details</b></th>
                                        <th><b class="amount">Amount</b></th>
                                    </tr>
                                    <tr>
                                        <td><span class="plan_name">BASIC</span> Membership Plan</td>
                                        <td><span class="plan_price">₹599</span></td>
                                    </tr>
                                    <tr class="amount_adjusted">
                                        <td>Amount Adjusted</td>
                                        <td><span class="amount_adjusted_value">₹0.00</span></td>
                                    </tr>
                                    <tr class="plan_sub_total" style="display: none;">
                                        <td>Sub total</td>
                                        <td><span class="plan_subtotal_price">0.00</span></td>
                                    </tr>
                                    @if(isset($show_discount_section))
                                        <tr class="discount_ten">
                                            <td>OneCode Discount @ <?php echo setting('admin.one_code_discount') ?>%</td>
                                            <td><span class="discount_ten_price">₹53.91</span></td>
                                        </tr>
                                        <tr class="discount_subtotal">
                                            <td>Sub total</td>
                                            <td><span class="discount_subtotal_price">₹53.91</span></td>
                                        </tr>
                                    @endif
                                    <tr class="central_gst">
                                        <td>IGST </td>
                                        <td><span class="plan_price_igst">₹53.91</span></td>
                                    </tr>
                                    <tr class="state_gst">
                                        <td>CGST </td>
                                        <td><span class="plan_price_cgst">₹53.91</span></td>
                                    </tr>
                                    <tr class="state_gst">
                                        <td>SGST </td>
                                        <td><span class="plan_price_sgst">₹53.91</span></td>
                                    </tr>
                                    <tr class="conv_fee_tr">
                                        <td>Convenience Fee</td>
                                        <td><span class="conv_fee">₹0</span></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td><span class="total_price">₹720.95</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <footer class="popup-footer">
                        <div class="pull-right">
                            <button type="button" name="submit_required" id="submit_required" class="btn-checkout btn btn-info">Submit</button>
                            <button type="button" name="checkout" id="checkout_button" class="btn-checkout btn btn-info">Pay Now</button>
                        </div>
                        <div class="clearfix"></div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>