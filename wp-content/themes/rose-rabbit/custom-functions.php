<?php
/**
 * Rose and Rabbit functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Rose_and_Rabbit
 */

 if( class_exists('WP_Customize_Control') ){

	 /**
	 * include Customize_Select2_Control
	 */
	require get_template_directory() . '/custom-templates/Customize_Select2_Control.php';
 }

// Custom Admin Color Scheme
function custom_color_admin_color_scheme(){
	//Get the theme directory
	$theme_dir = get_stylesheet_directory_uri();
	//Custom Color
	wp_admin_css_color(
		'custom_color',
		__('Custom Color'),
		$theme_dir . '/custom-admin.css',
		array('#f2a5a2', '#f8f8f8', '#d54e21', '#f2a5a2')
	);
}
add_action('admin_init', 'custom_color_admin_color_scheme');


if (!function_exists('rose_and_rabbit_register_nav_menu')) {
	function rose_and_rabbit_register_nav_menu()
	{
		$args = array();
		if (have_rows('register_menus', 'option')):
			while (have_rows('register_menus', 'option')):
				the_row();
				$args = array_merge(
					$args,
					array(
						get_sub_field('name') => __(get_sub_field('label'), 'rose_and_rabbit'),
					)
				);
			endwhile;
		endif;
		register_nav_menus($args);
	}
	add_action('after_setup_theme', 'rose_and_rabbit_register_nav_menu', 0);
}

if (!function_exists('rose_and_rabbit_style_css')) {
	function rose_and_rabbit_style_css()
	{
		wp_register_style('marcellus', 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&amp;family=Marcellus&amp;display=swap');
		wp_enqueue_style('marcellus');

		wp_register_style('font_awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
		wp_enqueue_style('font_awesome');

		wp_register_style('slick_carousel', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css');
		wp_enqueue_style('slick_carousel');

		wp_enqueue_style('main', get_template_directory_uri() . '/assets/css/main.css', array(), 'all');
	}
	add_action('wp_head', 'rose_and_rabbit_style_css', 1);
}

if (!function_exists('rose_and_rabbit_script_js')) {
	function rose_and_rabbit_script_js()
	{

		// wp_register_script( 'jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js', null, null, true );
		// wp_enqueue_script('jquery');

		wp_register_script('slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', array(), true);
		wp_enqueue_script('slick');

		wp_register_script('animejs', 'https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js', array(), true);
		wp_enqueue_script('animejs');

		wp_enqueue_script('index-js', get_template_directory_uri() . '/assets/js/index.js', array(), true);
		wp_enqueue_script('sick-js', get_template_directory_uri() . '/assets/js/sick.js', array(), true);
		wp_enqueue_script('multistepform-js', get_template_directory_uri() . '/assets/js/multistepform.js', array(), true);
		wp_enqueue_script('firebase-js', 'https://www.gstatic.com/firebasejs/6.0.2/firebase.js', array('jquery'), true);
	}
	add_action('wp_footer', 'rose_and_rabbit_script_js');
}

// add css file in admin for acf
function acf_admin_theme_style()
{
	wp_enqueue_style('acf-admin', get_template_directory_uri() . '/assets/css/acf-admin.css');
}
add_action('admin_enqueue_scripts', 'acf_admin_theme_style');
add_action('login_enqueue_scripts', 'acf_admin_theme_style');



// Allow to upload svg
function rose_and_rabbit_mime_types($mimes)
{
	$mimes['webp'] = 'image/webp';
	$mimes['ico'] = 'image/x-icon';
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'rose_and_rabbit_mime_types');
add_filter('mime_types', 'rose_and_rabbit_mime_types');


//** * Enable preview / thumbnail for webp image files.*/
function webp_is_displayable($result, $path)
{
	if ($result === false) {
		$displayable_image_types = array(IMAGETYPE_WEBP);
		$info = @getimagesize($path);
		if (empty($info)) {
			$result = false;
		} elseif (!in_array($info[2], $displayable_image_types)) {
			$result = false;
		} else {
			$result = true;
		}
	}
	return $result;
}
add_filter('file_is_displayable_image', 'webp_is_displayable', 10, 2);




function fields_list()
{
	return array(
		'active-megamenu' => 'Active MegaMenu',
		// 'active-column-divider' => 'Column Divider',
		'active-divider' => 'Inline Divider',
		'active-featured-image' => 'Featured Image',
	);
}


// Setup fields
function megamenu_fields($id, $item, $depth, $args)
{
	$fields = fields_list();
	foreach ($fields as $_key => $label):
		$key = sprintf('menu-item-%s', $_key);
		$id = sprintf('edit-%s-%s', $key, $item->ID);
		$name = sprintf('%s[%s]', $key, $item->ID);
		$value = get_post_meta($item->ID, $key, true);
		$class = sprintf('field-%s', $_key);
		?>
<p class="description description-wide <?php echo esc_attr($class) ?>">
    <label for="<?php echo esc_attr($id); ?>">
        <input type="checkbox" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="1"
            <?php echo ($value == 1) ? 'checked="checked"' : ''; ?> /><?php echo esc_attr($label); ?></label>
</p>
<?php
	endforeach;
}
add_action('wp_nav_menu_item_custom_fields', 'megamenu_fields', 10, 4);


// Create Columns
function megamenu_columns($columns)
{
	$fields = fields_list();
	$columns = array_merge($columns, $fields);
	return $columns;
}
add_filter('manage_nav-menus_columns', 'megamenu_columns', 99);

// Save fields
function megamenu_save($menu_id, $menu_item_db_id, $menu_item_args)
{
	if (defined('DOING_AJAX') && DOING_AJAX) {
		return;
	}
	check_admin_referer('update-nav_menu', 'update-nav-menu-nonce');
	$fields = fields_list();
	foreach ($fields as $_key => $label) {
		$key = sprintf('menu-item-%s', $_key);
		// Sanitize.
		if (!empty($_POST[$key][$menu_item_db_id])) {
			// Do some checks here...
			$value = $_POST[$key][$menu_item_db_id];
		} else {
			$value = null;
		}
		// Update.
		if (!is_null($value)) {
			update_post_meta($menu_item_db_id, $key, $value);
			// echo "key:$key<br />";
		} else {
			delete_post_meta($menu_item_db_id, $key);
		}
	}
}
add_action('wp_update_nav_menu_item', 'megamenu_save', 10, 3);


function post_thumb_image()
{
	$attr = ['title' => get_the_title(), 'alt' => get_the_title(), 'loading' => 'lazy'];
	if (has_post_thumbnail()):
		echo the_post_thumbnail(array(200, 200), $attr);
	else: ?>
<img width="200" height="200" src="<?php echo site_url('/wp-content/uploads/2023/05/no-img.webp'); ?>"
    loading="<?php echo $attr['loading']; ?>" title="<?php echo $attr['title']; ?>" alt="<?php echo $attr['alt']; ?>"
    decoding="async">
<?php endif;
}
add_shortcode('thumb_image', 'post_thumb_image');



function post_featured_image()
{
	$attr = ['title' => get_the_title(), 'alt' => get_the_title(), 'loading' => 'lazy'];
	if (has_post_thumbnail()):
		echo the_post_thumbnail(array(450, 450), $attr);
	else: ?>
<img width="450" height="450" src="<?php echo site_url('/wp-content/uploads/2023/05/no-img.webp'); ?>"
    loading="<?php echo $attr['loading']; ?>" title="<?php echo $attr['title']; ?>" alt="<?php echo $attr['alt']; ?>"
    decoding="async">
<?php endif;
}
add_shortcode('featured_image', 'post_featured_image');


function post_single_image()
{
	$attr = ['title' => get_the_title(), 'alt' => get_the_title(), 'loading' => 'lazy'];
	if (has_post_thumbnail()):
		echo the_post_thumbnail('full', $attr);
	else: ?>
<img src="<?php echo site_url('/wp-content/uploads/2023/05/no-img.webp'); ?>" loading="<?php echo $attr['loading']; ?>"
    title="<?php echo $attr['title']; ?>" alt="<?php echo $attr['alt']; ?>" decoding="async">
<?php endif;
}
add_shortcode('single_image', 'post_single_image');





function rose_and_rabbit_pagination() {
	$allowed_tags = [
		'span' => [
			'class' => []
		],
		'i' => [
			'class' => [],
		],
		'a' => [
			'class' => [],
			'href' => [],
		],
		'li' => [
			'class' => [],
		],
	];

	$args = [
		'type' => 'list',
		// 'before_page_number' => '<span class="paginate-btn">',
		// 'after_page_number' => '</span>',
		'prev_text' => __('<i class="fa fa-arrow-left"></i>'),
		'next_text' => __('<i class="fa fa-arrow-right"></i>'),
	];
	$paginationLink = paginate_links($args);
	$paginationLink = str_replace('page-numbers', 'vs-btn', $paginationLink);
	$paginationLink = str_replace('<li>', '<li class="me-2">', $paginationLink);
	$paginationLink = str_replace(
		'<li class="me-2"><span aria-current="page" class="vs-btn current">',
		'<li class="me-2"><span aria-current="page" class="vs-btn bg-black">',
		$paginationLink
	);
	printf('<ul class="pagination justify-content-center">%s</ul>', wp_kses($paginationLink, $allowed_tags));
}
add_shortcode('rose_and_rabbit_pagination', 'rose_and_rabbit_pagination');




add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init()
{
	if (function_exists('acf_add_options_page')) {
		// Theme General Options
		$general_options = array(
			'page_title' => __('Theme General Options', 'rose_and_rabbit'),
			'menu_title' => __('Theme Options', 'rose_and_rabbit'),
			'menu_slug' => 'theme-general-options',
			'capability' => 'edit_posts',
			'redirect' => true,
			'icon_url' => 'dashicons-screenoptions',
			'position' => 2
		);
		acf_add_options_page($general_options);

		acf_add_options_sub_page(
			array(
				'page_title' => 'Header',
				'menu_title' => 'Theme Header',
				'parent_slug' => 'theme-general-options',
			)
		);
		acf_add_options_sub_page(
			array(
				'page_title' => 'Footer',
				'menu_title' => 'Theme Footer',
				'parent_slug' => 'theme-general-options',
			)
		);
	}
}

add_filter('get_the_archive_title_prefix', '__return_empty_string');



// add_action( 'phpmailer_init', 'send_smtp_email' );
// function send_smtp_email( $phpmailer ) {
//     $phpmailer->isSMTP();
//     $phpmailer->Host       = 'smtp-relay.sendinblue.com';
//     $phpmailer->Port       = '587';
//     $phpmailer->SMTPSecure = 'tls';
//     $phpmailer->SMTPAuth   = true;
//     $phpmailer->Username   = 'pavan@glasier.in';
//     $phpmailer->Password   = '4tD3PXKsgTRvb8wF';
//     $phpmailer->From       = 'pavanvish001@yopmail.com';
//	   $phpmailer->FromName   = 'Rose & Rabbit';
//     // $phpmailer->addReplyTo('pavanvish001@yopmail.com', 'Information');
// }

// add_filter( 'wp_mail_content_type','set_my_mail_content_type' );
// function set_my_mail_content_type() {
//     return "text/html";
// }


function rose_and_rabbit_load_wp_customizer($wp_customize)
{
	// customizer code
	// START SOCIAL MEDIA
	// add section for social media links
	$wp_customize->add_section(
		"social_media_section",
		array(
			"title" => "Social Media Section",
			"description" => "This is the social media section",
		)
	);

	// $social_media_section = $wp_customize->get_section('social_media_section');
	// add setting for FACEBOOK
	$wp_customize->add_setting(
		"social_media_facebook",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add control for FACEBOOK
	$wp_customize->add_control(
		"social_media_facebook",
		array(
			"label" => "Facebook",
			"description" => "Please fill the facebook url",
			"section" => "social_media_section",
			"type" => "text"
		)
	);


	// add setting for TWITTER
	$wp_customize->add_setting(
		"social_media_twitter",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add control for TWITTER
	$wp_customize->add_control(
		"social_media_twitter",
		array(
			"label" => "Twitter",
			"description" => "Please fill the twitter url",
			"section" => "social_media_section",
			"type" => "text"
		)
	);

	// add setting for INSTAGRAM
	$wp_customize->add_setting(
		"social_media_instagram",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add control for INSTAGRAM
	$wp_customize->add_control(
		"social_media_instagram",
		array(
			"label" => "Instagram",
			"description" => "Please fill the instagram url",
			"section" => "social_media_section",
			"type" => "text"
		)
	);

	// add setting for LINKEDIN
	$wp_customize->add_setting(
		"social_media_linkedin",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add control for LINKEDIN
	$wp_customize->add_control(
		"social_media_linkedin",
		array(
			"label" => "Linkedin",
			"description" => "Please fill the linkedin url",
			"section" => "social_media_section",
			"type" => "text"
		)
	);

	// add setting for PINTEREST
	$wp_customize->add_setting(
		"social_media_pinterest",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add control for PINTEREST
	$wp_customize->add_control(
		"social_media_pinterest",
		array(
			"label" => "Pinterest",
			"description" => "Please fill the pinterest url",
			"section" => "social_media_section",
			"type" => "text"
		)
	);

	// add setting for YOUTUBE
	$wp_customize->add_setting(
		"social_media_youtube",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add control for YOUTUBE
	$wp_customize->add_control(
		"social_media_youtube",
		array(
			"label" => "Youtube",
			"description" => "Please fill the youtube url",
			"section" => "social_media_section",
			"type" => "text"
		)
	);
	// END SOCIAL MEDIA
	

	// add section
	$wp_customize->add_section(
		"sec_copyright",
		array(
			"title" => "Copyright Section",
			"description" => "This is the copyright section",
		)
	);

	// add setting/field for text
	$wp_customize->add_setting(
		"set_copyright",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add control for text
	$wp_customize->add_control(
		"set_copyright",
		array(
			"label" => "Copyright",
			"description" => "Please fill the copyright text",
			"section" => "sec_copyright",
			"type" => "text"
		)
	);

	// add setting/field for image
	$wp_customize->add_setting(
		'image_icon_upload',
		array(
			'default' => '',
			'capability' => 'edit_theme_options',
			'type' => 'option',
		)
	);
	// add control for image
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'image_icon_upload',
			array(
				'label' => __('Icons', 'rose_and_rabbit'),
				'section' => 'sec_copyright',
				'settings' => 'image_icon_upload',
			)
		)
	);


	//add section
	// $wp_customize->add_section( 'rose_and_rabbit_dropdown_page_section', array(
	// 	'title' => 'All Pages',
	// 	'priority' => 10
	// ));

	// //add setting
	// $wp_customize->add_setting( 'rose_and_rabbit_dropdown_page', array(
	// 		'default' => '0',
	// ));

	// //add control
	// $wp_customize->add_control( 'rose_and_rabbit_dropdown_page_control', array(
	// 		'label' => 'Select Page',
	// 		// 'type'  => 'dropdown-pages',
	// 		'type'  => 'menu_locations',
	// 		'section' => 'rose_and_rabbit_dropdown_page_section',
	// 		'settings' => 'rose_and_rabbit_dropdown_page'
	// ));


	//add header section
	$wp_customize->add_section('rose_and_rabbit_header_section', 
		array(
			'title' => 'Theme Header',
			'priority' => 1
		)
	);

	// add header setting
	$locations = get_registered_nav_menus();
	$array = array("0" => "Select Menu");
	$merge_locations = array_merge($array, $locations);
	// Add a setting for the custom post selection
	$wp_customize->add_setting('rose_and_rabbit_header', 
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);

	// Add a control for the custom post selection
	$wp_customize->add_control('rose_and_rabbit_header', 
		array(
			'label' => 'Select Menu',
			'section' => 'rose_and_rabbit_header_section',
			'type' => 'select',
			'choices' => $merge_locations,
		)
	);

	// add setting/field for header user icon
	$wp_customize->add_setting(
		'user_icon_upload',
		array(
			'default' => '',
			'capability' => 'edit_theme_options',
			'type' => 'option',

		)
	);
	// add control for header user icon
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'user_icon_upload',
			array(
				'label' => __('User Icon', 'rose_and_rabbit'),
				'section' => 'rose_and_rabbit_header_section',
				'settings' => 'user_icon_upload',
			)
		)
	);

	// add setting/field for header cart icon
	$wp_customize->add_setting(
		'cart_icon_upload',
		array(
			'default' => '',
			'capability' => 'edit_theme_options',
			'type' => 'option',

		)
	);
	// add control for header cart icon
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'cart_icon_upload',
			array(
				'label' => __('Cart Icon', 'rose_and_rabbit'),
				'section' => 'rose_and_rabbit_header_section',
				'settings' => 'cart_icon_upload',
			)
		)
	);
	
	// Footer Columns 

    // Add the recursive theme footer panel
    $wp_customize->add_panel('rose_and_rabbit_footer_section_panel', array(
        'title' => 'Theme Footer',
        'description' =>'This panel contains theme footer.',
        // 'section' => 'rose_and_rabbit_footer_section', // Specify the parent section ID
		'priority' => 2,
    ));

    // Add a top footer within the panel
    $wp_customize->add_section('top_footer_section', array(
        'title' => 'Top Footer',
        'description' => 'This is a Top Footer.',
        'panel' => 'rose_and_rabbit_footer_section_panel', // Specify the parent panel ID
    ));


	// add setting/field for footer logo
	$wp_customize->add_setting(
		'footer_logo',
		array(
			'default' => '',
			'capability' => 'edit_theme_options',
			'type' => 'option',
			'transport'     => 'refresh',

		)
	);
	// add control for footer logo
	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'footer_logo',
			array(
				'label' => __('Footer Logo', 'rose_and_rabbit'),
				'section' => 'top_footer_section',
				'settings' => 'footer_logo',
				'flex_width'  => true,
				'flex_height' => false,
				'width'       => 1920,
				'height'      => 1080,
			)
		)
	);
	// add setting/field for NEWSLETTER
	$wp_customize->add_setting(
		"newsletter_heading",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add control for NEWSLETTER
	$wp_customize->add_control(
		"newsletter_heading",
		array(
			"label" => "Newsletter Heading",
			"section" => "top_footer_section",
			"type" => "text",
			"input_attrs" => array(
				"placeholder" => "Please enter the heading",
			)
		)
	);

	$wp_customize->add_setting(
		'newsletter_form', 
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);

	$cf7 = get_posts(array(
		'post_type'     => 'wpcf7_contact_form',
		'numberposts'   => -1
	));
	$formArray = array("0" => "Newsletter Form");
	foreach($cf7 as $key){
		$formArray[$key->ID] = $key->post_title;
	}
	$wp_customize->add_control(
		'newsletter_form', 
		array(
			'label' => 'Newsletter Form',
			'section' => 'top_footer_section',
			'type' => 'select',
			'choices' => $formArray,
		)
	);

	// 
	$wp_customize->add_section('bottom_footer_section', array(
        'title' => 'Bottom Footer',
        'description' => 'This is a Bottom Footer.',
        'panel' => 'rose_and_rabbit_footer_section_panel', // Specify the parent panel ID
    ));

	// COLUMN ONE (1)
	// add column 1 section 
	$wp_customize->add_section('column_1', array(
        'title' => 'Column 1',
        'description' => 'This is a Column 1.',
        'section' => 'bottom_footer_section',
        'panel' => 'rose_and_rabbit_footer_section_panel',
    ));

	// add setting for column width
	$wp_customize->add_setting('col_1_size', 
		array(
			'default' => 'col-md-3',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);
	// add column column size dropdown control
	$wp_customize->add_control(new Customize_Select2_Control($wp_customize, 'col_1_size',
		array(
			'label' => 'Column Size',
			'section' => 'column_1',
			'panel' => 'rose_and_rabbit_footer_section_panel',
			'type' => 'select',
			'choices' => array(
				'col-md-1' => __( 'Col-1' ),
				'col-md-2' => __( 'Col-2' ),
				'col-md-3' => __( 'Col-3' ),
				'col-md-4' => __( 'Col-4' ),
				'col-md-5' => __( 'Col-5' ),
				'col-md-6' => __( 'Col-6' ),
				'col-md-7' => __( 'Col-7' ),
				'col-md-8' => __( 'Col-8' ),
				'col-md-9' => __( 'Col-9' ),
				'col-md-10' => __( 'Col-10' ),
				'col-md-11' => __( 'Col-11' ),
				'col-md-12' => __( 'Col-12' ),
			),
		)
		)
	);

	// add column 1 heading setting
	$wp_customize->add_setting(
		"col_1_heading",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add column 1 heading control
	$wp_customize->add_control(
		"col_1_heading",
		array(
			"label" => "Heading",
			'panel' => 'rose_and_rabbit_footer_section_panel',
			"section" => "column_1",
			"type" => "text",
			"input_attrs" => array(
				"placeholder" => "Please enter the heading",
			)
		)
	);

	// add column 1 address setting
	$wp_customize->add_setting('col_1_address', 
		array(
			"type" => "theme_mod",
			"default" => "",
			// "sanitize_callback" => "sanitize_text_field",
		)
	);
	// add column 1 address control
	$wp_customize->add_control('col_1_address', 
		array(
			"label" => "Address",
			'panel' => 'rose_and_rabbit_footer_section_panel',
			"section" => "column_1",
			"type" => "textarea",
			"input_attrs" => array(
				"placeholder" => "Please enter the address",
			)
		)
	);

	// add column 1 phone setting
	$wp_customize->add_setting('col_1_phone', 
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);
	// add column 1 phone control
	$wp_customize->add_control('col_1_phone', 
		array(
			"label" => "Phone Number",
			'panel' => 'rose_and_rabbit_footer_section_panel',
			"section" => "column_1",
			"type" => "text",
			"input_attrs" => array(
				"placeholder" => "Please enter the phone number",
			)
		)
	);

	// add column 1 email setting
	$wp_customize->add_setting('col_1_email', 
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);
	// add column 1 email control
	$wp_customize->add_control('col_1_email', 
		array(
			"label" => "Email",
			'panel' => 'rose_and_rabbit_footer_section_panel',
			"section" => "column_1",
			"type" => "text",
			"input_attrs" => array(
				"placeholder" => "Please enter the email number",
			)
		)
	);

	// END COLUMN ONE (1)


	// COLUMN TWO (2)
	// add column 2 section 
	$wp_customize->add_section('column_2', array(
        'title' => 'Column 2',
        'description' => 'This is a Column 2.',
        'section' => 'bottom_footer_section',
        'panel' => 'rose_and_rabbit_footer_section_panel',
    ));

	// add setting for column width
	$wp_customize->add_setting('col_2_size', 
		array(
			'default' => 'col-md-3',
			// 'capability' => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);
	// add column column size dropdown control
	$wp_customize->add_control('col_2_size', 
		array(
			'label' => 'Column Size',
			'section' => 'column_2',
			'panel' => 'rose_and_rabbit_footer_section_panel',
			'type' => 'select',
			'choices' => array(
				'col-md-1' => __( 'Col-1' ),
				'col-md-2' => __( 'Col-2' ),
				'col-md-3' => __( 'Col-3' ),
				'col-md-4' => __( 'Col-4' ),
				'col-md-5' => __( 'Col-5' ),
				'col-md-6' => __( 'Col-6' ),
				'col-md-7' => __( 'Col-7' ),
				'col-md-8' => __( 'Col-8' ),
				'col-md-9' => __( 'Col-9' ),
				'col-md-10' => __( 'Col-10' ),
				'col-md-11' => __( 'Col-11' ),
				'col-md-12' => __( 'Col-12' ),
			),
		)
	);

	// add column 2 heading setting
	$wp_customize->add_setting(
		"col_2_heading",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add column 2 heading control
	$wp_customize->add_control(
		"col_2_heading",
		array(
			"label" => "Heading",
			'panel' => 'rose_and_rabbit_footer_section_panel',
			"section" => "column_2",
			"type" => "text",
			"input_attrs" => array(
				"placeholder" => "Please enter the heading",
			)
		)
	);

	// add column 2 menu dropdown setting
	$wp_customize->add_setting('col_2_menu', 
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);
	// add column 2 menu dropdown control
	$wp_customize->add_control('col_2_menu', 
		array(
			'label' => 'Select Menu',
			'section' => 'column_2',
			'panel' => 'rose_and_rabbit_footer_section_panel',
			'type' => 'select',
			'choices' => $merge_locations,
		)
	);
	// END COLUMN TWO (2)

	// COLUMN THREE (3)
	// add column 3 section 
	$wp_customize->add_section('column_3', array(
        'title' => 'Column 3',
        'description' => 'This is a Column 3.',
        'section' => 'bottom_footer_section',
        'panel' => 'rose_and_rabbit_footer_section_panel',
    ));

	// add setting for column width
	$wp_customize->add_setting('col_3_size', 
		array(
			'default' => 'col-md-3',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);
	// add column column size dropdown control
	$wp_customize->add_control('col_3_size', 
		array(
			'label' => 'Column Size',
			'section' => 'column_3',
			'panel' => 'rose_and_rabbit_footer_section_panel',
			'type' => 'select',
			'choices' => array(
				'col-md-1' => __( 'Col-1' ),
				'col-md-2' => __( 'Col-2' ),
				'col-md-3' => __( 'Col-3' ),
				'col-md-4' => __( 'Col-4' ),
				'col-md-5' => __( 'Col-5' ),
				'col-md-6' => __( 'Col-6' ),
				'col-md-7' => __( 'Col-7' ),
				'col-md-8' => __( 'Col-8' ),
				'col-md-9' => __( 'Col-9' ),
				'col-md-10' => __( 'Col-10' ),
				'col-md-11' => __( 'Col-11' ),
				'col-md-12' => __( 'Col-12' ),
			),
		)
	);


	// add column 3 heading setting
	$wp_customize->add_setting(
		"col_3_heading",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add column 3 heading control
	$wp_customize->add_control(
		"col_3_heading",
		array(
			"label" => "Heading",
			'panel' => 'rose_and_rabbit_footer_section_panel',
			"section" => "column_3",
			"type" => "text",
			"input_attrs" => array(
				"placeholder" => "Please enter the heading",
			)
		)
	);

	// add column 3 menu dropdown setting
	$wp_customize->add_setting('col_3_menu', 
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);
	// add column 3 menu dropdown control
	$wp_customize->add_control('col_3_menu', 
		array(
			'label' => 'Select Menu',
			'section' => 'column_3',
			'panel' => 'rose_and_rabbit_footer_section_panel',
			'type' => 'select',
			'choices' => $merge_locations,
		)
	);
	// END COLUMN THREE (3)


	// COLUMN FOUR (4)
	// add column 4 section 
	$wp_customize->add_section('column_4', array(
        'title' => 'Column 4',
        'description' => 'This is a Column 4.',
        'section' => 'bottom_footer_section',
        'panel' => 'rose_and_rabbit_footer_section_panel',
    ));


	// add setting for column width
	$wp_customize->add_setting('col_4_size', 
		array(
			'default' => 'col-md-3',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);
	// add column 4 column size dropdown control
	$wp_customize->add_control('col_4_size', 
		array(
			'label' => 'Column Size',
			'section' => 'column_4',
			'panel' => 'rose_and_rabbit_footer_section_panel',
			'type' => 'select',
			'choices' => array(
				'col-md-1' => __( 'Col-1' ),
				'col-md-2' => __( 'Col-2' ),
				'col-md-3' => __( 'Col-3' ),
				'col-md-4' => __( 'Col-4' ),
				'col-md-5' => __( 'Col-5' ),
				'col-md-6' => __( 'Col-6' ),
				'col-md-7' => __( 'Col-7' ),
				'col-md-8' => __( 'Col-8' ),
				'col-md-9' => __( 'Col-9' ),
				'col-md-10' => __( 'Col-10' ),
				'col-md-11' => __( 'Col-11' ),
				'col-md-12' => __( 'Col-12' ),
			),
		)
	);

	// add column 4 heading setting
	$wp_customize->add_setting(
		"col_4_heading",
		array(
			"type" => "theme_mod",
			"default" => "",
			"sanitize_callback" => "sanitize_text_field",
		)
	);

	// add column 4 heading control
	$wp_customize->add_control(
		"col_4_heading",
		array(
			"label" => "Heading",
			'panel' => 'rose_and_rabbit_footer_section_panel',
			"section" => "column_4",
			"type" => "text",
			"input_attrs" => array(
				"placeholder" => "Please enter the heading",
			)
		)
	);

	// add column 4 menu dropdown setting
	$wp_customize->add_setting('col_4_menu', 
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'type' => 'theme_mod',
		)
	);
	// add column 4 menu dropdown control
	$wp_customize->add_control('col_4_menu', 
		array(
			'label' => 'Select Menu',
			'section' => 'column_4',
			'panel' => 'rose_and_rabbit_footer_section_panel',
			'type' => 'select',
			'choices' => $merge_locations,
		)
	);
	// END COLUMN FOUR (4)


}

add_action("customize_register", "rose_and_rabbit_load_wp_customizer");

function enqueue_select2() {
	// Enqueue Select2 library
	wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
	wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '', true);

	// Enqueue custom JavaScript file
	wp_enqueue_script('custom-select2', get_template_directory_uri() . '/js/custom-select2.js', array('jquery', 'select2'), '', true);
}
add_action('customize_controls_enqueue_scripts', 'enqueue_select2');





// Add plus button quantity input on cart page
add_action('woocommerce_after_quantity_input_field', 'rose_and_rabbit_display_quantity_plus');
function rose_and_rabbit_display_quantity_plus() {
	echo '<button type="button" data-field="quantity" class="plus qty-btn"><i class="fal fa-plus" aria-hidden="true"></i></button>';
}

// Add minus button quantity input on cart page
add_action('woocommerce_before_quantity_input_field', 'rose_and_rabbit_display_quantity_minus');
function rose_and_rabbit_display_quantity_minus() {
	echo '<label> QUANTITY: </label><button type="button" data-field="quantity" class="minus qty-btn"><i class="fal fa-minus" aria-hidden="true"></i></button>';
}

// -------------
// 2. Trigger update quantity script
add_action('wp_footer', 'rose_and_rabbit_add_cart_quantity_plus_minus', 10);
function rose_and_rabbit_add_cart_quantity_plus_minus()
{
	// if (!is_product() && !is_cart()) return;
	wc_enqueue_js("
	  jQuery('.woocommerce p.stars a').click(function(e){ e.preventDefault();
		jQuery( '.dis-none' ).removeClass( 'dis-none' );
	  });
   ");
}



function login_form_woocommerce($atts)
{
	if (!is_user_logged_in()) {
		if (
			function_exists('woocommerce_login_form') &&
			function_exists('woocommerce_output_all_notices')
		) {
			//render the WooCommerce login form   
			ob_start();
			woocommerce_output_all_notices();
			woocommerce_login_form();
			return ob_get_clean();
		} else {
			//render the WordPress login form
			return wp_login_form(array('echo' => false));
		}
	} else {
		return "Hello there! Welcome back.";
	}
}
add_shortcode('login_form_wc', 'login_form_woocommerce');




// remove item form cart
add_action('wp_footer', 'remove_cart_item_form_cart_script', 100);
function remove_cart_item_form_cart_script()
{ ?>
<script type="text/javascript">
// Ajax delete product in the cart
jQuery(document).on('click', '.remove-product', function(e) {
    e.preventDefault();
    let product_id = jQuery(this).attr("data-product_id"),
        cart_item_key = jQuery(this).attr("data-cart_item_key"),
        product_container = jQuery(this).parent().parent().parent('.recent-post');
    // Add loader
    product_container.block({
        message: null,
        overlayCSS: {
            background: '#fcebea',
            opacity: 0.8
        }
    });
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: wc_add_to_cart_params.ajax_url,
        data: {
            action: "product_remove",
            product_id: product_id,
            cart_item_key: cart_item_key
        },
        success: function(response) {
            if (!response || response.error) return;
            let fragments = response.fragments;
            // Replace fragments
            if (fragments) {
                jQuery(document.body).trigger('added_to_cart', [response.fragments, response
                    .cart_hash
                ]);
                jQuery.each(fragments, function(key, value) {
                    jQuery(key).replaceWith(value);
                });
            }
        }
    });
});
</script>

<script>
const firebaseConfig = {
    apiKey: "AIzaSyAau8ZO_YqY3WCjPLKPOux7sr1GNwHIGnA",
    authDomain: "rose-and-rabbit.firebaseapp.com",
    projectId: "rose-and-rabbit",
    storageBucket: "rose-and-rabbit.appspot.com",
    messagingSenderId: "1086258032396",
    appId: "1:1086258032396:web:71bc42f766f904114b2329",
    measurementId: "G-G0SEDQVHWB"
};
firebase.initializeApp(firebaseConfig);
window.onload = function() {
    let recaptchaContainer = document.getElementById('recaptcha-container');
    if (recaptchaContainer) {
        render();
    }
};

function render() {
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
        'size': 'invisible',
    });
    // recaptchaVerifier.render();
    recaptchaVerifier.render().then((widgetId) => {
        window.recaptchaWidgetId = widgetId;
    });

}

function phoneSendAuth() {
    msform.block({
        message: null,
        overlayCSS: {
            background: '#fcebea',
            opacity: 0.8
        }
    });
    const input = document.querySelectorAll(".input");
    let number = jQuery("#phone-number-input").val();
    let numberwithcode = '+91' + number;
    const appVerifier = window.recaptchaVerifier
    // firebase.auth().signInWithPhoneNumber(numberwithcode, window.recaptchaVerifier).then(function(confirmationResult) {
    firebase.auth().signInWithPhoneNumber(numberwithcode, appVerifier).then(function(confirmationResult) {
        window.confirmationResult = confirmationResult;
        coderesult = confirmationResult;
        jQuery("#error").html("");
        jQuery("#error").hide();
        jQuery("#sentSuccess").html("OTP Sent Successfully.");
        jQuery("#sentSuccess").show();
        jQuery('.otp-field').removeClass('d-none');
        jQuery('#send-otp').addClass('d-none');
        input[0].focus();
        msform.unblock();
        setTimeout(() => {
            jQuery("#sentSuccess").html("");
            jQuery("#sentSuccess").hide();
        }, 1000)

    }).catch(function(error) {
        jQuery("#error").html(error.message);
        jQuery("#error").show();
        msform.unblock();
    });

}

function codeverify() {
    msform.block({
        message: null,
        overlayCSS: {
            background: '#fcebea',
            opacity: 0.8
        }
    });
    const input = document.querySelectorAll(".input");
    jQuery("#otp-verify").html('Verifying...');
    let codes = "";
    input.forEach((element) => {
        codes += element.value;
    })
    try {
        coderesult.confirm(codes).then(function(result) {
            let user = result.user;
            let phoneNumber = user.phoneNumber;
            jQuery("#billing_phone").val(phoneNumber);
            jQuery("#billing_phone").prop('readonly', true);
            jQuery("#successRegsiter").html("Verified!");
            jQuery("#successRegsiter").show();

            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo admin_url( 'admin-ajax.php' );?>',
                data: {
                    action: "login_user_by_phone_number",
                    phone_number: phoneNumber,
                },
                beforeSend: function() {
                    jQuery("#error").html("");
                    jQuery("#error").hide();
                },
                success: function(response) {
                    if (response.status) {
                        let users = response.user;
                        jQuery.each(users, function(key, value) {
                            jQuery("#" + key).val(value[0]);
                        });
                    }
                    setTimeout(() => {
                        msform.unblock();
                        jQuery("#successRegsiter").html("");
                        jQuery("#successRegsiter").hide();
                        jQuery("#otp-verify").next().click();
                        jQuery("#otp-verify").html('Next');
                    }, 1000);
                },
                error: function(error) {
                    console.log('error :>> ', error);
                }
            });

        }).catch(function(error) {
            console.log('error :>> ', error);
            jQuery("#error").html(error.message);
            jQuery("#error").show();
            jQuery("#otp-verify").html('Next');
            msform.unblock();
        });
    } catch (error) {
        console.log('error :>> ', error);
        jQuery("#error").html(error.message);
        jQuery("#error").show();
        jQuery("#otp-verify").html('Next');
        msform.unblock();
    };
}
</script>
<?php }

add_action('wp_ajax_product_remove', 'product_remove');
add_action('wp_ajax_nopriv_product_remove', 'product_remove');
function product_remove()
{
	global $wpdb, $woocommerce;
	ob_start();

	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
		if ($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key']) {
			WC()->cart->remove_cart_item($cart_item_key);
		}
	}
	WC()->cart->calculate_totals();
	WC()->cart->maybe_set_cart_cookies();
	woocommerce_cart_items();
	$mini_cart = ob_get_clean();

	// Fragments and mini cart are returned
	$data = array(
		'fragments' => apply_filters(
			'woocommerce_add_to_cart_fragments',
			array(
				'div.cart-item' => '<div class="cart-item">' . $mini_cart . '</div>'
			)
		),
		'cart_hash' => apply_filters('woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5(json_encode(WC()->cart->get_cart_for_session())) : '', WC()->cart->get_cart_for_session())
	);

	wp_send_json($data);

	die();
}

// Remove product in the cart using ajax
function woocommerce_cart_items()
{
	if (WC()->cart->get_cart()):
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key); ?>
<div
    class="recent-post <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
    <div class="media-img">
        <?php $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
						if (!$product_permalink) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
						} ?>
    </div>

    <div class="media-body">
        <div class="tit">
            <div class="product_title">
                <?php if (!$product_permalink) {
						echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<h4 class="post-title product_title">%s</h4>', $_product->get_name()), $cart_item, $cart_item_key) . '&nbsp;');
					} else {
						echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s"><h4 class="post-title product_title">%s</h4></a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
					}
					do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
					echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.
					if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
						echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
					} ?>
            </div>

            <?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
				'<a href="%s" class="remove-product" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25" height="25" viewBox="0,0,256,256"><g fill-rule="evenodd" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(0.05905,0.05905)"><path d="M2841,1240l260,266l5,4l-5,5l-682,663l615,614l67,59l5,4l-5,5l-260,266l-5,4l-5,-5l-660,-706l-1,1v-8v-2h-2v2v8l-1,-1l-660,706l-4,5l-5,-4l-261,-266l-4,-5l4,-4l68,-59l615,-614l-683,-663l-4,-5l4,-4l261,-266l5,-5l4,5l661,709v1h2v-1l661,-709l5,-5z"></path></g></g></svg></a>',
				esc_url(WC()->cart->get_remove_url($cart_item_key)),
				esc_html__('Remove this item', 'woocommerce'),
				esc_attr($product_id),
				esc_attr($_product->get_sku()),
				esc_attr($cart_item_key)
			), $cart_item_key); ?>
        </div>

        <span class="sideml">
            <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.?>
        </span>
        <div class="actions qountbtn">
            <?php
				if ($_product->is_sold_individually()) {
					$min_quantity = 1;
					$max_quantity = 1;
				} else {
					$min_quantity = 0;
					$max_quantity = $_product->get_max_purchase_quantity();
				}
				$product_quantity = woocommerce_quantity_input(
					array(
						// 'input_name' => "cart[{$cart_item_key}][qty]",
						'input_name' => "quantity",
						'pro_qty_key' => $cart_item_key,
						'input_value' => $cart_item['quantity'],
						'max_value' => $max_quantity,
						'min_value' => $min_quantity,
						'product_name' => $_product->get_name(),
						'classes' => 'qty pqty_total'
					),
					$_product,
					false
				);
				
			echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok. ?>
            <p class="sideprice">
                <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok. ?>
            </p>
        </div>
    </div>
</div>
<?php
			}
		}
	else: ?>
<div class="d-block mb-40 pb-3 text-center">
    <h4 class="cart_empty_notice">Your cart is empty.</h4>
    <a class="btn_return_shop vs-btn mt-3" href="<?php echo wc_get_page_permalink('shop') ?>">Return to Shop</a>
</div>
<?php endif;
}


function enqueue_cart_scripts() {
    // if (is_cart()) {
		wp_enqueue_script('cart-scripts', get_template_directory_uri() . '/js/cart-scripts.js', array('jquery'), '1.0', true);
		$passarray =  array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'is_cart' => is_cart(),
			// 'product' => get_option('atcaiofw_product'),
			// 'basekt_position' => get_option('basekt_position'),
		);
  	 	wp_localize_script( 'cart-scripts', 'addtocart_sidebar', $passarray);
    // }
}
add_action('wp_enqueue_scripts', 'enqueue_cart_scripts');


function register_user_with_billing_details() {
    // Check if the form is submitted
    if (isset($_POST['register_user_on_order'])) {
		ob_start();
		$errors = array();
        // Example: Validate billing first name
        $billing_first_name = sanitize_text_field($_POST['billing_first_name']);
        $billing_last_name = sanitize_text_field($_POST['billing_last_name']);
        $billing_country = sanitize_text_field($_POST['billing_country']);
        $billing_address_1 = sanitize_text_field($_POST['billing_address_1']);
        $billing_address_2 = sanitize_text_field($_POST['billing_address_2']);
        $billing_company = sanitize_text_field($_POST['billing_company']);

        $billing_city = sanitize_text_field($_POST['billing_city']);
        $billing_state = sanitize_text_field($_POST['billing_state']);
        $billing_postcode = sanitize_text_field($_POST['billing_postcode']);
        $billing_phone = sanitize_text_field($_POST['billing_phone']);
        $billing_email = sanitize_text_field($_POST['billing_email']);
		
        if (empty($billing_first_name)) {
            $errors['billing_first_name'] = 'Billing first name is required.';
        }
		if (empty($billing_last_name)) {
            $errors['billing_last_name'] = 'Billing last name is required.';
        }
		if (empty($billing_country)) {
            $errors['billing_country'] = 'Country is required.';
        }
		if (empty($billing_address_1)) {
            $errors['billing_address_1'] = 'Address is required.';
        }
		if (empty($billing_city)) {
            $errors['billing_city'] = 'City is required.';
        }
		if (empty($billing_state)) {
            $errors['billing_state'] = 'State is required.';
        }
		if (empty($billing_postcode)) {
            $errors['billing_postcode'] = 'Postcode is required.';
        }
		if (empty($billing_phone)) {
            $errors['billing_phone'] = 'Phone number is required.';
        }
		if (empty($billing_email)) {
            $errors['billing_email'] = 'Email is required.';
        }
        // Repeat the above step for other billing fields
		// wc_add_notice($errors['billing_first_name'], 'error');
        // If there are validation errors, return the error messages
		if ( $errors ) { ?>
		<?php foreach($errors as $key => $value){ ?>
		<small id="<?php echo $key;?>_error" class="text-danger"><?php echo $value; ?></small>
		<?php } 
			
			$errors_html= ob_get_contents();
			ob_end_clean();
			$arr=array(
				"status" => false,
				"errors_html" => $errors_html,
				"errors" => $errors
			);
			echo json_encode($arr);
			exit();
			// wp_send_json($arr);
        }
        // Generate a unique username for the new user
        $username = sanitize_user(str_replace(' ', '', $billing_first_name . $billing_last_name ));
        // Create a random password
        $password = wp_generate_password();
		$user_phone = str_replace("+91", "", $billing_phone);
		$user = get_user_by( 'login', 'user_'.$user_phone );
		if($user){
			$user_id = $user->ID;
			if( empty( $user->user_email) ){
				wp_update_user( array( 'ID' => $user_id, 'user_email' => $billing_email ) );
			}
		}
		else{
			// $user_id = wp_create_user($username, $password, $billing_email );
			$user_id = wp_insert_user([
				'user_login' => $username, // Use email as the username
				'user_pass' => $password, // Generate a random password
				'user_email' => $billing_email,
				'first_name' => $billing_first_name,
				'last_name' => $billing_last_name,
				'role' => 'customer' // Set the user role as a customer
			]);
		}
        // Create the new user
        // Set the user's first name and last name
        wp_update_user(array('ID' => $user_id, 'first_name' => $billing_first_name, 'last_name' => $billing_last_name));
        // Store the billing details in the database
        update_user_meta($user_id, 'billing_first_name', $billing_first_name);
        update_user_meta($user_id, 'billing_last_name', $billing_last_name);
        update_user_meta($user_id, 'billing_email', $billing_email);
        update_user_meta($user_id, 'billing_phone', $billing_phone);
        update_user_meta($user_id, 'user_phone', $user_phone);
        update_user_meta($user_id, 'billing_company', $billing_company);
        update_user_meta($user_id, 'billing_address_1', $billing_address_1);
        update_user_meta($user_id, 'billing_address_2', $billing_address_2);
        update_user_meta($user_id, 'billing_country', $billing_country);
        update_user_meta($user_id, 'billing_state', $billing_state);
        update_user_meta($user_id, 'billing_city', $billing_city);
        update_user_meta($user_id, 'billing_postcode', $billing_postcode);

		// shipping address
        update_user_meta($user_id, 'shipping_first_name', $billing_first_name);
		update_user_meta($user_id, 'shipping_last_name', $billing_last_name);
		update_user_meta($user_id, 'shipping_phone', $billing_phone);
		update_user_meta($user_id, 'shipping_address_1', $billing_address_1);
        update_user_meta($user_id, 'shipping_address_2', $billing_address_2);
		update_user_meta($user_id, 'shipping_company', $billing_company);
		update_user_meta($user_id, 'shipping_country', $billing_country);
        update_user_meta($user_id, 'shipping_state', $billing_state);
        update_user_meta($user_id, 'shipping_city', $billing_city);
        update_user_meta($user_id, 'shipping_postcode', $billing_postcode);
		
        // Repeat the above step for other billing fields
        // Return a success response
		// wp_new_user_notification($user_id, $password);
		wp_new_user_notification($user_id, $password, 'user');
		$arr = array(
			"status" => true,
			"message" => 'User billing details stored successfully.',
			"user" => $user
		);
		echo json_encode($arr);
		exit();
    }
}
add_action('wp_ajax_register_user_with_billing_details', 'register_user_with_billing_details');
add_action('wp_ajax_nopriv_register_user_with_billing_details', 'register_user_with_billing_details');

// function auto_login_after_order($order_id) {
//     $order = wc_get_order($order_id);
//     $user_id = $order->get_customer_id();

//     if ($user_id > 0) {
//         wp_set_auth_cookie($user_id);
//         wp_set_current_user($user_id);
//         do_action('wp_login', $order->get_billing_email());
//     }
// }
// add_action('woocommerce_thankyou', 'auto_login_after_order', 10, 1);

function add_phone_user_table( $column ) {
    $column['phone'] = 'Phone Number';
    return $column;
}
add_filter( 'manage_users_columns', 'add_phone_user_table' );

function add_phone_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'phone' :
            return get_the_author_meta( 'user_phone', $user_id )?'+91'.str_replace("+91", "", get_the_author_meta( 'user_phone', $user_id )):'';
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'add_phone_user_table_row', 10, 3 );

// Add custom phone number field to user profile
function add_custom_phone_field( $user ) { ?>
<table class="form-table">
    <tr class="user-phone-wrap">
        <th><label for="user_phone"><?php _e( 'Phone Number', 'rose_and_rabbit' ); ?></label></th>
        <td>
            <input type="text" name="user_phone" id="user_phone"
                value="<?php echo esc_attr( get_the_author_meta( 'user_phone', $user->ID ) ); ?>"
                class="regular-text" /><br />
            <span
                class="description"><?php _e( 'Please enter your phone number without country code.', 'rose_and_rabbit' ); ?></span>
        </td>
    </tr>
</table>
<?php
}
add_action( 'show_user_profile', 'add_custom_phone_field' );
add_action( 'edit_user_profile', 'add_custom_phone_field' );

// Save custom phone number field
function save_custom_phone_field( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    update_user_meta( $user_id, 'user_phone', sanitize_text_field( $_POST['user_phone'] ) );
}
add_action( 'personal_options_update', 'save_custom_phone_field' );
add_action( 'edit_user_profile_update', 'save_custom_phone_field' );
// Adjust position of email and phone number fields
function adjust_user_profile_fields_order() { ?>
<script>
jQuery(document).ready(function($) {
    $('#email').closest('tr').after($('#user_phone').closest('tr'));
});
</script>
<?php
}
add_action( 'admin_footer', 'adjust_user_profile_fields_order' );




// login user by phone number

add_action('wp_footer', 'login_by_phone_number_script', 100);
function login_by_phone_number_script(){ ?>
<script>
function loginCodeverify() {
    const input = document.querySelectorAll(".input");
    jQuery("#otp-verify").html('Verifying...');
    let codes = "";
    input.forEach((element) => {
        codes += element.value;
    })
    coderesult.confirm(codes).then(function(result) {
        let user = result.user;
        let phoneNumber = user.phoneNumber;
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo admin_url( 'admin-ajax.php' );?>',
            data: {
                action: "login_user_by_phone_number",
                phone_number: phoneNumber,
            },
            success: function(response) {
                if (response.status) {
                    jQuery("#successRegsiter").html("Verified!");
                    jQuery("#successRegsiter").show();
                    window.location.href = "<?php echo wc_get_page_permalink('myaccount');?>";
                    setTimeout(() => {
                        jQuery("#successRegsiter").html("");
                        jQuery("#successRegsiter").hide();
                        jQuery("#otp-verify").html('Submit');
                    }, 1000);
                }
            },
            error: function(error) {
                console.log('error :>> ', error);
            }
        });
    }).catch(function(error) {
        console.log('error :>> ', error);
        jQuery("#error").text(error.message);
        jQuery("#error").show();
        jQuery("#otp-verify").html('Submit');
    });
}
</script>
<?php }
function login_user_by_phone_number() {
    global $wpdb;
	$phone_number = str_replace("+91", "", $_POST['phone_number']);
    $user_meta_table = $wpdb->prefix . 'usermeta';
    $user_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT user_id
            FROM $user_meta_table
            WHERE meta_key = 'user_phone' AND meta_value = %s",
            $phone_number
        )
    );
    if ($user_id) {
		$user_avatar = get_the_author_meta( 'user_avatar', $user_id );
		if( empty( $user_avatar ) ){
			update_user_meta( $user_id, 'user_avatar', 'http://1.gravatar.com/avatar/af315902cac2c05d76a03f8cca1b90cf?s=32&f=y&r=g' );
		}
        $user = get_userdata($user_id);
		wp_set_auth_cookie($user_id);
		wp_set_current_user($user_id);
        echo json_encode( array( 'status' => true, 'user' => get_user_meta($user_id) ) );
		exit();
    } else{
		$password = wp_generate_password();
		$user_id = wp_insert_user([
			'user_login' => 'user_'.$phone_number, // Use email as the username
			'user_pass' => $password, // Generate a random password
			'role' => 'customer' // Set the user role as a customer
		]);
		update_user_meta($user_id, 'user_phone', $phone_number);
		$user_avatar = get_the_author_meta( 'user_avatar', $user_id );
		if( empty( $user_avatar ) ){
			update_user_meta( $user_id, 'user_avatar', 'http://1.gravatar.com/avatar/af315902cac2c05d76a03f8cca1b90cf?s=32&f=y&r=g' );
		}
		$user = get_userdata($user_id);
		wp_set_auth_cookie($user_id);
		wp_set_current_user($user_id);

        echo json_encode( array( 'status' => true, 'user' => get_user_meta($user_id) ) );
		exit();
	}
    // echo json_encode( array( 'status' => false, 'user' => $user ) );
	// exit();
}
add_action('wp_ajax_login_user_by_phone_number', 'login_user_by_phone_number');
add_action('wp_ajax_nopriv_login_user_by_phone_number', 'login_user_by_phone_number');


// $user = wp_authenticate( 'RRAdmin', 'WF3P@uUIYmBN6ktrb8' );
// print_r($user);


function read_csv(){
	// Specify the path to the CSV file
	$csvFilePath = 'http://localhost/rose_and_rabbit/wp-content/uploads/2023/07/email.csv';
	
	// Open the CSV file
	$csvFile = fopen($csvFilePath, 'r');

	// Check if the file was opened successfully
	if ($csvFile) {
		// Initialize an empty array to store the Excel data
		$excelData = array();
		$i = 0;
		// Read each line of the CSV file until the end
		while (($data = fgetcsv($csvFile)) !== false) {
			// Add the data as a row to the Excel data array
				$c = 0;
				foreach($data as $col) {
					$excelData[$i][] = $col;
					$c++;
				}
			$i++;
		}
		// Close the CSV file
		fclose($csvFile);

		// Display the Excel data
		return $excelData;
	} else {
		echo "Failed to open the CSV file.";
	}
}

function csv_list(){ 
	if (isset($_POST['submit'])) {
		if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
			echo '<ul class="woocommerce-message" role="alert">CSV file uploaded successfully and csv posts have been created.</ul>';
		}
		else{
			echo '<ul class="woocommerce-error" role="alert">Failed!</ul>';
		}
	}
	if (isset($_POST['delete'])) {
		if ( isset( $_POST['post_id'] ) ) {
			if ( $_SESSION['deleted'] ) {
				echo '<ul class="woocommerce-message" role="alert">Delete Successfully.</ul>';
			}else{
				echo '<ul class="woocommerce-error" role="alert">Already Deleted!</ul>';
			}
		}
		else{
			echo '<ul class="woocommerce-error" role="alert">Failed!</ul>';
		}
	} ?>

<form method="post" enctype="multipart/form-data" class="mb-3" style="display: flex;align-items: center;flex-wrap: wrap;">
    <input type="file" name="csv_file" accept=".csv" class="h-auto m-0 p-2 w-25">
    <button type="submit" name="submit" value="Upload CSV" class="vs-btn style7">Upload CSV</button>
</form>
<div class="table-responsive">
<table class="table table-bordered table-striped text-center">
	<thead>
		<tr>
			<th>Sno.</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Date</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
<?php 
$args = array(
    'post_type' => 'csv_data', // Replace with your custom post type slug
    'posts_per_page' => -1, // Retrieve all posts
);

$query = new WP_Query($args);

if ($query->have_posts()) { ?>
    <?php $i=1; while ($query->have_posts()) { $query->the_post(); ?>
		<tr>
			<td><?php echo $i++;?></td>
			<td><?php echo get_post_meta( get_the_ID(), '_fname_field', true ); ?></td>
			<td><?php echo get_post_meta( get_the_ID(), '_lname_field', true ); ?></td>
			<td><?php echo get_post_meta( get_the_ID(), '_email_field', true ); ?></td>
			<td><?php echo get_post_meta( get_the_ID(), '_date_field', true ); ?></td>
			<td>
				<form method="post">
					<input type="hidden" name="post_id" value="<?php echo esc_attr(get_the_ID())?>" />
					<button type="submit" name="delete" class="p-2 vs-btn">Delete</button>
				</form>
			</td>
		</tr>
   <?php }
    // Restore original post data
    wp_reset_postdata(); ?>

<?php } else {
	// No posts found
	echo "<tr><td colspan='6' class=''>No Data Found!</td></tr>";
}
?>
	</tbody>
	</table>
</div>
<!-- csv data post type data -->

<div class="table-responsive mt-5 d-none">
<table class="table table-bordered table-striped">
<?php
$csv_data = read_csv();
$arr = array();
$k = 0;
foreach( $csv_data as $data){
    if (is_array($data)) {
        $j = 0;
        foreach ($data as $value) {
            $savekey = explode(";", $data[0]);
            $savedata = explode(";", $value);
                foreach( $savedata as $col ){
                $arr[$j] = ($savekey[$j]) ? $savekey[$j] : null;
                $j++;
            }
        }
    } ?>
<tr>
<?php
foreach($arr as $val){
    if($k == 0) { ?>
    <th><?php echo $val; ?></th>
    <?php } else{ ?>
    <td><?php echo $val; ?></td>
    <?php } ?>
<?php } ?>
</tr>
<?php
$k++; }
?>
</table>
</div>
<?php }
add_shortcode('csv_list', 'csv_list');


// Example csv post type registration
function custom_post_type_csv_data() {
    $labels = array(
        'name'               => _x( 'CSV Data', 'post type general name', 'textdomain' ),
        'singular_name'      => _x( 'CSV Data', 'post type singular name', 'textdomain' ),
        'menu_name'          => _x( 'CSV Data', 'admin menu', 'textdomain' ),
        'name_admin_bar'     => _x( 'CSV Data', 'add new on admin bar', 'textdomain' ),
        'add_new'            => _x( 'Add New', 'CSV Data', 'textdomain' ),
        'add_new_item'       => __( 'Add New CSV Data', 'textdomain' ),
        'new_item'           => __( 'New CSV Data', 'textdomain' ),
        'edit_item'          => __( 'Edit CSV Data', 'textdomain' ),
        'view_item'          => __( 'View CSV Data', 'textdomain' ),
        'all_items'          => __( 'All CSV Data', 'textdomain' ),
        'search_items'       => __( 'Search CSV Data', 'textdomain' ),
        'parent_item_colon'  => __( 'Parent CSV Data:', 'textdomain' ),
        'not_found'          => __( 'No CSV Data found.', 'textdomain' ),
        'not_found_in_trash' => __( 'No CSV Data found in Trash.', 'textdomain' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        // 'publicly_queryable' => true,
        // 'show_ui'            => true,
        // 'show_in_menu'       => true,
        // 'query_var'          => true,
        // // 'rewrite'            => array( 'slug' => 'csv-data' ),
        // 'capability_type'    => 'post',
        // 'has_archive'        => true,
        'hierarchical'       => true,
        // 'menu_position'      => null,
        // 'menu_icon' => 'dashicons-id',
        'supports' => array('title', 'editor'),
    );

    register_post_type( 'csv_data', $args );
}
add_action( 'init', 'custom_post_type_csv_data' );


function add_csv_meta_boxes() {
    add_meta_box(
		'csv_data_meta_box', 
		'CSV Data', 
		'display_csv_meta_box', 
		'csv_data', 
		'normal', 
		'high'
	);
}
add_action('add_meta_boxes', 'add_csv_meta_boxes');
function display_csv_meta_box($post) {
    // Retrieve existing meta field values if any
    $email_field_value = get_post_meta($post->ID, '_email_field', true);
    $fname_field_value = get_post_meta($post->ID, '_fname_field', true);
    $lname_field_value = get_post_meta($post->ID, '_lname_field', true);
    $date_field_value = get_post_meta($post->ID, '_date_field', true);
	?>
	<label for="fname_field" style="width: 8%;display: inline-block;"><strong>First Name:</strong></label>
    <input type="text" id="fname_field" name="fname_field" value="<?php echo esc_attr($fname_field_value); ?>" style="width: 25%;">
	<br>
	<br>
	<label for="lname_field" style="width: 8%;display: inline-block;"><strong>Last Name:</strong></label>
    <input type="text" id="lname_field" name="lname_field" value="<?php echo esc_attr($lname_field_value); ?>" style="width: 25%;">
	<br>
	<br>
    <label for="email_field" style="width: 8%;display: inline-block;"><strong>Email:</strong></label>
    <input type="text" id="email_field" name="email_field" value="<?php echo esc_attr($email_field_value); ?>" style="width: 25%;">
	<br>
	<br>
    <label for="date_field" style="width: 8%;display: inline-block;"><strong>Date:</strong></label>
    <input type="date" id="date_field" name="date_field" value="<?php echo esc_attr( date('Y-m-d', strtotime($date_field_value)) ); ?>" style="width: 25%;">
    <?php
}

function add_custom_image_meta_box() {
    add_meta_box(
        'custom_image_meta_box', // Unique ID
        'Custom Image Upload', // Meta Box title
        'render_custom_image_meta_box', // Callback function to render the meta box content
        'csv_data', // Post type where the meta box will be shown
        'side', // Context (normal, side, advanced)
        'high' // Priority (high, default, low, core)
    );
}
add_action('add_meta_boxes', 'add_custom_image_meta_box');

function render_custom_image_meta_box($post) {
    // Retrieve existing meta field values if any
	$csv_file_url = get_post_meta( $post->ID, '_csv_file_url', true );
	?>
	<label for="custom-image-button" style="width: 25%;display: inline-block;"><strong>Upload:</strong></label>
	<input type="hidden" id="custom-image" name="csv_file_url" value="<?php echo esc_attr($csv_file_url); ?>" size="60">
    <input type="button" class="button button-secondary" value="Upload File" id="custom-image-button">
	<div id="custom-image-preview">
		<?php if( !empty( $csv_file_url ) ) : ?>
		<img src="<?php echo esc_attr($csv_file_url); ?>" style="max-width: 100%;max-height: 100%;object-fit: cover;border-radius: 8px;margin-top: 12px;" />
		<?php endif; ?>
	</div>
	<script>
        jQuery(document).ready(function ($) {
            // Media uploader
            var customImageUploader;
            $('#custom-image-button').click(function (e) {
                e.preventDefault();
                if (customImageUploader) {
                    customImageUploader.open();
                    return;
                }
                customImageUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });
                customImageUploader.on('select', function () {
                    var attachment = customImageUploader.state().get('selection').first().toJSON();
                    $('#custom-image').val(attachment.url);
                    $('#custom-image-preview').html('<img src="'+attachment.url+'" style="max-width:100%;max-height:100%;object-fit:cover;border-radius:8px;margin-top:12px;" />');
                    // $('#custom-image-preview').attr('src', attachment.url);
                });
                customImageUploader.open();
            });
        });
    </script>
	<?php
}

function save_csv_meta_data($post_id) {
	// print_r($post_id);
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

	if (isset($_POST['fname_field'])) {
		$fname_field_value = sanitize_text_field($_POST['fname_field']);
        update_post_meta($post_id, '_fname_field', $fname_field_value);
    }
	if (isset($_POST['lname_field'])) {
		$lname_field_value = sanitize_text_field($_POST['lname_field']);
        update_post_meta($post_id, '_lname_field', $lname_field_value);
    }
	if (isset($_POST['email_field'])) {
		$email_field_value = sanitize_text_field($_POST['email_field']);
		update_post_meta($post_id, '_email_field', $email_field_value);
	}
	if (isset($_POST['date_field'])) {
		$date_field_value = sanitize_text_field($_POST['date_field']);
		update_post_meta($post_id, '_date_field', $date_field_value);
	}
	if (isset($_POST['csv_file_url'])) {
		$csv_file_url = sanitize_text_field($_POST['csv_file_url']);
		update_post_meta($post_id, '_csv_file_url', $csv_file_url);
	}
}
add_action('save_post', 'save_csv_meta_data');

function process_csv_upload() {
    if (isset( $_POST['submit'] )) {
		if( isset( $_FILES['csv_file'] ) ){
			if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
				// Get the uploaded file details
				$csvFile = $_FILES['csv_file']['tmp_name'];
				$handle = fopen($csvFile, 'r');
	
				$excelData = array();
				$finalData = array();
				$i = 0;
	
				// Check if the file was opened successfully
				if ($handle) {
					// Loop through each row of the CSV file
					while (($data = fgetcsv($handle)) !== false) {
						// Create a new csv post with CSV data
						$c = 0;
						foreach($data as $col) {
							$excelData[$c] = $col;
							$c++;
						}
						foreach($excelData as $val){
							if( $i > 0 ){
								$savekey = explode(";", $val);
								$finalData['post_title'] = $savekey[2];
								$finalData['post_type'] = 'csv_data';
								$finalData['post_status'] = 'publish';
								$post_id = wp_insert_post($finalData);
								update_post_meta($post_id, '_fname_field', $savekey[2]);
								update_post_meta($post_id, '_lname_field', $savekey[3]);
								update_post_meta($post_id, '_email_field', $savekey[0]);
								update_post_meta($post_id, '_date_field', date('Y-m-d'));
								
							}
						}
						$i++;
					}
					// Close the CSV file
					fclose($handle);
				}
			}
		}
    }
}
add_action('init', 'process_csv_upload');

function csv_data_delete(){
	if (isset($_POST['delete'])) {
		if( isset( $_POST['post_id'] ) ){
			$delete = wp_delete_post( $_POST['post_id'], true );
			if( $delete ){
				$_SESSION['deleted'] = true;
			}else{
				$_SESSION['deleted'] = false;
			}
		}
	}
}
add_action('init', 'csv_data_delete');






// Add a filter to modify the avatar output
add_filter('get_avatar', 'custom_avatar_title', 10, 5);
function custom_avatar_title($avatar, $id_or_email, $size, $default, $alt) {
    // Get the user ID from the provided argument
    $user_id = '';
    if (is_numeric($id_or_email)) {
        $user_id = $id_or_email;
    } elseif (is_string($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
        if ($user) {
            $user_id = $user->ID;
        }
    }
    // Get the user's display name
    $user_display_name = '';
    if ($user_id) {
        $user_display_name = get_the_author_meta('display_name', $user_id);
    }
    // Add the title attribute to the avatar HTML
    $avatar = str_replace('<img', '<img title="' . esc_attr($user_display_name) . '"', $avatar);
    return $avatar;
}




// password reset link send to user via mail 
add_filter( 'retrieve_password_message', 'my_retrieve_password_message', 10, 4 );
function my_retrieve_password_message( $message, $key, $user_login, $user_data ) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	$message = '<div class="main-box" style="border: 1px solid #dddddd;border-radius: 5px; width: 650px;float: none;text-align: center; margin:40px 10px 0px 6px; overflow: hidden;">
	<div class="mb-header" style="background:#f2a5a2; padding: 20px 30px;">
	<a href="'.network_site_url().'"><img src="'.$image[0].'" width="200" height="auto"></a>
	</div>
	<div class="mb-content" style="background:#fff; padding: 25px;text-align: center;">
	<table cellpadding="5" border="0" cellspacing="0" width="100%">
	<tr><td> <b>Username : </b>'.$user_login.'</td></tr>';
	// <tr><th valign="top">Email :</th><td>'.$user_data->user_email.'</td></tr>
	$message .= '<tr><td colspan="1">&nbsp;</td></tr>
	<tr><td colspan="1"><a href="'.network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ).'" style="background:#f2a5a2; padding: 10px 20px;display:inline-block;font-size:16px;">Reset</a></td></tr>
	<tr><td colspan="1">&nbsp;</td></tr>
	<tr><td colspan="1">This e-mail was sent from '.$site_name.'</td></tr>
	</table>
	</div>
	</div>';
	return $message;
}


// function se13911452_set_avatar_url($avatar_url, $user_id) {
// 	global $wpdb;
// 	$file = upload_product_image($avatar_url);
// 	$wp_filetype = wp_check_filetype($file['file']);
// 	$attachment = array(
// 		'guid' => $file['url'],
// 		'post_mime_type' => $wp_filetype['type'],
// 		'post_title' => preg_replace('/\.[^.]+$/', '', basename($file['file'])),
// 		'post_content' => '',
// 		'post_status' => 'inherit'
// 	);
// 	$attach_id = wp_insert_attachment($attachment, $file['file']);
// 	$attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
// 	wp_update_attachment_metadata($attach_id, $attach_data);
// 	update_user_meta($user_id, $wpdb->get_blog_prefix() . 'user_avatar', $attach_id);
// }

/* 
 * Add custom user profile information
 *
 */ 

 add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
 add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
 
 function my_show_extra_profile_fields( $user ) { ?>
 <table>
	<tr>
		<th><label for="user_avatar"></label></th>
		<td>
			<!-- <img id="preview-img" src="<?php echo esc_attr( get_the_author_meta( 'user_avatar', $user->ID ) ); ?>" style="height:50px;"><br> -->
			<input type="text" name="user_avatar" id="user_avatar" value="<?php echo esc_attr( get_the_author_meta( 'user_avatar', $user->ID ) ); ?>" class="regular-text" />
			<input type='button' class="button-primary" value="Upload Image" id="uploadimage"/><br />
			<span class="description">Please upload your image for your profile.</span>
		</td>
	</tr>
	</table>
 <?php }
 
 /* 
  * Script for saving profile image
  *
  */
 
 add_action('admin_head','my_profile_upload_js');
 wp_enqueue_script('media-upload');
 wp_enqueue_script('thickbox');
 wp_enqueue_style('thickbox'); 
 
 function my_profile_upload_js() { ?>
	 <script type="text/javascript">
		 jQuery(document).ready(function() {
			 jQuery(document).find("input[id^='uploadimage']").on('click', function(){
				 //var num = this.id.split('-')[1];
				 formfield = jQuery('#user_avatar').attr('name');
				 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	  
				 window.send_to_editor = function(html) {
					 imgurl = jQuery('img', html).attr('src');
					 jQuery('.avatar').attr('src', imgurl);
					 jQuery('#user_avatar').val(imgurl);
					 tb_remove();
				 }
				 return false;
			 });
		 });
	 </script>
 <?php }
 
 function user_profile_image_fields_order() { ?>
	<script>
	jQuery(document).ready(function($) {
		$('.user-profile-picture').after($('#user_avatar').closest('tr'));
	});
	</script>
	<?php
}
add_action( 'admin_footer', 'user_profile_image_fields_order' );
	
	
 /*
  * Save custom user profile data
  *
  */
 
 add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
 add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );
 function my_save_extra_profile_fields( $user_id ) {
	global $wpdb;
	 if ( !current_user_can( 'edit_user', $user_id ) )
		 return false;
	 /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	 update_user_meta( $user_id, 'user_avatar', $_POST['user_avatar'] );	 
 }


add_filter('get_avatar_data', 'ow_change_avatar', 100, 2);
function ow_change_avatar($args, $user_data) {
		if(is_object($user_data)){
			$user_id = $user_data->user_id;
		} else{
			$user_id = $user_data;
		}
		if($user_id){
			$author_pic = get_user_meta($user_id, 'user_avatar', true);
			// echo $author_pic;
			if(  $author_pic ) {
				$args['url'] = $author_pic;
			} else {
				// $args['url'] = 'tttt';
			}
		} else {
			// $args['url'] = 'ttt';
		}
	return $args;
} 


add_filter( 'get_avatar' , 'add_custom_avatar' , 1 , 6 );
function add_custom_avatar( $avatar, $id_or_email, $size, $default, $alt, $args ) {
	// print_r($args);
    $user = false;
    if ( is_numeric( $id_or_email ) ) {
        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );
    } elseif ( is_object( $id_or_email ) ) {
        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }
    } else {
        $user = get_user_by( 'email', $id_or_email );   
    }
    if ( $user && is_object( $user ) ) {
		$avatar = sprintf( '%s', get_user_meta($user->data->ID, 'user_avatar', true) );
		$classes = sprintf( '%s', $args['class']);
		$avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo $classes' height='{$size}' width='{$size}' />";
    }
    return $avatar;
}