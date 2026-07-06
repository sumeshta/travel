<style>
    .pac-container { z-index: 100000 !important; }
</style>
<script>
    jQuery(function ($) {
        var engineMap = null;
        var mapInited = false;
        var mapCenter = [{{$row->map_lat ?? setting_item('map_lat_default',51.505 ) }}, {{$row->map_lng ?? setting_item('map_lng_default',-0.09 ) }}];
        var mapZoom = {{$row->map_zoom ?? "8"}};
        var locationTab = '{{ $locationTab ?? "#nav-tour-location" }}';

        function placeCityName(place) {
            if (!place) return '';
            if (place.address_components && place.address_components.length) {
                var city = '';
                var fallback = '';
                place.address_components.forEach(function (c) {
                    if (c.types.indexOf('locality') !== -1) city = c.long_name;
                    if (!fallback && (c.types.indexOf('administrative_area_level_1') !== -1 || c.types.indexOf('administrative_area_level_2') !== -1)) {
                        fallback = c.long_name;
                    }
                });
                if (city) return city;
                if (fallback) return fallback;
            }
            return place.name || place.formatted_address || '';
        }

        function applyCoords(lat, lng, place) {
            $("input[name=map_lat]").val(lat).trigger('change');
            $("input[name=map_lng]").val(lng).trigger('change');
            if (place && place.formatted_address) {
                $('#customPlaceAddress').val(place.formatted_address);
            }
            if (!engineMap) return;
            engineMap.clearMarkers();
            engineMap.addMarker([lat, lng], { icon_options: {} });
            if (engineMap.map) {
                engineMap.map.setCenter({ lat: lat, lng: lng });
            }
        }

        function bindServicePlacesFields() {
            if (bookingCore.map_provider !== 'gmap' || typeof window.bravoBindPlacesAutocomplete !== 'function') {
                return;
            }

            window.bravoBindPlacesAutocomplete('#service_location_name', {
                types: ['(cities)'],
                onPlace: function (place, coords) {
                    var name = placeCityName(place);
                    $('#service_location_name').val(name);
                    $('input[name=location_id]').val('');
                    applyCoords(coords.lat, coords.lng, place);
                }
            });

            window.bravoBindPlacesAutocomplete('#customPlaceAddress', {
                onPlace: function (place, coords) {
                    applyCoords(coords.lat, coords.lng, place);
                }
            });

            window.bravoBindPlacesAutocomplete('.bravo_searchbox', {
                onPlace: function (place, coords) {
                    if (place.formatted_address || place.name) {
                        $('.bravo_searchbox').val(place.formatted_address || place.name);
                    }
                    applyCoords(coords.lat, coords.lng, place);
                }
            });
        }

        function initServiceMap() {
            if (mapInited || typeof BravoMapEngine === 'undefined') {
                if (engineMap && engineMap.map && typeof google !== 'undefined') {
                    google.maps.event.trigger(engineMap.map, 'resize');
                    engineMap.map.setCenter({ lat: mapCenter[0], lng: mapCenter[1] });
                }
                return;
            }
            mapInited = true;

            new BravoMapEngine('map_content', {
                fitBounds: true,
                center: mapCenter,
                zoom: mapZoom,
                ready: function (mapInstance) {
                    engineMap = mapInstance;
                    @if(!empty($row->map_lat) && !empty($row->map_lng))
                    engineMap.addMarker([{{$row->map_lat}}, {{$row->map_lng}}], {
                        icon_options: {}
                    });
                    @endif
                    engineMap.on('click', function (dataLatLng) {
                        applyCoords(dataLatLng[0], dataLatLng[1]);
                    });
                    engineMap.on('zoom_changed', function (zoom) {
                        $("input[name=map_zoom]").val(zoom);
                    });
                    setTimeout(function () {
                        if (engineMap.map && typeof google !== 'undefined') {
                            google.maps.event.trigger(engineMap.map, 'resize');
                            engineMap.map.setCenter({ lat: mapCenter[0], lng: mapCenter[1] });
                        }
                    }, 200);
                }
            });
        }

        function bootServiceLocationUi() {
            bindServicePlacesFields();
            if ($(locationTab).hasClass('active') || $(locationTab).hasClass('show')) {
                initServiceMap();
            }
        }

        $('a[href="' + locationTab + '"]').on('shown.bs.tab', function () {
            initServiceMap();
        });

        if (typeof window.bravoWhenGooglePlacesReady === 'function') {
            window.bravoWhenGooglePlacesReady(bootServiceLocationUi);
        } else {
            bootServiceLocationUi();
        }
    });
</script>
