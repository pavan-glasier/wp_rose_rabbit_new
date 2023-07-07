<?php 
//Add a filter to get the cart count
add_filter('woocommerce_add_to_cart_fragments', 'woo_cart_but_count');
/**
 * Add AJAX Shortcode when cart contents update
 */
function woo_cart_but_count($fragments) {
    ob_start();
    $show_product_count = get_option('show_product_count','true');
    $backet_product_count = get_option('backet_product_count','count_items');
    if($show_product_count == 'true'){
        if($backet_product_count == 'count_items'){
            $product_count = count(WC()->cart->get_cart());
        }else{
            $product_count = WC()->cart->get_cart_contents_count();
        }
    } ?>
    <span class="cart-count">
        <?php echo esc_attr($product_count); ?>
    </span>
    <?php $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
}


function woo_cart_count_icon(){
    ob_start(); ?>
    <button class="bar-btn sideMenuToggler d-xl-inline-block cart-menu-icon">
        <?php if( get_option( 'cart_icon_upload' ) ): ?>
            <img src="<?php echo get_option( 'cart_icon_upload', '' );?>" alt="cart" width="30">
        <?php else: ?>
            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
        <?php endif; ?>
        <span class="cart-count">0</span>
    </button>
    <?php return ob_get_clean();
}
add_shortcode('woo_cart_count', 'woo_cart_count_icon');

