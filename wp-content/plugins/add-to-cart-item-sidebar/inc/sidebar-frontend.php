<?php
function mcsfw_add_to_cart_popup(){
    global $mcsfw_icon, $woocommerce;

    $sidebar_max_width = get_option('sidebar_max_width','430');
    $atc_enable = get_option('atc_enable','true');
    $mobile_en = get_option('mobile_en','true');
    $shead_color = get_option('shead_color','#f8f8f8');
    $shborder_color = get_option('shborder_color','#b7b7b7');
    $shb_style = get_option('shb_style','solid');
    $shthead_color = get_option('shthead_color','#000');
    $head_font_size = get_option('head_font_size','28');
    $head_close_size = get_option('head_close_size','28');
    $clpback_color =get_option('clpback_color','#fff');
    $clpimg_width = get_option('clpimg_width','100');
    $pib_radious = get_option('pib_radious','0');
    $ptc_color = get_option('ptc_color','#000');
    $pth_hover = get_option('pth_hover','#ff9065');
    $prop_color = get_option('prop_color','#000');
    $pd_color = get_option('pd_color','#808b97');
    $pdc_hover = get_option('pdc_hover','#ff0000');
    $slider_back_color = get_option('slider_back_color','#f8f8f8');
    $slider_btn_back_color = get_option('slider_btn_back_color','#000000');
    $slider_btn_text_color = get_option('slider_btn_text_color','#ffffff');
    $slid_enable_desk = get_option('slid_enable_desk','true');
    $slid_enable_mob = get_option('slid_enable_mob','true');
    $display_subtotal = get_option('display_subtotal','true');
    $btn_font_size = get_option('btn_font_size','16');
    $atcfb_color = get_option('atcfb_color','#f8f8f8');
    $cbc_color = get_option('cbc_color','#000000');
    $cbh_color = get_option('cbh_color','#3cb247');
    $btn_text_color = get_option('btn_text_color','#ffffff');
    $btnh_color = get_option('btnh_color','#000');
    $cart_head_text = get_option('cart_head_text','Your Cart');
    $cart_btn_text = get_option('cart_btn_text','View Cart');
    $checkout_btn_text = get_option('checkout_btn_text','Checkout Now');
    $shopping_btn_text = get_option('shopping_btn_text','Keep Shopping');
    $empty_cart_text = get_option('empty_cart_text','Your cart is empty.');
    $return_shop_text = get_option('return_shop_text','Return to Shop');
    $cart_btn_url = get_option('cart_btn_url',wc_get_cart_url());
    $checkout_btn_url = get_option('checkout_btn_url',wc_get_checkout_url());
    $continue_shopping_btn_url = get_option('continue_shopping_btn_url','#');
    $basket_bg_color = get_option('basket_bg_color','#000000');
    $basket_color = get_option('basket_color','#ffffff');
    $basket_count_position = get_option('basket_count_position','top_right');
    $count_text_color = get_option('count_text_color','#ffffff');
    $count_bg_color = get_option('count_bg_color','#12b99a');
    $close_icon = get_option('close_icon','close_icon_1');
    $close_icon_color = get_option('close_icon_color','#000000');
    $trash_icon = get_option('trash_icon','trash_icon1');
    $basket_icon = get_option('basket_icon','cart_1');
    $basket_size = get_option('basket_size','30');
    $header_heading_position = get_option('header_heading_position','center');
    $header_close_position = get_option('header_close_position','right');
    $basekt_position = get_option('basekt_position','right');
    $basekt_shape = get_option('basekt_shape','round');
    $show_product_count = get_option('show_product_count','true');
    $backet_product_count = get_option('backet_product_count','count_items');
    $enable_pro_img = get_option('enable_pro_img','true');
    $enable_pro_name = get_option('enable_pro_name','true');
    $enable_pro_price = get_option('enable_pro_price','true');
    $enable_pro_total = get_option('enable_pro_total','true');
    $enable_pro_qty = get_option('enable_pro_qty','true');
    $enable_pro_delete = get_option('enable_pro_delete','true');
    $enable_product_link = get_option('enable_product_link','true');
    $enable_header_close = get_option('enable_header_close','true');
    if(isset($_POST['add-to-cart']) && isset($_POST['quantity'])){
    ?>
<script type="text/javascript">
setTimeout(function() {
    jQuery(".cart_icon").trigger('click');
    jQuery("body").addClass("cart_sidebar");
    jQuery(".popup_overlay").addClass("display");
    jQuery(".mcsfw_atc_success_message").slideDown(1000);
    setTimeout(function() {
        jQuery('.mcsfw_atc_success_message').slideUp();
    }, 1000);
}, 100);
</script>
<?php } ?>
<style type="text/css">
#close-btn {
    <?php if($header_close_position=='right') { ?>right: 12px; <?php }
    else { ?>left: 12px; <?php } ?>
}

#close-btn svg {
    fill: <?php echo esc_attr($close_icon_color); ?>;
    width: <?php echo esc_attr($head_close_size); ?>px;
    height: <?php echo esc_attr($head_close_size); ?>px;
}

.mcsfw_header_box {
    background-color: <?php echo esc_attr($shead_color); ?>;
    border-color: <?php echo esc_attr($shborder_color); ?>;
    border-style: <?php echo esc_attr($shb_style); ?>;
    <?php if($header_heading_position=='center') { ?>justify-content: center; <?php }
    else if($header_heading_position=='left') { ?>justify-content: flex-start; <?php }
    else { ?>justify-content: flex-end; <?php } ?>
}

.atc_header h4 {
    color: <?php echo esc_attr($shthead_color); ?>;
    font-size: <?php echo esc_attr($head_font_size); ?>px !important;
}

<?php if($basket_count_position=='top_right') {
    ?>.sidebar_cart_count {
        color: <?php echo esc_attr($count_text_color);?>;
        background: <?php echo esc_attr($count_bg_color);?>;left: 45px;}<?php
}

?><?php if($basket_count_position=='top_left') {
    ?>.sidebar_cart_count {
        color: <?php echo esc_attr($count_text_color); ?>; 
        background: <?php echo esc_attr($count_bg_color); ?>; right: 45px; } <?php
} ?>


.product_title {
    color: <?php echo esc_attr($ptc_color); ?>;
    transition: all .3s ease;
}
.product_title:hover {
    color: <?php echo esc_attr($pth_hover); ?>;
}
.sideprice {
    color: <?php echo esc_attr($prop_color); ?>;
}
.mcsfw_remove svg {
    fill: <?php echo esc_attr($pd_color);?>;
    transition: all .3s ease;
    width: 25px;
    height: 25px;
}
.mcsfw_remove svg:hover {
    fill: <?php echo esc_attr($pdc_hover);?>;
}
.mcsfw_product_slider {
    background-color: <?php echo esc_attr($slider_back_color); ?>;
    <?php if($slid_enable_desk=='true') { ?>display: block; <?php }
    else { ?>display: none; <?php } ?>
}
.atc_footer {
    background-color: <?php echo esc_attr($atcfb_color); ?>;
}
.top-flex .mcsfw_checkout_btn,
.top-flex .mcsfw_continue_shopping_btn,
.top-flex .mcsfw_view_cart_btn {
    background-color: <?php echo esc_attr($cbc_color); ?>;
    color: <?php echo esc_attr($btn_text_color); ?>;
    font-size: <?php echo esc_attr($btn_font_size); ?>px;
}

.cart_icon {
    <?php if($basekt_position=='left') { ?>left: 15px; <?php }
    else if($basekt_position=='right') { ?>right: 15px; <?php } ?>
    <?php if($basekt_shape=='square') { ?>border-radius: 10px; <?php }
    else if($basekt_shape=='round') { ?>border-radius: 100px; <?php } ?>
    background-color: <?php echo esc_attr($basket_bg_color); ?>;
}

.cart-sidemenu-wrapper .sidemenu-content {
    <?php if($basekt_position=='left') { ?>left: -<?php echo esc_attr($sidebar_max_width);?>px; <?php }
    else if($basekt_position=='rightt') { ?>right: -<?php echo esc_attr($sidebar_max_width);?>px;<?php }
    ?>width: <?php echo esc_attr($sidebar_max_width); ?>px;
}
.cart-sidemenu-wrapper.right.shows {
    transform: translateX(-<?php echo esc_attr($sidebar_max_width);?>px);
}

.cart-sidemenu-wrapper.left.shows {
    transform: translateX(<?php echo esc_attr($sidebar_max_width);?>px);
}

.footer-heding-bottom{
    width: <?php echo esc_attr($sidebar_max_width); ?>px;
}
.mcsfw_basket svg {
    fill: <?php echo esc_attr($basket_color); ?>;
    width: <?php echo esc_attr($basket_size); ?>px;
    height: <?php echo esc_attr($basket_size); ?>px;
}

@media only screen and (max-width: 767px) {
    .cart_pro_slide {
        <?php if($slid_enable_mob=='true') { ?>display: block; <?php }
        else { ?>display: none; <?php } ?>
    }
    .mcsfw_product_slider{
        <?php if($slid_enable_mob=='true') { ?>display: block; <?php }
        else { ?>display: none; <?php } ?>
    }
}
</style>

<div class="popup_overlay"></div>
<div class="cart-sidemenu-wrapper d-lg-block <?php echo $basekt_position;?>">
    <div class="sidemenu-content <?php echo $basekt_position;?>">
        <div class="sidemenu-peid">
            <div class="heding-top">
                <div class="top-flex">
                    <?php if(!empty($cart_head_text)){ ?>
                    <h2 class="text-<?php echo $header_heading_position;?>"><?php echo esc_attr($cart_head_text); ?></h2>
                    <?php } ?>
                    <?php if($enable_header_close == 'true') { ?>
                    <button class="closeButton" id="close-btn"> <!-- .sideMenuCls-->
                        <?php
                            if($close_icon == 'close_icon_1'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_1']));
                            }else if($close_icon == 'close_icon_2'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_2']));
                            }else if($close_icon == 'close_icon_3'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_3']));
                            }else if($close_icon == 'close_icon_4'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_4']));
                            }else if($close_icon == 'close_icon_5'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_5']));
                            }
                        ?>
                    </button>
                    <?php } ?>
                </div>
            </div>
            <div class="widget sidtop atcproduct_content">
                <div class="recent-post-wrap cart_products">
                <?php
                $items = $woocommerce->cart->get_cart();
                if(!empty($items)){
                    foreach($items as $item => $values) {
                        $_product =  wc_get_product($values['data']->get_id());
                        $product_id = $values['product_id'];
                        $product_attribute_key = $values['key'];
                        $img = $_product->get_image();
                        $product_name = $_product->get_name();
                        $pro_quantity = $values['quantity']; 
                        $price = WC()->cart->get_product_price( $_product );

                        $mcsfw_totals = WC()->cart->get_totals();
                        $sub_total = $mcsfw_totals['subtotal'];

                        $final_total = WC()->cart->total;
                        $cart_item_remove_url = wc_get_cart_remove_url( $item );
                        $permalink = $_product->get_permalink( $values );
                        $qty_subtotal = WC()->cart->get_product_subtotal( $_product, $values['quantity'] );
                ?>
                    <div class="recent-post" product_id="<?php echo esc_attr($product_id); ?>">
                        <?php if($enable_pro_img == 'true') { ?>
                        <div class="media-img">
                            <?php if( $enable_product_link == 'true' ){ ?>
                            <a href="<?php echo esc_url($permalink); ?>">
                                <?php echo $_product->get_image('thumbnail'); ?>
                            </a>
                            <?php }else{ ?>
                            <?php echo $_product->get_image('thumbnail'); ?>
                            <?php } ?>
                        </div>
                        <?php } ?>

                        <div class="media-body">
                            <div class="tit">
                                <?php if($enable_pro_name == 'true') { ?>
                                <div class="product_title">
                                    <?php if($enable_product_link == 'true'){ ?>
                                    <a href="<?php echo esc_url($permalink); ?>">
                                        <h4 class="post-title product_title"><?php echo esc_attr($product_name); ?></h4>
                                    </a>
                                    <?php }else{ ?>
                                    <h4 class="post-title product_title"><?php echo esc_attr($product_name); ?></h4>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                                <!-- <a href="#">
                              <i class="fa fa-close"></i>
                           </a> -->
                            <?php if($enable_pro_delete == 'true') { ?>
                            <a class="mcsfw_remove" data-product_id="<?php echo $product_id; ?>">
                            <?php
                            if($trash_icon == 'trash_icon1'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon1']));
                            }else if($trash_icon == 'trash_icon2'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon2']));
                            }else if($trash_icon == 'trash_icon3'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon3']));
                            }else if($trash_icon == 'trash_icon4'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon4']));
                            }else if($trash_icon == 'trash_icon5'){
                                echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon5']));
                            } ?>
                            </a>
                            <?php } ?>
                            </div>
                            <!-- <span class="sidetext">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</span> -->
                            <?php if($enable_pro_price == 'true'){ ?>
                            <span class="sideml"><?php echo wp_kses_post($price); ?></span>
                            <?php } ?>
                            <div class="actions qountbtn">
                                <div class="quantity">
                                    <?php if($enable_pro_qty == 'true'){ ?>
                                    <button class="quantity-minus qty-btn" data-field="quantity">
                                        <i class="fal fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" name="quantity" class="qty-input pqty_total" step="1" min="1"
                                        max="100" name="atcaiofw_qty"
                                        pro_qty_key="<?php echo esc_attr($product_attribute_key); ?>"
                                        value="<?php echo esc_attr($pro_quantity); ?>">
                                    <button class="quantity-plus qty-btn" data-field="quantity">
                                        <i class="fal fa-plus"></i>
                                    </button>
                                    <?php } ?>
                                </div>
                                <!-- <p class="sideprice">₹ 14,400</p> -->
                                <?php if($enable_pro_total == 'true'){ ?>
                                <p class="sideprice"><?php echo wp_kses_post($qty_subtotal); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php }
                } else{ ?>
                <div class="d-block mb-40 pb-3 text-center">
                    <?php
                    if(!empty($empty_cart_text)){ ?>
                    <h4 class="cart_empty_notice"><?php echo esc_attr($empty_cart_text); ?></h4>
                    <?php } ?>
                    <?php if(!empty($return_shop_text)){ ?>
                    <a class="btn_return_shop vs-btn mt-3"
                        href="<?php echo wc_get_page_permalink( 'shop' ) ?>"><?php echo esc_attr($return_shop_text); ?></a>
                    <?php } ?>
                </div>
                <?php } ?>


                <?php 
                $product = get_option('mcsfw_product_slider');
                if(!empty($product)){ ?>
                <div class="mcsfw_product_slider">
                    <h3 class="widget_title">
                        <?php echo __('Products you might like','mini-cart-sidebar-for-woocommerce'); ?>
                    </h3>
                    <?php
                    foreach ($product as $value) {
                        $pro_data = wc_get_product( $value );
                        $product_id = $pro_data->get_id();
                        $p_image = $pro_data->get_image();
                        $product_name = $pro_data->get_name();
                        $product_price = WC()->cart->get_product_price( $pro_data );
                        $footer_pro_permalink = get_permalink( $product_id );
                        $cart_product_ids = array();
                        foreach( WC()->cart->get_cart() as $values ){
                            $cart_product_ids[] = $values['data']->get_id();
                        }
                        if (!in_array($value, $cart_product_ids)) {  ?>
                        <div class="recent-post">
                            <div class="media-img">
                                <a href="<?php echo esc_url($footer_pro_permalink); ?>"><?php echo wp_kses_post($p_image); ?></a>
                            </div>
                            <div class="media-body">
                                <div class="tit">
                                    <a href="<?php echo esc_url($footer_pro_permalink); ?>">
                                        <h4 class="post-title product_title"><?php echo esc_attr($product_name); ?></h4>
                                    </a>
                                </div>
                                <div class="actions qountbtn">
                                    <div class="quantity">
                                        <?php 
                                        if($pro_data->get_type() == 'simple') {?>
                                        <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>" data-quantity="1"
                                            class="vs-btn product_slide_cart"
                                            data-product_id=<?php echo esc_attr($product_id);?>><?php echo __('ADDED TO CART','mini-cart-sidebar-for-woocommerce'); ?></a>
                                        <?php }elseif($pro_data->get_type() == 'variable' ) { ?>
                                        <a href="<?php echo esc_url($footer_pro_permalink); ?>" data-quantity="1"
                                            class="vs-btn variable_product_slide_cart"
                                            data-product_id=<?php echo esc_attr($product_id);?>><?php echo __('VIEW CART','mini-cart-sidebar-for-woocommerce'); ?></a>
                                        <?php }elseif ($pro_data->get_type() == 'variation') { ?>
                                        <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>" data-quantity="1"
                                            class="vs-btn product_slide_cart"
                                            data-product_id=<?php echo esc_attr($product_id);?>><?php echo __('ADDED TO CART','mini-cart-sidebar-for-woocommerce'); ?></a>
                                        <?php } ?>
                                    </div>
                                    <p class="sideprice"><?php echo wp_kses_post($product_price); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php  
                        }else{
                            if(count( $cart_product_ids ) > 1){ ?>
                            <style>
                                .mcsfw_product_slider{
                                    display: none;
                                }
                            </style>
                            <?php }
                        }
                    } ?>
                    <div class="mobilemar"></div>
                    <?php } ?>
                </div>
                </div>
            </div>

            <?php //$sub_total = WC()->cart->get_cart_total(); 
                $mcsfw_totals = WC()->cart->get_totals();
                $sub_total = $mcsfw_totals['subtotal'];
                $total = $mcsfw_totals['total'];?>
            <div class="footer-heding-bottom">
                <?php if($display_subtotal == 'true'){ ?>
                <div class="bot-heding">
                    <span
                        class="bot-heding-sub"><?php echo __('Orders will be delivered within 4-5 working* days. *T&C apply. ', 'mini-cart-sidebar-for-woocommerce'); ?></span>
                    <span><?php echo esc_attr(WC()->cart->get_cart_contents_count()); ?> ITEMS</span>
                </div>
                <?php } ?>

                <div class="bot-heding">
                    <span class="subside"><?php echo __('TOTAL','mini-cart-sidebar-for-woocommerce'); ?></span>
                    <span
                        class="subside"><?php echo get_woocommerce_currency_symbol().number_format($total, 2); ?></span>
                </div>
                <div class="top-flex">
                    <?php if(!empty($cart_btn_url) && !empty($cart_btn_text)){ ?>
                    <a class="mcsfw_view_cart_btn vs-btn"
                        href="<?php echo esc_url($cart_btn_url); ?>"><?php echo esc_attr($cart_btn_text); ?></a>
                    <?php } ?>
                    <?php if(!empty($checkout_btn_url) && !empty($checkout_btn_text)){ ?>
                    <a class="mcsfw_checkout_btn vs-btn"
                        href="<?php echo esc_url($checkout_btn_url); ?>"><?php echo esc_attr($checkout_btn_text); ?></a>
                    <?php } ?>
                    <?php if(!empty($continue_shopping_btn_url) && !empty($shopping_btn_text)){ ?>
                    <a class="mcsfw_continue_shopping_btn vs-btn"
                        href="<?php echo esc_url($continue_shopping_btn_url); ?>"><?php echo esc_attr($shopping_btn_text); ?></a>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="cart_icon atc_custom <?php if($atc_enable == true){ echo "atc_disblock"; }?> <?php if($mobile_en == true){echo "atcmo_disblock";} ?>">
    <div class="sidebar_cart_count">
        <div class="cart_product_count">
            <?php 
            if($show_product_count == 'true'){
                if($backet_product_count == 'count_items'){
                    $product_count = count(WC()->cart->get_cart());
                }else{
                    $product_count = WC()->cart->get_cart_contents_count();
                }
            } ?>
            <?php echo esc_attr($product_count); ?>
        </div>
    </div>
    <div class="">
        <?php 
        if($basket_icon == 'cart_1'){
            echo $mcsfw_icon['cart_1'];
        }else if($basket_icon == 'cart_2'){
            echo $mcsfw_icon['cart_2'];
        }else if($basket_icon == 'cart_3'){
            echo $mcsfw_icon['cart_3'];
        }else if($basket_icon == 'cart_4'){
            echo $mcsfw_icon['cart_4'];
        }else if($basket_icon == 'cart_5'){
            echo $mcsfw_icon['cart_5'];
        }
        ?>
    </div>
</div>
<?php
}
add_action( 'wp_footer', 'mcsfw_add_to_cart_popup' );


function mcsfw_atcaiofw_cart() {
    global $mcsfw_icon;
    ob_start();

    $trash_icon = get_option('trash_icon','fa-solid fa-trash');
    $close_icon = get_option('close_icon','close_icon_1');
    $cart_head_text = get_option('cart_head_text','Your Cart');
    $cart_btn_text = get_option('cart_btn_text','View Cart');
    $header_heading_position = get_option('header_heading_position','center');
    $checkout_btn_text = get_option('checkout_btn_text','Checkout Now');
    $shopping_btn_text = get_option('shopping_btn_text','Keep Shopping');
    $empty_cart_text = get_option('empty_cart_text','Your cart is empty.');
    $return_shop_text = get_option('return_shop_text','Return to Shop');
    $enable_pro_img = get_option('enable_pro_img','true');
    $enable_pro_name = get_option('enable_pro_name','true');
    $enable_pro_price = get_option('enable_pro_price','true');
    $enable_pro_total = get_option('enable_pro_total','true');
    $enable_pro_qty = get_option('enable_pro_qty','true');
    $enable_pro_delete = get_option('enable_pro_delete','true');
    $enable_product_link = get_option('enable_product_link','true');
    $enable_header_close = get_option('enable_header_close','true');
    $show_product_count = get_option('show_product_count','true');
    $backet_product_count = get_option('backet_product_count','count_items');
    $display_subtotal = get_option('display_subtotal','true');
    $cart_btn_url = get_option('cart_btn_url',wc_get_cart_url());
    $checkout_btn_url = get_option('checkout_btn_url',wc_get_checkout_url());
    $continue_shopping_btn_url = get_option('continue_shopping_btn_url','#');
 ?>
<script type="text/javascript">
jQuery('.mcsfw_atc_success_message').hide();
jQuery(".mcsfw_atc_success_message").slideDown(600);
setTimeout(function() {
    jQuery('.mcsfw_atc_success_message').slideUp();
}, 1000);

</script>
<div class="mcsfw_atc_success_message">
    <i class="fa fa-check-circle" aria-hidden="true"></i>
    <?php echo __('Item has been added to the cart.', 'mini-cart-sidebar-for-woocommerce'); ?>
</div>

<div class="heding-top">
    <div class="top-flex">
        <?php if(!empty($cart_head_text)){ ?>
        <h2 class="text-<?php echo $header_heading_position;?>"><?php echo esc_attr($cart_head_text); ?></h2>
        <?php } ?>
        <?php if($enable_header_close == 'true') { ?>
        <button class="closeButton" id="close-btn"> <!-- .sideMenuCls-->
            <?php
            if($close_icon == 'close_icon_1'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_1']));
            }else if($close_icon == 'close_icon_2'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_2']));
            }else if($close_icon == 'close_icon_3'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_3']));
            }else if($close_icon == 'close_icon_4'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_4']));
            }else if($close_icon == 'close_icon_5'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_5']));
            }
            ?>
        </button>
        <?php } ?>
    </div>
</div>

<div class="widget sidtop atcproduct_content">
    <div class="recent-post-wrap cart_products">
    <?php
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    if(!empty($items)){
        foreach($items as $item => $values) {
            $_product =  wc_get_product($values['data']->get_id());
            $product_id = $values['product_id'];
            $product_attribute_key = $values['key'];
            $img = $_product->get_image();
            $product_name = $_product->get_name();
            $pro_quantity = $values['quantity']; 
            $price = WC()->cart->get_product_price( $_product );

            $mcsfw_totals = WC()->cart->get_totals();
            $sub_total = $mcsfw_totals['subtotal'];

            $final_total = WC()->cart->total;
            $cart_item_remove_url = wc_get_cart_remove_url( $item );
            $permalink = $_product->get_permalink( $values );
            $qty_subtotal = WC()->cart->get_product_subtotal( $_product, $values['quantity'] );
        ?>
        <div class="recent-post" product_id="<?php echo esc_attr($product_id); ?>">
            <?php if($enable_pro_img == 'true') { ?>
            <div class="media-img">
                <?php if($enable_product_link == 'true'){ ?>
                <a href="<?php echo esc_url($permalink); ?>">
                    <?php echo $_product->get_image('thumbnail'); ?>
                </a>
                <?php }else{ ?>
                <?php echo $_product->get_image('thumbnail'); ?>
                <?php } ?>
            </div>
            <?php } ?>

            <div class="media-body">
                <div class="tit">
                    <?php if($enable_pro_name == 'true') { ?>
                    <div class="product_title">
                        <?php if($enable_product_link == 'true'){ ?>
                        <a href="<?php echo esc_url($permalink); ?>">
                            <h4 class="post-title product_title"><?php echo esc_attr($product_name); ?></h4>
                        </a>
                        <?php }else{ ?>
                        <h4 class="post-title product_title"><?php echo esc_attr($product_name); ?></h4>
                        <?php } ?>
                    </div>
                    <?php } ?>
                <?php if($enable_pro_delete == 'true') { ?>
                <a class="mcsfw_remove" data-product_id="<?php echo $product_id; ?>">
                <?php
                if($trash_icon == 'trash_icon1'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon1']));
                }else if($trash_icon == 'trash_icon2'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon2']));
                }else if($trash_icon == 'trash_icon3'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon3']));
                }else if($trash_icon == 'trash_icon4'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon4']));
                }else if($trash_icon == 'trash_icon5'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon5']));
                } ?>
                </a>
                <?php } ?>
                </div>
                <!-- <span class="sidetext">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</span> -->
                <?php if($enable_pro_price == 'true'){ ?>
                <span class="sideml"><?php echo wp_kses_post($price); ?></span>
                <?php } ?>
                <div class="actions qountbtn">
                    <div class="quantity">
                        <?php if($enable_pro_qty == 'true'){ ?>
                        <button class="quantity-minus qty-btn" data-field="quantity">
                            <i class="fal fa-minus"></i>
                        </button>
                        <input type="number" id="quantity" name="quantity" class="qty-input pqty_total" step="1" min="1"
                            max="100" name="atcaiofw_qty"
                            pro_qty_key="<?php echo esc_attr($product_attribute_key); ?>"
                            value="<?php echo esc_attr($pro_quantity); ?>">
                        <button class="quantity-plus qty-btn" data-field="quantity">
                            <i class="fal fa-plus"></i>
                        </button>
                        <?php } ?>
                    </div>
                    <!-- <p class="sideprice">₹ 14,400</p> -->
                    <?php if($enable_pro_total == 'true'){ ?>
                    <p class="sideprice"><?php echo wp_kses_post($qty_subtotal); ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php }
    } else{ ?>
    <div class="d-block mb-40 pb-3 text-center">
        <?php
        if(!empty($empty_cart_text)){ ?>
        <h4 class="cart_empty_notice"><?php echo esc_attr($empty_cart_text); ?></h4>
        <?php } ?>
        <?php if(!empty($return_shop_text)){ ?>
        <a class="btn_return_shop vs-btn mt-3"
            href="<?php echo wc_get_page_permalink( 'shop' ) ?>"><?php echo esc_attr($return_shop_text); ?></a>
        <?php } ?>
    </div>
    <?php } ?>
    <?php 
    $product = get_option('mcsfw_product_slider');
    if(!empty($product)){ ?>
    <div class="mcsfw_product_slider">
        <h3 class="widget_title">
            <?php echo __('Products you might like','mini-cart-sidebar-for-woocommerce'); ?>
        </h3>
        <?php  
        foreach ($product as $value) {
            $pro_data = wc_get_product( $value );
            $product_id = $pro_data->get_id();
            $p_image = $pro_data->get_image();
            $product_name = $pro_data->get_name();
            $product_price = WC()->cart->get_product_price( $pro_data );
            $footer_pro_permalink = get_permalink( $product_id );
            $cart_product_ids = array();
            foreach( WC()->cart->get_cart() as $values ){
                $cart_product_ids[] = $values['data']->get_id();
            }
            if (!in_array($value, $cart_product_ids)) { ?>

            <div class="recent-post">
                <div class="media-img">
                    <a href="<?php echo esc_url($footer_pro_permalink); ?>"><?php echo wp_kses_post($p_image); ?></a>
                </div>
                <div class="media-body">
                    <div class="tit">
                        <a href="<?php echo esc_url($footer_pro_permalink); ?>">
                            <h4 class="post-title product_title"><?php echo esc_attr($product_name); ?></h4>
                        </a>
                    </div>
                    <div class="actions qountbtn">
                        <div class="quantity">
                            <?php 
                            if($pro_data->get_type() == 'simple') {?>
                            <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>" data-quantity="1"
                                class="vs-btn product_slide_cart"
                                data-product_id=<?php echo esc_attr($product_id);?>><?php echo __('ADDED TO CART','mini-cart-sidebar-for-woocommerce'); ?></a>
                            <?php }elseif($pro_data->get_type() == 'variable' ) { ?>
                            <a href="<?php echo esc_url($footer_pro_permalink); ?>" data-quantity="1"
                                class="vs-btn variable_product_slide_cart"
                                data-product_id=<?php echo esc_attr($product_id);?>><?php echo __('VIEW CART','mini-cart-sidebar-for-woocommerce'); ?></a>
                            <?php }elseif ($pro_data->get_type() == 'variation') { ?>
                            <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>" data-quantity="1"
                                class="vs-btn product_slide_cart"
                                data-product_id=<?php echo esc_attr($product_id);?>><?php echo __('ADDED TO CART','mini-cart-sidebar-for-woocommerce'); ?></a>
                            <?php } ?>
                        </div>
                        <p class="sideprice"><?php echo wp_kses_post($product_price); ?></p>
                    </div>
                </div>
            </div>
            <?php  
            }
            else{
                if(count( $cart_product_ids ) > 1){ ?>
                <style>
                    .mcsfw_product_slider{
                        display: none;
                    }
                </style>
                <?php }
            }
        } ?>
        <div class="mobilemar"></div>
    <?php } ?>
    </div>
    </div>
</div>

<?php
    $mcsfw_totals = WC()->cart->get_totals();
    $sub_total = $mcsfw_totals['subtotal'];
    $total = $mcsfw_totals['total'];
    ?>
<div class="footer-heding-bottom">
    <?php if($display_subtotal == 'true'){ ?>
    <div class="bot-heding">
        <span
            class="bot-heding-sub"><?php echo __('Orders will be delivered within 4-5 working* days. *T&C apply. ', 'mini-cart-sidebar-for-woocommerce'); ?></span>
        <span><?php echo esc_attr(WC()->cart->get_cart_contents_count()); ?> ITEMS</span>
    </div>
    <?php } ?>

    <div class="bot-heding">
        <span class="subside"><?php echo __('TOTAL','mini-cart-sidebar-for-woocommerce'); ?></span>
        <span
            class="subside"><?php echo get_woocommerce_currency_symbol().number_format($total, 2); ?></span>
    </div>
    <div class="top-flex">
        <?php if(!empty($cart_btn_url) && !empty($cart_btn_text)){ ?>
        <a class="mcsfw_view_cart_btn vs-btn"
            href="<?php echo esc_url($cart_btn_url); ?>"><?php echo esc_attr($cart_btn_text); ?></a>
        <?php } ?>
        <?php if(!empty($checkout_btn_url) && !empty($checkout_btn_text)){ ?>
        <a class="mcsfw_checkout_btn vs-btn"
            href="<?php echo esc_url($checkout_btn_url); ?>"><?php echo esc_attr($checkout_btn_text); ?></a>
        <?php } ?>
        <?php if(!empty($continue_shopping_btn_url) && !empty($shopping_btn_text)){ ?>
        <a class="mcsfw_continue_shopping_btn vs-btn"
            href="<?php echo esc_url($continue_shopping_btn_url); ?>"><?php echo esc_attr($shopping_btn_text); ?></a>
        <?php } ?>
    </div>
</div>

<?php
 $htmlcart = ob_get_contents();
 ob_end_clean();
 ob_start();
 ?>
<div class="cart_product_count">
    <?php 
        if($show_product_count == 'true'){
            if($backet_product_count == 'count_items'){
                $product_count = count(WC()->cart->get_cart());
            }else{
                $product_count = WC()->cart->get_cart_contents_count();
            }
        }
    ?>
    <?php echo esc_attr($product_count); ?>
</div>
<?php
 $htmlcount= ob_get_contents();
 ob_end_clean();
 $arr=array(
    "htmlcart"=>$htmlcart,
    "htmlcount"=>$htmlcount
);
echo json_encode($arr);
exit();
}
add_action( 'wp_ajax_mcsfw_atcaiofw_cart', 'mcsfw_atcaiofw_cart' );
add_action( 'wp_ajax_nopriv_mcsfw_atcaiofw_cart', 'mcsfw_atcaiofw_cart' );


/**
 * Remove product from the cart
*/
function mcsfw_remove_product_from_cart() {
    global $mcsfw_icon;
    ob_start();
    $items = WC()->cart->get_cart();
    foreach($items as $item_key => $values) {     
        if($values['product_id'] == $_POST['product_id']){
            WC()->cart->remove_cart_item($item_key);
        }
    }

    $trash_icon = get_option('trash_icon','fa-solid fa-trash');
    $close_icon = get_option('close_icon','close_icon_1');
    $cart_head_text = get_option('cart_head_text','Your Cart');
    $cart_btn_text = get_option('cart_btn_text','View Cart');
    $header_heading_position = get_option('header_heading_position','center');
    $checkout_btn_text = get_option('checkout_btn_text','Checkout Now');
    $shopping_btn_text = get_option('shopping_btn_text','Keep Shopping');
    $empty_cart_text = get_option('empty_cart_text','Your cart is empty.');
    $return_shop_text = get_option('return_shop_text','Return to Shop');
    $enable_pro_img = get_option('enable_pro_img','true');
    $enable_pro_name = get_option('enable_pro_name','true');
    $enable_pro_price = get_option('enable_pro_price','true');
    $enable_pro_total = get_option('enable_pro_total','true');
    $enable_pro_qty = get_option('enable_pro_qty','true');
    $enable_pro_delete = get_option('enable_pro_delete','true');
    $enable_product_link = get_option('enable_product_link','true');
    $enable_header_close = get_option('enable_header_close','true');
    $show_product_count = get_option('show_product_count','true');
    $backet_product_count = get_option('backet_product_count','count_items');
    $display_subtotal = get_option('display_subtotal','true');
    $cart_btn_url = get_option('cart_btn_url',wc_get_cart_url());
    $checkout_btn_url = get_option('checkout_btn_url',wc_get_checkout_url());
    $continue_shopping_btn_url = get_option('continue_shopping_btn_url','#');
 ?>
<script type="text/javascript">

jQuery('.mcsfw_atc_remove_message').hide();
jQuery(".mcsfw_atc_remove_message").slideDown(600);
setTimeout(function() {
    jQuery('.mcsfw_atc_remove_message').slideUp();
}, 1000);
</script>
<div class="mcsfw_atc_remove_message">
    <i class="fa fa-check-circle" aria-hidden="true"></i>
    <?php echo __('Item removed.','mini-cart-sidebar-for-woocommerce'); ?>
</div>

<div class="heding-top">
    <div class="top-flex">
        <?php if(!empty($cart_head_text)){ ?>
        <h2 class="text-<?php echo $header_heading_position;?>"><?php echo esc_attr($cart_head_text); ?></h2>
        <?php } ?>
        <?php if($enable_header_close == 'true') { ?>
        <button class="closeButton" id="close-btn"> <!-- .sideMenuCls-->
            <?php
            if($close_icon == 'close_icon_1'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_1']));
            }else if($close_icon == 'close_icon_2'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_2']));
            }else if($close_icon == 'close_icon_3'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_3']));
            }else if($close_icon == 'close_icon_4'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_4']));
            }else if($close_icon == 'close_icon_5'){
                echo html_entity_decode(esc_attr($mcsfw_icon['close_icon_5']));
            }
            ?>
        </button>
        <?php } ?>
    </div>
</div>

<div class="widget sidtop atcproduct_content">
    <div class="recent-post-wrap cart_products">
    <?php
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    if(!empty($items)){
        foreach($items as $item => $values) {
            $_product =  wc_get_product($values['data']->get_id());
            $product_id = $values['product_id'];
            $product_attribute_key = $values['key'];
            $img = $_product->get_image();
            $product_name = $_product->get_name();
            $pro_quantity = $values['quantity']; 
            $price = WC()->cart->get_product_price( $_product );

            $mcsfw_totals = WC()->cart->get_totals();
            $sub_total = $mcsfw_totals['subtotal'];

            $final_total = WC()->cart->total;
            $cart_item_remove_url = wc_get_cart_remove_url( $item );
            $permalink = $_product->get_permalink( $values );
            $qty_subtotal = WC()->cart->get_product_subtotal( $_product, $values['quantity'] );
        ?>
        <div class="recent-post" product_id="<?php echo esc_attr($product_id); ?>">
            <?php if($enable_pro_img == 'true') { ?>
            <div class="media-img">
                <?php if($enable_product_link == 'true'){ ?>
                <a href="<?php echo esc_url($permalink); ?>">
                    <?php echo $_product->get_image('thumbnail'); ?>
                </a>
                <?php }else{ ?>
                <?php echo $_product->get_image('thumbnail'); ?>
                <?php } ?>
            </div>
            <?php } ?>

            <div class="media-body">
                <div class="tit">
                    <?php if($enable_pro_name == 'true') { ?>
                    <div class="product_title">
                        <?php if($enable_product_link == 'true'){ ?>
                        <a href="<?php echo esc_url($permalink); ?>">
                            <h4 class="post-title product_title"><?php echo esc_attr($product_name); ?></h4>
                        </a>
                        <?php }else{ ?>
                        <h4 class="post-title product_title"><?php echo esc_attr($product_name); ?></h4>
                        <?php } ?>
                    </div>
                    <?php } ?>
                <?php if($enable_pro_delete == 'true') { ?>
                <a class="mcsfw_remove" data-product_id="<?php echo $product_id; ?>">
                <?php
                if($trash_icon == 'trash_icon1'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon1']));
                }else if($trash_icon == 'trash_icon2'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon2']));
                }else if($trash_icon == 'trash_icon3'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon3']));
                }else if($trash_icon == 'trash_icon4'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon4']));
                }else if($trash_icon == 'trash_icon5'){
                    echo html_entity_decode(esc_attr($mcsfw_icon['trash_icon5']));
                } ?>
                </a>
                <?php } ?>
                </div>
                <!-- <span class="sidetext">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</span> -->
                <?php if($enable_pro_price == 'true'){ ?>
                <span class="sideml"><?php echo wp_kses_post($price); ?></span>
                <?php } ?>
                <div class="actions qountbtn">
                    <div class="quantity">
                        <?php if($enable_pro_qty == 'true'){ ?>
                        <button class="quantity-minus qty-btn" data-field="quantity">
                            <i class="fal fa-minus"></i>
                        </button>
                        <input type="number" id="quantity" name="quantity" class="qty-input pqty_total" step="1" min="1"
                            max="100" name="atcaiofw_qty"
                            pro_qty_key="<?php echo esc_attr($product_attribute_key); ?>"
                            value="<?php echo esc_attr($pro_quantity); ?>">
                        <button class="quantity-plus qty-btn" data-field="quantity">
                            <i class="fal fa-plus"></i>
                        </button>
                        <?php } ?>
                    </div>
                    <!-- <p class="sideprice">₹ 14,400</p> -->
                    <?php if($enable_pro_total == 'true'){ ?>
                    <p class="sideprice"><?php echo wp_kses_post($qty_subtotal); ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php }
    } else{ ?>
    <div class="d-block mb-40 pb-3 text-center">
        <?php
        if(!empty($empty_cart_text)){ ?>
        <h4 class="cart_empty_notice"><?php echo esc_attr($empty_cart_text); ?></h4>
        <?php } ?>
        <?php if(!empty($return_shop_text)){ ?>
        <a class="btn_return_shop vs-btn mt-3"
            href="<?php echo wc_get_page_permalink( 'shop' ) ?>"><?php echo esc_attr($return_shop_text); ?></a>
        <?php } ?>
    </div>
    <?php } ?>

    <?php 
    $product = get_option('mcsfw_product_slider');
    if(!empty($product)){ ?>
    <div class="mcsfw_product_slider">
        <h3 class="widget_title">
            <?php echo __('Products you might like','mini-cart-sidebar-for-woocommerce'); ?>
        </h3>
        <?php  
        foreach ($product as $value) {
            $pro_data = wc_get_product( $value );
            $product_id = $pro_data->get_id();
            $p_image = $pro_data->get_image();
            $product_name = $pro_data->get_name();
            $product_price = WC()->cart->get_product_price( $pro_data );
            $footer_pro_permalink = get_permalink( $product_id );
            $cart_product_ids = array();
            foreach( WC()->cart->get_cart() as $values ){
                $cart_product_ids[] = $values['data']->get_id();
            }
            if (!in_array($value, $cart_product_ids)) { ?>

            <div class="recent-post">
                <div class="media-img">
                    <a href="<?php echo esc_url($footer_pro_permalink); ?>"><?php echo wp_kses_post($p_image); ?></a>
                </div>
                <div class="media-body">
                    <div class="tit">
                        <a href="<?php echo esc_url($footer_pro_permalink); ?>">
                            <h4 class="post-title product_title"><?php echo esc_attr($product_name); ?></h4>
                        </a>
                    </div>
                    <div class="actions qountbtn">
                        <div class="quantity">
                            <?php 
                            if($pro_data->get_type() == 'simple') {?>
                            <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>" data-quantity="1"
                                class="vs-btn product_slide_cart"
                                data-product_id=<?php echo esc_attr($product_id);?>><?php echo __('ADDED TO CART','mini-cart-sidebar-for-woocommerce'); ?></a>
                            <?php }elseif($pro_data->get_type() == 'variable' ) { ?>
                            <a href="<?php echo esc_url($footer_pro_permalink); ?>" data-quantity="1"
                                class="vs-btn variable_product_slide_cart"
                                data-product_id=<?php echo esc_attr($product_id);?>><?php echo __('VIEW CART','mini-cart-sidebar-for-woocommerce'); ?></a>
                            <?php }elseif ($pro_data->get_type() == 'variation') { ?>
                            <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>" data-quantity="1"
                                class="vs-btn product_slide_cart"
                                data-product_id=<?php echo esc_attr($product_id);?>><?php echo __('ADDED TO CART','mini-cart-sidebar-for-woocommerce'); ?></a>
                            <?php } ?>
                        </div>
                        <p class="sideprice"><?php echo wp_kses_post($product_price); ?></p>
                    </div>
                </div>
            </div>
            <?php  
            }else{
                if(count( $cart_product_ids ) > 1){ ?>
                <style>
                    .mcsfw_product_slider{
                        display: none;
                    }
                </style>
                <?php }
            }
        } ?>
        <div class="mobilemar"></div>
        <?php } ?>
    </div>
    </div>
</div>
<?php
$mcsfw_totals = WC()->cart->get_totals();
$sub_total = $mcsfw_totals['subtotal'];
$total = $mcsfw_totals['total'];?>
<div class="footer-heding-bottom">
    <?php if($display_subtotal == 'true'){ ?>
    <div class="bot-heding">
        <span
            class="bot-heding-sub"><?php echo __('Orders will be delivered within 4-5 working* days. *T&C apply. ', 'mini-cart-sidebar-for-woocommerce'); ?></span>
        <span><?php echo esc_attr(WC()->cart->get_cart_contents_count()); ?> ITEMS</span>
    </div>
    <?php } ?>

    <div class="bot-heding">
        <span class="subside"><?php echo __('TOTAL','mini-cart-sidebar-for-woocommerce'); ?></span>
        <span
            class="subside"><?php echo get_woocommerce_currency_symbol().number_format($total, 2); ?></span>
    </div>
    <div class="top-flex">
        <?php if(!empty($cart_btn_url) && !empty($cart_btn_text)){ ?>
        <a class="mcsfw_view_cart_btn vs-btn"
            href="<?php echo esc_url($cart_btn_url); ?>"><?php echo esc_attr($cart_btn_text); ?></a>
        <?php } ?>
        <?php if(!empty($checkout_btn_url) && !empty($checkout_btn_text)){ ?>
        <a class="mcsfw_checkout_btn vs-btn"
            href="<?php echo esc_url($checkout_btn_url); ?>"><?php echo esc_attr($checkout_btn_text); ?></a>
        <?php } ?>
        <?php if(!empty($continue_shopping_btn_url) && !empty($shopping_btn_text)){ ?>
        <a class="mcsfw_continue_shopping_btn vs-btn"
            href="<?php echo esc_url($continue_shopping_btn_url); ?>"><?php echo esc_attr($shopping_btn_text); ?></a>
        <?php } ?>
    </div>
</div>
<?php
 $htmlcart = ob_get_contents();
 ob_end_clean();
 ob_start(); ?>
<div class="cart_product_count">
    <?php 
        if($show_product_count == 'true'){
            if($backet_product_count == 'count_items'){
                $product_count = count(WC()->cart->get_cart());
            }else{
                $product_count = WC()->cart->get_cart_contents_count();
            }
        }
    ?>
    <?php echo esc_attr($product_count); ?>
</div>
<?php
 $htmlcount= ob_get_contents();
 ob_end_clean();
 $arr=array(
        "htmlcart"=>$htmlcart,
        "htmlcount"=>$htmlcount
    );
echo json_encode($arr);
exit();
}
add_action('wp_ajax_mcsfw_remove_product_from_cart', 'mcsfw_remove_product_from_cart');
add_action('wp_ajax_nopriv_mcsfw_remove_product_from_cart', 'mcsfw_remove_product_from_cart');


/* Update Product Quantity */
function mcsfw_atcpro_qty_val() { 

$qty = sanitize_text_field($_REQUEST['qty']);
$pro_key  = sanitize_text_field($_REQUEST['product_key']);

global $woocommerce;
$update_quantity = $woocommerce->cart->set_quantity($pro_key, $qty);

exit; ?>
<?php
}
add_action( 'wp_ajax_mcsfw_atcpro_qty_val', 'mcsfw_atcpro_qty_val');
add_action( 'wp_ajax_nopriv_mcsfw_atcpro_qty_val', 'mcsfw_atcpro_qty_val' );


/* Add to cart slider */
function woocommerce_ajax_add_to_cart(){
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $product_status = get_post_status($product_id);
    $quantity = absint($_POST['quantity']?$_POST['quantity']:1);
    $variation_id = absint($_POST['variation_id']?$_POST['variation_id']:0);
    if (WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        WC_AJAX :: get_refreshed_fragments();
        wp_send_json_success();
    }
    wp_die();
}
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');


function woocommerce_ajax_remove_to_cart(){
    global $wpdb, $woocommerce;
    session_start();
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item){
        if($cart_item['product_id'] == $_POST['product_id'] ){
            // Remove product in the cart using  cart_item_key.
            $woocommerce->cart->get_remove_url($cart_item_key);
			WC_AJAX::get_refreshed_fragments();
        	wp_send_json_success();
        }
    }
    wp_die();
}
add_action('wp_ajax_woocommerce_ajax_remove_to_cart', 'woocommerce_ajax_remove_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_remove_to_cart', 'woocommerce_ajax_remove_to_cart');