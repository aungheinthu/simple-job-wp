<?php
// Register AJAX actions for saving confirm messages
add_action('wp_ajax_sjw_save_confirm_messages', 'handle_sjw_save_confirm_messages');
add_action('wp_ajax_nopriv_sjw_save_confirm_messages', 'handle_sjw_save_confirm_messages');

function handle_sjw_save_confirm_messages() {
    // Verify the nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sjw_nonce_action')) {
        wp_send_json_error([
            'message' => 'Nonce verification failed. Please try again.'
        ]);
    }

    // Sanitize and save the "Confirm Message" field (WYSIWYG)
    if (isset($_POST['sjw_confirm_thank_you_message'])) {
        $confirm_message = wp_kses_post(wp_unslash($_POST['sjw_confirm_thank_you_message']));
        update_option('sjw_confirm_thank_you_message', $confirm_message);
    }

    // Sanitize and save the "Redirect Page" field
    if (isset($_POST['sjw_confirm_redirect_page'])) {
        $redirect_page_id = absint($_POST['sjw_confirm_redirect_page']);
        update_option('sjw_confirm_redirect_page', $redirect_page_id);
    }

    // Return a success response
    wp_send_json_success([
        'message' => 'Confirm message settings saved successfully.',
    ]);

    wp_die(); // Terminate AJAX request
}
