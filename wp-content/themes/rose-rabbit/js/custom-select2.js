jQuery(document).ready(function($) {
    // Initialize Select2
    $('.customize-control-select2').select2();

    // Refresh Select2 on change
    $('.customize-control-select2 select').on('change', function() {
        $(this).trigger('input');
    });
});
