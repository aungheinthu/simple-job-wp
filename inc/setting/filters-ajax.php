<?php
add_action('wp_ajax_sjw_save_filters_form_fields', 'handle_sjw_filters_form'); // For logged-in users
add_action('wp_ajax_nopriv_sjw_save_filters_form_fields', 'handle_sjw_filters_form'); // For non-logged-in users

function handle_sjw_filters_form() {
    // Check if nonce is valid for security purposes
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sjw_nonce_action')) {
        wp_send_json_error([
            'message' => 'Nonce verification failed. Please try again.'
        ]);
    }

    // Define the filter options
    $filter_options = [
        'sjw_enable_category_filter',
        'sjw_enable_type_filter',
        'sjw_enable_location_filter',
        'sjw_enable_search_bar'
    ];

    // Loop through each filter option and save its value
    foreach ($filter_options as $option) {
        $value = isset($_POST[$option]) && $_POST[$option] === 'yes' ? 'yes' : 'no';
        update_option($option, $value);
    }

    // Return a success response
    wp_send_json_success([
        'message' => 'Settings saved successfully.'
    ]);

    wp_die(); // Terminate AJAX request
}
