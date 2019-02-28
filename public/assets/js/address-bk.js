var mapsLoaded = true;
var mapsWaiting = [];

var prepareMapFunction = function( callback ) {
    if(mapsLoaded) {
        callback();
    } else {
        mapsWaiting.push(callback);
    }
};

var initAddressSuggesters;
jQuery(document).ready(function($){
    initAddressSuggesters = function() {
        prepareMapFunction(function() {
            $('.address-suggester').each( function() {
                var this_suggester = $(this);
                var step_container = this_suggester.closest('.step.second');

                this_suggester.on('keyup keypress', function(e) {
                    var keyCode = e.keyCode || e.which;
                    if (keyCode === 13) {
                        e.preventDefault();
                        return false;
                    }

                    if(this_suggester.val().trim() == '') {
                        step_container.find('.suggester-map-div').hide();
                    }
                });

                step_container.find('.country-select').change( function() {
                    var cc = $(this).find('option:selected').val();
                    GMautocomplete.setComponentRestrictions({
                        'country': cc
                    });
                });

                if(step_container.find('.suggester-map-div').attr('lat')) {
                    var coords = {
                        lat: parseFloat( step_container.find('.suggester-map-div').attr('lat')),
                        lng: parseFloat( step_container.find('.suggester-map-div').attr('lon'))
                    };

                    step_container.find('.suggester-map-div').show();
                    var profile_address_map = new google.maps.Map(step_container.find('.suggester-map-div')[0], {
                        center: coords,
                        zoom: 14,
                        backgroundColor: 'none'
                    });

                    var marker = new google.maps.Marker({
                        map: profile_address_map,
                        position: coords,
                    });
                }

                var input = $(this)[0];
                var cc = step_container.find('.country-select option:selected').attr('value');
                var options = {
                    componentRestrictions: {
                        country: cc
                    },
                    types: ['address']
                };

                var GMautocomplete = new google.maps.places.Autocomplete(input, options);
                GMautocomplete.step_container = step_container;
                google.maps.event.addListener(GMautocomplete, 'place_changed', (function () {
                    var place = this.getPlace();

                    this.step_container.find('.address-suggester').blur();
                    this.step_container.find('.geoip-hint').hide();
                    this.step_container.find('.suggester-map-div').hide();

                    if(place && place.geometry) {
                        //address_components
                        if(place.types.indexOf('street_address')!=-1 || place.types.indexOf('street_number')!=-1) {
                            var newaddress = place.name + ', ' + place.vicinity;
                            this.step_container.find('.address-suggester').val(newaddress);

                            prepareMapFunction((function() {
                                var coords = {
                                    lat: place.geometry.location.lat(),
                                    lng: place.geometry.location.lng()
                                };

                                this.step_container.find('.suggester-map-div').show();
                                var profile_address_map = new google.maps.Map(this.step_container.find('.suggester-map-div')[0], {
                                    center: coords,
                                    zoom: 14,
                                    backgroundColor: 'none'
                                });

                                var marker = new google.maps.Marker({
                                    map: profile_address_map,
                                    position: coords,
                                });
                            }).bind(this));
                            return;
                        }
                    }
                    this.step_container.find('.geoip-hint').show();
                }).bind(GMautocomplete));
            });
        });
    };

    if($('.address-suggester').length) {
        initAddressSuggesters();
    }
});