<?php
add_action('admin_menu', 'mcsfw_menu_settings');
function mcsfw_menu_settings(){
    add_menu_page( 
        'Prodcut Side Cart', // page <title>Title</title>
        'Prodcut Side Cart', // menu link text
        'manage_options', // capability to access the page
        'mcsfw_settings', // page URL slug
        'mcsfw_add_to_cart_settings', // callback function /w content
        'dashicons-cart', // menu icon
        58
    );
}
function mcsfw_add_to_cart_settings(){
    global $mcsfw_icon, $woocommerce;
    if(isset($_REQUEST['message'])  && $_REQUEST['message'] == 'success'){ ?>
<div class="notice notice-success is-dismissible">
    <p><strong><?php echo __( 'Setting saved successfully.', 'mini-cart-sidebar-for-woocommerce' );?></strong></p>
</div>
<?php } ?>
<div class="mcsfw_main_container">
    <ul class="nav-tab-wrapper woo-nav-tab-wrapper">
        <li class="nav-tab nav-tab-active" data-tab="mcsfw-tab-general">
            <?php echo __('General','mini-cart-sidebar-for-woocommerce');?></li>
        <li class="nav-tab" data-tab="mcsfw-tab-style-settings">
            <?php echo __('Side Cart Style','mini-cart-sidebar-for-woocommerce');?></li>
        <li class="nav-tab" data-tab="mcsfw-tab-text-url-settings">
            <?php echo __('Text / Url','mini-cart-sidebar-for-woocommerce');?></li>
    </ul>

<?php
settings_fields( 'mcsfw_settings' );
do_settings_sections( 'mcsfw_settings' );
?>
    <form action='<?php echo get_permalink(); ?>' id="mcsfw-add-to-cart" method='post'>
        <div id="mcsfw-tab-general" class="tab-content current">
            <h2><?php echo __('Side Cart Basket', 'mini-cart-sidebar-for-woocommerce'); ?></h2>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><?php echo __('Enable','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" class="ui-toggle" name="atc_enable" value="true"
                                    <?php checked('true', get_option("atc_enable",'true')); ?>>
                                <?php echo __('Enable this option cart button will show.','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Enable Mobile','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" class="ui-toggle" name="mobile_en" value="true"
                                    <?php checked('true', get_option("mobile_en",'true')); ?>>
                                <?php echo __('Enable this option mobile view.','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Show Product Count','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" class="ui-toggle" name="show_product_count" value="true"
                                    <?php checked('true', get_option("show_product_count",'true')); ?>>
                                <?php echo __('Show Product Count','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Basket Count','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <select name="backet_product_count">
                                <option value="count_quantity"
                                    <?php selected('count_quantity', get_option("backet_product_count","count_items")); ?>>
                                    <?php echo __('Sum of Quantity of all the products','mini-cart-sidebar-for-woocommerce');?>
                                </option>
                                <option value="count_items"
                                    <?php selected('count_items', get_option("backet_product_count","count_items")); ?>>
                                    <?php echo __('Number of products','mini-cart-sidebar-for-woocommerce');?>
                                </option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="sidecart_header">
                <table class="form-table">
                    <h2><?php echo __('Side Cart Header','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Show','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" class="ui-toggle" name="enable_header_close" value="true"
                                    <?php checked('true', get_option("enable_header_close",'true')); ?>>
                                <b><?php echo __('Close Icon','mini-cart-sidebar-for-woocommerce'); ?></b>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="sidecart_body">
                <table class="form-table">
                    <h2><?php echo __('Side Cart Body','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Show','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <label class="mcsfw_cart_body">
                                <label>
                                    <input type="checkbox" class="ui-toggle" name="enable_pro_img" value="true"
                                        <?php checked('true', get_option("enable_pro_img",'true')); ?>>
                                    <?php echo __('Product Image','mini-cart-sidebar-for-woocommerce'); ?>
                                </label>
                            </label>
                            <label class="mcsfw_cart_body">
                                <label>
                                    <input type="checkbox" class="ui-toggle" name="enable_pro_name" value="true"
                                        <?php checked('true', get_option("enable_pro_name",'true')); ?>>
                                    <?php echo __('Product Name','mini-cart-sidebar-for-woocommerce'); ?>
                                </label>
                            </label>
                            <label class="mcsfw_cart_body">
                                <label>
                                    <input type="checkbox" class="ui-toggle" name="enable_pro_price" value="true"
                                        <?php checked('true', get_option("enable_pro_price",'true')); ?>>
                                    <?php echo __('Product Price','mini-cart-sidebar-for-woocommerce'); ?>
                                </label>
                            </label>
                            <label class="mcsfw_cart_body">
                                <label>
                                    <input type="checkbox" class="ui-toggle" name="enable_pro_total" value="true"
                                        <?php checked('true', get_option("enable_pro_total",'true')); ?>>
                                    <?php echo __('Product Total','mini-cart-sidebar-for-woocommerce'); ?>
                                </label>
                            </label>
                            <label class="mcsfw_cart_body">
                                <label>
                                    <input type="checkbox" class="ui-toggle" name="enable_pro_qty" value="true"
                                        <?php checked('true', get_option("enable_pro_qty",'true')); ?>>
                                    <?php echo __('Product qty box','mini-cart-sidebar-for-woocommerce'); ?>
                                </label>
                            </label>
                            <label class="mcsfw_cart_body">
                                <label>
                                    <input type="checkbox" class="ui-toggle" name="enable_pro_delete" value="true"
                                        <?php checked('true', get_option("enable_pro_delete",'true')); ?>>
                                    <?php echo __('Product Delete','mini-cart-sidebar-for-woocommerce'); ?>
                                </label>
                            </label>
                            <label class="mcsfw_cart_body">
                                <label>
                                    <input type="checkbox" class="ui-toggle" name="enable_product_link" value="true"
                                        <?php checked('true', get_option("enable_product_link",'true')); ?>>
                                    <?php echo __('Link to Product Page','mini-cart-sidebar-for-woocommerce'); ?>
                                </label>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="sidecart_slider">
                <table class="form-table">
                    <h2><?php echo __('Side Cart Product Slider','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th><?php echo __('Select Product','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <select class="mcsfw_product_select_slider" name="mcsfw_product_slider[]"
                                multiple="multiple" style="width:100%;max-width:15em;">
                                <?php
                                    $product = get_option('mcsfw_product_slider');
                                    if(!empty($product)){
                                    foreach ($product as $value) {
                                        $productc = wc_get_product( $value );
                                        if ( !empty($productc) && $productc->is_in_stock() && $productc->is_purchasable() ) {
                                            $title = $productc->get_name();
                                            if(isset($value)){
                                                $select = "selected";
                                            }else{
                                                $select = "";
                                            }
                                     ?>
                                <option value="<?php echo esc_attr($value); ?>" <?php echo $select; ?>>
                                    <?php echo esc_attr($title); ?></option>
                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Enable On Desktop','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" class="ui-toggle" name="slid_enable_desk" value="true"
                                    <?php checked('true', get_option("slid_enable_desk",'true')); ?>>
                                <?php echo __('Enable This Option Product Slider Will Display On Desktop.','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Enable On Mobile','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" class="ui-toggle" name="slid_enable_mob" value="true"
                                    <?php checked('true', get_option("slid_enable_mob",'true')); ?>>
                                <?php echo __('Enable This Option Product Slider Will Display On Mobile.','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="sidecart_footer">
                <table class="form-table">
                    <h2><?php echo __('Side Cart Footer','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Show','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" class="ui-toggle" name="display_subtotal" value="true"
                                    <?php checked('true', get_option("display_subtotal",'true')); ?>>
                                <?php echo __('Subtotal','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="mcsfw-tab-style-settings" class="tab-content">
            <div class="sidecart_body">
                <table class="form-table">
                    <h2><?php echo __('Side Cart','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Sidebar Width','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="number" name="sidebar_max_width"
                                    value="<?php echo esc_attr(get_option('sidebar_max_width','670')); ?>">
                                <?php echo __('Value in px (Default: 670).','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="sidecart_width">
                <table class="form-table">
                    <h2><?php echo __('Side Cart Header','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Heading Font Size','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="number" name="head_font_size"
                                    value="<?php echo esc_attr(get_option('head_font_size','28')); ?>">
                                <?php echo __('Value in px (Default: 28).','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Close Icon Size','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="number" name="head_close_size"
                                    value="<?php echo esc_attr(get_option('head_close_size','28')); ?>">
                                <?php echo __('Value in px (Default: 28).','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __('Heading Position','mini-cart-sidebar-for-woocommerce');?></th>
                        <td>
                            <select name="header_heading_position">
                                <option value="center"
                                    <?php selected('center', get_option("header_heading_position","center")); ?>>
                                    <?php echo __('Center','mini-cart-sidebar-for-woocommerce');?></option>
                                <option value="left"
                                    <?php selected('left', get_option("header_heading_position","center")); ?>>
                                    <?php echo __('Left','mini-cart-sidebar-for-woocommerce');?></option>
                                <option value="right"
                                    <?php selected('right', get_option("header_heading_position","center")); ?>>
                                    <?php echo __('Right','mini-cart-sidebar-for-woocommerce');?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __('Close Icon Position','mini-cart-sidebar-for-woocommerce');?></th>
                        <td>
                            <select name="header_close_position">
                                <option value="left"
                                    <?php selected('left', get_option("header_close_position","right")); ?>>
                                    <?php echo __('Left','mini-cart-sidebar-for-woocommerce');?></option>
                                <option value="right"
                                    <?php selected('right', get_option("header_close_position","right")); ?>>
                                    <?php echo __('Right','mini-cart-sidebar-for-woocommerce');?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __('Background Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#f8f8f8" name="shead_color"
                                value="<?php echo esc_attr(get_option('shead_color','#f8f8f8')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __('Header Border Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#b7b7b7" name="shborder_color"
                                value="<?php echo esc_attr(get_option('shborder_color','#b7b7b7')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __('Header Border style','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <select name="shb_style" class="shb_border">
                                <option value="solid" <?php selected('solid', get_option("shb_style","solid")) ?>>
                                    <?php echo __('Solid','mini-cart-sidebar-for-woocommerce'); ?></option>
                                <option value="dotted" <?php selected('dotted', get_option("shb_style","solid")) ?>>
                                    <?php echo __('Dotted','mini-cart-sidebar-for-woocommerce'); ?></option>
                                <option value="dashed" <?php selected('dashed', get_option("shb_style","solid")) ?>>
                                    <?php echo __('Dashed','mini-cart-sidebar-for-woocommerce'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __('Header Title Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#000000" name="shthead_color"
                                value="<?php echo esc_attr(get_option('shthead_color','#000000')); ?>">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="sidecart_body">
                <table class="form-table">
                    <h2><?php echo __('Side Cart Body','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Background Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#fff" name="clpback_color"
                                value="<?php echo esc_attr(get_option('clpback_color','#fff')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Image Width','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="number" name="clpimg_width"
                                value="<?php echo esc_attr(get_option('clpimg_width','100')); ?>"><label><?php echo __('Value in px (Default: 100).','mini-cart-sidebar-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Product Image Border Radius','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="number" name="pib_radious"
                                value="<?php echo esc_attr(get_option('pib_radious','0')); ?>"><label><?php echo __('Value in px.','mini-cart-sidebar-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Product Title Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#000000" name="ptc_color"
                                value="<?php echo esc_attr(get_option('ptc_color','#000000')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Product Title Hover Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#ff9065" name="pth_hover"
                                value="<?php echo esc_attr(get_option('pth_hover','#ff9065')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Product Price Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#000000" name="prop_color"
                                value="<?php echo esc_attr(get_option('prop_color','#000000')); ?>">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="sidecart_slider">
                <table class="form-table">
                    <h2><?php echo __('Side Cart Product Slider','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Background Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#f8f8f8"
                                name="slider_back_color"
                                value="<?php echo esc_attr(get_option('slider_back_color','#f8f8f8')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Button Background Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#000000"
                                name="slider_btn_back_color"
                                value="<?php echo esc_attr(get_option('slider_btn_back_color','#000000')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Button Text Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#ffffff"
                                name="slider_btn_text_color"
                                value="<?php echo esc_attr(get_option('slider_btn_text_color','#ffffff')); ?>">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="sidecart_footer">
                <table class="form-table">
                    <h2><?php echo __('Side Cart Footer','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Button Font Size','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="number" name="btn_font_size"
                                value="<?php echo esc_attr(get_option('btn_font_size','16')); ?>"><label><?php echo __('Value in px (Default: 16).','mini-cart-sidebar-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Background Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#f8f8f8" name="atcfb_color"
                                value="<?php echo esc_attr(get_option('atcfb_color','#f8f8f8')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Button background Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#000000" name="cbc_color"
                                value="<?php echo esc_attr(get_option('cbc_color','#000000')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Button background Hover Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#3cb247" name="cbh_color"
                                value="<?php echo esc_attr(get_option('cbh_color','#3cb247')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Button Text Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#ffffff" name="btn_text_color"
                                value="<?php echo esc_attr(get_option('btn_text_color','#ffffff')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Button Text Hover Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#000" name="btnh_color"
                                value="<?php echo esc_attr(get_option('btnh_color','#000')); ?>">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="footer_contain">
                <table class="form-table">
                    <h2><?php echo __('Basket Setting','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th><?php echo __('Basket Position','mini-cart-sidebar-for-woocommerce');?></th>
                        <td>
                            <select name="basekt_position">
                                <option value="right"
                                    <?php selected('right', get_option("basekt_position","right")); ?>>
                                    <?php echo __('Right','mini-cart-sidebar-for-woocommerce');?></option>
                                <option value="left" <?php selected('left', get_option("basekt_position","right")); ?>>
                                    <?php echo __('Left','mini-cart-sidebar-for-woocommerce');?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __('Basket Shape','mini-cart-sidebar-for-woocommerce');?></th>
                        <td>
                            <select name="basekt_shape">
                                <option value="square" <?php selected('square', get_option("basekt_shape","round")); ?>>
                                    <?php echo __('Square','mini-cart-sidebar-for-woocommerce');?></option>
                                <option value="round" <?php selected('round', get_option("basekt_shape","round")); ?>>
                                    <?php echo __('Round','mini-cart-sidebar-for-woocommerce');?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Basket Icon Size','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="number" name="basket_size"
                                    value="<?php echo esc_attr(get_option('basket_size','30')); ?>">
                                <?php echo __('Value in px (Default: 30).','mini-cart-sidebar-for-woocommerce'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Select Cart','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <label class="radio-icons">
                                <input type="radio" name="basket_icon" value="cart_1"
                                    <?php checked('cart_1',get_option('basket_icon')); ?> checked>
                                <?php echo $mcsfw_icon['cart_1']; ?>
                            </label>

                            <label class="radio-icons">
                                <input type="radio" name="basket_icon" value="cart_2"
                                    <?php checked('cart_2',get_option('basket_icon')); ?>>
                                <?php echo $mcsfw_icon['cart_2']; ?>
                            </label>

                            <label class="radio-icons">
                                <input type="radio" name="basket_icon" value="cart_3"
                                    <?php checked('cart_3',get_option('basket_icon')); ?>>
                                <?php echo $mcsfw_icon['cart_3']; ?>
                            </label>

                            <label class="radio-icons">
                                <input type="radio" name="basket_icon" value="cart_4"
                                    <?php checked('cart_4',get_option('basket_icon')); ?>>
                                <?php echo $mcsfw_icon['cart_4']; ?>
                            </label>

                            <label class="radio-icons">
                                <input type="radio" name="basket_icon" value="cart_5"
                                    <?php checked('cart_5', get_option('basket_icon')); ?>>
                                <?php echo $mcsfw_icon['cart_5']; ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Basket Background Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#000000" name="basket_bg_color"
                                value="<?php echo esc_attr(get_option('basket_bg_color','#000000')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Basket Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#ffffff" name="basket_color"
                                value="<?php echo esc_attr(get_option('basket_color','#ffffff')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __('Basket Count Position','mini-cart-sidebar-for-woocommerce');?></th>
                        <td>
                            <select name="basket_count_position">
                                <option value="top_right"
                                    <?php selected('top_right', get_option("basket_count_position","top_right")); ?>>
                                    <?php echo __('Top right','mini-cart-sidebar-for-woocommerce');?></option>
                                <option value="top_left"
                                    <?php selected('top_left', get_option("basket_count_position","top_right")); ?>>
                                    <?php echo __('Top Left','mini-cart-sidebar-for-woocommerce');?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Count Text Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#ffffff" name="count_text_color"
                                value="<?php echo esc_attr(get_option('count_text_color','#ffffff')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Count Background Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#12b99a" name="count_bg_color"
                                value="<?php echo esc_attr(get_option('count_bg_color','#12b99a')); ?>">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="footer_contain">
                <table class="form-table">
                    <h2><?php echo __('Trash Icon Setting','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Select Trash Icon','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <label class="trash_radio radio-icons">
                                <input type="radio" name="trash_icon" value="trash_icon1"
                                <?php checked('trash_icon1',get_option('trash_icon')); ?> checked>
                                <?php echo $mcsfw_icon['trash_icon1']; ?>
                            </label>

                            <label class="trash_radio radio-icons">
                                <input type="radio" name="trash_icon" value="trash_icon2"
                                    <?php checked('trash_icon2',get_option('trash_icon')); ?>>
                                <?php echo $mcsfw_icon['trash_icon2']; ?>
                            </label>

                            <label class="trash_radio radio-icons">
                                <input type="radio" name="trash_icon" value="trash_icon3"
                                    <?php checked('trash_icon3',get_option('trash_icon')); ?>>
                                <?php echo $mcsfw_icon['trash_icon3']; ?>
                            </label>
                            <label class="trash_radio radio-icons">
                                <input type="radio" name="trash_icon" value="trash_icon4"
                                    <?php checked('trash_icon4',get_option('trash_icon')); ?>>
                                <?php echo $mcsfw_icon['trash_icon4']; ?>
                            </label>

                            <label class="trash_radio radio-icons">
                                <input type="radio" name="trash_icon" value="trash_icon5"
                                    <?php checked('trash_icon5',get_option('trash_icon')); ?>>
                                <?php echo $mcsfw_icon['trash_icon5']; ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Trash Icon Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#808b97" name="pd_color"
                                value="<?php echo esc_attr(get_option('pd_color','#808b97')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Trash Icon Hover Color','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#ff0000" name="pdc_hover"
                                value="<?php echo esc_attr(get_option('pdc_hover','#ff0000')); ?>">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="footer_contain">
                <table class="form-table">
                    <h2><?php echo __('Close Icon Setting','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Select Close Icon','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <label class="close_radio radio-icons">
                                <input type="radio" name="close_icon" value="close_icon_1"
                                    <?php checked('close_icon_1',get_option('close_icon')); ?> checked>
                                <?php echo $mcsfw_icon['close_icon_1']; ?>
                            </label>

                            <label class="close_radio radio-icons">
                                <input type="radio" name="close_icon" value="close_icon_2"
                                    <?php checked('close_icon_2',get_option('close_icon')); ?>>
                                <?php echo $mcsfw_icon['close_icon_2']; ?>
                            </label>

                            <label class="close_radio radio-icons">
                                <input type="radio" name="close_icon" value="close_icon_3"
                                    <?php checked('close_icon_3',get_option('close_icon')); ?>>
                                <?php echo $mcsfw_icon['close_icon_3']; ?>
                            </label>

                            <label class="close_radio radio-icons">
                                <input type="radio" name="close_icon" value="close_icon_4"
                                    <?php checked('close_icon_4',get_option('close_icon')); ?>>
                                <?php echo $mcsfw_icon['close_icon_4']; ?>
                            </label>
                            
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Close Icon Color','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" class="color-picker" data-default-color="#000000" name="close_icon_color"
                                value="<?php echo esc_attr(get_option('close_icon_color','#000000')); ?>" />
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="mcsfw-tab-text-url-settings" class="tab-content">
            <span><?php echo __('To Remove Element Leave text or Link Empty','mini-cart-sidebar-for-woocommerce'); ?></span>
            <h2><?php echo __('Text','mini-cart-sidebar-for-woocommerce'); ?></h2>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><?php echo __('Cart Heading','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" name="cart_head_text"
                                value="<?php echo esc_attr(get_option('cart_head_text','Your Cart')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Cart Button','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" name="cart_btn_text"
                                value="<?php echo esc_attr(get_option('cart_btn_text','View Cart')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Checkout Text','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" name="checkout_btn_text"
                                value="<?php echo esc_attr(get_option('checkout_btn_text','Checkout Now')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Keep Shopping Text','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" name="shopping_btn_text"
                                value="<?php echo esc_attr(get_option('shopping_btn_text','Keep Shopping')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Empty Cart Text','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" name="empty_cart_text"
                                value="<?php echo esc_attr(get_option('empty_cart_text','Your cart is empty.')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo __('Return To Shop Button','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" name="return_shop_text"
                                value="<?php echo esc_attr(get_option('return_shop_text','Return to Shop')); ?>" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="sidecart_url">
                <table class="form-table">
                    <h2><?php echo __('Url','mini-cart-sidebar-for-woocommerce'); ?></h2>
                    <tr>
                        <th scope="row"><?php echo __('Cart Url','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" name="cart_btn_url"
                                value="<?php echo esc_attr(get_option('cart_btn_url',wc_get_cart_url())); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Checkout Url','mini-cart-sidebar-for-woocommerce'); ?></th>
                        <td>
                            <input type="text" name="checkout_btn_url"
                                value="<?php echo esc_attr(get_option('checkout_btn_url',wc_get_checkout_url())); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo __('Continue Shopping','mini-cart-sidebar-for-woocommerce'); ?>
                        </th>
                        <td>
                            <input type="text" name="continue_shopping_btn_url"
                                value="<?php echo esc_attr(get_option('continue_shopping_btn_url','#')); ?>" /><label><?php echo __('Use # to close side cart & remain on the same page','mini-cart-sidebar-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <p class="submit">
            <input type="hidden" name="action" value="mcsfw_save_option">
            <input type="submit" value="Save changes" name="submit" class="button-primary">
        </p>
    </form>
</div>

<?php
}


function mcsfw_recursive_sanitize_text_field($array) {
    if(!empty($array)) {
        foreach ( $array as $key => $value ) {
            if ( is_array( $value ) ) {
                $value = mcsfw_recursive_sanitize_text_field($value);
            }else{
                $value = sanitize_text_field( $value );
            }
        }
    }
    return $array;
}

add_action('init','mcsfw_add_setting_type');

function mcsfw_add_setting_type(){
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'mcsfw_save_option') {
        if (isset($_REQUEST['sidebar_max_width'])) {
            update_option('sidebar_max_width',sanitize_text_field($_REQUEST['sidebar_max_width']));
        }else{
            update_option('sidebar_max_width','');
        }
        update_option('atc_enable',sanitize_text_field($_REQUEST['atc_enable']));
        update_option('mobile_en',sanitize_text_field($_REQUEST['mobile_en']));
        update_option('shead_color',sanitize_text_field($_REQUEST['shead_color']));
        update_option('shborder_color',sanitize_text_field($_REQUEST['shborder_color']));
        update_option('shb_style',sanitize_text_field($_REQUEST['shb_style']));
        update_option('shthead_color',sanitize_text_field($_REQUEST['shthead_color']));
        update_option('clpback_color',sanitize_text_field($_REQUEST['clpback_color']));
        update_option('clpimg_width',sanitize_text_field($_REQUEST['clpimg_width']));
        update_option('pib_radious',sanitize_text_field($_REQUEST['pib_radious']));
        update_option('ptc_color',sanitize_text_field($_REQUEST['ptc_color']));
        update_option('pth_hover',sanitize_text_field($_REQUEST['pth_hover']));
        update_option('prop_color',sanitize_text_field($_REQUEST['prop_color']));
        update_option('pd_color',sanitize_text_field($_REQUEST['pd_color']));
        update_option('pdc_hover',sanitize_text_field($_REQUEST['pdc_hover']));

        if(isset($_REQUEST['mcsfw_product_slider'])) {
            $mcsfw_product_slider = mcsfw_recursive_sanitize_text_field($_REQUEST['mcsfw_product_slider'] );
            update_option('mcsfw_product_slider', $mcsfw_product_slider, 'yes');
        }
        update_option('slider_back_color',sanitize_text_field($_REQUEST['slider_back_color']));
        update_option('slider_btn_back_color',sanitize_text_field($_REQUEST['slider_btn_back_color']));
        update_option('slider_btn_text_color',sanitize_text_field($_REQUEST['slider_btn_text_color']));
        
        if(!empty($_REQUEST['slid_enable_desk'])){
            update_option('slid_enable_desk',sanitize_text_field($_REQUEST['slid_enable_desk']));
        }else{
            update_option('slid_enable_desk','');
        }

        if(!empty($_REQUEST['slid_enable_mob'])){
            update_option('slid_enable_mob',sanitize_text_field($_REQUEST['slid_enable_mob']));
        }else{
            update_option('slid_enable_mob','');
        }

        if(!empty($_REQUEST['display_subtotal'])){
            update_option('display_subtotal',sanitize_text_field($_REQUEST['display_subtotal']));
        }else{
            update_option('display_subtotal','');
        }

        if(!empty($_REQUEST['enable_pro_img'])){
            update_option('enable_pro_img',sanitize_text_field($_REQUEST['enable_pro_img']));
        }else{
            update_option('enable_pro_img','');
        }

        if(!empty($_REQUEST['enable_pro_name'])){
            update_option('enable_pro_name',sanitize_text_field($_REQUEST['enable_pro_name']));
        }else{
            update_option('enable_pro_name','');
        }

        if(!empty($_REQUEST['enable_pro_price'])){
            update_option('enable_pro_price',sanitize_text_field($_REQUEST['enable_pro_price']));
        }else{
            update_option('enable_pro_price','');
        }

        if(!empty($_REQUEST['enable_pro_total'])){
            update_option('enable_pro_total',sanitize_text_field($_REQUEST['enable_pro_total']));
        }else{
            update_option('enable_pro_total','');
        }

        if(!empty($_REQUEST['enable_pro_qty'])){
            update_option('enable_pro_qty',sanitize_text_field($_REQUEST['enable_pro_qty']));
        }else{
            update_option('enable_pro_qty','');
        }

        if(!empty($_REQUEST['enable_pro_delete'])){
            update_option('enable_pro_delete',sanitize_text_field($_REQUEST['enable_pro_delete']));
        }else{
            update_option('enable_pro_delete','');
        }

        if(!empty($_REQUEST['enable_product_link'])){
            update_option('enable_product_link',sanitize_text_field($_REQUEST['enable_product_link']));
        }else{
            update_option('enable_product_link','');
        }

        if(!empty($_REQUEST['enable_header_close'])){
            update_option('enable_header_close',sanitize_text_field($_REQUEST['enable_header_close']));
        }else{
            update_option('enable_header_close','');
        }

        if(!empty($_REQUEST['show_product_count'])){
            update_option('show_product_count',sanitize_text_field($_REQUEST['show_product_count']));
        }else{
            update_option('show_product_count','');
        }
        
        update_option('atcfb_color',sanitize_text_field($_REQUEST['atcfb_color']));
        update_option('cbc_color',sanitize_text_field($_REQUEST['cbc_color']));
        update_option('cbh_color',sanitize_text_field($_REQUEST['cbh_color']));
        update_option('btn_text_color',sanitize_text_field($_REQUEST['btn_text_color']));
        update_option('btnh_color',sanitize_text_field($_REQUEST['btnh_color']));
        update_option('cart_head_text',sanitize_text_field($_REQUEST['cart_head_text']));
        update_option('cart_btn_text',sanitize_text_field($_REQUEST['cart_btn_text']));
        update_option('checkout_btn_text',sanitize_text_field($_REQUEST['checkout_btn_text']));
        update_option('shopping_btn_text',sanitize_text_field($_REQUEST['shopping_btn_text']));
        update_option('empty_cart_text',sanitize_text_field($_REQUEST['empty_cart_text']));
        update_option('return_shop_text',sanitize_text_field($_REQUEST['return_shop_text']));
        update_option('cart_btn_url',sanitize_text_field($_REQUEST['cart_btn_url']));
        update_option('checkout_btn_url',sanitize_text_field($_REQUEST['checkout_btn_url']));
        update_option('continue_shopping_btn_url',sanitize_text_field($_REQUEST['continue_shopping_btn_url']));
        update_option('basket_bg_color',sanitize_text_field($_REQUEST['basket_bg_color']));
        update_option('basket_color',sanitize_text_field($_REQUEST['basket_color']));
        update_option('basket_count_position',sanitize_text_field($_REQUEST['basket_count_position']));
        update_option('count_text_color',sanitize_text_field($_REQUEST['count_text_color']));
        update_option('count_bg_color',sanitize_text_field($_REQUEST['count_bg_color']));
        update_option('close_icon',sanitize_text_field($_REQUEST['close_icon']));
        update_option('close_icon_color',sanitize_text_field($_REQUEST['close_icon_color']));
        update_option('trash_icon',sanitize_text_field($_REQUEST['trash_icon']));
        update_option('basket_icon',sanitize_text_field($_REQUEST['basket_icon']));
        update_option('basket_size',sanitize_text_field($_REQUEST['basket_size']));
        update_option('head_font_size',sanitize_text_field($_REQUEST['head_font_size']));
        update_option('head_close_size',sanitize_text_field($_REQUEST['head_close_size']));
        update_option('header_heading_position',sanitize_text_field($_REQUEST['header_heading_position']));
        update_option('header_close_position',sanitize_text_field($_REQUEST['header_close_position']));
        update_option('basekt_position',sanitize_text_field($_REQUEST['basekt_position']));
        update_option('basekt_shape',sanitize_text_field($_REQUEST['basekt_shape']));
        update_option('backet_product_count',sanitize_text_field($_REQUEST['backet_product_count']));
        update_option('btn_font_size',sanitize_text_field($_REQUEST['btn_font_size']));

        wp_redirect( admin_url( '/admin.php?page=mcsfw_settings&message=success' ));
    }
}




register_activation_hook(MCSFW_PLUGIN_DIR, 'mcsfw_install_default_value');
function mcsfw_install_default_value() {
    update_option('sidebar_max_width','670');
    update_option('atc_enable','true');
    update_option('mobile_en','true');
    update_option('shead_color','#f8f8f8');
    update_option('shborder_color','#b7b7b7');
    update_option('shb_style','solid');
    update_option('shthead_color','#000000');
    update_option('clpback_color','#fff');
    update_option('clpimg_width','100');
    update_option('pib_radious','0');
    update_option('ptc_color','#000000');
    update_option('pth_hover','#ff9065');
    update_option('prop_color','#000000');
    update_option('pd_color','#808b97');
    update_option('pdc_hover','#ff0000');
    update_option('slider_back_color','#f8f8f8');
    update_option('slider_btn_back_color','#000000');
    update_option('slider_btn_text_color','#ffffff');
    update_option('slid_enable_desk','true');
    update_option('slid_enable_mob','true');
    update_option('display_subtotal','true');
    update_option('btn_font_size','16');
    update_option('atcfb_color','#f8f8f8');
    update_option('cbc_color','#000000');
    update_option('cbh_color','#3cb247');
    update_option('btn_text_color','#ffffff');
    update_option('btnh_color','#000');
    update_option('cart_head_text','Your Cart');
    update_option('cart_btn_text','View Cart');
    update_option('checkout_btn_text','Checkout Now');
    update_option('shopping_btn_text','Keep Shopping');
    update_option('empty_cart_text','Your cart is empty.');
    update_option('return_shop_text','Return to Shop');
    update_option('cart_btn_url',wc_get_cart_url());
    update_option('checkout_btn_url',wc_get_checkout_url());
    update_option('continue_shopping_btn_url','#');
    update_option('head_font_size','28');
    update_option('head_close_size','28');
    update_option('header_heading_position','center');
    update_option('header_close_position','right');
    update_option('basekt_position','right');
    update_option('basekt_shape','round');
    update_option('show_product_count','true');
    update_option('backet_product_count','count_items');
    update_option('basket_size','30');
    update_option('basket_icon','cart_1');
    update_option('basket_bg_color','#000000');
    update_option('basket_color','#ffffff');
    update_option('basket_count_position','top_right');
    update_option('count_text_color','#ffffff');
    update_option('count_bg_color','#12b99a');
    update_option('trash_icon','trash_icon1');
    update_option('close_icon','close_icon_1');
    update_option('close_icon_color','#000000');
    update_option('enable_pro_img','true');
    update_option('enable_pro_name','true');
    update_option('enable_pro_price','true');
    update_option('enable_pro_total','true');
    update_option('enable_pro_qty','true');
    update_option('enable_pro_delete','true');
    update_option('enable_product_link','true');
    update_option('enable_header_close','true');
}



/* Product Slider */
function mcsfw_product_slider_search() {
    $search = $_GET['term'];
    $post_types = array( 'product','product_variation');
      $args = array(
        'post_type' => $post_types,
        's' => $search,
        'posts_per_page' => -1
      );
      $query = new WP_Query( $args );
      $products = array();
      if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
          $query->the_post();
          $productc = wc_get_product( $query->post->ID );
          if ( $productc && $productc->is_in_stock() && $productc->is_purchasable() ) {
              $products[] = array(
                'id' => $query->post->ID,
                'text' => $query->post->post_title,
                'price' => $productc->get_price_html(),
              );
            }
          }
        }
      // wp_reset_postdata();
      wp_send_json( $products );
}
add_action('wp_ajax_mcsfw_product_slider_search', 'mcsfw_product_slider_search');
add_action('wp_ajax_nopriv_mcsfw_product_slider_search', 'mcsfw_product_slider_search');

/* Icon svgs */
add_action('init','mcsfw_svg');
function mcsfw_svg(){
    global $mcsfw_icon;

    $mcsfw_icon = [
        'close_icon_1' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25" height="25" viewBox="0 0 122.881 122.88" enable-background="new 0 0 122.881 122.88" xml:space="preserve"><g><path d="M61.44,0c16.966,0,32.326,6.877,43.445,17.996c11.119,11.118,17.996,26.479,17.996,43.444 c0,16.967-6.877,32.326-17.996,43.444C93.766,116.003,78.406,122.88,61.44,122.88c-16.966,0-32.326-6.877-43.444-17.996 C6.877,93.766,0,78.406,0,61.439c0-16.965,6.877-32.326,17.996-43.444C29.114,6.877,44.474,0,61.44,0L61.44,0z M80.16,37.369 c1.301-1.302,3.412-1.302,4.713,0c1.301,1.301,1.301,3.411,0,4.713L65.512,61.444l19.361,19.362c1.301,1.301,1.301,3.411,0,4.713 c-1.301,1.301-3.412,1.301-4.713,0L60.798,66.157L41.436,85.52c-1.301,1.301-3.412,1.301-4.713,0c-1.301-1.302-1.301-3.412,0-4.713 l19.363-19.362L36.723,42.082c-1.301-1.302-1.301-3.412,0-4.713c1.301-1.302,3.412-1.302,4.713,0l19.363,19.362L80.16,37.369 L80.16,37.369z M100.172,22.708C90.26,12.796,76.566,6.666,61.44,6.666c-15.126,0-28.819,6.13-38.731,16.042 C12.797,32.62,6.666,46.314,6.666,61.439c0,15.126,6.131,28.82,16.042,38.732c9.912,9.911,23.605,16.042,38.731,16.042 c15.126,0,28.82-6.131,38.732-16.042c9.912-9.912,16.043-23.606,16.043-38.732C116.215,46.314,110.084,32.62,100.172,22.708 L100.172,22.708z"/></g></svg>',

        'close_icon_2' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25" height="25" viewBox="0 0 122.878 122.88" enable-background="new 0 0 122.878 122.88" xml:space="preserve"><g><path d="M1.426,8.313c-1.901-1.901-1.901-4.984,0-6.886c1.901-1.902,4.984-1.902,6.886,0l53.127,53.127l53.127-53.127 c1.901-1.902,4.984-1.902,6.887,0c1.901,1.901,1.901,4.985,0,6.886L68.324,61.439l53.128,53.128c1.901,1.901,1.901,4.984,0,6.886 c-1.902,1.902-4.985,1.902-6.887,0L61.438,68.326L8.312,121.453c-1.901,1.902-4.984,1.902-6.886,0 c-1.901-1.901-1.901-4.984,0-6.886l53.127-53.128L1.426,8.313L1.426,8.313z"/></g></svg>',

        'close_icon_3' => '<svg width="25" height="25" id="Icons" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#232323;}</style></defs><path class="cls-1" d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm4.707,14.293a1,1,0,1,1-1.414,1.414L12,13.414,8.707,16.707a1,1,0,1,1-1.414-1.414L10.586,12,7.293,8.707A1,1,0,1,1,8.707,7.293L12,10.586l3.293-3.293a1,1,0,1,1,1.414,1.414L13.414,12Z"/></svg>',

        'close_icon_4' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="25" height="25" viewBox="0 0 256 256" xml:space="preserve">
            <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                <path d="M 84.568 73.986 H 5.431 C 2.437 73.986 0 71.55 0 68.555 V 37.728 c 0 -2.995 2.437 -5.431 5.431 -5.431 h 4.723 c 0.552 0 1 0.448 1 1 s -0.448 1 -1 1 H 5.431 C 3.539 34.297 2 35.836 2 37.728 v 30.827 c 0 1.893 1.539 3.432 3.431 3.432 h 79.137 c 1.893 0 3.432 -1.539 3.432 -3.432 V 37.728 c 0 -1.892 -1.539 -3.431 -3.432 -3.431 H 71.58 c -0.553 0 -1 -0.448 -1 -1 s 0.447 -1 1 -1 h 12.988 c 2.995 0 5.432 2.437 5.432 5.431 v 30.827 C 90 71.55 87.563 73.986 84.568 73.986 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 45 23.55 c -2.078 0 -3.768 -1.69 -3.768 -3.769 c 0 -2.078 1.69 -3.768 3.768 -3.768 c 2.078 0 3.769 1.69 3.769 3.768 C 48.769 21.86 47.078 23.55 45 23.55 z M 45 18.014 c -0.975 0 -1.768 0.793 -1.768 1.768 S 44.025 21.55 45 21.55 s 1.769 -0.793 1.769 -1.769 S 45.975 18.014 45 18.014 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 18.564 34.297 c -0.364 0 -0.714 -0.199 -0.892 -0.545 c -0.251 -0.492 -0.056 -1.094 0.436 -1.346 l 23.982 -12.26 c 0.492 -0.25 1.094 -0.057 1.346 0.436 c 0.251 0.492 0.056 1.094 -0.436 1.346 l -23.982 12.26 C 18.873 34.262 18.717 34.297 18.564 34.297 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 71.579 34.297 c -0.153 0 -0.309 -0.035 -0.454 -0.109 L 47.03 21.87 c -0.492 -0.251 -0.687 -0.854 -0.436 -1.346 s 0.855 -0.686 1.346 -0.436 l 24.095 12.318 c 0.492 0.251 0.687 0.854 0.436 1.346 C 72.294 34.098 71.943 34.297 71.579 34.297 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 59.233 34.297 h -40.67 c -0.552 0 -1 -0.448 -1 -1 s 0.448 -1 1 -1 h 40.67 c 0.553 0 1 0.448 1 1 S 59.786 34.297 59.233 34.297 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 46.199 63.084 h -3.208 c -2.565 0 -4.652 -2.087 -4.652 -4.651 V 48.8 c 0 -2.565 2.087 -4.652 4.652 -4.652 h 3.208 c 2.565 0 4.652 2.086 4.652 4.652 v 9.633 C 50.852 60.997 48.765 63.084 46.199 63.084 z M 42.991 46.148 c -1.462 0 -2.652 1.189 -2.652 2.651 v 9.633 c 0 1.462 1.189 2.651 2.652 2.651 h 3.208 c 1.463 0 2.652 -1.189 2.652 -2.651 V 48.8 c 0 -1.462 -1.189 -2.651 -2.652 -2.651 H 42.991 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 79.525 63.084 h -7.508 c -0.553 0 -1 -0.447 -1 -1 V 45.148 c 0 -0.552 0.447 -1 1 -1 h 7.508 c 0.553 0 1 0.448 1 1 s -0.447 1 -1 1 h -6.508 v 14.936 h 6.508 c 0.553 0 1 0.447 1 1 S 80.078 63.084 79.525 63.084 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 76.929 54.616 h -4.911 c -0.553 0 -1 -0.447 -1 -1 s 0.447 -1 1 -1 h 4.911 c 0.553 0 1 0.447 1 1 S 77.481 54.616 76.929 54.616 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 17.335 63.084 h -3.209 c -2.565 0 -4.651 -2.087 -4.651 -4.651 V 48.8 c 0 -2.565 2.086 -4.652 4.651 -4.652 h 3.209 c 2.565 0 4.651 2.086 4.651 4.652 c 0 0.553 -0.448 1 -1 1 s -1 -0.447 -1 -1 c 0 -1.462 -1.189 -2.651 -2.651 -2.651 h -3.209 c -1.462 0 -2.651 1.189 -2.651 2.651 v 9.633 c 0 1.462 1.189 2.651 2.651 2.651 h 3.209 c 1.462 0 2.651 -1.189 2.651 -2.651 c 0 -0.553 0.448 -1 1 -1 s 1 0.447 1 1 C 21.986 60.997 19.9 63.084 17.335 63.084 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 35.16 63.084 h -7.508 c -0.552 0 -1 -0.447 -1 -1 V 45.148 c 0 -0.552 0.448 -1 1 -1 s 1 0.448 1 1 v 15.936 h 6.508 c 0.552 0 1 0.447 1 1 S 35.712 63.084 35.16 63.084 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 62.439 63.343 h -5.61 c -0.553 0 -1 -0.447 -1 -1 s 0.447 -1 1 -1 h 5.61 c 1.096 0 1.987 -0.891 1.987 -1.986 v -2.624 c 0 -1.096 -0.892 -1.987 -1.987 -1.987 h -3.124 c -2.198 0 -3.986 -1.788 -3.986 -3.986 v -2.624 c 0 -2.198 1.788 -3.987 3.986 -3.987 h 4.027 c 0.553 0 1 0.448 1 1 s -0.447 1 -1 1 h -4.027 c -1.096 0 -1.986 0.892 -1.986 1.987 v 2.624 c 0 1.096 0.891 1.986 1.986 1.986 h 3.124 c 2.198 0 3.987 1.789 3.987 3.987 v 2.624 C 66.427 61.555 64.638 63.343 62.439 63.343 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" /></g>
                </svg>',
                
        'trash_icon1' => '<svg width="25" height="25" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M413.7,133.4c-2.4-9-4-14-4-14c-2.6-9.3-9.2-9.3-19-10.9l-53.1-6.7c-6.6-1.1-6.6-1.1-9.2-6.8c-8.7-19.6-11.4-31-20.9-31   h-103c-9.5,0-12.1,11.4-20.8,31.1c-2.6,5.6-2.6,5.6-9.2,6.8l-53.2,6.7c-9.7,1.6-16.7,2.5-19.3,11.8c0,0-1.2,4.1-3.7,13   c-3.2,11.9-4.5,10.6,6.5,10.6h302.4C418.2,144.1,417,145.3,413.7,133.4z"/><path d="M379.4,176H132.6c-16.6,0-17.4,2.2-16.4,14.7l18.7,242.6c1.6,12.3,2.8,14.8,17.5,14.8h207.2c14.7,0,15.9-2.5,17.5-14.8   l18.7-242.6C396.8,178.1,396,176,379.4,176z"/></g></svg>',

        'trash_icon2' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="163.839" height="163.839" viewBox="0,0,256,256"><g fill-rule="evenodd" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(0.05905,0.05905)"><path d="M2841,1240l260,266l5,4l-5,5l-682,663l615,614l67,59l5,4l-5,5l-260,266l-5,4l-5,-5l-660,-706l-1,1v-8v-2h-2v2v8l-1,-1l-660,706l-4,5l-5,-4l-261,-266l-4,-5l4,-4l68,-59l615,-614l-683,-663l-4,-5l4,-4l261,-266l5,-5l4,5l661,709v1h2v-1l661,-709l5,-5z"></path></g></g></svg>',

        'trash_icon3' => '<svg width="25" height="25" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M32 464C32 490.5 53.5 512 80 512h288c26.5 0 48-21.5 48-48V128H32V464zM304 208C304 199.1 311.1 192 320 192s16 7.125 16 16v224c0 8.875-7.125 16-16 16s-16-7.125-16-16V208zM208 208C208 199.1 215.1 192 224 192s16 7.125 16 16v224c0 8.875-7.125 16-16 16s-16-7.125-16-16V208zM112 208C112 199.1 119.1 192 128 192s16 7.125 16 16v224C144 440.9 136.9 448 128 448s-16-7.125-16-16V208zM432 32H320l-11.58-23.16c-2.709-5.42-8.25-8.844-14.31-8.844H153.9c-6.061 0-11.6 3.424-14.31 8.844L128 32H16c-8.836 0-16 7.162-16 16V80c0 8.836 7.164 16 16 16h416c8.838 0 16-7.164 16-16V48C448 39.16 440.8 32 432 32z"/></svg>',

        'trash_icon4' => '<svg width="25" height="25" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/></svg>',

        'trash_icon5' => '<svg enable-background="new -0.5 -0.7 31 32" width="31px" height="25" version="1.1" viewBox="-0.5 -0.7 31 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs/><path clip-rule="evenodd" d="M29.8,8.3C29.8,8.3,29.8,8.3,29.8,8.3L29.8,8.3  C29.8,8.3,29.8,8.3,29.8,8.3z M27.8,10.3h-1v18c0,1.1-0.9,2-2,2h-19c-1.1,0-2-0.9-2-2v-18H2c-1.1,0-2-0.9-2-2c0-1.1,0.9-2,2-2h6.9  C9.2,2.8,12,0,15.4,0s6.3,2.8,6.5,6.3h5.8c1.1,0,2,0.9,2,2C29.8,9.4,28.9,10.3,27.8,10.3z M15.4,3.2c-1.8,0-3.2,1.3-3.4,3.1h6.9  C18.6,4.6,17.2,3.2,15.4,3.2z M23.8,11.5c0-0.5-0.2-0.9-0.5-1.1H7.3C7,10.6,6.8,11,6.8,11.5v14.7c0,0.9,0.7,1.6,1.6,1.6h13.8  c0.9,0,1.6-0.7,1.6-1.6V11.5z M18.8,12.3h3v14h-3V12.3z M13.8,12.3h3v14h-3V12.3z M8.8,12.3h3v14h-3V12.3z M0,8.3C0,8.3,0,8.3,0,8.3  C0,8.3,0,8.3,0,8.3L0,8.3z" fill-rule="evenodd"/></svg>',

        'cart_1' => '<svg id="Layer_1" width="30" height="30" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128.12 106.26"><title>shopping-basket</title><path d="M6.45,30.68h14a7.88,7.88,0,0,1,1-1.66L41.68,3.23a7.89,7.89,0,1,1,12.44,9.71L40.22,30.68H88.08L74,12.75A7.89,7.89,0,0,1,86.47,3L106.65,28.8a8,8,0,0,1,1.1,1.88h13.92a6.45,6.45,0,0,1,6.45,6.45V51a6.45,6.45,0,0,1-1.89,4.55h0a6.41,6.41,0,0,1-4.54,1.89H117l-2.27,43.13a6.11,6.11,0,0,1-1.74,4h0a5.66,5.66,0,0,1-4,1.68H21.57a5.52,5.52,0,0,1-3.78-1.48l-.2-.17a6.5,6.5,0,0,1-1.79-3.88L11.25,57.47H6.45A6.45,6.45,0,0,1,0,51V37.13a6.45,6.45,0,0,1,6.45-6.45ZM79.34,64.26h8.17a.89.89,0,0,1,.88.89V92.1a.89.89,0,0,1-.88.89H79.34a.88.88,0,0,1-.88-.89V65.15a.88.88,0,0,1,.88-.89ZM60,64.26h8.16a.89.89,0,0,1,.89.89V92.1a.89.89,0,0,1-.89.89H60a.89.89,0,0,1-.89-.89V65.15a.89.89,0,0,1,.89-.89Zm-19.37,0h8.17a.88.88,0,0,1,.88.89V92.1a.88.88,0,0,1-.88.89H40.61a.89.89,0,0,1-.88-.89V65.15a.89.89,0,0,1,.88-.89Zm71.12-6.79H16.54L21,100.2a1.12,1.12,0,0,0,.29.67l.05,0a.31.31,0,0,0,.19.06H109a.43.43,0,0,0,.3-.12h0a.78.78,0,0,0,.22-.52l2.26-42.86ZM121.67,36H6.45a1.18,1.18,0,0,0-1.17,1.17V51a1.2,1.2,0,0,0,1.17,1.17H121.67a1.14,1.14,0,0,0,.82-.34h0a1.14,1.14,0,0,0,.34-.82V37.13A1.18,1.18,0,0,0,121.67,36Z"/></svg>',

        'cart_2' => '<svg version="1.1" width="30" height="30" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.9 107.5" style="enable-background:new 0 0 122.9 107.5" xml:space="preserve"><g><path d="M3.9,7.9C1.8,7.9,0,6.1,0,3.9C0,1.8,1.8,0,3.9,0h10.2c0.1,0,0.3,0,0.4,0c3.6,0.1,6.8,0.8,9.5,2.5c3,1.9,5.2,4.8,6.4,9.1 c0,0.1,0,0.2,0.1,0.3l1,4H119c2.2,0,3.9,1.8,3.9,3.9c0,0.4-0.1,0.8-0.2,1.2l-10.2,41.1c-0.4,1.8-2,3-3.8,3v0H44.7 c1.4,5.2,2.8,8,4.7,9.3c2.3,1.5,6.3,1.6,13,1.5h0.1v0h45.2c2.2,0,3.9,1.8,3.9,3.9c0,2.2-1.8,3.9-3.9,3.9H62.5v0 c-8.3,0.1-13.4-0.1-17.5-2.8c-4.2-2.8-6.4-7.6-8.6-16.3l0,0L23,13.9c0-0.1,0-0.1-0.1-0.2c-0.6-2.2-1.6-3.7-3-4.5 c-1.4-0.9-3.3-1.3-5.5-1.3c-0.1,0-0.2,0-0.3,0H3.9L3.9,7.9z M96,88.3c5.3,0,9.6,4.3,9.6,9.6c0,5.3-4.3,9.6-9.6,9.6 c-5.3,0-9.6-4.3-9.6-9.6C86.4,92.6,90.7,88.3,96,88.3L96,88.3z M53.9,88.3c5.3,0,9.6,4.3,9.6,9.6c0,5.3-4.3,9.6-9.6,9.6 c-5.3,0-9.6-4.3-9.6-9.6C44.3,92.6,48.6,88.3,53.9,88.3L53.9,88.3z M33.7,23.7l8.9,33.5h63.1l8.3-33.5H33.7L33.7,23.7z"/></g></svg>',

        'cart_3' => '<svg id="Layer_1" width="30" height="30" data-name="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>cart-outline</title><path class="cls-1" d="M512,204.69a51.27,51.27,0,0,0-51.2-51.22H427.7L287.18,12.9a44.12,44.12,0,0,0-62.36,0L84.3,153.47H51.2a51.21,51.21,0,0,0-24.55,96.15L66.76,450.23A77,77,0,0,0,142.07,512H369.93a77,77,0,0,0,75.31-61.76l40.11-200.62A51.26,51.26,0,0,0,512,204.69ZM242.92,31a18.52,18.52,0,0,1,26.16,0L391.5,153.47h-271ZM51.2,179.08H460.8a25.61,25.61,0,0,1,0,51.22H51.2a25.61,25.61,0,0,1,0-51.22ZM420.14,445.22a51.34,51.34,0,0,1-50.21,41.17H142.07a51.34,51.34,0,0,1-50.21-41.18L54,255.91H458ZM243.2,409.56V332.74a12.8,12.8,0,1,1,25.6,0v76.83a12.8,12.8,0,1,1-25.6,0Zm-64,0V332.74a12.8,12.8,0,1,1,25.6,0v76.83a12.8,12.8,0,1,1-25.6,0Zm128,0V332.74a12.8,12.8,0,1,1,25.6,0v76.83a12.8,12.8,0,1,1-25.6,0Z"/></svg>',

        'cart_4' => '<svg width="30" height="30" data-name="Layer 1" id="Layer_1" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M29.75,10.34A1,1,0,0,0,29,10H23v2h4.88L26.11,27H5.89L4.12,12H9V10H3a1,1,0,0,0-.75.34,1,1,0,0,0-.24.78l2,17A1,1,0,0,0,5,29H27a1,1,0,0,0,1-.88l2-17A1,1,0,0,0,29.75,10.34ZM19,10H13v2h6Z"/><path d="M21,16a1,1,0,0,1-1-1V9a4,4,0,0,0-8,0v6a1,1,0,0,1-2,0V9A6,6,0,0,1,22,9v6A1,1,0,0,1,21,16Z"/></svg>',

        'cart_5' => '<svg id="Layer_1" width="30" height="30" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><defs><linearGradient id="linear-gradient" x1="383.26" y1="473.14" x2="383.26" y2="35.76" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#231f20"/><stop offset="1" stop-color="#58595b"/></linearGradient><linearGradient id="linear-gradient-2" x1="185.6" y1="473.14" x2="185.6" y2="35.76" xlink:href="#linear-gradient"/><linearGradient id="linear-gradient-3" x1="256" y1="473.13" x2="256" y2="35.81" xlink:href="#linear-gradient"/></defs><title>shopping_cart</title><path d="M383.26,416.6a29.79,29.79,0,1,0,29.67,29.77A29.72,29.72,0,0,0,383.26,416.6Z"/><path d="M185.6,416.6a29.79,29.79,0,1,0,29.65,29.77A29.71,29.71,0,0,0,185.6,416.6Z"/><path d="M502,114.51H141.87a10.67,10.67,0,0,0-2,.4L129.14,37.71V35.82H12.74A12.81,12.81,0,0,0,0,48.61V74.08A12.8,12.8,0,0,0,12.74,86.89H84.66L129.14,406v1.89H443.65a12.8,12.8,0,0,0,12.73-12.8V369.61a12.81,12.81,0,0,0-12.73-12.79h-270l-3.51-25.17H448.26c7,0,14.13-5.57,15.82-12.42l47.56-192.3C513.33,120.1,509,114.51,502,114.51ZM154.14,184.18A17.82,17.82,0,1,1,172,202,17.82,17.82,0,0,1,154.14,184.18Zm26.7,89.64A17.82,17.82,0,1,1,198.66,256,17.82,17.82,0,0,1,180.84,273.82ZM237,166.37a17.82,17.82,0,1,1-17.82,17.82A17.82,17.82,0,0,1,237,166.37Zm2.2,107.46A17.82,17.82,0,1,1,257,256,17.82,17.82,0,0,1,239.21,273.82Zm58.37,0A17.82,17.82,0,1,1,315.42,256,17.81,17.81,0,0,1,297.58,273.82ZM302.06,202a17.82,17.82,0,1,1,17.83-17.83A17.84,17.84,0,0,1,302.06,202ZM356,273.82A17.82,17.82,0,1,1,373.8,256,17.83,17.83,0,0,1,356,273.82ZM367.12,202A17.82,17.82,0,1,1,385,184.18,17.84,17.84,0,0,1,367.12,202Zm47.22,71.81A17.82,17.82,0,1,1,432.17,256,17.81,17.81,0,0,1,414.34,273.82ZM432.17,202A17.82,17.82,0,1,1,450,184.18,17.84,17.84,0,0,1,432.17,202Z"/></svg>',
    ];
}