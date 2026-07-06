<div class="form-group">
    <i class="field-icon fa icofont-map"></i>
    <div class="form-content">
        <label>{{ $field['title'] ?? __("Tour Destination") }}</label>
        <div class="input-search tour-destination-search">
            <input type="text" name="destination" id="tour_destination" class="form-control"
                   placeholder="{{__("Tour end destination")}}" value="{{ request()->input('destination') }}" autocomplete="off">
            <div class="suggestions-list tour-destination-suggestions"></div>
        </div>
    </div>
</div>
<style>
    .tour-destination-search { position: relative; }
    .tour-destination-suggestions {
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        z-index: 1000;
        background: #fff;
        left: 0;
        right: 0;
        display: none;
    }
    .tour-destination-suggestions .suggestion-item {
        padding: 10px;
        cursor: pointer;
    }
    .tour-destination-suggestions .suggestion-item:hover {
        background: #f0f0f0;
    }
</style>
<script>
jQuery(function ($) {
    var $input = $('#tour_destination');
    var $box = $input.siblings('.tour-destination-suggestions');
    var xhr = null;

    $input.on('keyup', function () {
        var query = $.trim($(this).val());
        if (query.length < 1) {
            $box.hide().empty();
            return;
        }
        if (xhr) xhr.abort();
        xhr = $.get('{{ route("tour.destination.search") }}', { query: query })
            .done(function (data) {
                $box.empty();
                if (data && data.length) {
                    data.forEach(function (item) {
                        if (item && item.title) {
                            $box.append($('<div class="suggestion-item"></div>').text(item.title));
                        }
                    });
                    $box.show();
                } else {
                    $box.hide();
                }
            })
            .fail(function () {
                $box.hide().empty();
            });
    });

    $(document).on('click', '.tour-destination-suggestions .suggestion-item', function () {
        $input.val($(this).text());
        $box.hide().empty();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.tour-destination-search').length) {
            $box.hide();
        }
    });
});
</script>
