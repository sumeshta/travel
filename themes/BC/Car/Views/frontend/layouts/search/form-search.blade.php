<form action="{{ route("car.search") }}" class="form bravo_form car-search-form" method="get">
    <div class="g-field-search">
        <div class="row no-gutters car-search-row">
            @php $car_search_fields = setting_item_array('car_search_fields');
            $car_search_fields = array_values(array_filter($car_search_fields, function ($field) {
                return ($field['field'] ?? '') !== 'date';
            }));
            $car_search_fields = array_values(\Illuminate\Support\Arr::sort($car_search_fields, function ($value) {
                $field = $value['field'] ?? '';
                if ($field === 'location') {
                    return 0;
                }
                return (int) ($value['position'] ?? 0) + 1;
            }));
            $carFieldCount = count($car_search_fields);
            $carColSize = $carFieldCount > 0 ? (int) floor(12 / $carFieldCount) : 6;
            @endphp
            @if(!empty($car_search_fields))
                @foreach($car_search_fields as $field)
                    @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
                    <div class="col-md-{{ $carColSize }} border-right car-search-field car-search-field-{{ $field['field'] }}">
                        @switch($field['field'])
                            @case ('service_name')
                                @include('Car::frontend.layouts.search.fields.service_name')
                            @break
                            @case ('location')
                                @include('Car::frontend.layouts.search.fields.location')
                            @break
                            @case ('date')
                                @include('Car::frontend.layouts.search.fields.date')
                            @break
                            @case ('attr')
                                @include('Car::frontend.layouts.search.fields.attr')
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
<style id="car-search-form-critical">
@media (min-width:1024px){
.bravo_search_car .car-search-form .car-search-row{display:flex!important;flex-wrap:nowrap!important;width:100%!important;margin:0!important}
.bravo_search_car .car-search-form .car-search-row>.car-search-field{flex:1 1 0!important;max-width:none!important;width:auto!important;padding:0!important}
}
</style>
