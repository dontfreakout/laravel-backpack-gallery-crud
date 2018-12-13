<!-- Select template field. Used in Backpack/PageManager to redirect to a form with different fields if the template changes. A fork of the select_from_array field with an extra ID and an extra javascript file. -->
<div @include('crud::inc.field_wrapper_attributes') >

    <h3>{{ $field['label'] }}</h3>

    <div id="elfinder"></div>
</div>


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    @php
        $dir = '/packages/barryvdh/elfinder';
        $locale = false;
    @endphp

    @push('after_styles')

    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- jQuery and jQuery UI (REQUIRED) -->
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
        <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

        <!-- elFinder CSS (REQUIRED) -->
        <link rel="stylesheet" type="text/css" href="<?= asset($dir . '/css/elfinder.min.css') ?>">
        <!-- <link rel="stylesheet" type="text/css" href="<?= asset($dir . '/css/theme.css') ?>"> -->
        <link rel="stylesheet" type="text/css"
              href="<?= asset('vendor/backpack/elfinder/elfinder.backpack.theme.css') ?>">

        <!-- elFinder JS (REQUIRED) -->
        <script src="<?= asset($dir . '/js/elfinder.min.js') ?>"></script>

        <?php if ($locale) { ?>
        <!-- elFinder translation (OPTIONAL) -->
        <script src="<?= asset($dir . "/js/i18n/elfinder.$locale.js") ?>"></script>
        <?php } ?>

        <!-- elFinder initialization (REQUIRED) -->
        <script type="text/javascript" charset="utf-8">
            // Documentation for client options:
            // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
                    @if($field['dirPath'])
            var initialItemHash = 'l1_' + btoa("{{ $field['dirPath'] }}")
                    .replace(/\+/g, '-')
                    .replace(/\//g, '_')
                    .replace(/=/g, '.')
                    .replace(/\.+$/, '');
                    @else
            var initialItemHash = 'l1_Lw';
            @endif

            $().ready(function () {
                var el = $('#elfinder').elfinder({
                    // set your elFinder options here
                    <?php if ($locale) { ?>
                    lang: '<?= $locale ?>', // locale
                    <?php } ?>
                    customData: {
                        _token: '<?= csrf_token() ?>'
                    },
                    url: '<?= route("elfinder.connector") ?>',  // connector URL
                    startPathHash: initialItemHash
                }).elfinder('instance');
            });
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}