<form action="{{ route("space.search") }}" class="form bravo_form space-search-form" method="get">
    <div class="g-field-search">
        <div class="row no-gutters space-search-row">
            @php $space_search_fields = setting_item_array('space_search_fields');
            $space_search_fields = array_values(array_filter($space_search_fields, function ($field) {
                return !in_array($field['field'] ?? '', ['date', 'guests'], true);
            }));
            $space_search_fields = array_values(\Illuminate\Support\Arr::sort($space_search_fields, function ($value) {
                $field = $value['field'] ?? '';
                if ($field === 'location') {
                    return 0;
                }
                return (int) ($value['position'] ?? 0) + 1;
            }));
            $spaceFieldCount = count($space_search_fields);
            $spaceColSize = $spaceFieldCount > 0 ? (int) floor(12 / $spaceFieldCount) : 6;
            @endphp
            @if(!empty($space_search_fields))
                @foreach($space_search_fields as $field)
                    @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
                    <div class="col-md-{{ $spaceColSize }} border-right space-search-field space-search-field-{{ $field['field'] }}">
                        @switch($field['field'])
                            @case ('service_name')
                                @include('Space::frontend.layouts.search.fields.service_name')
                            @break
                            @case ('location')
                                @include('Space::frontend.layouts.search.fields.location')
                            @break
                            @case ('date')
                                @include('Space::frontend.layouts.search.fields.date')
                            @break
                            @case ('attr')
                                @include('Space::frontend.layouts.search.fields.attr')
                            @break
                            @case ('guests')
                                @include('Space::frontend.layouts.search.fields.guests')
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
<style id="space-search-form-critical">
@media (min-width:1024px){
.bravo_search_space .space-search-form .space-search-row{display:flex!important;flex-wrap:nowrap!important;width:100%!important;margin:0!important}
.bravo_search_space .space-search-form .space-search-row>.space-search-field{flex:1 1 0!important;max-width:none!important;width:auto!important;padding:0!important}
}
</style>
