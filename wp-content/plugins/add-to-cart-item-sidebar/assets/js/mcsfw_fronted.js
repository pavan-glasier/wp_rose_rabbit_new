jQuery( document ).ready(function() {

    function alljQueries(){
        let product = addtocart_sidebar.product;
        let cart_position = addtocart_sidebar.basekt_position;
        jQuery("body").on("added_to_cart",function() {
            jQuery.ajax({
                type : "post",
                url : addtocart_sidebar.ajaxurl,
                data : {action: "mcsfw_atcaiofw_cart"},
                beforeSend: function() {
                    jQuery(".qty-btn").prop("disabled", true);
                },
                success : function(data){
                   let obj = jQuery.parseJSON(data);
                   jQuery(".sidemenu-peid").html(obj.htmlcart);
                   jQuery(".sidebar_cart_count").html(obj.htmlcount);
                   jQuery(".cart-count").html(obj.htmlcount);
    
                    setTimeout(function() {
                        jQuery(".cart-sidemenu-wrapper").addClass("shows");
                        jQuery(".popup_overlay").addClass("display");
                    }, 100);
                    alljQueries();
                },
                complete: function() {
                    jQuery(".qty-btn").prop("disabled", false);
                },
            });
        });
    
        /* Update Product Quantity */
        jQuery('body').on('change','.pqty_total',function () {
            jQuery( document.body ).trigger( 'update_checkout' );
            let qty = jQuery(this).val();
            let product_key = jQuery(this).attr('pro_qty_key');
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
                    
                    setTimeout(function() {
                        jQuery(".mcsfw_atc_success_message").slideDown(8000);
                        jQuery(".mcsfw_atc_success_message").html('Item updated.');
                    }, 600);
                    setTimeout(function() {
                        jQuery('.mcsfw_atc_success_message').slideUp(1000);
                    }, 1000);
                    alljQueries();
                }
            });
        });
        
    
        /* Remove Product */
        jQuery(".tit .mcsfw_remove").click( function (e) {
            e.preventDefault();
            let jQuerythisbutton = jQuery(this);
            let product_id = jQuery(this).attr("data-product_id");
            jQuery.ajax({
                type : "post",
                url: addtocart_sidebar.ajaxurl,
                data: {
                    action: 'mcsfw_remove_product_from_cart',
                    product_id: product_id,
                },
                success: function(response) {
                    // jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, jQuerythisbutton]);
                    // jQuery( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, jQuerythisbutton ] );
                    let obj = jQuery.parseJSON(response);
                    console.log('response :>> ', response);
                    jQuery( document.body ).trigger( 'removed_from_cart', [ response.fragments, response.cart_hash, jQuerythisbutton ] );
                    jQuery(".sidemenu-peid").html(obj.htmlcart);
                    jQuery(".sidebar_cart_count").html(obj.htmlcount);
                    jQuery(".cart-count").html(obj.htmlcount);
                    alljQueries();
        
                }
            });
        });

    
        jQuery('.product_slide_cart').on('click', function (e) {
            e.preventDefault();
            let jQuerythisbutton = jQuery(this),
                product_id = jQuerythisbutton.attr('data-product_id'),
                product_qty =  jQuerythisbutton.attr('data-quantity'),
                variation_id = jQuerythisbutton.attr('variation-id');
    
            let data = {
                action: 'woocommerce_ajax_add_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
            };
            jQuery(document.body).trigger('adding_to_cart', [jQuerythisbutton, data]);
            jQuery.ajax({
                type: 'post',
                url: addtocart_sidebar.ajaxurl,
                data: data,
                success: function (response) {
                    console.log('response :>> ', response);
                    jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, jQuerythisbutton]);
                    alljQueries();
                },
            });
    
            return false;
        });

        
        jQuery('.single_add_to_cart_button').on('click', function (e) {
            e.preventDefault();
            let $thisbutton = jQuery(this),
            $form = $thisbutton.closest('form.cart'),
            id = $thisbutton.val(),
            product_qty = $form.find('input[name=quantity]').val() || 1,
            product_id = $form.find('input[name=product_id]').val() || id,
            variation_id = $form.find('input[name=variation_id]').val() || 0;
            let data = {
                action: 'woocommerce_ajax_add_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
            };    
            jQuery(document.body).trigger('adding_to_cart', [$thisbutton, data]);
    
            jQuery.ajax({
                type: 'post',
                url: addtocart_sidebar.ajaxurl,
                data: data,
                beforeSend: function (response) {
                    $thisbutton.removeClass('added').addClass('loading');
                },
                complete: function (response) {
                    $thisbutton.addClass('added').removeClass('loading');
                },
                success: function (response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    } else {
                        jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                    }
                },
            });
    
            return false;
        });
    
        jQuery(".cart_icon, .cart-menu-icon").on("click",function(){
            jQuery(".cart-sidemenu-wrapper").addClass("shows");
            jQuery(".popup_overlay").addClass("display");
        });

        jQuery("#close-btn").click(function(){
            jQuery(".cart-sidemenu-wrapper").removeClass("shows");
            jQuery(".popup_overlay").removeClass("display");
        });

        jQuery(".btn_return_shop, .mcsfw_continue_shopping_btn").click(function(){
            jQuery(".cart-sidemenu-wrapper").removeClass("shows");
            jQuery(".popup_overlay").removeClass("display");
        });


        jQuery(".popup_overlay").click(function(){
            // jQuery( document.body ).trigger( '#close-btn' );
            jQuery("#close-btn").trigger('click');
        });


        function updateQty(qty, product_key){
            jQuery.ajax({
                type : "post",
                url : addtocart_sidebar.ajaxurl,
                data : {
                    action: "mcsfw_atcpro_qty_val",
                    qty: qty,
                    product_key:product_key
                },
                beforeSend: function() {
                    jQuery(".qty-btn").prop("disabled", true);
                    jQuery( document.body ).trigger( 'update_checkout' );
                },
                success: function(data){
                    jQuery( document.body ).trigger( 'added_to_cart', [ data.fragments, data.cart_hash ] );
                },
                complete: function() {
                    jQuery(".qty-btn").prop("disabled", false);
                },
            });
        }

        function incrementValue(e) {
            e.preventDefault();
            let fieldName = jQuery(e.target).data('field');
            let parent = jQuery(e.target).closest('div');
            let currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val());
            let product_key = parent.find('input[name=' + fieldName + ']').attr('pro_qty_key');
            
            if (!isNaN(currentVal)) {
                parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
                updateQty((currentVal + 1), product_key);
            } else {
                parent.find('input[name=' + fieldName + ']').val(1);
                // updateQty(1, product_key);
            }
        }
        
        function decrementValue(e) {
            e.preventDefault();
            let fieldName = jQuery(e.target).data('field');
            let parent = jQuery(e.target).closest('div');
            let currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val());
            let product_key = parent.find('input[name=' + fieldName + ']').attr('pro_qty_key');
        
            if (!isNaN(currentVal) && currentVal > 1) {
                parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
                updateQty((currentVal - 1), product_key);
            } else {
                parent.find('input[name=' + fieldName + ']').val(1);
                // updateQty(1, product_key);
            }
        }

        jQuery('.quantity-plus').on('click', function(e){
            console.log('e :>> ', e);
            incrementValue(e);
        })

        jQuery('.quantity-minus').on('click', function(e){
            console.log('e :>> ', e);
            decrementValue(e);
        });
    }
    alljQueries();
});
