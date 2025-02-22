<?php
/**
 * Plugin Name: Simple Job WP
 * Description: A plugin to manage job postings, applications, and settings.
 * Version: 1.0.0
 * Author: The Digital Spacee
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if ( ! function_exists( 'debug_logger' ) ) {
    function debug_logger( $filename, $data ) {
        $file = plugin_dir_path( __FILE__ ) . 'logs/' . $filename . '_debug.log';
        date_default_timezone_set( 'Asia/Singapore' );
        $currentTimestamp = date( 'Y-m-d H:i:s' );
        if ( ! file_exists( $file ) ) {
            $fp = fopen( $file, "wb" );
            fwrite( $fp, '[' . $currentTimestamp . ']' . ': ' . print_r( $data, true ) . "\n" );
            fclose( $fp );
        } else {
            file_put_contents( $file, '[' . $currentTimestamp . ']' . ': ' . print_r( $data, true ) . "\n", FILE_APPEND | LOCK_EX );
        }
    }
}

require_once plugin_dir_path( __FILE__ ) . '/inc/enqueue-scripts.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/create-confirm-page.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/register-posts.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/prefix-data.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/socials-ajax.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/filters-ajax.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/application-form-ajax.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/confirm-form-ajax.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/application-messages-ajax.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/confirm-messages-ajax.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/application-export-fields-ajax.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/form-fields-value-shortcode.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/common-email-template-fields.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/setting/setting-general-ajax.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/job-filter-ajax.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/render-form-shortcode.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/application-export.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/templates/send-common-email.php';

// Load Custom Templates for Job Post and Pages
function sjw_custom_load_job_templates($template) {
    // Load the single job template
    if (is_singular('sjw_jobs')) {
        $custom_single_template = plugin_dir_path(__FILE__) . '/inc/templates/single-jobs.php';

        if (file_exists($custom_single_template)) {
            return $custom_single_template;
        }
    }

    // Retrieve the selected archive page ID from the settings
    $archive_page_id = get_option('sjw_archive_page_id', '');

    // Path to the custom archive template
    $custom_archive_template = plugin_dir_path(__FILE__) . '/inc/templates/archive-jobs.php';

    // Check if the current page matches the selected archive page ID
    if (!empty($archive_page_id) && is_page($archive_page_id)) {
        if (file_exists($custom_archive_template)) {
            return $custom_archive_template;
        }
    }
	
    // Check if it is the default archive for the "jobs" post type
    else if (is_archive('jobs')) {
        if (file_exists($custom_archive_template)) {
            return $custom_archive_template;
        }
    }

    // Load the confirm page template
    if (is_page() && get_post_meta(get_the_ID(), 'sjw_confirm_page', true)) {
        $custom_confirm_template = plugin_dir_path(__FILE__) . '/inc/templates/confirm-page.php';

        if (file_exists($custom_confirm_template)) {
            return $custom_confirm_template;
        }
    }

    // Return the default template if no custom template is found
    return $template;
}
add_filter('template_include', 'sjw_custom_load_job_templates');

/**
 * Add theme, title, and text colors as CSS variables to the header.
 */
function render_sjw_color_variables() {
    // Get the options and set defaults if not defined
    $theme_color = get_option('sjw_theme_color', ''); // Default: white
    $title_color = get_option('sjw_title_color', ''); // Default: black
    $text_color = get_option('sjw_text_color', '');   // Default: dark gray

    // Sanitize the values
    $theme_color = sanitize_hex_color($theme_color);
    $title_color = sanitize_hex_color($title_color);
    $text_color = sanitize_hex_color($text_color);

    // Output the CSS variables
    echo "<style>
        :root {
            --theme-color: {$theme_color};
            --title-color: {$title_color};
            --text-color: {$text_color};
        }
    </style>";
}
add_action('wp_head', 'render_sjw_color_variables');

add_action('admin_menu', function() {
    if (current_user_can('recruiter')) { // Apply only to Recruiters
        global $menu;

        // Allowed menus (only "Jobs" post type menu)
        $allowed_menus = array('edit.php?post_type=sjw_jobs');

        foreach ($menu as $key => $item) {
            if (!in_array($item[2], $allowed_menus)) {
                remove_menu_page($item[2]); // Remove all other menus
            }
        }
    }
});


