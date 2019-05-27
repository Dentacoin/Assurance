<div class="lato-regular fs-40 fs-xs-30 text-center padding-bottom-10 padding-top-20">
    <div class="inline-block fs-0">
        <svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 46.5 58.8" style="enable-background:new 0 0 46.5 58.8;" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}  .st1{fill:#126585;stroke:#FFFFFF;stroke-width:0.5;stroke-miterlimit:10;}  .st2{fill:#126585;}  .st3{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;stroke:#FFFFFF;stroke-width:2;stroke-miterlimit:10;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="58.8" width="46.5" x="23.7" y="34.1"></sliceSourceBounds></sfw></metadata><path class="st0" d="M23.3,14.1c-7.3,0-13.5,5.3-14.9,12.2c-0.1,0.7-0.2,1.5-0.3,2.2c0,0.3,0,0.6,0,0.9c0,8.4,6.8,15.2,15.2,15.2c7.4,0,13.5-5.3,14.9-12.2c0.2-0.7,0.2-1.4,0.3-2.2c0-0.3,0-0.6,0-0.9C38.5,20.9,31.6,14.1,23.3,14.1z M30.4,33.5c1.9,1.9-1,4.9-2.9,3l-4.2-4.2l-4.2,4.2c-2,1.9-4.9-1-3-3l4.2-4.2l-4.2-4.2c-2-2,1-4.9,3-2.9l4.2,4.2l4.2-4.2c1.9-2,4.8,1,2.9,2.9l-4.2,4.2L30.4,33.5z"/><polygon class="st1" points="36.1,1.1 36.1,10.5 45.5,10.5 "/><path class="st2" d="M38.4,28.6c0,0.3,0,0.5,0,0.8c0,8.4-6.8,15.2-15.2,15.2c-7.3,0-13.4-5.2-14.9-12.1c1.4,6.9,7.5,12.2,14.9,12.2c8.4,0,15.2-6.8,15.2-15.2C38.5,29.2,38.5,28.9,38.4,28.6z M38.4,28.6c0,0.3,0,0.5,0,0.8c0,8.4-6.8,15.2-15.2,15.2c-7.3,0-13.4-5.2-14.9-12.1c1.4,6.9,7.5,12.2,14.9,12.2c8.4,0,15.2-6.8,15.2-15.2C38.5,29.2,38.5,28.9,38.4,28.6z M46.5,58.8V12h-12V0L0,0l0,58.8H46.5z M40,29.5c0,9.2-7.5,16.7-16.7,16.7c-9.2,0-16.7-7.5-16.7-16.7c0-9.2,7.5-16.7,16.7-16.7C32.5,12.7,40,20.2,40,29.5z M38.5,29.5c0-0.3,0-0.6,0-0.9C38.4,28.9,38.5,29.1,38.5,29.5c0,8.3-6.8,15.1-15.2,15.1c-7.3,0-13.4-5.2-14.9-12.1c1.4,6.9,7.5,12.2,14.9,12.2C31.6,44.6,38.5,37.8,38.5,29.5z"/><g><path class="st3" d="M23.3,14.1c-7.3,0-13.5,5.3-14.9,12.2c-0.1,0.7-0.2,1.5-0.3,2.2c0,0.3,0,0.5,0,0.8c0,8.4,6.8,15.2,15.2,15.2c7.3,0,13.5-5.2,14.9-12.1c0.2-0.7,0.2-1.4,0.3-2.2c0-0.3,0-0.6,0-0.9C38.5,20.9,31.6,14.1,23.3,14.1z M30.4,33.5c1.9,1.9-1,4.9-2.9,3l-4.2-4.2l-4.2,4.2c-2,1.9-4.9-1-3-3l4.2-4.2l-4.2-4.2c-2-2,1-4.9,3-2.9l4.2,4.2l4.2-4.2c1.9-2,4.8,1,2.9,2.9l-4.2,4.2L30.4,33.5z"/></g></svg>
    </div>
    <div class="inline-block">Cancel Contract</div>
</div>
<div class="fs-20 fs-16 padding-bottom-20 calibri-regular padding-bottom-30 text-center width-80 margin-0-auto width-xs-100">Please let {{$receiver_name}} know why you'd like to cancel this contract:</div>
<div class="popup-row">
    <label for="cancel-contract-reason" class="inline-block-top">Cancellation reason</label>
    <select id="cancel-contract-reason" class="inline-block-top">
        <option selected value="">Choose from the list</option>
        @if($type == 'dentist')
            <option value="Overdue payments">Overdue payments</option>
            <option value="Missed regular check-ups">Missed regular check-ups</option>
            <option value="Inappropriate behaviour">Inappropriate behaviour</option>
        @elseif($type == 'patient')
            @if($contract_status == 'pending')
                <option value="I don't need this contract.">I don't need this contract.</option>
                <option value="The monthly premium is too high.">The monthly premium is too high.</option>
                <option value="I don't like the conditions.">I don't like the conditions.</option>
                <option value="I think I've received this by mistake.">I think I've received this by mistake.</option>
            @else
                <option value="I don't need this contract anymore.">I don't need this contract anymore.</option>
                <option value="I decided to go to a different dentist.">I decided to go to a different dentist.</option>
                <option value="Inappropriate behaviour or service.">Inappropriate behaviour or service.</option>
            @endif
        @endif
        <option value="Other" data-open-bonus-field="true">Other</option>
    </select>
</div>
<div class="camp-for-row"></div>
<div class="popup-row">
    <label for="cancel-contract-comments" class="inline-block-top">Comments:</label>
    <textarea id="cancel-contract-comments" maxlength="3000" class="pencil-background inline-block-top" placeholder="Describe the reason for cancelling this contract in more details (optional)"></textarea>
</div>
<div class="btn-container text-center padding-top-35 padding-bottom-25">
    <a href="javascript:void(0);" class="red-white-btn cancel-contract-popup-confirmation">CANCEL NOW</a>
</div>