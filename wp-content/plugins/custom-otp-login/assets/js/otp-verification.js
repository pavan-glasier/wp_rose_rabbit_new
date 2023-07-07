jQuery( document ).ready( function( $ ) {
    $( '.woocommerce-form-login' ).on( 'submit', function( e ) {
        if ( $( '#otp' ).length > 0 ) {
            var otp = $( '#otp' ).val();
            if ( otp === '' ) {
                e.preventDefault();
                alert( 'Please enter the OTP.' );
            }
        }
    });
});
