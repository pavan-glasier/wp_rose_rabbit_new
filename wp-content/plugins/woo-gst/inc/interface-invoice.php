<?php 
interface WGPInvoiceTemplate{
	public function render_invoice_html($order);
}