<?php
// Custom login form template
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$otp_sent = false;
if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) && isset( $_POST['otp'] ) ) {
    // Perform OTP verification
    $username = $_POST['username'];
    $password = $_POST['password'];
    $otp = $_POST['otp'];

    // Perform OTP validation and login logic here
    // Example code to verify OTP:
    // $valid_otp = verify_otp( $username, $otp );
    // if ( $valid_otp ) {
    //     $user = wp_signon( array( 'user_login' => $username, 'user_password' => $password ) );
    //     if ( ! is_wp_error( $user ) ) {
    //         // Redirect the user to the desired page upon successful login
    //         wp_redirect( home_url() );
    //         exit;
    //     }
    // }

    $otp_sent = true; // Change to false if OTP verification fails
}
?>

<form method="post" class="woocommerce-form woocommerce-form-login login" action="">
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="username">Username or email address<span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" required>
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="password">Password<span class="required">*</span></label>
        <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required>
    </p>
    <?php if ( $otp_sent ) : ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="otp">OTP<span class="required">*</span></label>
            <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="otp" id="otp" required>
        </p>
    <?php endif; ?>
    <p class="form-row">
        <input type="hidden" name="woocommerce-login-nonce" value="<?php echo wp_create_nonce( 'woocommerce-login' ); ?>">
        <button type="submit" class="woocommerce-Button button" name="login" value="Log in">Log in</button>
    </p>
</form>
