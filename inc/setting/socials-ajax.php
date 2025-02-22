<?php
// Handle the AJAX request
function sjw_save_socials() {
    // Check the nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sjw_nonce_action')) {
        wp_send_json_error(array('message' => 'Nonce verification failed.'));
    }

    // Retrieve the selected socials and the new order from the AJAX request
    $selected_socials = isset($_POST['socials']) ? $_POST['socials'] : [];
    $sorted_socials = isset($_POST['sjw_sorted_socials']) ? $_POST['sjw_sorted_socials'] : '';

    // Save the selected socials to the options table
    update_option('sjw_selected_socials', $selected_socials);

    // Send a success response
    wp_send_json_success(array('message' => 'Social platforms saved successfully.'));
}
add_action('wp_ajax_sjw_save_socials', 'sjw_save_socials');