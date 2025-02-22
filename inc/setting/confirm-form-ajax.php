<?php
add_action('wp_ajax_sjw_save_confirm_form_fields', 'handle_sjw_confirm_form'); // for logged-in users
add_action('wp_ajax_nopriv_sjw_save_confirm_form_fields', 'handle_sjw_confirm_form'); // for non-logged-in users

function handle_sjw_confirm_form() {
    // Check if nonce is valid for security purposes
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'sjw_nonce_action')) {
        
        // Retrieve form data sent via AJAX
        $fields = isset($_POST['fields']) ? json_decode(stripslashes($_POST['fields']), true) : [];

        // Example: Logging the fields received via AJAX (for debugging)
        error_log(print_r($fields, true)); // This logs the data to the error log file

        // Process the data (e.g., save it to the database, send an email, etc.)
        // Here you can handle form fields, perform validation, or save the data
        update_option('sjw_confirm_form_fields', $fields);
        
        // Assuming successful processing, send a response back to the client
        wp_send_json_success([
            'message' => 'Form data saved successfully!',
            'fields_received' => $fields // Optionally send the received fields back as part of the response
        ]);
    } else {
        // If nonce is invalid, send an error response
        wp_send_json_error([
            'message' => 'Nonce verification failed. Please try again.'
        ]);
    }

    wp_die(); // Terminate AJAX request
}
