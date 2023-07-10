function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			jQuery('#profile-img-tag').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}
jQuery(function($) {
	$("#profile-img").change(function(){
		readURL(this);

	});
});