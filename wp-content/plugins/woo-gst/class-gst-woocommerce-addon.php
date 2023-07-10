<?php
/**
 * Custom Class
 *
 * @access public
 * @return void
*/

require_once( 'class-gst-addon-authentication.php' );

class WC_GST_Settings {
    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public function init() {

        $auth = new WooGstLicensing();

        if( $auth->GST_is_active() ) :

            require_once( 'class-gst-wc-checkout.php' );
            $this->GST_init();
        endif;
    }

    /**
     * 
     */
    public function GST_init() {

        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'fn_add_settings_tab' ), 50 );
        add_action( 'woocommerce_settings_tabs_settings_gst_tab', array( $this, 'fn_settings_tab' ) );
        add_action( 'woocommerce_update_options_settings_gst_tab', array( $this, 'fn_update_settings' ) );
        add_action( 'woocommerce_update_options_tax', array( $this, 'fn_update_tax_settings' ) );
        // add_action( 'init', array( $this, 'fn_gst_callback' ) );
        add_action( 'woocommerce_update_options_settings_gst_tab', array( $this , 'fn_update_tax_settings') );
        add_action('woocommerce_product_options_general_product_data', array( $this, 'fn_add_product_custom_meta_box' ));
        add_action( 'woocommerce_process_product_meta',  array( $this, 'fn_save_license_field' ));
        add_action( 'admin_print_scripts',  array( $this, 'fn_load_custom_wp_admin_script' ), 999 );
        add_action( 'woocommerce_email_after_order_table', array( $this, 'fn_woocommerce_gstin_invoice_fields' ) );
        add_action( 'woocommerce_email_customer_details', array( $this, 'fn_woocommerce_customer_gstin_fields' ), 99, 1 );
        add_action( 'woocommerce_admin_order_data_after_billing_address', array($this,'fn_woocommerce_customer_gstin_fields'), 1, 1 );
        add_filter( 'woocommerce_order_formatted_line_subtotal', array($this,'fn_woocommerce_email_itemized_tax'), 99, 3 );

        // @since 1.5.3
        add_action( 'admin_notices', array( $this , 'woogst_print_db_update_notice') );
        //Add meta box for editing customer gst number and pdf download button
        add_action( 'save_post', array($this, 'woo_gst_save_customer_gstin_number'), 10, 1 );

        // @since 1.5.3
        add_action( 'admin_post_nopriv_woogst_update_db', array( $this , 'woogst_update_database_callback' ) );
        add_action( 'admin_post_woogst_update_db', array( $this , 'woogst_update_database_callback' ) );

        // @since 1.6
        add_action( 'admin_notices', array( $this , 'woogst_licence_expired') );

    }

        /**
     * woo_gst_save_customer_gstin_number
     * Saves the customer GSTIN number
     * @param $post_id | int order id
     */
    function woo_gst_save_customer_gstin_number( $post_id ) {

        // Check if our nonce is set.
        if ( ! isset( $_POST[ 'woo_gst_customer_gst_nonce' ] ) ) {
            return $post_id;
        }
        $nonce = $_REQUEST[ 'woo_gst_customer_gst_nonce' ];

        //Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        update_post_meta( $post_id, 'gstin_number', $_POST[ 'gstin_number' ] );
        update_post_meta( $post_id, 'gst_order_invoice_no', $_POST[ 'order_invoice_no' ] );
    }

    function fn_woocommerce_gstin_invoice_fields( $order ) {
        ?>
        <p><strong><?php _e('GSTIN:', 'woocommerce'); ?></strong> <?php echo get_option('woocommerce_gstin_number'); ?></p>
        <?php
    }

    /**
     * fn_woocommerce_customer_gstin_fields
     * Adds the customer GSTIN number to the emails after the billing and shipping address
     *
     * @param $order  The order
     */
    public function fn_woocommerce_customer_gstin_fields( $order ) {
        if( get_post_meta( $order->get_id(), 'gstin_number', true ) ) :
        ?>
        <p><strong><?php _e('GSTIN:', 'woocommerce'); ?></strong> <?php echo get_post_meta( $order->get_id(), 'gstin_number', true ); ?></p>
        <?php
        endif;
    }

    public function fn_load_custom_wp_admin_script() {
       ?>
       <script>
        jQuery(document).ready(function($) {
            
            if($('#woocommerce_product_types').val() == 'multiple'){
                hide_singe();
            } else {
                hide_mutiple();
            }
            $('#woocommerce_product_types').change(function(){
                if($(this).val() == 'single'){
                    hide_mutiple();
                } else {
                    hide_singe();
                }
            }); 

            function hide_singe(){
                $('input[name="woocommerce_gst_single_select_slab"]').parents('tr:first').hide();
                $('select[name="woocommerce_gst_multi_select_slab[]"]').parents('tr:first').show();
                add_chosen();

            }

            function hide_mutiple(){
                $('select[name="woocommerce_gst_multi_select_slab[]"]').parents('tr:first').hide();
                $('input[name="woocommerce_gst_single_select_slab"]').parents('tr:first').show();
                
            }

            function add_chosen() {
                $('#woocommerce_gst_multi_select_slab').chosen({
                    placeholder_text : "Select multiple tax slabs"
                });
            }
        });
       </script>
       <?php
    }

    public function fn_add_product_custom_meta_box() {
        woocommerce_wp_text_input( 
            array( 
                'id'            => 'hsn_prod_id', 
                'label'         => __('HSN Code', 'woocommerce' ), 
                'description'   => __( 'HSN Code is mandatory for GST.', 'woocommerce' ),
                'custom_attributes' => array( 'required' => 'required' ),
                'value'         => get_post_meta( get_the_ID(), 'hsn_prod_id', true )
                )
            );
    }

    public function fn_save_license_field( $post_id ) {
        $value = ( $_POST['hsn_prod_id'] )? sanitize_text_field( $_POST['hsn_prod_id'] ) : '' ;
        update_post_meta( $post_id, 'hsn_prod_id', $value );
    }
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function fn_add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_gst_tab'] = __( 'GST Settings', 'woocommerce' );
        return $settings_tabs;
    }
    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::fn_get_settings()
     */
    public static function fn_settings_tab() {
        woocommerce_admin_fields( self::fn_get_settings() );
    }
    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::fn_get_settings()
     */
    public static function fn_update_settings() {
        self::gst_insrt_tax_slab_rows();
        woocommerce_update_options( self::fn_get_settings() );
    }

    /**
     * call to gst_callback function on tax tab save button.
     *
     */
    public static function fn_update_tax_settings() {
        if ( isset( $_POST['custom_gst_nonce'] ) && wp_verify_nonce( $_POST['custom_gst_nonce'], 'wc_gst_nonce' )){
            self::fn_gst_callback();
        }

    }

    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_set_gst_tax_slabs()
     * @uses self::gst_callback()
     */
    public static function fn_gst_callback() {
        global $wpdb;
        $table_prefix = $wpdb->prefix . "wc_tax_rate_classes";
        $a_currunt_tax_slabs = array();
        $a_gst_tax_slabs = array();
        $s_woocommerce_product_types = get_option( 'woocommerce_product_types' );

        if( isset( $s_woocommerce_product_types ) && $s_woocommerce_product_types == 'multiple' ){
            $s_product_types = get_option( 'woocommerce_gst_multi_select_slab' );
            $a_gst_tax_slabs = array_merge( $a_gst_tax_slabs, $s_product_types );
        } elseif( isset( $s_woocommerce_product_types ) && $s_woocommerce_product_types == 'single' ) {
            $s_product_types = get_option( 'woocommerce_gst_single_select_slab' );
            array_push( $a_gst_tax_slabs, $s_product_types );
        }

        $s_woocommerce_tax_classes = get_option('woocommerce_tax_classes');
        if( isset( $s_woocommerce_tax_classes ) ){
            // $a_currunt_tax_slabs = explode( PHP_EOL, $s_woocommerce_tax_classes );
            $a_currunt_tax_slabs = array();
            $i_old_count = count( $a_currunt_tax_slabs );
            $old_tax_slabs = $a_currunt_tax_slabs;
            foreach ( $a_gst_tax_slabs as $gst_tax_value ) {
                if ( !in_array( $gst_tax_value, $a_currunt_tax_slabs ) ) 
                    array_push( $a_currunt_tax_slabs, $gst_tax_value );
            }
            $i_new_count = count( $a_currunt_tax_slabs );

            $diff1 = array_diff($old_tax_slabs,$a_currunt_tax_slabs);
            $diff2 = array_diff($a_currunt_tax_slabs,$old_tax_slabs);

            if(!empty($diff1) || !empty($diff2)) {
                $tax_slab_array = $a_currunt_tax_slabs;
                if(woogst_get_woo_version_number() >= '3.7.0') {
                    foreach ($tax_slab_array as $tax_value) {
                        $slug = str_replace('%', '', $tax_value);
                        $tax_rate_class_id = $wpdb->get_var("SELECT tax_rate_class_id FROM $table_prefix WHERE name='$tax_value'");
                        if(($tax_rate_class_id == NULL || empty($tax_rate_class_id)) && !empty($tax_value)) {
                            $wpdb->insert($table_prefix,array( 'name' => $tax_value, 'slug' => $slug),array( '%s','%s' ));
                        }
                    }
                }
            } else {
                return;
            }
        }
        $a_currunt_tax_slabs = ( !$a_currunt_tax_slabs ) ? $a_gst_tax_slabs : $a_currunt_tax_slabs ;
        $a_currunt_tax_slabs = implode( PHP_EOL, $a_currunt_tax_slabs );
        
        update_option( 'woocommerce_tax_classes', $a_currunt_tax_slabs );
    }

    /**
     * Uses this function to insert tax slab rows.
     *
     */
    public static function gst_insrt_tax_slab_rows() {
        global $wpdb;

        $a_multiple_slabs = array();
        if( isset( $_POST['woocommerce_product_types'] ) && $_POST['woocommerce_product_types'] == 'multiple' ){
            $multi_select_slab = (isset($_POST['woocommerce_gst_multi_select_slab'])) ? $_POST['woocommerce_gst_multi_select_slab'] : array();

            if( ! empty( $multi_select_slab ) )
                $a_multiple_slabs = array_merge( $a_multiple_slabs, $multi_select_slab );
        } elseif ( isset( $_POST['woocommerce_product_types'] ) ){
            $single_select_slab = sanitize_text_field( $_POST['woocommerce_gst_single_select_slab'] );
            array_push( $a_multiple_slabs, $single_select_slab . '%' );       
        }

        $table_prefix = $wpdb->prefix . "woocommerce_tax_rates";

        $s_woocommerce_tax_classes = get_option('woocommerce_tax_classes');
        $a_currunt_tax_slabs = array();


        if( !empty( $s_woocommerce_tax_classes ) )
            $a_currunt_tax_slabs = explode( PHP_EOL, $s_woocommerce_tax_classes );

        foreach ( $a_multiple_slabs as $a_multiple_slab ) {
          // if( $a_multiple_slab != '0%' && ! in_array( $a_multiple_slab, $a_currunt_tax_slabs ) ){
                $slab_name = preg_replace('/%/', '', $a_multiple_slab);
                $state_tax ='';

                $state_tax = $slab_name / 2;

                // $state = get_option( 'woocommerce_store_state' );
                $location = wc_get_base_location();
                $state = esc_attr( $location['state'] );
                $ut_state = array('CH','AN','DN','DD', 'LD');
                if( isset( $state ) ) :

                    $tax_slab_row_cgst = $state_tax."% CGST";
                    $tax_slab_row_utgst = $state_tax."% UTGST";
                    $tax_slab_row_sgst = $state_tax."% SGST";
                    $tax_slab_row_igst = $slab_name."% IGST";

                    $table_tax_prefix = $wpdb->prefix . "woocommerce_tax_rates";

                    $select_table_tax_cgst = $wpdb->get_var("SELECT tax_rate_id FROM $table_tax_prefix WHERE tax_rate_name='$tax_slab_row_cgst'");

                    $select_table_tax_utgst = $wpdb->get_var("SELECT tax_rate_id FROM $table_tax_prefix WHERE tax_rate_name='$tax_slab_row_utgst'");

                    $select_table_tax_sgst = $wpdb->get_var("SELECT tax_rate_id FROM $table_tax_prefix WHERE tax_rate_name='$tax_slab_row_sgst'");

                    $select_table_tax_igst = $wpdb->get_var("SELECT tax_rate_id FROM $table_tax_prefix WHERE tax_rate_name='$tax_slab_row_igst'");

                    if( ($select_table_tax_cgst == NULL || empty($select_table_tax_cgst)) ){    
                         $wpdb->insert($table_prefix,array( 'tax_rate_country' => 'IN', 'tax_rate_state' => $state,'tax_rate' => $state_tax,'tax_rate_name' => $state_tax."% CGST",'tax_rate_priority' => 1,'tax_rate_compound' => 0,'tax_rate_shipping' => 0,'tax_rate_order' => 0,'tax_rate_class' =>$slab_name),array( '%s','%s','%s','%s','%d','%d','%d','%d','%s'));
                    }     
                    if(in_array($state, $ut_state)){
                       if( ($select_table_tax_utgst == NULL || empty($select_table_tax_utgst)) ){ 
                         $wpdb->insert($table_prefix,array( 'tax_rate_country' => 'IN', 'tax_rate_state' => $state,'tax_rate' => $state_tax,'tax_rate_name' => $state_tax."% UTGST",'tax_rate_priority' => 2,'tax_rate_compound' => 0,'tax_rate_shipping' => 0,'tax_rate_order' => 0,'tax_rate_class' =>$slab_name),array( '%s','%s','%s','%s','%d','%d','%d','%d','%s'));
                        } 
                    } else {
                        if( ($select_table_tax_sgst == NULL || empty($select_table_tax_sgst)) ){
                            $wpdb->insert($table_prefix,array( 'tax_rate_country' => 'IN', 'tax_rate_state' => $state,'tax_rate' => $state_tax,'tax_rate_name' => $state_tax."% SGST",'tax_rate_priority' => 2,'tax_rate_compound' => 0,'tax_rate_shipping' => 0,'tax_rate_order' => 0,'tax_rate_class' =>$slab_name),array( '%s','%s','%s','%s','%d','%d','%d','%d','%s'));
                        }    
                    }
                    if( ($select_table_tax_igst == NULL || empty($select_table_tax_igst)) ){
                        $wpdb->insert($table_prefix,array( 'tax_rate_country' => 'IN', 'tax_rate_state' => '','tax_rate' => $slab_name,'tax_rate_name' => $slab_name."% IGST",'tax_rate_priority' => 1,'tax_rate_compound' => 0,'tax_rate_shipping' => 0,'tax_rate_order' => 0,'tax_rate_class' =>$slab_name),array( '%s','%s','%s','%s','%d','%d','%d','%d','%s'));
                    }    
                endif;
            // }
        }

    }

    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function fn_get_settings() {

        // $state = get_option( 'woocommerce_store_state' );
        $location = wc_get_base_location();
        $state = esc_attr( $location['state'] );
        
        $arr_tax_slab = array();
        for( $i = 0 ; $i <= 35 ; $i++ )
            $arr_tax_slab[$i . '%'] = $i . '%';

        $settings = array(
            'section_title' => array(
                'name'     => __( 'Select Product Type', 'woocommerce' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_gst_tab_section_title'
            ),
            'GSTIN_number' => array(

                'name'    => __( 'GSTIN Number', 'woocommerce' ),

                'desc'    => __( 'This GSTIN number will display on your PDF invoice.', 'woocommerce' ),

                'id'      => 'woocommerce_gstin_number',

                'css'     => 'min-width:150px;',

                'std'     => 'left', // WooCommerce < 2.0

                'default' => '', // WooCommerce >= 2.0

                'custom_attributes' => array( 'required' => 'required' ),

                'type'    => 'text',

            ),
            'store_state' => array(

                'name'    => __( 'Store location state', 'woocommerce' ),

                'desc'    => __( 'Please insert state code of store location.', 'woocommerce' ),

                'id'      => 'woocommerce_store_state',

                'css'     => 'min-width:150px;',

                'std'     => 'left', // WooCommerce < 2.0

                'default' => $state, // WooCommerce >= 2.0

                'custom_attributes' => array( 'required' => 'required' ),
                
                'type'    => 'text',

            ),
            'prod_types' => array(

                'name'    => __( 'Select Product Types', 'woocommerce' ),

                'desc'    => __( 'Select single or multiple tax slab.', 'woocommerce' ),

                'id'      => 'woocommerce_product_types',

                'css'     => 'min-width:150px;height:auto;',

                    'std'     => 'left', // WooCommerce < 2.0

                    'default' => 'left', // WooCommerce >= 2.0

                    'type'    => 'select',

                    'options' => array(

                        'single'        => __( 'Single', 'woocommerce' ),

                        'multiple'       => __( 'Multiple', 'woocommerce' ),

                    ),

                    'desc_tip' =>  true,

                ),
            'woocommerce_gst_multi_select_slab' => array(

                'name'    => __( 'Select Multiple Tax Slabs', 'woocommerce' ),

                'desc'    => __( 'Multiple tax slabs.', 'woocommerce' ),

                'placeholder' => __( 'Select Tax Slab', 'woocommerce' ),

                'id'      => 'woocommerce_gst_multi_select_slab',

                'css'     => 'min-width:150px;height:auto;',

                'std'     => 'left', // WooCommerce < 2.0

                'default' => 'left', // WooCommerce >= 2.0

                // 'type'    => 'multi_select_countries',
                'type'    => 'multiselect',

                'options' => $arr_tax_slab,

                'desc_tip' =>  true,

            ),

            'woocommerce_gst_single_select_slab' => array(

                'name'    => __( 'Select Tax Slab (in percentage)', 'woocommerce' ),

                'desc'    => __( 'Tax slab in percentage.', 'woocommerce' ),

                'id'      => 'woocommerce_gst_single_select_slab',

                'css'     => 'min-width:150px;height:auto;',

                'std'     => 'left', // WooCommerce < 2.0

                'default' => 'left', // WooCommerce >= 2.0

                'type'    => 'number',

                'custom_attributes' => array( 'min' => 0, 'max' => 35 ),

                'desc_tip' =>  true,

            ),

            'show_itemised_tax_email' => array(

                'name'    => __( 'Show itemised tax slabs in email', 'woocommerce' ),

                'desc'    => __( 'Displays the tax slabs in the email', 'woocommerce' ),

                'id'      => 'show_itemised_tax_email',

                'css'     => 'min-width:150px;height:auto;',

                'std'     => 'left', // WooCommerce < 2.0

                'default' => 'left', // WooCommerce >= 2.0

                'type'    => 'checkbox',
                
                'desc_tip' =>  true,

            ),

            'attach_order_invoice_to_mail' => array(

                'name'    => __( 'Attach PDF invoice to email', 'woocommerce' ),

                'desc'    => __( 'Attach PDF invoice to customer email when order is COMPLETED', 'woocommerce' ),

                'id'      => 'attach_order_invoice_to_mail',

                'css'     => 'min-width:150px;height:auto;',

                'std'     => 'left', // WooCommerce < 2.0

                'default' => 'left', // WooCommerce >= 2.0

                'type'    => 'checkbox',
                
                'desc_tip' =>  true,

            ),

            'show_itemised_tax_invoice' => array(

                'name'    => __( 'Show itemised tax slab in PDF invoice', 'woocommerce' ),

                'desc'    => __( 'Displays the tax slabs in the PDF invoice', 'woocommerce' ),

                'id'      => 'show_itemised_tax_invoice',

                'css'     => 'min-width:150px;height:auto;',

                'std'     => 'left', // WooCommerce < 2.0

                'default' => 'left', // WooCommerce >= 2.0

                'type'    => 'checkbox',

                'desc_tip' =>  true,

            ),

            'woogst_invoice_tax_display' => array(

                'name'    => __( 'Display product price in PDF invoice', 'woocommerce' ),

                'desc'    => __( 'Display product price in PDF invoice including/excluding tax, if inherit is selected it will inherit the WooCommerce checkout settings which are configued for tax.' , 'woocommerce' ),

                'id'      => 'woogst_invoice_tax_display',

                // 'css'     => 'min-width:150px;height:auto;',

                'std'     => 'left', // WooCommerce < 2.0

                'default' => 'left', // WooCommerce >= 2.0

                'type'    => 'select',

                'options' => array(

                    'inherit'       => __( 'Inherit from WooCommerce Tax settings', 'woocommerce' ),
                    
                    'incl'        => __( 'Including Tax', 'woocommerce' ),

                    'excl'       => __( 'Excluding Tax', 'woocommerce' ),

                ),

                'desc_tip' =>  true,

                ),

            'gst_nonce' => array(

                'name'    => __( 'GST nonce', 'woocommerce' ),

                'desc'    => __( 'GST nonce.', 'woocommerce' ),

                'id'      => 'woocommerce_gst_nonce',

                'css'     => 'min-width:150px;',

                'std'     => 'left', // WooCommerce < 2.0

                'default' => wp_nonce_field( 'wc_gst_nonce', 'custom_gst_nonce' ), // WooCommerce >= 2.0
                
                'type'    => 'hidden',

            ),

            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_gst_tab_section_end'
            )
        );
        return apply_filters( 'wc_settings_gst_tab_settings', $settings );
    }


    /**
     * fn_woocommerce_email_itemized_tax
     * 
     */
    function fn_woocommerce_email_itemized_tax($subtotal, $item, $order) {

        $show_itemised = get_option( 'show_itemised_tax_email', true );
        if( $show_itemised != "yes" ) return $subtotal;

        $taxes = $order->get_taxes();
        $arr_tax = array();
        $symbol = get_woocommerce_currency_symbol();
        
        foreach($taxes as $tax ){    
            $tax_data = $tax->get_data();
            $price = $tax_data['tax_total'];
            $taxes_rate_id = $tax_data['rate_id'];
            $taxes_label = $tax_data['label'];
            $arr_tax[$taxes_rate_id] = array(
                    'label' => $taxes_label,
                    'cost' => $price
                );
        }

        $txn = $item->get_data()['taxes']['total'];
        $price = "";
        foreach ($txn as $tax_key => $value) {
            if(!empty($value)){
                if (array_key_exists($tax_key,$arr_tax)){
                    $value = number_format((float)$value, 2, '.', '');
                    $price .= $symbol.$value . "(".$arr_tax[$tax_key]['label'].")" . "<br>";
                }
            }
        }
        return $subtotal . "<br>" . $price;
    }


    /**
     * check_woo_gst_tax_slabs check tax slabs
     * @return [type] [description]
     * @since 1.5.3
     */
    function check_woo_gst_tax_slabs() {
        global $wpdb;
        $table_prefix = $wpdb->prefix . "wc_tax_rate_classes";
        $s_woocommerce_tax_classes = get_option('woocommerce_tax_classes');
        if( isset( $s_woocommerce_tax_classes ) && !empty( $s_woocommerce_tax_classes ) ){
            $a_currunt_tax_slabs = explode( PHP_EOL, $s_woocommerce_tax_classes );
            if(woogst_get_woo_version_number() >= '3.7.0') {       
                $tax_counts = $wpdb->get_var("SELECT count(tax_rate_class_id) FROM $table_prefix");
                if($tax_counts != count($a_currunt_tax_slabs)) {
                    foreach ($a_currunt_tax_slabs as $tax_value) {
                        $slug = str_replace('%', '', $tax_value);
                        $tax_rate_class_id = $wpdb->get_var("SELECT tax_rate_class_id FROM $table_prefix WHERE name='$tax_value'");
                        if(($tax_rate_class_id == NULL || empty($tax_rate_class_id)) && !empty($tax_value)) {
                            $wpdb->insert($table_prefix,array( 'name' => $tax_value, 'slug' => $slug),array( '%s','%s' ));
                        }
                    }
                    update_option( 'woogst_update_database', 'updated' );
                    update_option( 'woogst_update_database_version', GST_VERSION );
                }
            }
        }       
    }

    /**
     * woogst_print_db_update_notice Update databse notice
     * @return [type] [description]
     * @since 1.5.3
     */
    function woogst_print_db_update_notice() {
        global $wpdb;
        $class = 'notice notice-error';
        $pro_link = '#';
        $update_notice = get_option( 'woogst_update_database', true );
        $update_db = get_option('woogst_update_database_version');
        
        //$alloptions  = wp_load_alloptions();
        //var_dump( $alloptions );
        //echo GST_VERSION;
        if($update_db > GST_VERSION ) {     
            printf( '<div class="%1$s"><p>This tool will update your WooGST database to the latest version. Please ensure you make sufficient backups before proceeding. <form action="'.admin_url( 'admin-post.php' ).'" method="post"><input type="hidden" name="action" value="woogst_update_db"><button type="submit" name="woogst_submit" value="woogst_update_database" class="button btn">Update Database</button></form></p></div>', $class, $pro_link );
        }
    }

    /**
     * woogst_update_database_callback Update database form callback
     * @return [type] [description]
     * @since 1.5.3
     */
    function woogst_update_database_callback() {
        /**
         * At this point, $_GET/$_POST variable are available
         *
         * We can do our normal processing here
         */ 
        if(isset($_POST) && isset($_POST['woogst_submit']) && $_POST['woogst_submit'] == 'woogst_update_database') {
            $this->check_woo_gst_tax_slabs();
        }
        wp_redirect( admin_url(), $status = 302 );
    }



    /**
     * woogst_licence_expired description Woogst Exprired notification
     * @return [type] [description]
     * @since 1.5.3
     */
    function woogst_licence_expired() {
        global $wpdb;
        $class = 'notice notice-error';
        $pro_link = '#';

        $response = get_transient( 'GST_transient_response' );
        if(!empty($response)) {
            $data = json_decode( $response['body'],true );
            if($data['status'] == 'expired') {
                printf( '<div class="%1$s"><p>Your WooCommerce GST PRO plugin licence is expired. Please renew your licence to continue the PRO features.</p></div>', $class );
            }
        }
    }
}