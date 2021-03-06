<form method="POST" enctype="multipart/form-data" id="invite-dentists" action="{{route('submit-invite-dentists')}}">
    <div class="padding-bottom-15 fs-0">
        <select name="title" class="custom-input">
            <option value="">Choose a title (optional)</option>
            @php($api_enums = (new \App\Http\Controllers\APIRequestsController())->getAllEnums())
            @if (!empty($api_enums))
                @foreach ($api_enums->titles as $key => $title)
                    <option value="{{$title}}">{{$title}}</option>
                @endforeach
            @endif

        </select>
    </div>
    <div class="padding-bottom-15 fs-0">
        <input class="fs-16 custom-input required" maxlength="100" type="text" name="dentist-name" placeholder="Your Dentist/ Clinic Name"/>
    </div>
    <div class="padding-bottom-15 fs-0">
        <input class="fs-16 custom-input required" maxlength="250" type="text" name="website" placeholder="Your Dentist/ Clinic Website"/>
    </div>
    <div class="padding-bottom-15 fs-0">
        <input class="fs-16 custom-input required" maxlength="100" type="email" name="email" placeholder="Your Dentist/ Clinic Email"/>
    </div>
    <div class="padding-bottom-15 fs-0">
        <input class="fs-16 custom-input" maxlength="50" type="number" name="phone" placeholder="Your Dentist/ Clinic Phone"/>
    </div>
    <div class="btn-container padding-top-40">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="redirect" value="{{$redirect}}"/>
        <input type="submit" value="SEND" class="white-blue-green-btn min-width-220"/>
    </div>
</form>