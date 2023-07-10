<?php 
/**
 * 
 */
class WGPTemplateDefault implements WGPInvoiceTemplate
{
	
	public function render_invoice_html($order){
			$order_data = $order->get_data();
			$order_id = $order_data['id'];
			$tax_display = get_option('woogst_invoice_tax_display');
			$tax_display = ( !$tax_display || $tax_display == 'inherit' ) ? get_option('woocommerce_tax_display_cart') : $tax_display;
			$totals = $order->get_order_item_totals($tax_display);
			$symbol = get_woocommerce_currency_symbol();
			$invoice_title = ( get_option( 'gst_invoice_heading' ) ) ? get_option( 'gst_invoice_heading' ) : "TAX INVOICE" ;
			$show_itemised = get_option( 'show_itemised_tax_invoice', true );
			$invoice_prifix = get_post_meta( $order_id, 'gst_order_invoice_no', true );
			$invoice_prifix_number = str_replace("_"," ",$invoice_prifix);
			
			if (!empty($invoice_prifix)) {
				$invoice_number = $invoice_prifix_number;
			} else {
				$invoice_number = $order_data['number'];
			}
			ob_start();
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<title><?php _e( 'Invoice', 'woo-gst' ); ?></title>
			<style type="text/css"><?php $this->gst_invoice_template_style(); ?></style>
		</head>
		<body class="">
		<h1 class="text-center"><?php echo $invoice_title; ?></h1>
		<table class="head container">
			<tr>
				<td class="header">
				<?php if(get_option('gst_profile_pic')) : ?>
					<img src="<?php echo get_option('gst_profile_pic', true);?>" id="profile-img-tag" width="100px" />
				<?php endif; ?>
				</td>
				<td class="shop-info">
				<h4 class="shop-name"><?php _e('Sold by', 'gst'); ?></h4>
				<?php if(get_option('gst_shop_name')) : ?>
					<div class="shop-name"><?php echo get_option('gst_shop_name', true);?></div>
				<?php endif; ?>	
				<?php if(get_option('gst_shop_address')) : ?>
					<div class="shop-address"><?php echo nl2br(get_option('gst_shop_address', true)) ;?></div>
				<?php endif; ?>
				<?php if ( $gstno = get_option('woocommerce_gstin_number') ) : ?>
				<div class="shop-gstin">
					<?php _e( 'GSTIN:', 'woo-gst' ); ?> <?php echo $gstno; ?>
				</div>
				<?php endif; ?>
				</td>
			</tr>
		</table>
		<table class="order-data-addresses">
			<tr>
				<td class="address billing-address">
					<h4 class="address-title"><?php _e( 'Billing Details', 'gst' ); ?></h4>
					<?php echo $order->get_formatted_billing_address(); ?>
					<div class="billing-email"><?php echo $order_data['billing']['email']; ?></div>
					<div class="billing-phone"><?php echo $order_data['billing']['phone']; ?></div>
					<?php if( get_post_meta( $order->id, 'gstin_number', true ) ) : ?>
					<div class="billing-phone">
					<?php _e( 'GSTIN:', 'gst' ); ?>
					<?php echo get_post_meta( $order->get_id(), 'gstin_number', true ); ?>
					</div>
					<?php endif; ?>

					<?php 

					// BILL TO PARTY
					$bill_to_party_after_gstin_number = ''; 
					$cus_field_bill_to_party_after_gstin_number = apply_filters('pdf_bill_to_party_after_gstin_number', $bill_to_party_after_gstin_number, $item, $product_id, $order); 

					// SHIP TO PARTY
					$ship_to_party_after_gstin_number = '';
					$cus_field_ship_to_party_after_gstin_number = apply_filters('pdf_ship_to_party_after_gstin_number', $ship_to_party_after_gstin_number, $item, $product_id, $order);

					 ?>
					<?php if(!empty($cus_field_bill_to_party_after_gstin_number)): ?> 
						<div>
							<?php echo $cus_field_bill_to_party_after_gstin_number; ?>
						</div>	
					<?php endif; ?>	
				</td>
				<td class="address shipping-address">
					<?php if ( $order->get_formatted_shipping_address() ) : ?>
					<h4 class="address-title"><?php _e( 'Shipping Details', 'gst' ); ?></h4>
					<?php echo $order->get_formatted_shipping_address(); ?>
					<?php endif; ?>
					<?php if(!empty($cus_field_ship_to_party_after_gstin_number)): ?>
						<div>
							<?php echo $cus_field_ship_to_party_after_gstin_number; ?>
						</div>
					<?php endif; ?>	
				</td>
				<td class="order-data">
					<table>		
						<tr class="order-number">
							<th><?php _e( 'Invoice Number:', 'woo-gst' ); ?></th>
							<td><?php echo $invoice_number; ?></td>
						</tr>
						<tr class="order-number">
							<th><?php _e( 'Order Number:', 'woo-gst' ); ?></th>
							<td><?php echo $order_data['number']; ?></td>
						</tr>
						<tr class="order-date">
							<th><?php _e( 'Order Date:', 'woo-gst' ); ?></th>
							<td><?php echo date(' F j, Y', strtotime( $order->order_date ) ); ?></td>
						</tr>
						<tr class="payment-method">
							<th><?php _e( 'Payment Method:', 'woo-gst' ); ?></th>
							<td><?php echo $order_data['payment_method_title']; ?></td>
						</tr>
					</table>			
				</td>
			</tr>
		</table>
		<table class="order-details">
			<thead>
				<tr>
					<th class="product"><?php $item_text = apply_filters( 'pdf_template_item_text', __( 'Product', 'woo-gst' ) ); echo $item_text; ?></th>
					<th class="quantity"><?php $quantity_text = apply_filters( 'pdf_template_quantity_text', __( 'Quantity', 'woo-gst' ) ); echo $quantity_text; ?></th>
					<th class="price"><?php $column_total = apply_filters( 'pdf_template_column_total_text', __( 'Price', 'woo-gst' ) ); echo $column_total; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$taxes = $order->get_taxes();
					$arr_tax = array();
					
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
				 ?>
				<?php $items = $order->get_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item ) : 

				/*16072021 start*/

				$item_total = $item->get_total();
				$item_total_tax = $item->get_total_tax();
				$inc_item_total = $item_total+$item_total_tax;
				
				/*16072021 end*/
				

				$product_id = $item->get_data()['product_id'];
				$item_id = $item->get_data()['id'];
				// $product = new WC_Product($product_id);
				$product = $item->get_product();

				?>
				<tr class="">
					<td class="product">
						<span class="item-name" style="font-family: hindi, DejaVu Sans, sans-serif;"><?php echo $product->get_name(); ?></span><br>
						<?php
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
						?>
						<dl class="meta">
							<?php 
							if($product->is_type('variation')){
						         // Get the variation attributes
								$variation_attributes = $product->get_variation_attributes();
						        // Loop through each selected attributes
								foreach($variation_attributes as $attribute_taxonomy => $term_slug){
									$taxonomy = str_replace('attribute_', '', $attribute_taxonomy );
						            // The name of the attribute
									$attribute_name = get_taxonomy( $taxonomy )->labels->singular_name;
						            // The term name (or value) for this attribute
									$attribute_value = get_term_by( 'slug', $term_slug, $taxonomy )->name;
									?>
									<dt class="sku"><?php echo $attribute_name; ?></dt>
									<dd class="sku"><?php echo $attribute_value;  ?></dd>	
									<?php
								}
							}
							 ?>
							
							<!-- 27072021 start -->
							<?php 
								$hsn = get_post_meta( $product_id, 'hsn_prod_id', true );
								if(!empty(get_post_meta( $product_id, 'hsn_prod_id', true ))){
									$arr_meta_field = [
										'HSN Code' => $hsn
									];
								}

								$formatted_meta_data = apply_filters('woogst_product_metafield', $arr_meta_field, $product_id, $order_id, $item);
								if (!empty($formatted_meta_data)) {
									
									foreach ($formatted_meta_data as $key => $value) :
										?>
										<dt class="sku" style="text-transform: capitalize;"><?php echo $key; ?>:</dt>
										<dd class="sku"><?php echo $value; ?></dd>
										<?php
									endforeach;
								}
							?>
							<?php if( !empty( $product->get_sku() ) ) : ?>
								<dt class="sku"><?php _e( 'SKU:', 'woo-gst' ); ?></dt>
								<dd class="sku"><?php echo $product->get_sku()  ?></dd>
							<?php endif; ?>
							<!-- 27072021 end -->
						</dl>
					</td>
					<td class="quantity"><?php echo $item->get_data()['quantity']; ?></td>
					<td class="price" style="font-family: DejaVu Sans;">
						<?php
						switch ($tax_display) {
							case 'incl': echo wc_price( $inc_item_total ); break;
							case 'excl': echo wc_price( $item_total ); break;
							default: echo wc_price( $product->get_price() ); break;
						}
						?>
						<?php if( !empty($price) && $show_itemised == "yes" ) echo "<br>" .$price; ?>
					</td>
				</tr>
				
				<?php endforeach; endif;  ?>
				
			</tbody>
			<tfoot>
				<tr class="no-borders">
					<td class="no-borders">
						<div class="customer-notes">
							<?php if(get_option('gst_sign_pic')) : ?>
								<img src="<?php echo get_option('gst_sign_pic', true);?>" id="sign-img-tag" width="100px" />
							<?php endif; ?>
						</div>				
					</td>
					<td class="no-borders" colspan="2">
						<table class="totals">
							<tfoot>
								<?php foreach( $totals as $key => $total ) : ?>
								<tr class="<?php echo $key; ?>">
									<td class="no-borders"></td>
									<th class="description"><?php echo $total['label']; ?></th>
									<td class="price" style="font-family: DejaVu Sans;"><span class="totals-price"><?php echo $total['value']; ?></span></td>
								</tr>
								<?php endforeach; ?>
							</tfoot>
						</table>
					</td>
				</tr>
			</tfoot>
		</table>

		<?php if(get_option('gst_footer_conditions', true)) : ?>
		<div id="footer">
			<?php echo get_option('gst_footer_conditions', true);?>
		</div>
		<?php endif; ?>
		</body>
		</html>
		<?php
			return ob_get_clean();
	}

	function gst_invoice_template_style() {
		ob_start();
		include( 'style.css' );
		echo ob_get_clean();
	}
}