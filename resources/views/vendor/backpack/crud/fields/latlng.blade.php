<div class="form-group col-md-8">
    <label>{{ $field['label'] }}</label>
    <div class="input-group">          
        <div class="input-group-addon">
            <i class="fa fa-map-marker"></i>
        </div>
        <input id="search_address" class="form-control" type="text" placeholder="{{ __('Location') }}" />
        <div id="geolocate" class="input-group-addon btn btn-primary">
            <i class="fa fa-compass"></i>
        </div>
    </div>
</div>
<div class="form-group col-md-4">
    <label>&nbsp;</label>
    <input
    type="text"
    id="latlong"
    class="form-control"
    @foreach ($field as $attribute => $value)
        @if (is_string($attribute) && is_string($value))
           @if($attribute == 'value')
                {{ $attribute }}="{{ old($field['name']) ? old($field['name']) : $value }}"
            @else
                {{ $attribute }}="{{ $value }}"
            @endif
        @endif
    @endforeach
    >
</div>
<div class="form-group col-md-12">
    <div id="map-canvas" style="{{ $field['map_style'] }}"></div>  
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
	    {{-- YOUR CSS HERE --}}
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    	{{-- YOUR JS HERE --}}
        <script src="https://maps.googleapis.com/maps/api/js?v=3&libraries=places&key={{ $field['google_api_key'] }}"></script>
        <script>
        $(document).ready(function() {

            // Prevent submit on Enter
            $('#search_address').keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            function initialize() {
                var default_zoom = {{ $field['default_zoom'] }};
                var latlong = document.getElementById('latlong').value.split(','); // get latlng value if any
                var latlng = latlong.length != 2 ? new google.maps.LatLng(41.1579438, -8.6291053) : new google.maps.LatLng(latlong[0], latlong[1]);
                var geocoder = new google.maps.Geocoder();
                var map = new google.maps.Map(document.getElementById('map-canvas'), {
                        center: latlng,
                        zoom: default_zoom,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        draggableCursor: "pointer",
                        streetViewControl: false
                    });
                var marker = new google.maps.Marker({
                        map: map,
                        icon : '{{ $field['marker_icon'] ?? '' }}',
                        position: latlng
                    });

                // set search_address with formatted_address if latlng has value
                if (latlong.length == 2) {
                    setGeoCoder(latlng);
                } else {
                    geolocate();
                }

                google.maps.event.addListener(map, "click", function (location) {
                    //map.setCenter(location.latLng); // set location to map's center each time map is clicked
                    setLatLong(location.latLng.lat(), location.latLng.lng());

                    marker.setPosition(location.latLng);
                    setGeoCoder(location.latLng);
                });

                var input = document.getElementById('search_address');
                var autocomplete = new google.maps.places.Autocomplete(input);

                autocomplete.bindTo('bounds', map);
                autocomplete.setTypes([]);

                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    var place = autocomplete.getPlace();

                    if (!place.geometry) return;

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(default_zoom);
                    }
                    map.setZoom(default_zoom);
                    marker.setPosition(place.geometry.location);
                    setLatLong(place.geometry.location.lat(), place.geometry.location.lng());
                });

                document.getElementById('geolocate').onclick = geolocate;

                function geolocate() {
                    $('#geolocate > i').addClass('fa-spin'); // add spin animation to locate icon

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition( function(position) {
                            var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                            map.setCenter(pos);
                            map.setZoom(default_zoom);
                            marker.setPosition(pos);

                            setGeoCoder(pos);
                            setLatLong(pos.lat(), pos.lng());

                            $('#geolocate > i').removeClass('fa-spin');
                        }, function() {
                            handleNoGeolocation(true);
                        });
                    } else {
                        handleNoGeolocation(false);
                    }
                }

                function setLatLong(lat, long) {
                    document.getElementById('latlong').value = lat + ', ' + long;
                }

                function setGeoCoder(pos) {
                    geocoder.geocode({'location': pos}, function(results, status) {
                        document.getElementById('search_address').value = status == google.maps.GeocoderStatus.OK && results[0] ? results[0].formatted_address : '';
                    });
                }

                function handleNoGeolocation(userBlocked) {
                    if(userBlocked)
                        alert("Please allow Geolocation to fill up the maps location");
                    else
                        alert("Browser doesn't support Geolocation");

                    $('#geolocate > i').removeClass('fa-spin');
                }
            }
            
            google.maps.event.addDomListener(window, 'load', initialize);

        });
        </script>

    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
