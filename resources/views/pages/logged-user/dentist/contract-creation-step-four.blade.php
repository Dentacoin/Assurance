<div class="four step">
    @if(!empty($contract))
        @php($params = array('contract' => $contract, 'type' => 'contract-creation-step-four'))
    @else
        @php($params = array('type' => 'contract-creation-step-four'))
    @endif
    @include('partials.contract-details', $params)
</div>