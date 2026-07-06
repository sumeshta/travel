@php
    $homeSearchMapId = 'map-home-' . \Illuminate\Support\Str::random(8);
    $initialCategory = request()->query('search_category', 'tourpackage');
    $validCategories = ['tourpackage', 'touragnt', 'tourvehicle', 'stay', 'event', 'boat', 'touritinerary'];
    if (!in_array($initialCategory, $validCategories, true)) {
        $initialCategory = 'tourpackage';
    }
    $homeSearchFormClass = 'form bravo_form';
    if ($initialCategory === 'stay') {
        $homeSearchFormClass .= ' is-stay-search';
    } elseif ($initialCategory === 'tourvehicle') {
        $homeSearchFormClass .= ' is-vehicle-search';
    } elseif ($initialCategory === 'touragnt') {
        $homeSearchFormClass .= ' is-agent-search';
    } elseif (in_array($initialCategory, ['tourpackage', 'touritinerary'], true)) {
        $homeSearchFormClass .= ' is-tour-package-search';
    }
@endphp
<!-- home-search-form v{{ config('app.asset_version') }} built {{ date('Y-m-d H:i') }} -->
<link rel="stylesheet" href="{{ asset('css/home-search-form.css?_ver='.config('app.asset_version')) }}">
<style id="home-search-form-critical">
/* Inline fallback: live servers often cache Blade/OPcache — this always ships with the block */
.bravo_wrap .page-template-content .bravo-form-search-all.carousel_v2 #g-form-control-id .nav-tabs:empty{display:none!important;margin:0!important;padding:0!important;height:0!important;min-height:0!important;border:none!important}
.bravo_wrap .page-template-content .bravo-form-search-all.carousel_v2 #g-form-control-id .nav-tabs{margin-top:0!important}
.bravo_wrap .page-template-content .bravo-form-search-all.carousel_v2 #g-form-control-id{margin-top:0!important;margin-bottom:0!important}
.bravo_wrap .page-template-content .bravo-form-search-all #g-form-control-id{top:21px!important}
@media (max-width:767px){.bravo_wrap .page-template-content .bravo-form-search-all #g-form-control-id{top:120px!important}}
</style>
<div class="g-form-control" id="g-form-control-id" style="position:relative !important;">
    <ul class="nav nav-tabs" role="tablist"></ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active">
            <form id="searchForm" action="{{ route('tour.search') }}" class="{{ $homeSearchFormClass }}" method="get">
                <div class="g-field-search">
                    <div class="row">
                        <div class="col-md-3 border-right home-search-category">
                            <div class="form-group">
                                <i class="field-icon icofont-travelling"></i>
                                <div class="form-content">
                                    <label>{{ __('Category List') }}</label>
                                    <div class="input-search">
                                        <select class="form-control" style="border:none;" id="selectcategoryid">
                                            <option value="tourpackage" @if($initialCategory === 'tourpackage') selected @endif>{{ __('Tour Packages') }}</option>
                                            <option value="touragnt" @if($initialCategory === 'touragnt') selected @endif>{{ __('Travel Agent') }}</option>
                                            <option value="tourvehicle" @if($initialCategory === 'tourvehicle') selected @endif>{{ __('Tourist Vehicle') }}</option>
                                            <option value="stay" @if($initialCategory === 'stay') selected @endif>{{ __('Stay List') }}</option>
                                            <option value="event" @if($initialCategory === 'event') selected @endif>{{ __('Event & Park') }}</option>
                                            <option value="boat" @if($initialCategory === 'boat') selected @endif>{{ __('Boat & Cruise') }}</option>
                                            <option value="touritinerary" @if($initialCategory === 'touritinerary') selected @endif>{{ __('Tour Itinerary') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 border-right location-field stay-field" style="display:none;">
                            <div class="form-group">
                                <i class="field-icon fa icofont-map"></i>
                                <div class="form-content">
                                    <label>{{ __('Location') }}</label>
                                    <div class="input-search g-map-place">
                                        <input type="text" name="map_place" class="form-control border-0 location-only-input pac-target-input"
                                               placeholder="{{ __('Where are you going?') }}" value="{{ request()->input('map_place') }}" autocomplete="off">
                                        <div class="map d-none" id="{{ $homeSearchMapId }}"></div>
                                        <input type="hidden" name="map_lat" class="location-only-input" value="{{ request()->input('map_lat') }}">
                                        <input type="hidden" name="map_lgn" class="location-only-input" value="{{ request()->input('map_lgn') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 border-right tour-package-field">
                            <div class="form-group">
                                <i class="field-icon fa icofont-map"></i>
                                <div class="form-content">
                                    <label>{{ __('Location') }}</label>
                                    <div class="input-search tour-home-start-search">
                                        <input type="text" name="departure" id="homeTourStart" class="form-control border-0 tour-only-input"
                                               placeholder="{{ __('Tour start location') }}" value="{{ request()->input('departure') }}" autocomplete="off">
                                        <div id="homeTourStartSuggestions" class="suggestions-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 border-right tour-package-field">
                            <div class="form-group">
                                <i class="field-icon fa icofont-map"></i>
                                <div class="form-content">
                                    <label>{{ __('Tour Destination') }}</label>
                                    <div class="input-search tour-home-destination-search">
                                        <input type="text" name="destination" id="homeTourDestination" class="form-control border-0 tour-only-input"
                                               placeholder="{{ __('Tour end destination') }}" value="{{ request()->input('destination') }}" autocomplete="off">
                                        <div id="homeTourDestinationSuggestions" class="suggestions-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 border-right home-search-list-name">
                            <div class="form-group">
                                <i class="field-icon fa icofont-search"></i>
                                <div class="form-content">
                                    <label id="homeServiceNameLabel">{{ __('List Name') }}</label>
                                    <div class="input-search">
                                        <input type="text" id="homeServiceName" name="service_name" class="form-control"
                                               placeholder="{{ __('Search package name') }}" value="{{ request()->input('service_name') }}" autocomplete="off">
                                        <div id="homeSearchSuggestions" class="suggestions-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="g-button-submit">
                    <button class="btn btn-primary btn-search" type="submit">{{ __('Search') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
jQuery(function ($) {
    var searchRoutes = {
        tourpackage: '{{ route("tour.search") }}',
        touragnt: '{{ route("space.search") }}',
        tourvehicle: '{{ route("car.search") }}',
        stay: '{{ route("hotel.search") }}',
        event: '{{ route("event.search") }}',
        boat: '{{ route("boat.search") }}',
        touritinerary: '{{ route("tour.search") }}'
    };

    var suggestRoutes = {
        tourpackage: '{{ route("tourservices.search") }}',
        touragnt: '{{ route("spaceservices.search") }}',
        tourvehicle: '{{ route("carservices.search") }}',
        stay: '{{ route("hotelservices.search") }}',
        event: '{{ route("eventservices.search") }}',
        boat: '{{ route("boatservices.search") }}',
        touritinerary: '{{ route("tourservices.search") }}'
    };

    function applyCategory(category) {
        var $form = $('#searchForm');
        $form.attr('action', searchRoutes[category] || searchRoutes.tourpackage);

        var isStay = category === 'stay';
        var isVehicle = category === 'tourvehicle';
        var isAgent = category === 'touragnt';
        var isTourPackage = category === 'tourpackage' || category === 'touritinerary';
        var showLocation = isStay || isVehicle || isAgent;

        $('.location-field').toggle(showLocation);
        $('.tour-package-field').toggle(isTourPackage);
        $('.tour-field').toggle(false);
        $form.toggleClass('is-stay-search', isStay);
        $form.toggleClass('is-vehicle-search', isVehicle);
        $form.toggleClass('is-agent-search', isAgent);
        $form.toggleClass('is-tour-package-search', isTourPackage);
        $('.tour-only-input').prop('disabled', !isTourPackage);
        $('.location-only-input').prop('disabled', !showLocation);

        $('#homeServiceNameLabel').text(isStay ? '{{ __("Hotel / Stay Name") }}' : '{{ __("List Name") }}');
        $('#homeSearchSuggestions').empty();
        $('#homeTourDestinationSuggestions').empty();
        $('#homeTourStartSuggestions').empty();

        if (showLocation && typeof window.bravoInitMapPlaceLocation === 'function') {
            $('#searchForm .g-map-place').each(function () {
                window.bravoInitMapPlaceLocation($(this), { autoFill: true });
            });
        }
    }

    $('#selectcategoryid').on('change', function () {
        applyCategory(this.value);
    });

    applyCategory($('#selectcategoryid').val());

    var suggestXhr = null;

    $('#homeServiceName').on('keyup', function () {
        var query = $.trim($(this).val());
        var category = $('#selectcategoryid').val();
        var url = suggestRoutes[category];
        var $box = $('#homeSearchSuggestions');

        if (!url || query.length < 1) {
            $box.empty();
            return;
        }

        if (suggestXhr) {
            suggestXhr.abort();
        }

        suggestXhr = $.get(url, { query: query })
            .done(function (data) {
                $box.empty();
                if (data && data.length) {
                    data.forEach(function (service) {
                        if (service && service.title) {
                            $box.append($('<div class="suggestion-item"></div>').text(service.title));
                        }
                    });
                }
            })
            .fail(function () {
                $box.empty();
            });
    });

    $(document).on('click', '#homeSearchSuggestions .suggestion-item', function () {
        $('#homeServiceName').val($(this).text());
        $('#homeSearchSuggestions').empty();
    });

    function bindTourSuggest($inputSel, $boxSel, url) {
        var xhr = null;
        $(inputSel).on('keyup', function () {
            var query = $.trim($(this).val());
            var $box = $(boxSel);
            if (query.length < 1) {
                $box.empty();
                return;
            }
            if (xhr) {
                xhr.abort();
            }
            xhr = $.get(url, { query: query })
                .done(function (data) {
                    $box.empty();
                    if (data && data.length) {
                        data.forEach(function (item) {
                            if (item && item.title) {
                                $box.append($('<div class="suggestion-item"></div>').text(item.title));
                            }
                        });
                    }
                })
                .fail(function () {
                    $box.empty();
                });
        });

        $(document).on('click', boxSel + ' .suggestion-item', function () {
            $(inputSel).val($(this).text());
            $(boxSel).empty();
        });
    }

    bindTourSuggest('#homeTourStart', '#homeTourStartSuggestions', '{{ route("tour.departure.search") }}');
    bindTourSuggest('#homeTourDestination', '#homeTourDestinationSuggestions', '{{ route("tour.destination.search") }}');
});
</script>
@endpush
