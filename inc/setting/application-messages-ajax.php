<?php
// Register AJAX actions for saving application messages
add_action('wp_ajax_sjw_save_application_messages', 'handle_sjw_save_application_messages');
add_action('wp_ajax_nopriv_sjw_save_application_messages', 'handle_sjw_save_application_messages');

function handle_sjw_save_application_messages() {
    // Verify the nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sjw_nonce_action')) {
        wp_send_json_error([
            'message' => 'Nonce verification failed. Please try again.'
        ]);
    }

    // Sanitize and save the "Thank You Message" field (WYSIWYG)
    if (isset($_POST['sjw_application_thank_you_message'])) {
        $thank_you_message = wp_kses_post(wp_unslash($_POST['sjw_application_thank_you_message']));
        update_option('sjw_application_thank_you_message', $thank_you_message);
    }

    // Sanitize and save the "Redirect Page" field
    if (isset($_POST['sjw_application_redirect_page'])) {
        $archive_page_id = absint($_POST['sjw_application_redirect_page']);
        update_option('sjw_application_redirect_page', $archive_page_id);
    }

    // Return a success response
    wp_send_json_success([
        'message' => 'Settings saved successfully.',
    ]);

    wp_die(); // Terminate AJAX request
}
