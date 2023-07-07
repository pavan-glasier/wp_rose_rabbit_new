jQuery(document).ready(function($) {
	// for redirect method 1
	document.addEventListener('wpcf7mailsent', function( event ) {
		let id_long = 			event.detail.id;
		let id = 				event.detail.contactFormId;
		let formid = id;
		let forms = cf7_gi_ajax_object.cf7_gi_forms;
		let array_list = forms.split(",");
		array_list.forEach(function(item) {
			
			// check to see if this array item has redirect enabled
			let result_url = 	forms.indexOf(id+'|url');
			let result_page = 	forms.indexOf(id+'|page');
			let result_thank = 	forms.indexOf(id+'|thank');
			let item_list = item.split("|");

			if (item_list[1] == id) {
				let url = item_list[3];
				let tab = item_list[4];

				// url
				if (result_url > -1) {
					// open in same tab
					if (tab == 0) {
						window.location.href = url;
					}
					// open in new tab
					if (tab == 1) {
						let win = window.open(url, '_blank');
						win.focus();
					}
				}
				
				// page
				if (result_page > -1) {
					// open in same tab
					if (tab == 0) {
						window.location.href = url;
					}
					// open in new tab
					if (tab == 1) {
						let win = window.open(url, '_blank');
						win.focus();
					}
				}

				// thank you page
				if (result_thank > -1) {
					let data = {
						'action':	'cf7_gi_get_form_thank',
						'formid':	formid,
					};
					jQuery.ajax({
						type: "POST",
						data: data,
						dataType: "json",
						async: false,
						url: cf7_gi_ajax_object.cf7_gi_ajax_url,
						xhrFields: {
							withCredentials: true
						},
						success: function (response) {
							jQuery('#'+id_long).html(response.html);
						}
					});
				}
			}
		});
	}, false );
});
