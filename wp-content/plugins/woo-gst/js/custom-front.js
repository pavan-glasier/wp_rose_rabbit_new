jQuery(document).ready(function($){
	
	
	if( $('#woo_gst_has_gstin_number').is(':checked') ) {
		$('#woo_gst_gstin_number_field').show();
	}else{
		$('#woo_gst_gstin_number_field').hide();
	}

	$('body').on( 'change', '#woo_gst_has_gstin_number', function(){
		if( $(this).is(':checked') )
			$('#woo_gst_gstin_number_field').show();
		else
			$('#woo_gst_gstin_number_field').hide();

	});
});