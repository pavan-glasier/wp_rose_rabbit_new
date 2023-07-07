<?php 





add_action('admin_menu', 'smtp_menu_settings');
function smtp_menu_settings(){
    add_menu_page( 
        'Mail SMTP', // page <title>Title</title>
        'Mail SMTP', // menu link text
        'manage_options', // capability to access the page
        'smtp_settings', // page URL slug
        'mail_smtp_settings', // callback function /w content
        'dashicons-email-alt2', // menu icon
        58
    );
}
function mail_smtp_settings(){
   if(isset($_REQUEST['message'])  && $_REQUEST['message'] == 'success'){ ?>
    <div class="notice notice-success is-dismissible">
        <p><strong><?php echo __( 'Setting saved successfully.', 'mail-smtp-phpmailer' );?></strong></p>
    </div>
<?php } ?>

<div class="mail_smtp_main_container">
    <?php settings_fields( 'smtp_settings' );
    do_settings_sections( 'smtp_settings' ); ?>
    <form action='<?php echo get_permalink(); ?>' id="mail_smtp" method='post'>
        <h2><?php echo __('General Configuration', 'mail-smtp-phpmailer'); ?></h2>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><?php echo __('Enable','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" class="smtp-ui-toggle" id="_mail_smtp_enabled"
                                name="_mail_smtp_enabled" value="1"
                                <?php echo ((int) get_option('_mail_smtp_enabled') == 1) ? 'checked' : ''; ?>>
                            <label
                                for="_mail_smtp_enabled"><?php echo __('Enable SMTP.','mail-smtp-phpmailer'); ?></label>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php echo __('SMTP Host','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <label>
                            <input type="text" name="_mail_smtp_host" class="form-control"
                                value="<?php echo esc_attr(get_option('_mail_smtp_host')); ?>" />
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php echo __('SMTP Port','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <label>
                            <input type="number" name="_mail_smtp_port" class="form-control"
                                value="<?php echo esc_attr(get_option('_mail_smtp_port')); ?>" />
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php echo __('SMTP Encryption','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <select name="_mail_smtp_secure" class="form-control">
                            <option value="" selected><?php echo __('None','mail-smtp-phpmailer');?></option>
                            <option value="ssl" <?php selected('ssl', get_option("_mail_smtp_secure", "ssl")); ?>>
                                <?php echo __('SSL','mail-smtp-phpmailer');?></option>
                            <option value="tls" <?php selected('tls', get_option("_mail_smtp_secure", "tls")); ?>>
                                <?php echo __('TLS','mail-smtp-phpmailer');?></option>
                        </select>
                    </td>
                </tr>


                <tr>
                    <th scope="row"><?php echo __('Enable','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" class="smtp-ui-toggle" id="_mail_smtp_auth" name="_mail_smtp_auth"
                                value="true" <?php checked('true', get_option("_mail_smtp_auth", 'false')); ?>>
                            <label
                                for="_mail_smtp_auth"><?php echo __('Enable Authentication.','mail-smtp-phpmailer'); ?></label>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php echo __('SMTP Username','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <label>
                            <input type="text" name="_mail_smtp_username" class="form-control" placeholder="Username"
                                value="<?php echo esc_attr(get_option('_mail_smtp_username', '')); ?>" />
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php echo __('SMTP Password','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <label>
                            <input type="text" name="_mail_smtp_password" class="form-control" placeholder="********"
                                value="<?php echo esc_attr( get_option('_mail_smtp_password', '') ); ?>" />
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php echo __('From Email','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <label>
                            <input type="text" name="_mail_smtp_from" class="form-control"
                                placeholder="example@gmail.com"
                                value="<?php echo esc_attr(get_option('_mail_smtp_from', '')); ?>" />
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php echo __('Form Name','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <label>
                            <input type="text" name="_mail_smtp_from_name" class="form-control"
                                placeholder="<?php bloginfo( 'name' ); ?>"
                                value="<?php echo esc_attr(get_option('_mail_smtp_from_name', '')); ?>" />
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php echo __('Content Type','mail-smtp-phpmailer'); ?></th>
                    <td>
                        <select name="_mail_smtp_content_type" class="form-control">
                            <option value="plain"
                                <?php selected('plain', get_option("_mail_smtp_content_type", "plain")); ?>>
                                <?php echo __('Plain','mail-smtp-phpmailer');?></option>
                            <option value="html"
                                <?php selected('html', get_option("_mail_smtp_content_type", "html")); ?>>
                                <?php echo __('Html','mail-smtp-phpmailer');?></option>
                        </select>
                    </td>
                </tr>

            </tbody>
        </table>

        <p class="submit">
            <input type="hidden" name="action" value="mail_smtp_save_option">
            <input type="submit" value="Save changes" name="submit" class="button-primary">
        </p>

    </form>
</div>
<?php }


add_action('init','mail_smtp_add_setting_type');
function mail_smtp_add_setting_type(){
   if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'mail_smtp_save_option') {
      if (isset($_REQUEST['_mail_smtp_enabled'])) {
         update_option('_mail_smtp_enabled', sanitize_text_field($_REQUEST['_mail_smtp_enabled']));
      }else{
         update_option('_mail_smtp_enabled', '0');
      }
         update_option('_mail_smtp_host', sanitize_text_field($_REQUEST['_mail_smtp_host']));
         update_option('_mail_smtp_port', sanitize_text_field($_REQUEST['_mail_smtp_port']));
         update_option('_mail_smtp_secure', sanitize_text_field($_REQUEST['_mail_smtp_secure']));
         update_option('_mail_smtp_auth', sanitize_text_field($_REQUEST['_mail_smtp_auth']));
         update_option('_mail_smtp_username', sanitize_text_field($_REQUEST['_mail_smtp_username']));
         update_option('_mail_smtp_password', sanitize_text_field($_REQUEST['_mail_smtp_password']));
         update_option('_mail_smtp_from', sanitize_text_field($_REQUEST['_mail_smtp_from']));
         update_option('_mail_smtp_from_name', sanitize_text_field($_REQUEST['_mail_smtp_from_name']));
         update_option('_mail_smtp_content_type', sanitize_text_field($_REQUEST['_mail_smtp_content_type']));
      wp_redirect( admin_url( '/admin.php?page=smtp_settings&message=success' ));
   }
}

function initial_phpmailer(){
    if( get_option('_mail_smtp_enabled') == 1 ){
        add_action( 'phpmailer_init', 'smtp_phpmailer_init' );
        add_filter( 'wp_mail_content_type','smtp_mail_content_type' );
    }    
}
add_action('init','initial_phpmailer');
function smtp_mail_content_type() {
    $content_type = get_option( '_mail_smtp_content_type' );
    return 'text/'.$content_type;
}
function smtp_phpmailer_init( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = get_option('_mail_smtp_host', 'smtp.gmail.com');
    $phpmailer->Port       = get_option('_mail_smtp_port', '587');
    $phpmailer->SMTPSecure = get_option('_mail_smtp_secure', 'tls');
    $phpmailer->SMTPAuth   = get_option('_mail_smtp_auth')==true?true:false;
    $phpmailer->Username   = get_option('_mail_smtp_username', '');
    $phpmailer->Password   = get_option('_mail_smtp_password', '');
    $phpmailer->From       = get_option('_mail_smtp_from', '');
    $phpmailer->FromName   = get_option('_mail_smtp_from_name', '');
    // $phpmailer->addReplyTo('pavanvish001@yopmail.com', 'Information');
}