jQuery( document ).ready(function() {
    // console.log(addtocart_sidebar);
    var enable = addtocart_sidebar.ecb_enable;
    var product = addtocart_sidebar.product;
    // console.log("product : " + product);

    jQuery("body").on("added_to_cart",function() {
        // console.log("call ajax");
        jQuery.ajax({
            type : "post",
            url : addtocart_sidebar.ajaxurl,
            data : {action: "mcsfw_atcaiofw_cart"},
            success : function(data){
              var obj = jQuery.parseJSON(data);
              // console.log(obj);
               jQuery(".cart_container").html(obj.htmlcart);
               jQuery(".sidebar_cart_count").html(obj.htmlcount);

               if(enable == 'true'){

                    jQuery(".cart_icon").trigger('click');
                    // console.log("click success.");

                    jQuery("body").addClass("cart_sidebar");
                    jQuery(".cart_container").addClass("product_detail");
                    jQuery(".background_overlay").addClass("overlay_disable");

                    if(jQuery('.cart_icon').hasClass('atc_custom')){
                       jQuery('.cart_icon').removeClass('atc_custom');
                    }
               }
            }
        });
    });
    
    if(enable == ''){
        jQuery(".cart_icon").on("click",function(){
            
            jQuery("body").addClass("cart_sidebar");
            jQuery(".cart_container").addClass("product_detail");
            jQuery(".background_overlay").addClass("overlay_disable");

            if(jQuery('.cart_icon').hasClass('atc_custom')){
               jQuery('.cart_icon').removeClass('atc_custom');
            }
        });
    }
    if(product == ''){
        jQuery('.cart_footer_spro').hide();
    }else{
        jQuery('.cart_footer_spro').show();
    }

    jQuery('body').on('change','.pqty_total',function (e) {
      // console.log('change event');
        var qty = jQuery(this).val();
        var product_key = jQuery(this).attr('pro_qty_key');
        // console.log(product_key);

         jQuery.ajax({
            type : "post",
            url : addtocart_sidebar.ajaxurl,
            data : {
                action: "mcsfw_atcpro_qty_val",
                qty: qty,
                product_key:product_key
            },
            success : function(data){
              jQuery( document.body ).trigger( 'added_to_cart', [ data.fragments, data.cart_hash ] );
            }
        });

    }); 

    jQuery('body').on('click', '#close-btn', function(){
            // console.log('click generete');
            jQuery("body").removeClass("cart_sidebar");
            jQuery(".cart_container").removeClass("product_detail");
            jQuery(".background_overlay").removeClass("overlay_disable");
            jQuery('.cart_icon').addClass('atc_custom');
    });
});