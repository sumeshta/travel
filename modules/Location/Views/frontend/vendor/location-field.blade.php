<?php
$location_name = "";
if (!empty($row->location_id) && !empty($row->location)) {
    $location_name = $row->location->translate()->name ?? $row->location->name;
}
$location_is_draft = !empty($row->location_id) && !empty($row->location) && $row->location->status !== 'publish';
?>
<div class="smart-search service-location-places-wrap">
    <input type="text" class="service-location-places parent_text form-control" name="location_name" id="service_location_name" placeholder="{{__("Search city or place...")}}" value="{{ $location_name }}" autocomplete="off">
    <input type="hidden" class="child_id" name="location_id" value="{{$row->location_id ?? Request::query('location_id')}}">
</div>
<p class="text-muted small mt-1 mb-0">{{__("Start typing and pick a Google place suggestion. New locations need admin approval before the listing appears in location search.")}}</p>
@if($location_is_draft)
    <p class="text-warning small mt-1 mb-0"><i class="fa fa-clock-o"></i> {{__("This location is pending admin approval.")}}</p>
@endif
