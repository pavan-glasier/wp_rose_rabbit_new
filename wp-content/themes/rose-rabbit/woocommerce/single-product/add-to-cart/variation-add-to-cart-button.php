<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product; ?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
	<?php do_action( 'woocommerce_before_add_to_cart_quantity' ); ?>
	<div class="actions">
		<?php woocommerce_quantity_input(
			array(
				'classes'     => apply_filters( 'woocommerce_quantity_input_classes', array( 'qty-input', 'input-text', 'qty', 'text' ), $product ),
				'step'        => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
				'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
				'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
			)
		); ?>
		<?php do_action( 'woocommerce_after_add_to_cart_quantity' ); ?>
		<div class="top-flex">
			<button type="submit" class="single_add_to_cart_button vs-btn alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>">
				<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
			</button>
		</div>
		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</div>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
