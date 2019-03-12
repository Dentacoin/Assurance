<div class="lato-regular fs-40 text-center padding-bottom-40 padding-bottom-xs-20 padding-top-15"><i class="fa fa-calculator blue-green-color margin-right-15 fs-36" aria-hidden="true"></i>Calculator</div>
<div class="popup-row fs-0 flex-xs">
    <label class="fs-18 fs-xs-16 calibri-light dark-color inline-block">Average number of patients per day:</label>
    <div class="input-holder inline-block">
        <input type="number" id="number-of-patients" class="fs-18" @if(!empty($params)) value="{{$params['patients_number']}}" @endif/>
    </div>
</div>
<div class="popup-row padding-top-15 padding-bottom-15 fs-0">
    <label class="fs-18 fs-xs-16 calibri-light dark-color padding-bottom-15">Specialties:</label>
    <div class="input-holder padding-top-10 inline-block">
        <div class="pretty p-svg p-curve">
            <input type="checkbox" id="general-dentistry" @if(!empty($params)) @if($params['param_gd']) checked @endif @endif />
            <div class="state p-success">
                <!-- svg path -->
                <svg class="svg svg-icon" viewBox="0 0 20 20">
                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                </svg>
                <label class="fs-18 fs-xs-16 calibri-light black-color">General Dentistry</label>
            </div>
        </div>
        <div class="pretty p-svg p-curve">
            <input type="checkbox" id="cosmetic-dentistry" @if(!empty($params)) @if($params['param_cd']) checked @endif @endif />
            <div class="state p-success">
                <!-- svg path -->
                <svg class="svg svg-icon" viewBox="0 0 20 20">
                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                </svg>
                <label class="fs-18 fs-xs-16 calibri-light black-color">Cosmetic Dentistry</label>
            </div>
        </div>
        <div class="pretty p-svg p-curve">
            <input type="checkbox" id="implant-dentistry" @if(!empty($params)) @if($params['param_id']) checked @endif @endif />
            <div class="state p-success">
                <!-- svg path -->
                <svg class="svg svg-icon" viewBox="0 0 20 20">
                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                </svg>
                <label class="fs-18 fs-xs-16 calibri-light black-color">Implant Dentistry</label>
            </div>
        </div>
    </div>
</div>
<div class="popup-row padding-top-15 padding-bottom-15 select fs-0">
    <label class="fs-18 fs-xs-16 calibri-light dark-color inline-block">Your country:</label>
    <div class="input-holder inline-block">
        <select class="selectpicker" data-live-search="true" required id="country">
            @foreach($parameters as $parameter)
                <option value="{{$parameter->id}}" @if(!empty($params)) @if($params['country'] == $parameter->id) selected @endif @endif>{{$parameter->country}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="popup-row padding-top-15 padding-bottom-15 select fs-0">
    <label class="fs-18 fs-xs-16 calibri-light dark-color inline-block">Your currency:</label>
    <div class="input-holder inline-block">
        <select class="selectpicker" data-live-search="true" required id="currency">
            @foreach($currencies as $currency)
                <option value="{{$currency}}" @if(!empty($params)) @if($params['currency'] == $currency) selected @endif @endif>{{$currency}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="text-center padding-top-40 padding-bottom-30">
    <a href="javascript:void(0)" class="white-blue-green-btn min-width-250 calculate">CALCULATE</a>
</div>