<?php
//session_destroy();
//session_start();
//session_destroy();
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
$red_login_url = site_url().'/wp-admin/admin.php?page=home';
$_SESSION['token']='';
ob_start();
header('Location: '.$red_login_url);
ob_end_flush();

?>