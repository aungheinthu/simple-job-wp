<?php
add_action('wp_ajax_sjw_save_general_form_fields', 'handle_sjw_general_form'); // for logged-in users
add_action('wp_ajax_nopriv_sjw_save_general_form_fields', 'handle_sjw_general_form'); // for non-logged-in users

function handle_sjw_general_form() {
    // Check if nonce is valid for security purposes
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'sjw_nonce_action')) {
        
        // Check if the required fields are submitted
        if (!isset($_POST['sjw_posts_per_page'], $_POST['sjw_archive_page_id'])) {
            wp_send_json_error([
                'message' => 'Required fields are missing.',
            ]);
        }

        // Sanitize and save the "Jobs Posts Per Page" field
        $posts_per_page = absint($_POST['sjw_posts_per_page']);
        if ($posts_per_page < 1) {
            $posts_per_page = 12; // Default value
        }
        update_option('sjw_posts_per_page', $posts_per_page);

        // Sanitize and save the "Jobs Archive Page" field
        $archive_page_id = absint($_POST['sjw_archive_page_id']);
        update_option('sjw_archive_page_id', $archive_page_id);

        // $sjw_hr_email = sanitize_email($_POST['sjw_hr_email']);
        // update_option('sjw_hr_email', $sjw_hr_email);

        // HR Email
        if (isset($_POST['sjw_hr_email'])) {
            $sjw_hr_email = sanitize_email($_POST['sjw_hr_email']);
            update_option('sjw_hr_email', $sjw_hr_email);
        }

        // Theme Color
        if (isset($_POST['sjw_theme_color']) && !empty($_POST['sjw_theme_color'])) {
            $sjw_theme_color = sanitize_hex_color($_POST['sjw_theme_color']);
            update_option('sjw_theme_color', $sjw_theme_color);
        }

        // Title Color
        if (isset($_POST['sjw_title_color']) && !empty($_POST['sjw_title_color'])) {
            $sjw_title_color = sanitize_hex_color($_POST['sjw_title_color']);
            update_option('sjw_title_color', $sjw_title_color);
        }

        // Text Color
        if (isset($_POST['sjw_text_color']) && !empty($_POST['sjw_text_color'])) {
            $sjw_text_color = sanitize_hex_color($_POST['sjw_text_color']);
            update_option('sjw_text_color', $sjw_text_color);
        }

        if (isset($_POST['sjw_file_extensions']) && is_array($_POST['sjw_file_extensions'])) {
            $selected_extensions = array_map('sanitize_text_field', $_POST['sjw_file_extensions']);
            update_option('sjw_file_extensions', $selected_extensions);
        } else {
            // If no checkboxes are selected, save an empty array
            update_option('sjw_file_extensions', []);
        }        

        // Return a success response
        wp_send_json_success([
            'message' => 'Settings saved successfully.',
        ]);
    } else {
        // If nonce is invalid, send an error response
        wp_send_json_error([
            'message' => 'Nonce verification failed. Please try again.'
        ]);
    }

    wp_die(); // Terminate AJAX request
}
