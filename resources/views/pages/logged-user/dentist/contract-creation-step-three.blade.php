<div class="three step">
    @if(!empty($renew_contract))
        @php($general_dentistry_arr = unserialize($renew_contract->general_dentistry))
    @endif
    <h2 class="text-center calibri-bold fs-30 padding-bottom-25">CONTRACT CONDITIONS</h2>
    <h3 class="calibri-light light-gray-color fs-18 padding-top-35 padding-bottom-10 light-gray-bottom-border">Prophylaxis <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Prophylaxis visits are always covered by Dentacoin Assurance."></i></h3>
    <div class="step-fields module margin-top-10">
        <div class="single-row flex-row fs-0 no-border">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Required Check-ups per Year:</label>
            <select class="right-field calibri-regular fs-18 dark-color inline-block" name="check-ups-per-year">
                @for ($i = 3; $i <= 12; $i+=1)
                    <option @if(!empty($renew_contract) && $renew_contract->check_ups_per_year == $i) selected @endif>{{$i}}</option>
                @endfor
            </select>
        </div>
        <div class="single-row flex-row fs-0 no-border">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Required Teeth Cleaning per Year:</label>
            <select class="right-field calibri-regular fs-18 dark-color inline-block" name="teeth-cleaning-per-year">
                @for ($i = 1; $i <= 4; $i+=1)
                    <option @if(!empty($renew_contract) && $renew_contract->teeth_cleaning_per_year == $i) selected @endif>{{$i}}</option>
                @endfor
            </select>
        </div>
        <div class="single-row flex-row fs-0 padding-bottom-15">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Required Successful Dentacare Journeys:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">1 (90 days)</div>
        </div>
        <div class="single-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 padding-top-0 margin-bottom-0 inline-block">Services Covered:</label>
            <div class="right-extra-field checkboxes-right-container calibri-regular fs-18 dark-color inline-block">
                <div class="pretty single-checkbox-container margin-bottom-5 p-svg p-curve on-white-background">
                    <input type="checkbox" name="general-dentistry[]" value="param_gd" @if(!empty($renew_contract) && in_array('param_gd', $general_dentistry_arr)) checked @endif/>
                    <div class="state p-success">
                        <!-- svg path -->
                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                        </svg>
                        <label class="fs-18 calibri-light">General Dentistry</label>
                    </div>
                </div>
                <div class="show-category-list">
                    <a href="javascript:void(0)" class="lato-bold blue-green-color fs-16 display-block margin-bottom-10">See all services in this category</a>
                    <ul>
                        <li>Check-up</li>
                        <li>Tooth cleaning / scaling / polishing</li>
                        <li>Fillings</li>
                        <li>Caries infiltration</li>
                        <li>Dental sealants for children</li>
                        <li>Root canal treatment</li>
                        <li>Periodontal treatment</li>
                        <li>Tooth extraction</li>
                    </ul>
                </div>
                <div class="pretty single-checkbox-container margin-bottom-5 p-svg p-curve on-white-background">
                    <input type="checkbox" name="general-dentistry[]" value="param_cd" @if(!empty($renew_contract) && in_array('param_cd', $general_dentistry_arr)) checked @endif/>
                    <div class="state p-success">
                        <!-- svg path -->
                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                        </svg>
                        <label class="fs-18 calibri-light">Cosmetic Dentistry</label>
                    </div>
                </div>
                <div class="show-category-list">
                    <a href="javascript:void(0)" class="lato-bold blue-green-color fs-16 display-block margin-bottom-10">See all services in this category</a>
                    <ul>
                        <li>Composite bonding</li>
                        <li>Porcelain veneers (material & laboratory costs - not covered)</li>
                        <li>Composite veneers (material & laboratory costs - not covered)</li>
                        <li>Inlays & onlays (material & laboratory costs - not covered)</li>
                        <li>Crowns (material & laboratory costs - not covered)</li>
                        <li>Bridges (material & laboratory costs - not covered)</li>
                        <li>Dentures (material & laboratory costs - not covered)</li>
                    </ul>
                </div>
                <div class="pretty single-checkbox-container margin-bottom-5 p-svg p-curve on-white-background">
                    <input type="checkbox" name="general-dentistry[]" value="param_id" @if(!empty($renew_contract) && in_array('param_id', $general_dentistry_arr)) checked @endif/>
                    <div class="state p-success">
                        <!-- svg path -->
                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                        </svg>
                        <label class="fs-18 calibri-light">Implant Dentistry</label>
                    </div>
                </div>
                <div class="show-category-list">
                    <a href="javascript:void(0)" class="lato-bold blue-green-color fs-16 display-block margin-bottom-10">See all services in this category</a>
                    <ul>
                        <li>Implant placement (implants and abutments - not covered)</li>
                        <li>Porcelain veneers (material & laboratory costs - not covered)</li>
                        <li>Composite veneers (material & laboratory costs - not covered)</li>
                        <li>Inlays & onlays (material & laboratory costs - not covered)</li>
                        <li>Crowns (material & laboratory costs - not covered)</li>
                        <li>Bridges (material & laboratory costs - not covered)</li>
                        <li>Bone augmentation (bone replacement material - not covered)</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0 cursor-pointer" for="monthly-premium">Monthly Premium:</label>
            <input type="number" name="monthly-premium" id="monthly-premium" maxlength="20" placeholder="The value is in USD" class="right-field calibri-regular fs-18 dark-color inline-block" @if(!empty($renew_contract)) value="{{$renew_contract->monthly_premium}}" @endif/>
        </div>
        @if(!empty($renew_contract))
            @if(in_array('param_gd', $general_dentistry_arr) && in_array('param_cd', $general_dentistry_arr) && in_array('param_id', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_gd_cd_id'])
            @elseif(in_array('param_gd', $general_dentistry_arr) && in_array('param_cd', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_gd_cd'])
            @elseif(in_array('param_gd', $general_dentistry_arr) && in_array('param_id', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_gd_id'])
            @elseif(in_array('param_cd', $general_dentistry_arr) && in_array('param_id', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_cd_id'])
            @elseif(in_array('param_gd', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_gd'])
            @elseif(in_array('param_cd', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_cd'])
            @elseif(in_array('param_id', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_id'])
            @endif
            <div class="single-row fs-0 show-on-services-pick renew-contract no-transition">
                <div class="fs-14 calibri-light light-gray-color padding-top-5">Based on the services covered, the average monthly rate in your country is <span class="calibri-bold blue-green-color"><span class="suggested-price">{{$price}}</span> USD</span> in Dentacoin (DCN).</div>
                <div class="fs-14 calibri-light light-gray-color padding-bottom-25">You are free to propose a different rate to your patient.</div>
            </div>
        @else
            <div class="single-row fs-0 show-on-services-pick no-transition">
                <div class="fs-14 calibri-light light-gray-color padding-top-5">Based on the services covered, the average monthly rate in your country is <span class="calibri-bold blue-green-color"><span class="suggested-price"></span> USD</span> in Dentacoin (DCN).</div>
                <div class="fs-14 calibri-light light-gray-color padding-bottom-25">You are free to propose a different rate to your patient.</div>
            </div>
        @endif
    </div>
</div>