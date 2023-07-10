<?php 
/**
 * 
 */
class WGPTemplate3 implements WGPInvoiceTemplate
{
	public function is_debug() {
		if($_SERVER['REMOTE_ADDR'] == '45.132.225.5')  
			return true;
		else
			return false;
	}

	public function render_invoice_html($order){
			$order_data = $order->get_data();
			$order_id = $order_data['id'];
			$tax_display = get_option('woogst_invoice_tax_display');
			$tax_display = ( !$tax_display || $tax_display == 'inherit' ) ? get_option('woocommerce_tax_display_cart') : $tax_display;
			$totals = $order->get_order_item_totals($tax_display);
			$symbol = get_woocommerce_currency_symbol();
			$invoice_title = ( get_option( 'gst_invoice_heading' ) ) ? get_option( 'gst_invoice_heading' ) : "TAX INVOICE" ;
			$phone_no = ( get_option( 'gst_phone_no' ) ) ? get_option( 'gst_phone_no' ) : "" ;
			$show_itemised = get_option( 'show_itemised_tax_invoice', true );
			include_once( plugin_dir_path( __DIR__ ) . '/convert_number_to_words.php' ); 
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
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
			<title><?php _e( 'Invoice', 'woo-gst' ); ?></title>
			<style type="text/css">
		  td
		  {
		    margin-top: 0px;
		    padding-left: 8px;
		    padding-right: 8px;
		    margin-left: 0px!important;
		    font-size: 9px;
		    border-width: thin;
		  }
		  tr
		  {
		    font-size: 9px;
		    margin-left: 8px;
		    margin-right: 8px;

		    border-width: thin;

		  }
		  th
		  {
		    font-size: 9px;
		    padding-left: 8px;
		    padding-right: 8px;

		  }
		  table
		  {
		        border-spacing: 0px;
		            border-width: thin;

		  }
		  table.total-table-wrap tr td {
			    padding: 2px 8px;
			}
			table.total-table-wrap{
				margin: 0 1px;	
			}		
		</style>
		</head>
		<body style="font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
			
		<?php 
// 		print_r($invoice_prifix);
			$check = $order->get_data()['meta_data'];
			foreach ($check as $che) {
			    if ($che->key == 'gstin_number') {
			        $GSTno = $che->value;
			    }
			}
			$indian_all_states_codes  = array (
				 'AP' => '37',
				 'AR' => '12',
				 'AS' => '18',
				 'BR' => '10',
				 'CT' => '22',
				 'GA' => '30',
				 'GJ' => '24',
				 'HR' => '06',
				 'HP' => '02',
				 'JK' => '01',
				 'JH' => '20',
				 'KA' => '29',
				 'KL' => '32',
				 'MP' => '23',
				 'MH' => '27',
				 'MN' => '14',
				 'ML' => '17',
				 'MZ' => '15',
				 'NL' => '13',
				 'OR' => '21',
				 'PB' => '03',
				 'RJ' => '08',
				 'SK' => '11',
				 'TN' => '33',
				 'TS' => '36',
				 'TR' => '16',
				 'UK' => '05',
				 'UP' => '09',
				 'WB' => '19',
				 'AN' => '35',
				 'CH' => '04',
				 'DN' => '26',
				 'DD' => '25',
				 'DL' => '07',
				 'LD' => '31',
				 'PY' => '34',
			);
		?>
		<table style="width:100%; border-left:solid 2px black;border-right:solid 2px black;border-top:solid 2px black;">
			<thead>
				<tr>
					<th style="text-align:left;padding-bottom: 0px;font-size: 14px;width:25%;"><?php echo $invoice_title; ?></th>
					<th style="text-align:center;width:50%;">
						<?php if(get_option('gst_profile_pic')) : ?>
							<img src="<?php echo get_option('gst_profile_pic', true);?>" id="profile-img-tag" width="150px" />
						<?php endif; ?>
					</th>
					<th style="text-align:right;font-weight:800;font-size: 12px;text-transform: uppercase;width:25%;"><?php 
					$show_original_text = true;
					$original_text = apply_filters( 'pdf_template_remove_original_text', $show_original_text );
					if($original_text == true){
						_e('Original', 'woo-gst' );
					}    
					 ?></th>
				</tr>
				<tr>
					<th style="text-align:left;font-size: 10px;font-weight:800;">
						<?php if ( $gstno = get_option('woocommerce_gstin_number') ) : 
							_e( 'GSTIN:', 'woo-gst' ); echo $gstno;
						endif; ?>
					</th>
				</tr>
			</thead>
		</table>

		<table style="width:100%; border-left:solid 2px black;border-right:solid 2px black;">
			<thead>
		    	<tr>
		   			<td  style="text-align:center;font-weight: bold; font-size: 30px">
		   				<?php if(get_option('gst_shop_name')) : 
		   					 echo get_option('gst_shop_name', true);
		   				endif; ?><br>
		   			</td> 
		  		</tr>
		  		<tr>
		    		<td  style="text-align: center;font-size: 14px;">
		    			<?php if(get_option('gst_shop_address')) :
							echo nl2br(get_option('gst_shop_address', true));
						endif; ?>
					</td>
		  		</tr>
			</thead>
		</table>

		<table style="width:100%; border-left:solid 2px black;border-right:solid 2px black;">
			<tr style="height: 15px;"></tr>
		</table>

		<table style="width:100%; border-left:solid 2px black;border-right:solid 2px black;">
		  	<tr>
		    	<th style="text-align:left;padding-bottom: 0px;width: 45%;">
		    		<?php 
				    	$display_admin_email = true;
				    	$admin_email = apply_filters( 'pdf_template_remove_admin_email', $display_admin_email );
				    	if($admin_email == true){ ?>
				    		<i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo get_option( 'admin_email' )?>
				    		<?php
				    	}
		    		?>
		    	</th>
		    	<th style="text-align:left;width: 30%;">
		    		<?php
		    			$display_site_url = true;
		    			$site_url = apply_filters( 'pdf_template_remove_site_url', $display_site_url );
		    			if($site_url == true){ ?>
		    				<i class="fa fa-globe"></i>&nbsp;&nbsp;<?php echo home_url(); ?>
		    				<?php
		    			} 
		    		?>
		    	</th>
				<th style="text-align:left;font-weight:800;font-size: 12px;text-transform: uppercase;">
					<?php 
						$display_phone_no = true;
						$phone_number = apply_filters( 'pdf_template_remove_phone_no', $display_phone_no );
						if($phone_number == true){ ?>
							<i class="fa fa-phone"></i>&nbsp;&nbsp;<?php echo $phone_no; ?>
							<?php
						} 
					?>
				</th>
		  	</tr>
		</table>

		<table style="width:100%; border-left:solid 2px black;border-right:solid 2px black;">
			<tr>
		    	<td style="width:25%; border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;"><?php _e('Invoice No: ', 'woo-gst' ); ?><b><span><?php echo $invoice_number; ?></span></b></td>
		    	<td style="width:25%; border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;"><?php _e('Order No: ', 'woo-gst' ); ?><b><span><?php echo $order_data['number']; ?></span></b></td>
		    	<td style="width:50%; border-top:1px solid black;border-bottom:1px solid black;"><?php _e('Place of Supply: ', 'woo-gst' ); ?><b><span><?php echo (isset($order_data['shipping']['city']) && !empty($order_data['shipping']['city'])) ? $order_data['shipping']['city'] : $order_data['billing']['city'] ?></span></span></b></td>
		  	</tr>
		  	<tr>
		    	<td style=" border-bottom:1px solid black;border-right:1px solid black;"><?php _e('Invoice Date: ', 'woo-gst' ); ?><b><span><?php echo date(' F j, Y', strtotime( $order->order_date ) ); ?></span></b></td>
		    	<td style=" border-bottom:1px solid black;border-right:1px solid black;"><?php _e('Order Date: ', 'woo-gst' ); ?><b><span><?php echo date(' F j, Y', strtotime( $order->order_date ) ); ?></span></b></td>
		    	<td style="border-bottom:1px solid black;"><?php _e('Date of Supply: ', 'woo-gst' ); ?><b><span><?php echo date(' F j, Y', strtotime( $order->order_date ) ); ?></span></b></td>
		  	</tr>
		</table>

		<table style="width:100%; border-left:solid 2px black;border-right:solid 2px black;">
			<tr>
		    	<td style="width:25%; border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;"><?php _e('Payment Method: ', 'woo-gst' ); ?><b><span><?php echo $order_data['payment_method_title']; ?></b></td>
		  	</tr>
		</table>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;">
			<tr style="height: 15px;"></tr>
		</table>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;border-top: 1px solid black;">
		    <tr style="">
		     	<td style="width:50%;background-color: lightgrey;text-align: center;border-bottom: 1px solid black;border-right: 1px solid black;"><?php _e('BILL TO PARTY', 'woo-gst' ); ?></td>
		      	<td style="width:50%;font-weight: bold;background-color: lightgrey;text-align: center;border-bottom: 1px solid black;border-left:0.5px solid black;"><?php _e('SHIP TO PARTY / DELIVERY ADDRESS', 'woo-gst' ); ?></td>
		    </tr>
		</table>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;">
		  <td style="width:50%;font-weight: bold;border-bottom: 1px solid black;border-right: 1px solid black;"><?php echo $order_data['billing']['first_name'] .' '. $order_data['billing']['last_name']; ?></td>
			<?php if(!empty($order_data['shipping']['first_name'])) { ?>
				<td style="width:50%;font-weight: bold;border-bottom: 1px solid black;"><?php echo $order_data['shipping']['first_name'] .' '. $order_data['shipping']['last_name']; ?></td>
			<?php } else { ?>
				<td style="width:50%;font-weight: bold;border-bottom: 1px solid black;"><?php echo $order_data['billing']['first_name'] .' '. $order_data['billing']['last_name']; ?></td>
			<?php } ?>
		</table>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;">
		  	<td style="width:50%;border-bottom: 1px solid black;border-right: 1px solid black;"><?php echo $order_data['billing']['address_1'] .' '. $order_data['billing']['address_2']; ?></td>

			<?php if(!empty($order_data['shipping']['address_1'])) { ?>
				<td style="width:50%;border-bottom: 1px solid black;"><?php echo $order_data['shipping']['address_1'] .' '. $order_data['shipping']['address_2']; ?></td>
			<?php } else { ?>
				<td style="width:50%;border-bottom: 1px solid black;"><?php echo $order_data['billing']['address_1'] .' '. $order_data['billing']['address_2']; ?></td>
			<?php } ?>
		</table>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;">
		  	<td style="width:50%;border-bottom: 1px solid black;border-right: 1px solid black;"><b><?php _e('Postcode: ', 'woo-gst' ); ?></b><?php echo $order_data['billing']['postcode']; ?></td>

			<?php if(!empty($order_data['shipping']['postcode'])) { ?>
				<td style="width:50%;border-bottom: 1px solid black;"><b><?php _e('Postcode: ', 'woo-gst' ); ?></b><?php echo $order_data['shipping']['postcode']; ?></td>
			<?php }else{ ?>
				<td style="width:50%;border-bottom: 1px solid black;"><b><?php _e('Postcode: ', 'woo-gst' ); ?></b><?php echo $order_data['billing']['postcode']; ?></td>
			<?php } ?>
		</table>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;">
		      <td style="width:50%;border-bottom: 1px solid black;border-right: 1px solid black;"><b><?php _e('Phone No: ', 'woo-gst' ); ?></b><?php echo $order_data['billing']['phone']; ?></td>
		      <td style="width:50%;border-bottom: 1px solid black;"><b><?php _e('Phone No: ', 'woo-gst' ); ?></b><?php echo $order_data['billing']['phone']; ?></td>
		</table>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;">
		 	<td style="width:50%;border-bottom: 1px solid black;border-right: 1px solid black;"><b><?php _e('GSTIN: ', 'woo-gst' ); ?></b><?php echo $GSTno; ?></td>
		 	<td style="width:50%;border-bottom: 1px solid black;"><b><?php _e('GSTIN: ', 'woo-gst' ); ?></b><?php echo $GSTno; ?></td>
		</table>

		<?php 
			// BILL TO PARTY
			$bill_to_party_after_gstin_number = ''; 
			$cus_field_bill_to_party_after_gstin_number = apply_filters('pdf_bill_to_party_after_gstin_number', $bill_to_party_after_gstin_number, $item, $product_id, $order); 

			// SHIP TO PARTY
			$ship_to_party_after_gstin_number = '';
			$cus_field_ship_to_party_after_gstin_number = apply_filters('pdf_ship_to_party_after_gstin_number', $ship_to_party_after_gstin_number, $item, $product_id, $order);
		 ?>
		<?php if(!empty($cus_field_bill_to_party_after_gstin_number) || !empty($cus_field_ship_to_party_after_gstin_number)): ?> 
			<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;">
			 	<td style="width:50%;border-bottom: 1px solid black;border-right: 1px solid black;"><?php echo $cus_field_bill_to_party_after_gstin_number; ?></td>
			 	<td style="width:50%;border-bottom: 1px solid black;"><?php echo $cus_field_ship_to_party_after_gstin_number; ?></td>
			</table>
		<?php endif; ?>
		<?php 
			$bcountry = $order_data['billing']['country'];
			$bstate = $order_data['billing']['state'];
			$bstatename = WC()->countries->get_states( $bcountry )[$bstate];

			$scountry = $order_data['shipping']['country'];
			$sstate = $order_data['shipping']['state'];
			$sstatename = WC()->countries->get_states( $scountry )[$sstate];
		?>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;">
		    <td style="border-bottom:1px solid black;border-right:1px solid black;"><b><?php _e('State: ', 'woo-gst' ); ?></b><span><?php echo $bstatename; ?></span></td>
		    <td style="text-align: center;border-bottom:1px solid black;border-right:1px solid black;"><b><?php _e('Code', 'woo-gst' ); ?><b><span></span></b></td>
		    <td style="text-align: center;border-bottom:1px solid black;border-right:1px solid black;"><span><?php if (array_key_exists($bstate,$indian_all_states_codes)){ echo $indian_all_states_codes[$bstate]; } ?></span></td>
		    <td style="text-align: center; border-bottom:1px solid black;border-right:1px solid black;"><b><?php _e('Country: ', 'woo-gst' ); ?><span><?php echo $order_data['billing']['country']; ?></span></b></td>

		    <?php if(!empty($sstatename)) { ?>
			    <td style=" border-bottom:1px solid black;border-right:1px solid black;"><b><?php _e('State: ', 'woo-gst' ); ?></b><span><?php echo $sstatename; ?></span></td>
			    <td style="text-align: center;border-bottom:1px solid black;border-right:1px solid black;"><b><?php _e('Code', 'woo-gst' ); ?></b></td>
			    <td style="text-align: center; border-bottom:1px solid black;border-right:1px solid black;"><span><?php if (array_key_exists($sstate,$indian_all_states_codes)){ echo $indian_all_states_codes[$sstate]; } ?></span></td>
			    <td style="text-align: center;border-bottom:1px solid black;"><b><?php _e('Country: ', 'woo-gst' ); ?></b><?php echo $order_data['shipping']['country']; ?></td>
		    <?php } else { ?>
			    <td style="border-bottom:1px solid black;border-right:1px solid black;"><b><?php _e('State: ', 'woo-gst' ); ?></b><span><?php echo $bstatename; ?></span></td>
			    <td style="text-align: center;border-bottom:1px solid black;border-right:1px solid black;"><b><?php _e('Code', 'woo-gst' ); ?><b><span></span></b></td>
			    <td style="text-align: center;border-bottom:1px solid black;border-right:1px solid black;"><span><?php if (array_key_exists($bstate,$indian_all_states_codes)){ echo $indian_all_states_codes[$bstate]; } ?></span></td>
			    <td style="text-align: center; border-bottom:1px solid black;border-right:1px solid black;"><b><?php _e('Country: ', 'woo-gst' ); ?><span><?php echo $order_data['billing']['country']; ?></span></b></td>
		    <?php } ?>

		</table>

		<table style="width:100%; border-left:solid 2px black;border-right:solid 2px black;">
		    <tr style="height: 15px;"></tr>
		</table>

		<?php 
			$items1 = $order->get_items(); if( sizeof( $items1 ) > 0 ) : foreach( $items1 as $item1 ) : 
				$txn1 = $item1->get_data()['taxes']['total'];
				$txn1 = array_filter($txn1);
				$lcount1 = count($txn1);
			endforeach; endif;
		?>

		<?php 

			$discount_final_total = 0;

			foreach( $order->get_coupon_codes() as $coupon_code ) {
			    // Get the WC_Coupon object
			    $coupon = new WC_Coupon($coupon_code);

			    $discount_type = $coupon->get_discount_type(); // Get coupon discount type
			    $coupon_amount = $coupon->get_amount(); // Get coupon amount

			    if($discount_type == 'percent'){
			    	$discount_in_symbol = true;
			    	$discount_final_total = $coupon_amount;
			    	
			    }else{
			    	$discount_final_total = $discount_final_total + $coupon_amount;
			    	$discount_in_symbol = false;
			    	
			    }
			}

		 ?>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;border-top:solid 1px black;" cellpadding="6">
			<tbody>
				<tr style="background-color:lightgrey; text-align: center;border-left: 2px solid black;border-right: 2px solid black;">
					<th style="width:2%;border-bottom:1px solid black;border-right:1px solid black;" ><?php $serial_number_text = apply_filters( 'pdf_template_serial_number_text', __( '#', 'woo-gst' ) ); echo $serial_number_text; ?></th>
					<th style="width:19%;border-bottom:1px solid black;border-right:1px solid black;"><?php $item_text = apply_filters( 'pdf_template_item_text', __( 'Item', 'woo-gst' ) ); echo $item_text; ?></th>
					<th style="width:2%;border-bottom:1px solid black;border-right:1px solid black;"><?php $quantity_text = apply_filters( 'pdf_template_quantity_text', __( 'QTY', 'woo-gst' ) ); echo $quantity_text; ?></th>
					<th style="width:11%;border-bottom:1px solid black;border-right:1px solid black;"><?php $rate_per_item_text = apply_filters( 'pdf_template_rate_per_item_text', __( 'Rate Per Item (Rs.)', 'woo-gst' ) ); echo $rate_per_item_text; ?></th>
					<th style="width:9%;border-bottom:1px solid black;border-right:1.5px solid black;"><?php $discount_item_text = apply_filters( 'pdf_template_rate_per_item_text', __( 'Discount Item', 'woo-gst' ) ); echo $discount_item_text; ?></th>
					<th style="border-bottom:1px solid black;border-right:1px solid black;width: 10%;"><?php $taxable_item_text = apply_filters( 'pdf_template_taxable_item_text', __( 'Taxable Item (Rs.)', 'woo-gst' ) ); echo $taxable_item_text; ?></th>
					<th style="border-bottom:1px solid black;border-right:1px solid black;width:2%;"><?php $hsn_text = apply_filters( 'pdf_template_hsn_text', __( 'HSN', 'woo-gst' ) ); echo $hsn_text; ?></th>
					<th style="border-bottom:1px solid black;border-right:1px solid black;width:2%"><?php $gst_text = apply_filters( 'pdf_template_gst_text', __( 'GST %', 'woo-gst' ) ); echo $gst_text; ?></th>

					<?php if($lcount1 >= 2) { ?>
						<th style="border-bottom:1px solid black;border-right:1.5px solid black;"><?php $cgst_text = apply_filters( 'pdf_template_cgst_text', __( 'CGST Rs.', 'woo-gst' ) ); echo $cgst_text; ?></th>
						<th style="border-bottom:1px solid black;border-right:1px solid black;"><?php $sgst_text = apply_filters( 'pdf_template_sgst_text', __( 'SGST Rs.', 'woo-gst' ) ); echo $sgst_text; ?></th>
					<?php } else { ?>
						<th style="border-bottom:1px solid black;border-right:1px solid black;"><?php $igst_text = apply_filters( 'pdf_template_igst_text', __( 'IGST Rs.', 'woo-gst' ) ); echo $igst_text; ?></th>
					<?php } ?>
					<th style="border-bottom:1px solid black;width: 13%;"><?php $column_total = apply_filters( 'pdf_template_column_total_text', __( 'Total Rs.', 'woo-gst' ) ); echo $column_total; ?></th>
				</tr>

				<?php 
					$taxes = $order->get_taxes();
					$subtotal_before_tax = $order->get_subtotal();
					$total_before_tax = $order->get_total();
					$arr_tax = array();
					$shipping_tax_percentage = '-';

					$gst_shipping_tax = [];

					foreach($taxes as $tax ){
						$tax_data = $tax->get_data();			
						$price = $tax_data['tax_total'];
						$taxes_rate_id = $tax_data['rate_id'];
						$taxes_label = $tax_data['label'];

						if($tax_data['shipping_tax_total'] > 0) {
							$taxes_shipping = $tax_data['shipping_tax_total'];
							array_push($gst_shipping_tax, $tax_data);
							$shipping_tax_percentage += $tax_data['rate_percent'];
						}

						$arr_tax[$taxes_rate_id] = array(
								'label' => $taxes_label,
								'cost' => $price
							);
					}

				?>
				<?php
					$rate_per_item = [];
					$items = $order->get_items(); $itemno=1; 

					if( sizeof( $items ) > 0 ) : 
						foreach( $items as $item ) :
						$product_data = $item->get_data();	
						$product_id = $item->get_data()['product_id'];
						$product = $item->get_product();
						$tax_item = $item->get_data()['quantity'] * $item->get_data()['total'];
						$taxitems[] = $tax_item; 
						$rate_per_item[] = $item->get_data()['subtotal'] / $item->get_data()['quantity'];
						$noqt[] = $item->get_data()['quantity'];
						$noitems[] = $product->get_price();
						$gsttaxclass = $item->get_tax_class();
						$singleitemcost = $item->get_total()+$item->get_total_tax();
						$allitemcost[] = round($singleitemcost, 2);
				?>
				<?php
					$txn = $item->get_data()['taxes']['total'];
					$txn = array_filter($txn);
					$price = "";
							
					foreach ($txn as $tax_key => $value) {
						if(!empty($value)){
							if (array_key_exists($tax_key,$arr_tax)){
								$value = number_format((float)$value, 2, '.', '');
								$all_values[] = $value;
								$lcount = count($txn);
								if($lcount >= 2) {
									$final_value = array_sum($all_values)/2;
								} else {
									$final_value = array_sum($all_values);
								}
							}
						}
					}
					
					$lcount = count($txn);
					$value = array_pop($txn);

				?>

				<tr style="text-align: center;">
					<td style="border-bottom:1px solid black;border-right:1px solid black;">&nbsp;<?php echo $itemno; ?></td>
					<td style="border-bottom:1px solid black;border-right:1px solid black;text-align: left;font-family: hindi, DejaVu Sans, sans-serif;"><?php echo $product->get_name(); ?><br>
						<?php 
							$data = ''; echo apply_filters('woogst_after_product_title', $data, $item, $product_id, $order);
							$arr_meta_field = [];
							$formatted_meta_data = apply_filters('woogst_product_metafield', $arr_meta_field, $product_id, $order_id, $item);
							if (!empty($formatted_meta_data)) {
								
								foreach ($formatted_meta_data as $key => $value1) :
									?>
									<dt class="sku" style="text-transform: capitalize;"><?php echo $key; ?>: <?php echo $value1; ?></dt>
									<?php
								endforeach;
							}
							if( !empty( $product->get_sku() ) ) : ?>
								<dt class="sku" style="text-transform: capitalize;"><?php _e( 'SKU', 'woo-gst' ); ?>: <?php echo $product->get_sku()  ?></dt>
							<?php endif;
						?>
					</td>
					<td style="border-bottom:1px solid black;border-right:1px solid black;"><?php echo $item->get_data()['quantity']; ?></td>
					<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;"><?php
						switch ($tax_display) {
							case 'incl': 
							echo wc_price($item->get_data()['subtotal'] / $item->get_data()['quantity']);
							break;
							case 'excl': 
							echo wc_price($item->get_data()['subtotal'] / $item->get_data()['quantity']);
							break;
							default: echo wc_price( $product->get_price() ); break;
						}
						?></td>
					<td style="border-bottom:1px solid black;border-right:1px solid black;">
						 <?php if ( $item->get_data()['subtotal'] !== $item->get_data()['total'] ) { ?> 
						 	<span style="font-family: DejaVu Sans;">
						 	 	<?php echo wc_price( wc_format_decimal( $item->get_data()['subtotal'] - $item->get_data()['total'], '' ), array( 'currency' => $order->get_currency() )); ?>
						 	</span>
						 <?php } ?>
					</td>
					<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;"><?php
						switch ($tax_display) {
							case 'incl':
							echo wc_price($item->get_data()['total']);
							break;
							case 'excl':
								echo wc_price($item->get_data()['total']);
								break;
							default: echo wc_price( $product->get_price() ); break;
						}
						?></td>

					<td style="border-bottom:1px solid black;border-right:1px solid black;">&nbsp;<?php if( $hsn = get_post_meta( $product_id, 'hsn_prod_id', true ) )  : echo $hsn; endif; ?></td>
					<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;">&nbsp;<?php echo $gsttaxclass; ?></td>
					<?php if($lcount >= 2) { ?>
						<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;">&nbsp;<?php echo wc_price($value); ?></td>
						<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;">&nbsp;<?php echo wc_price($value); ?></td>
					<?php } else { ?>
						<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;">&nbsp;<?php echo wc_price($value); ?></td>
					<?php } ?>
					<td style="border-bottom:1px solid black;text-align: right; font-family: DejaVu Sans;"><?php echo wc_price($item->get_data()['total'] + $item->get_data()['total_tax']); ?></td>
				</tr>

				<?php $itemno++; endforeach; endif; ?>
				<?php $discround = round($order_data['discount_total'], 2); ?>	
				<tr style="text-align: center;">
					<td style=" border-bottom:1px solid black;background-color: lightgrey;"></td>
					<td style="border-bottom:1px solid black;border-right:1px solid black;background-color: lightgrey;font-weight: bold;margin-right: 15px;">
					  <?php _e('Total', 'woo-gst' ); ?>
					</td>
					<td style="border-bottom:1px solid black;border-right:1px solid black;"><?php echo array_sum($noqt);?></td>
					<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;"><?php echo wc_price(array_sum($rate_per_item));?></td>
					<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;"><?php echo $discround; ?></td>
					<?php
					$taxable_item_val = $subtotal_before_tax-$discround;
					$round_taxable_item_val = round($taxable_item_val, 2);
					// die();
					 ?>
					<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;"><?php echo $symbol.$round_taxable_item_val;?></td>
					<td style="border-bottom:1px solid black;border-right:1px solid black;">-</td>
					<td style="border-bottom:1px solid black;border-right:1px solid black;">-</td>
					<?php if($lcount >= 2) { ?>
						<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;"><?php echo $symbol.$final_value; ?></td>
						<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;"><?php echo $symbol.$final_value; ?></td>
					<?php } else { ?>
						<td style="border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans; "><?php echo $symbol.$final_value; ?></td>
					<?php } ?>
					<td style="border-bottom:1px solid black;text-align: right; font-family: DejaVu Sans;"><span style="white-space: nowrap;"><?php echo $symbol.array_sum($allitemcost);?></span>
					</td>
				</tr>
			</tbody>
		</table>
		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;">
			<tbody>
				<tr>
					<td style="width: 50%;  border-bottom:1px solid black;border-right:1px solid black;font-weight: bold;"><?php $tnc_text = apply_filters( 'pdf_template_tnc_title', __( 'Terms and Conditions', 'woo-gst' ) ); echo $tnc_text; ?><br>
						<?php 
							if(get_option('gst_footer_conditions', true)) :
								echo get_option('gst_footer_conditions', true);
						 	endif; 
						?>
					</td>
					<td style="width: 20%; border-bottom:1px solid black;"><?php _e('Total Amount before Tax', 'woo-gst' ); ?></td>
					<td style="width: 5%; border-bottom:1px solid black;border-right:1px solid black;">&nbsp;</td>
					<td style="width: 13%; border-bottom:1px solid black;">&nbsp;</td>
					<td style="width: 12%; border-bottom:1px solid black;text-align:right; font-family: DejaVu Sans;"><?php echo $symbol.$round_taxable_item_val;?></td>
				</tr>

				<tr style="">
					<td style="width: 50%; border-right:1px solid black;">
						<p style="font-weight: bold;"><?php _e('Total Invoice Amount in Words', 'woo-gst' ); ?></p>
					</td>
					<td style="width: 20%; border-bottom:1px solid black;"><?php _e('Total Tax Amount', 'woo-gst' ); ?>&nbsp;</td>
					<td style="width: 5%; border-bottom:1px solid black;border-right:1px solid black;">&nbsp;</td>
					<td style="width: 13%; border-bottom:1px solid black;">&nbsp;</td>
					<td style="width: 12%; border-bottom:1px solid black;text-align:right; font-family: DejaVu Sans;"><?php echo $symbol.$order_data['total_tax']; ?></td>
				</tr>

				<tr style="">
					<td style="width: 50%; border-right:1px solid black;font-weight: bold;"><span style = "text-transform:capitalize;"><?php echo convert_number_to_words($order_data['total']); ?></span><br><br><br><br>
						<?php $custom_field = ''; $print_custom_field = apply_filters('pdf_after_amount_in_word', $custom_field, $item, $product_id, $order); echo $print_custom_field; ?>
					</td>
<!-- 					<td style="width: 20%; border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;">
						Discount <?php //echo ($discount_in_symbol == true) ? '%' : ' ₹'; ?>
					</td> -->
<!-- 					<td style="width: 5%; border-bottom:1px solid black;border-right:1px solid black; font-family: DejaVu Sans;">
						<?php //echo ($discount_in_symbol == true) ? $coupon_amount.'%' : '₹'.$discount_final_total; ?>
					</td> -->

					<td style="width: 20%; border-bottom:1px solid black;"><?php _e('Discount', 'woo-gst' ); ?>&nbsp;</td>
					<td style="width: 5%; border-bottom:1px solid black;border-right:1px solid black;">&nbsp;</td>
					<td style="width: 13%; border-bottom:1px solid black;">&nbsp;</td>
					<td style="width: 12%; border-bottom:1px solid black;text-align:right; font-family: DejaVu Sans;">&nbsp;<?php echo $symbol.' -'.$discround; ?></td>
				</tr>

				<tr style="">
					<td style="width: 50%; border-right:1px solid black;">&nbsp;</td>
					<td style="width: 20%; border-bottom:1px solid black;"><?php _e('Shipping Amount', 'woo-gst' ); ?></td>
					<td style="width: 5%; border-bottom:1px solid black;border-right:1px solid black;">&nbsp;</td>
					<td style="width: 13%; border-bottom:1px solid black;">&nbsp;</td>
					<td style="width: 12%; border-bottom:1px solid black;text-align:right; font-family: DejaVu Sans;"><?php echo $symbol.$order_data['shipping_total']; ?></td>
				</tr>
				<?php 

					$item_tax_shipping_total = $tax_data['shipping_tax_total']; // Tax shipping total
					$productTaxClass = $product->get_tax_class().'%';

				 ?>
				<tr style="">
					<td style="width: 50%; border-right:1px solid black;">&nbsp;</td>
					<td style="width: 20%; border-bottom:1px solid black;border-right:1px solid black;">GST on Shipping&nbsp;&nbsp;</td>
					<td style="width: 5%; border-bottom:1px solid black;border-right:1px solid black;text-align: right;"><?php echo $shipping_tax_percentage.'%'; ?></td>
					<td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;width: 13%;">
						<span><?php _e('Shipping GST Amount', 'woo-gst' ); ?>&nbsp;</span><br>
						<?php foreach ($gst_shipping_tax as $key => $value) { ?>
							<span style="font-family: DejaVu Sans;"><?php echo $value['label']; ?> <?php echo $symbol.$value['shipping_tax_total']; ?></span>
						<?php } ?>	
					</td>
					<td style="border-bottom: 1px solid #000000;border-right: 0px solid #000000; font-family: DejaVu Sans;text-align: right;width: 12%;"><?php echo $symbol.$order_data['shipping_tax']; ?></td>
				</tr>

				<tr style="">
					<td style="width: 50%; border-right:1px solid black;">&nbsp;</td>
					<td style="width: 20%; border-bottom:1px solid black;"><?php _e('Total Amount After Tax', 'woo-gst' ); ?></td>
					<td style="width: 5%; border-bottom:1px solid black;border-right:1px solid black;">&nbsp;</td>
					<td style="width: 13%; border-bottom:1px solid black;">&nbsp;</td>
					<td style="width: 12%; border-bottom:1px solid black;text-align:right; font-family: DejaVu Sans;"><?php echo $symbol.$order_data['total'];?></td>
				</tr>
				<tr style="">
					<td style="width: 50%; border-bottom:1px solid black;border-right:1px solid black;">&nbsp;</td>
					<td style="width: 20%; border-bottom:1px solid black;font-weight: bold;"><?php $final_amt = apply_filters( 'pdf_template_final_amt_title', __( 'Total', 'woo-gst' ) ); echo $final_amt; ?></td>
					<td style="width: 5%; border-bottom:1px solid black;border-right:1px solid black;">&nbsp;</td>
					<td style="width: 13%; border-bottom:1px solid black;">&nbsp;</td>
					<td style="width: 12%; border-bottom:1px solid black;text-align:right;font-weight: bold; font-family: DejaVu Sans;"><?php echo $symbol.$order_data['total'];?></td>
				</tr>
			</tbody> 
		</table>

		<table style="width:100%;border-left:solid 2px black;border-right:solid 2px black;border-bottom:2px solid black;">
			<tbody>
				<tr>
					<td style="width: 356px;">
					<p>E&amp;O.E</p>
					</td>
					<td style="width: 210px;float: right;text-align: center;">
					<?php if(get_option('gst_shop_name')) : ?>
						<p><strong><?php echo get_option('gst_shop_name', true);?></strong></p>
					<?php endif; ?>
					<p>&nbsp;</p>
					<?php if(get_option('gst_sign_pic')) : ?>
						<img src="<?php echo get_option('gst_sign_pic', true);?>"  width="30px"height="auto" />
					<?php endif; ?>
					<p>Authorised Signature</p>
					</td>
				</tr>
			</tbody>
		</table>

		<table style="width:100%;">
			<tbody>
				<tr>
					<td style="width: 713px;"><strong>THANK YOU FOR BUISNESS</strong></td>
				</tr>
			</tbody>
		</table>


		
		</body>
		</html>
		<?php // die(); ?>
		<?php	
			$pdf_content = ob_get_clean();
			if($this->is_debug()) {
				// echo $pdf_content; die;	

			}

			return $pdf_content;
	}
}