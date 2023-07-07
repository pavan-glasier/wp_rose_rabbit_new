<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
delete_option('_mail_smtp_enabled');
delete_option('_mail_smtp_host');
delete_option('_mail_smtp_port');
delete_option('_mail_smtp_secure');
delete_option('_mail_smtp_auth');
delete_option('_mail_smtp_username');
delete_option('_mail_smtp_password');
delete_option('_mail_smtp_from');
delete_option('_mail_smtp_from_name');
delete_option('_mail_smtp_content_type');