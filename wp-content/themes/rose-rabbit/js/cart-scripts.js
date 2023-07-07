jQuery( document ).ready(function() {

    function alljQueries(){
        // let product = addtocart_sidebar.product;
        // let cart_position = addtocart_sidebar.basekt_position;
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
                        // jQuery(".cart-sidemenu-wrapper").addClass("shows");
                        // jQuery(".popup_overlay").addClass("display");
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


        

        function incrementValue(e) {
            e.preventDefault();
            let fieldName = jQuery(e.target).data('field');
            let parent = jQuery(e.target).closest('div');
            let currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val());
            let product_key = parent.find('input[name=' + fieldName + ']').attr('pro_qty_key');
            console.log('object :>> ', product_key);
            
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
            jQuery(".qty-btn").prop("disabled", false);
        },
        complete: function() {
            jQuery(".qty-btn").prop("disabled", false);
        },
    });
}

jQuery(document).on( 'click', 'button.plus, button.minus', function() {
    let qty = jQuery( this ).parent( '.quantity' ).find( '.qty' );
    let val = parseFloat(qty.val());
    let max = parseFloat(qty.attr( 'max' ));
    let min = parseFloat(qty.attr( 'min' ));
    let step = parseFloat(qty.attr( 'step' ));
    let product_key =qty.attr('pro_qty_key');
    
    if ( jQuery( this ).is( '.plus' ) ) {
       if ( max && ( max <= val ) ) {
          qty.val( max ).change();
       } else {
          qty.val( val + step ).change();
       }
       updateQty(parseFloat(qty.val()), product_key)
    } else {
       if ( min && ( min >= val ) ) {
          qty.val( min ).change();
       } else if ( val > 1 ) {
          qty.val( val - step ).change();
       }
       updateQty(parseFloat(qty.val()), product_key)
    }
});


jQuery('.woocommerce p.stars a').click(function(e){ e.preventDefault();
    jQuery( '.dis-none' ).removeClass( 'dis-none' );
});

// jQuery('#send-otp').click( function(e) {
    // e.preventDefault();
    // jQuery('.otp-field').removeClass('d-none');
    // jQuery(this).addClass('d-none');
    // jQuery(this).next().removeClass('d-none');
// });


// let result = "";
// let verificationCode = jQuery("#verificationCode");
// jQuery('.digit-group').find('input').each(function() {

// 	jQuery(this).attr('maxlength', 1);
// 	jQuery(this).on('keyup', function(e) {
// 		let parent = jQuery(jQuery(this).parent());
//         const keyPressed = jQuery(this).val();
//         result += keyPressed;
//         if( result.length > 6) return false
//         // console.log('result :>> ', result );
//         // jQuery("#verificationCode").val(result);
//         if(e.keyCode === 8 || e.keyCode === 37) {
//             let prev = parent.find('input#' + jQuery(this).data('previous'));
//             if(prev.length) {
//                 jQuery(prev).select();
//             }
//         } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
//             let next = parent.find('input#' + jQuery(this).data('next'));
//             if(next.length) {
//                 jQuery(next).select();
//             } else {
//                 if(parent.data('autosubmit')) {
//                     parent.submit();
//                 }
//             }
//         }
//         verificationCode.val(result);
//         console.log('verificationCode :>> ', verificationCode);
// 	});
// });


// input phone number validation
// Get the input field element
let phoneNumberInput = jQuery('#phone-number-input');
let otpField = jQuery('.otp-field');
let sendOtpBtn = jQuery('#send-otp');
let verifyOtpBtn = jQuery('#otp-verify');
// Attach the input event listener
phoneNumberInput.on('input', function() {
    phoneNumberInput.focus();
    verifyOtpBtn.addClass('d-none');
    // Get the current input value
    let phoneNumber = phoneNumberInput.val();
    // Remove any non-digit characters from the phone number
    phoneNumber = phoneNumber.replace(/\D/g, '');
    // Limit the phone number to 10 digits
    phoneNumber = phoneNumber.slice(0, 10);
    let isValid = (phoneNumber.length === 10);
    phoneNumberInput.val(phoneNumber);
    phoneNumberInput.toggleClass('valid', isValid);
    phoneNumberInput.toggleClass('invalid', !isValid);
    otpField.addClass('d-none', !isValid);
    sendOtpBtn.toggleClass('d-none', !isValid);
    jQuery('.otp-field input').prop('disabled', false);
});

// billing address store
jQuery(document).ready(function($) {
    // Intercept the form submission event
    $('#billing_details_submit').on('click', function(event) {
        let $this = jQuery(this);
        $this.html('Submitting...');
        event.preventDefault(); // Prevent form submission
        // Collect the billing details form data
        let noticesWrapper = $('.woocommerce-error');
        
        let billing_first_name = $('#billing_first_name').val();
        let billing_last_name = $('#billing_last_name').val();
        let billing_company = $('#billing_company').val();
        let billing_country = $('#billing_country').val();
        let billing_address_1 = $('#billing_address_1').val();
        let billing_address_2 = $('#billing_address_2').val();
        let billing_city = $('#billing_city').val();
        let billing_state = $('#billing_state').val();
        let billing_postcode = $('#billing_postcode').val();
        let billing_phone = $('#billing_phone').val();
        let billing_email = $('#billing_email').val();
        let register_user_on_order = $('input[name="register_user_on_order"]').val();
        
        let data = {
            action: "register_user_with_billing_details",
            register_user_on_order: register_user_on_order,
            billing_first_name: billing_first_name,
            billing_last_name: billing_last_name,
            billing_country: billing_country,
            billing_address_1: billing_address_1,
            billing_address_2: billing_address_2,
            billing_company: billing_company,
            billing_city: billing_city,
            billing_state: billing_state,
            billing_postcode: billing_postcode,
            billing_phone: billing_phone,
            billing_email: billing_email
        }
        $.ajax({
            type: 'POST',
            url: addtocart_sidebar.ajaxurl, // WooCommerce AJAX URL
            data : data,// Add custom action
            beforeSend:function(){
                noticesWrapper.addClass('d-none');
                noticesWrapper.html('');
                $.each(data, function (key, val) {
                    if( key != 'action' || key != 'register_user_on_order' ){
                        $('#'+key).parent().parent().removeClass('woocommerce-invalid');
                    }
                });
            },
            success: function(response) {
                // Handle the response from the server
                let obj = jQuery.parseJSON(response);
                if(obj.status){
                    $this.next().click();
                    noticesWrapper.addClass('d-none');
                }
                else{
                    noticesWrapper.removeClass('d-none');
                    noticesWrapper.html(obj.errors_html);
                    $('.form-row').addClass('woocommerce-validated');
                    $.each(obj.errors, function (key, val) {
                        $('#'+key).parent().parent().removeClass('woocommerce-validated');
                        $('#'+key).parent().parent().addClass('woocommerce-invalid');
                        $('html, body').animate({ scrollTop: $(".woocommerce-error").offset().top-120}, 'slow');
                    });
                }
                $this.html('Next');
                // You can perform additional actions here based on the response
            }
        });
    });
});



let result = "";
// new otp javascript code
const submitButton = document.getElementById("otp-verify");
let in1 = document.getElementById('otp-1'),
    ins = document.querySelectorAll('input[type="number"]'),
	 splitNumber = function(e) {
         let data = e.data || e.target.value; // Chrome doesn't get the e.data, it's always empty, fallback to value then.
		if ( ! data ) return; // Shouldn't happen, just in case.
		if ( data.length === 1 ) return; // Here is a normal behavior, not a paste action.
		
		popuNext(e.target, data);
		// for (i = 0; i < data.length; i++ ) { 
        //     ins[i].value = data[i]; 
        // }
	},
	popuNext = function(el, data) {
		el.value = data[0]; // Apply first item to first input
		data = data.substring(1); // remove the first char.
		if ( el.nextElementSibling && data.length ) {
			// Do the same with the next element and next data
			popuNext(el.nextElementSibling, data);
		}
	};

ins.forEach(function(input) {
	input.addEventListener('keyup', function(e){
		if (e.keyCode === 16 || e.keyCode == 9 || e.keyCode == 224 || e.keyCode == 18 || e.keyCode == 17) {
			return;
		}
		
		if ( (e.keyCode === 8 || e.keyCode === 37) && this.previousElementSibling && this.previousElementSibling.tagName === "INPUT" ) {
			this.previousElementSibling.select();
            result --;
		} else if (e.keyCode !== 8 && this.nextElementSibling) {
			this.nextElementSibling.select();
            if(result < 5 ){
                result ++;
            }
		}
        else{
            if(result == 5 ){
                result ++;
            }
        }
		if ( e.target.value.length > 1 ) {
			splitNumber(e);
		}
        
        if(result > 5 ){
            submitButton.classList.remove("d-none");
            submitButton.classList.add("d-inline-block");
        }
        else{
            submitButton.classList.add("d-none");
            submitButton.classList.remove("d-inline-block");
        }
	});
    
	input.addEventListener('focus', function(e) {
		if ( this === in1 ) return;
		
		if ( in1.value == '' ) {
			in1.focus();
		}
		
		if ( this.previousElementSibling.value == '' ) {
			this.previousElementSibling.focus();
		}
	});
});

in1.addEventListener('input', splitNumber);