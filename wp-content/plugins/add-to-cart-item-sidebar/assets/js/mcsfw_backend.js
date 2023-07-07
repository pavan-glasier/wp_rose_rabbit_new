jQuery(document).ready(function(){

    jQuery('ul.nav-tab-wrapper li').click(function(){
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('ul.nav-tab-wrapper li').removeClass('nav-tab-active');
        jQuery('.tab-content').removeClass('current');
        jQuery(this).addClass('nav-tab-active');
        jQuery("#"+tab_id).addClass('current');
    });

    
    jQuery('.mcsfw_product_select_slider').select2({
        ajax: {
          url: ajaxurl,
          dataType: 'json',
          delay: 250,
          data: function(params) {
            return {
              term: params.term,
              action: 'mcsfw_product_slider_search'
            };
          },
          processResults: function(data) {
            var options = [];
            if (data) {
              jQuery.each(data, function(index, item) {
                options.push({
                  id: item.id,
                  text: item.text,
                });
              });
            }
            return {
              results: options
            };
          },
          cache: true
        },
        minimumInputLength: 3
    });

});