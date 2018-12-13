<?php
$max = isset($field['max']) && (int)$field['max'] > 0 ? $field['max'] : -1;
$min = isset($field['min']) && (int)$field['min'] > 0 ? $field['min'] : -1;
$item_name = strtolower(isset($field['entity_singular']) && !empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);

$items = (isset($field['value']) ? ($field['value']) : (isset($field['default']) ? ($field['default']) : ''));

// make sure not matter the attribute casting
// the $items variable contains a properly defined JSON
if (is_array($items)) {
    if (count($items)) {
        $items = json_encode($items);
    } else {
        $items = '[]';
    }
} elseif (is_string($items) && !is_array(json_decode($items))) {
    $items = '[]';
}

if (is_string($items)) {
    $items = json_decode($items, true);
}
?>
<!-- upload multiple input -->
<div class="array-container form-group col-md-12">
    <label>{!! $field['label'] !!}</label>


    @if (isset($field['value']) && count($field['value']))
        <table class="table table-bordered table-striped m-b-0">
            <thead>
            <tr>
                <th style="font-weight: 600!important;">
                    Preview
                </th>
                <th style="font-weight: 600!important;">
                    Caption
                </th>
                <th style="font-weight: 600!important;">
                    Include Pic
                </th>
                <th class="text-center" ng-if="max == -1 || max > 1"> {{-- <i class="fa fa-sort"></i> --}} </th>
            </tr>
            </thead>

            <tbody id="sortable" class="table-striped">

            @foreach($items as $key => $item)
                <tr class="array-row ui-state-default">

                    <td class="col-xs-2">
                        <a target="_blank" href="{{ gallery_image_url($item['image_path']) }}">
                            <img src="{{ gallery_image_url($item['image_path'].'?w=50&h=50&fit=fill') }}" width="50" height="50"/>
                        </a>
                    </td>

                    <td class="col-xs-8">
                        <input class="form-control input-sm" type="text" name="captions[{{ $item['image'] }}]" value="{{ isset($item['caption']) ? $item['caption'] : '' }}">
                    </td>
                    <td class="col-xs-2">
                        <label for="images[{{ $item['image'] }}]">
                            <input type="checkbox" name="images[{{ $item['image'] }}]" id="images[{{ $item['image'] }}]"
                                   value="1" @if($item['live'] !== 0)checked="checked" @endif/> Include
                        </label>
                    </td>
                    <td>
                        <span class="btn btn-sm btn-default sort-handle"><span class="sr-only">sort item</span><i class="fa fa-sort" role="presentation" aria-hidden="true"></i></span>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script type="text/javascript"
                src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script>
            $(function () {
                $("#sortable").sortable({appendTo: 'body', helper: 'clone', zIndex: 300});
                $("#sortable").disableSelection();
            });
        </script>
    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
