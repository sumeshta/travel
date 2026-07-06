<div class="form-group">
    <i class="field-icon fa icofont-search"></i>
    <div class="form-content">
        <label>{{ $field['title'] ?? __("List Name") }}</label>
        <div class="input-search tour-list-name-search">
            <input type="text" name="service_name" id="tour_list_name" class="form-control"
                   placeholder="{{__("Search package name")}}" value="{{ request()->input('service_name') }}" autocomplete="off">
            <div class="suggestions-list tour-list-name-suggestions"></div>
        </div>
    </div>
</div>
<style>
    .tour-list-name-search { position: relative; }
    .tour-list-name-suggestions {
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
    .tour-list-name-suggestions .suggestion-item {
        padding: 10px;
        cursor: pointer;
    }
    .tour-list-name-suggestions .suggestion-item:hover {
        background: #f0f0f0;
    }
</style>
<script>
jQuery(function ($) {
    var $input = $('#tour_list_name');
    var $box = $input.siblings('.tour-list-name-suggestions');
    var xhr = null;

    $input.on('keyup', function () {
        var query = $.trim($(this).val());
        if (query.length < 1) {
            $box.hide().empty();
            return;
        }
        if (xhr) xhr.abort();
        xhr = $.get('{{ route("tourservices.search") }}', { query: query })
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

    $(document).on('click', '.tour-list-name-suggestions .suggestion-item', function () {
        $input.val($(this).text());
        $box.hide().empty();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.tour-list-name-search').length) {
            $box.hide();
        }
    });
});
</script>
