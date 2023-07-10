<?php 
/**
 * 
 */
class WGPTemplate2 implements WGPInvoiceTemplate
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
			<style type="text/css">
				.wrapper {
					max-width: 900px;
					margin: 0 auto;
					padding: 30px;
					font-size: 16px;
					width: 100%;
					box-sizing: border-box;
				}
				.table-header td{			
					border-bottom: 2px solid #e1e1e1;
					padding-bottom: 20px;
				}
				table {
					border-collapse: collapse;
					width: 100%;
					border:0;
				}
				h1 {
					margin: 0 0 10px;
					font-size: 32px;
				}
				h6 {
					margin: 0;
					font-size: 16px;
				}
				.number-list {
					display: table;
					margin-bottom: 8px;
				}
				.number-list strong {
					width: 150px;
					display: table-cell;
					text-align: left;
				}
				.number-list span {
					display: table-cell;
					width: 130px;
					text-align: left;
					color: #3a3a3a;
				}
				.box {
					border: 1px solid #f4f4f4;
					padding: 10px;
				}
				.table-layout thead {
					border: 1px solid #f4f4f4;
				}
				.table-layout thead th {
					padding: 12px;
					font-size: 14px;
					text-transform: uppercase;
					vertical-align: top;
					min-width: 80px;
				}
				.table-layout td {
					padding: 7px 0 0;
				}
				.table-layout td .bg-box {
					background-color: #f4f4f4;
					padding: 12px 12px 19px;
				}
				.billing-section td{width: 50%; padding-left: 7px;}
				.billing-section table td {
					padding:0 0 5px;
				}
				.billing-section table td .bg-box{
					padding: 10px 12px;
					background-color: #f4f4f4;
					font-weight: bold;
					min-height: 19px;
				}
				.footer td{
					padding: 15px 0;
				}
				.footer td .bg-box{
					background-color: #f4f4f4;
					padding: 20px 20px 5px;
					font-size: 13px;
					line-height: 22px;
				}
				.address-table{
					padding: 10px;
				}
				.address-table h2 {
					font-size: 22px;
				}
				.pink-color {
					background-color:#efe5ed !important;
				}
			</style>
		</head>
		<body style="font-family: 'Helvetica'; padding: 0; margin: 0;color: #3a3a3a;">
			<div class="wrapper">
				<table class="table" border="0" colspace="0">
					<tr class="table-header">
						<td style="width: 50%;">
							<h1 style="color: #3a3a3a"><?php echo $invoice_title; ?></h1>
							<h6 style="color: #3a3a3a">Order Date : <?php echo date(' F j, Y', strtotime( $order->order_date ) ); ?></h6>
						</td>
						<td align="right" style="width: 50%;">
							<div class="number-list">
								<strong>Invoice Number:</strong>
								<span><?php echo $invoice_number; ?></span>
							</div>
							<div class="number-list">
								<strong>Order Number:</strong>
								<span><?php echo $order_data['number']; ?></span>
							</div>
							<div class="number-list">
								<strong>Payment Method:</strong>
								<span><?php echo $order_data['payment_method_title']; ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding:10px 10px;"></td>					
					</tr>
					<tr class="table-address">
						<td style="width: 40%;padding-top: 15px;padding-right: 4px; vertical-align: top; border: 1px solid #f4f4f4">
							<table class="address-table" style="width: 100%;">
								<tr>
									<td class="address" style="padding:0 15px 15px; ">
										<?php if(get_option('gst_shop_name')) : ?>
										<h2><?php echo get_option('gst_shop_name', true);?></h2>
										<?php endif; ?>	
										<?php if(get_option('gst_shop_address')) : ?>
										<p style="display: block;color: #3a3a3a;line-height: 21px;margin-bottom: 0;"><?php echo nl2br(get_option('gst_shop_address', true)) ;?></p>
										<?php endif; ?>
										<?php if ( $gstno = get_option('woocommerce_gstin_number') ) : ?>
										<p style="display: block;color: #3a3a3a;line-height: 21px;margin-bottom: 0;">GSTIN : <?php echo $gstno; ?></p>
										<?php endif; ?>
									</td>
								</tr>
							</table>
						</td>
						<td style="width: 60%;padding: 15px;padding-left: 4px; border: 1px solid #f4f4f4">
							<table class="address-table" style="width: 100%;">
								<tr>
									<td style="width: 50%; vertical-align: top; padding:0 15px 15px;">
										<h2>Bill To</h2>
										<p style="color: #3a3a3a;line-height: 21px;"><?php echo $order->get_formatted_billing_address(); ?>
											<?php echo $order_data['billing']['email']; ?>
											<?php echo $order_data['billing']['phone']; ?></p>
									</td>
									<td style="width: 50%; vertical-align: top; padding:0 15px 15px;">
										<?php if ( $order->get_formatted_shipping_address() ) : ?>
										<h2>Ship To</h2>
										<p style="color: #3a3a3a;line-height: 21px;"><?php echo $order->get_formatted_shipping_address(); ?></p>
										<?php endif; ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding-bottom: 5px;padding-top: 10px;">
							<table class="table-layout" style="text-align: center;" class="">
								<thead>
									<tr>
										<th>SR. NO.</th>
										<th style="text-align: left;">Description in Detail</th>
										<th>HSN Code</th>
										<th>QTY</th>
										<th>TAX</th>
										<th>Amount</th>
									</tr>
								</thead>
								<tbody style="width:100%; background-color: #f4f4f4;">
									<?php 
										$taxes = $order->get_taxes();
										$arr_tax = array();
										$i = 1;
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
									 // $product = new WC_Product($product_id);
									 $product = $item->get_product();
									 $txn = $item->get_data()['taxes']['total'];
									 $price = "";
									 foreach ($txn as $tax_key => $value) {
									 	if(!empty($value)){
									 		if (array_key_exists($tax_key,$arr_tax)){
									 			$value = number_format((float)$value, 2, '.', '');
									 			$price .= wc_price($value) . "(".$arr_tax[$tax_key]['label'].")" . "<br>";
									 		}
									 	}
									 }
									 ?>
									<tr>
										<td>
											<div class="bg-box">
												<?php echo $i; ?>
											</div>
										</td>
										<td style="text-align: left;">
											<div class="bg-box">
												<span style="font-family: hindi, DejaVu Sans, sans-serif;"><?php echo $product->get_name(); ?></span>
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
														if(!empty($attribute_value)) {
															?>
															<dt class="sku"><?php echo $attribute_name; ?>:</dt>
															<dd class="sku"><?php echo $attribute_value;  ?></dd>	
															<?php
														}
													}
												}
												?>
											</div>
										</td>
										<td>
											<div class="bg-box">
												<?php $hsn = get_post_meta( $product_id, 'hsn_prod_id', true ); ?>
												<?php echo $hsn; ?>
											</div>
										</td>
										<td>
											<div class="bg-box"><?php echo $item->get_data()['quantity']; ?></div>
										</td>
										<td><div class="bg-box" style="font-family: DejaVu Sans;"><?php echo $price; ?></div></td>
										<td><div class="bg-box" style="font-family: DejaVu Sans;"><?php
						switch ($tax_display) {
							case 'incl': echo wc_price( $inc_item_total ); break;
							case 'excl': echo wc_price( $item_total ); break;
							default: echo wc_price( $product->get_price() ); break;
						}
						?></div></td>
									</tr>
									<?php $i++; endforeach; endif;  ?>
								</tbody>
							</table>
						</td>
					</tr>
					<tr class="billing-section">
						<td style="background-color: #f4f4f4;text-align: center;font-weight: bold;">
							<div class="bg-box">
								<!--p>Authorized Signature </p-->
								<?php if(get_option('gst_sign_pic')) : ?>
									<img src="<?php echo get_option('gst_sign_pic', true);?>" id="sign-img-tag" width="100px" />
								<?php endif; ?>
							</div>
						</td>
						<td>
							<table>
								<?php foreach( $totals as $key => $total ) : ?>
								<tr class="<?php echo $key; ?>">
									<td <?php echo ($total['label'] == 'Total:') ? 'style="padding-bottom: 0;background-color:#efe5ed;"' : ''; ?>>
										<div class="bg-box <?php echo ($total['label'] == 'Total:') ? 'pink-color' : ''; ?>">
											<?php echo $total['label']; ?>
										</div>
									</td>
									<td align="right" <?php echo ($total['label'] == 'Total:') ? 'style="padding-bottom: 0;background-color:#efe5ed;"' : ''; ?>>
										<div class="bg-box <?php echo ($total['label'] == 'Total:') ? 'pink-color' : ''; ?>" style="font-weight: normal;font-size: 14px; font-family: DejaVu Sans;">
											<?php echo $total['value']; ?>
										</div>
									</td>
								</tr>
								<?php endforeach; ?>
							</table>
						</td>
					</tr>
					
					<?php if(get_option('gst_footer_conditions', true)) : ?>
					<tr class="footer">
						<td colspan="2" style="padding-top: 20px">
							<div class="bg-box">
								<?php echo get_option('gst_footer_conditions', true);?>
							</div>
						</td>
					</tr>
					<?php endif; ?>
				</table>
			</div>
		</body>
		</html>
		<?php
		return ob_get_clean();
	}
}