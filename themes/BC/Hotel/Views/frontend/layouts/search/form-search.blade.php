<form action="{{ route("hotel.search") }}" class="form bravo_form hotel-search-form" method="get">
    <div class="g-field-search">
        <div class="row no-gutters hotel-search-row">
            @php $hotel_search_fields = setting_item_array('hotel_search_fields');
            $hotel_search_fields = array_values(array_filter($hotel_search_fields, function ($field) {
                return !in_array($field['field'] ?? '', ['date', 'guests'], true);
            }));
            $hotel_search_fields = array_values(\Illuminate\Support\Arr::sort($hotel_search_fields, function ($value) {
                $field = $value['field'] ?? '';
                if ($field === 'location') {
                    return 0;
                }
                return (int) ($value['position'] ?? 0) + 1;
            }));
            $hotelFieldCount = count($hotel_search_fields);
            $hotelColSize = $hotelFieldCount > 0 ? (int) floor(12 / $hotelFieldCount) : 6;
            @endphp
            @if(!empty($hotel_search_fields))
                @foreach($hotel_search_fields as $field)
                    @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
                    <div class="col-md-{{ $hotelColSize }} border-right hotel-search-field hotel-search-field-{{ $field['field'] }}">
                        @switch($field['field'])
                            @case ('service_name')
                                @include('Hotel::frontend.layouts.search.fields.service_name')
                            @break
                            @case ('location')
                                @include('Hotel::frontend.layouts.search.fields.location')
                            @break
                            @case ('date')
                                @include('Hotel::frontend.layouts.search.fields.date')
                            @break
                            @case ('attr')
                                @include('Hotel::frontend.layouts.search.fields.attr')
                            @break
                            @case ('guests')
                                @include('Hotel::frontend.layouts.search.fields.guests')
                            @break
                        @endswitch
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="g-button-submit">
        <button class="btn btn-primary btn-search" type="submit">{{__("Search")}}</button>
    </div>
</form>

<link rel="stylesheet" href="{{ asset('css/service-search-form.css?_ver='.config('app.asset_version')) }}">
<style id="hotel-search-form-critical">
@media (min-width:1024px){
.bravo_search_hotel .hotel-search-form .hotel-search-row{display:flex!important;flex-wrap:nowrap!important;width:100%!important;margin:0!important}
.bravo_search_hotel .hotel-search-form .hotel-search-row>.hotel-search-field{flex:1 1 0!important;max-width:none!important;width:auto!important;padding:0!important}
}
</style>

@push('js')
<script>
jQuery(function ($) {
    $(document).on('keyup', '.hotel-service-name-input', function () {
        var query = $.trim($(this).val());
        var $box = $(this).closest('.input-search').find('.hotel-service-suggestions');

        if (query.length < 1) {
            $box.empty();
            return;
        }

        $.get('{{ route("hotelservices.search") }}', { query: query })
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

    $(document).on('click', '.hotel-service-suggestions .suggestion-item', function () {
        var $input = $(this).closest('.input-search').find('.hotel-service-name-input');
        $input.val($(this).text());
        $(this).closest('.hotel-service-suggestions').empty();
    });
});
</script>
@endpush
