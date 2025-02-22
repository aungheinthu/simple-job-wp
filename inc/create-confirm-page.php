<?php
function create_confirm_page() {
    $page_title = 'Confirm Page';

    $page = get_posts([
        'post_type'   => 'page',
        'title'       => $page_title,
        'post_status' => 'any', // Include all statuses
        'numberposts' => 1,     // Limit to one result
    ]);

    if (empty($page)) {
        // Page doesn't exist; create it
        $page_id = wp_insert_post([
            'post_title'   => $page_title,
            'post_content' => 'This is the content of the confirm page.',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ]);

        if ($page_id) {
            // Add a meta value to identify this page later
            update_post_meta($page_id, 'sjw_confirm_page', true);
        }
    }
}
add_action('init', 'create_confirm_page');

function hide_confirm_page_from_admin($query) {
    if (is_admin() && $query->is_main_query() && $query->get('post_type') === 'page') {
        $meta_query = $query->get('meta_query');

        // Ensure $meta_query is an array
        if (!is_array($meta_query)) {
            $meta_query = [];
        }

        $meta_query[] = [
            'key'     => 'sjw_confirm_page',
            'compare' => 'NOT EXISTS',
        ];

        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'hide_confirm_page_from_admin');