<?php
function current_date_shortcode($atts) {
    // Default format
    $atts = shortcode_atts(array(
        'format' => 'Y-m-d', // Default format if none is provided
    ), $atts, 'currentdate');

    // Get the current date with the specified format
    return date($atts['format']);
}
add_shortcode('currentdate', 'current_date_shortcode');

// Register Shortcodes Dynamically
function register_dynamic_shortcodes() {
    // Retrieve all saved fields from the database
    $saved_fields = get_option('sjw_application_form_fields', []);
	$confirm_form_fields = get_option('sjw_confirm_form_fields', []);

    if (!empty($confirm_form_fields)) {
        $saved_fields = array_merge($saved_fields, $confirm_form_fields);
    }

//     if (empty($saved_fields)) {
//         return '<p>No fields available to display.</p>';
//     }

    if (!empty($saved_fields)) {
        foreach ($saved_fields as $field) {
            if (!empty($field['field_id'])) {
                $field_id = $field['field_id'];

                // Register the shortcode with an anonymous function
                add_shortcode($field_id, function ($atts, $content = null) use ($field_id) {
                    $post_id = get_the_ID(); // Get the current post ID
                    $meta_value = get_post_meta($post_id, $field_id, true);

                    // Return the meta value or a default message if not found
                    return !empty($meta_value) ? esc_html($meta_value) : __('Field not found.', 'text-domain');
                });
            }
        }
    }
}
add_action('init', 'register_dynamic_shortcodes');

// Shortcode: Display Candidate First Name (First Word Only)
function display_candidate_first_name() {
    $post_id = get_the_ID(); // Get the current post ID
    $meta_value = get_post_meta($post_id, 'sjw_candidate_name', true);

    // Extract the first word from the meta value
    $first_word = '';
    if (!empty($meta_value)) {
        $words = explode(' ', trim($meta_value)); // Split the string into words
        $first_word = $words[0]; // Get the first word
    }

    // Return the first word or a default message if not found
    return !empty($first_word) ? esc_html($first_word) : __('Field not found.', 'text-domain');
}
add_shortcode('sjw_candidate_first_name', 'display_candidate_first_name');

// Shortcode: Display Company Name
function display_company_name() {
    $job_id = get_post_meta(get_the_ID(), 'sjw_job_id', true);

    if (empty($job_id)) {
        return '<p style="color:red;">Error: No job ID found for this context.</p>';
    }

    $company_name = get_post_meta($job_id, 'sjw_job_company_name', true);

    if (empty($company_name)) {
        return '<p style="color:red;">Error: No company name found for the specified job.</p>';
    }

    return esc_html($company_name);
}
add_shortcode('company_name', 'display_company_name');

// Shortcode: Display Job Full Description
function display_job_full_description() {
    // Get the Job ID from the current post's meta
    $job_id = get_post_meta(get_the_ID(), 'sjw_job_id', true);

    // Check if Job ID exists
    if (empty($job_id)) {
        return '<p style="color:red;">Error: No job ID found for this context.</p>';
    }

    // Retrieve the full job description using the Job ID
    $job_description = get_post_meta($job_id, 'sjw_job_full_description', true);

    // Check if the job description exists
    if (empty($job_description)) {
        return '<p style="color:red;">Error: No job description found for the specified job.</p>';
    }

    // Return the job description (HTML allowed for formatting)
    return wp_kses_post($job_description);
}
add_shortcode('job_full_description', 'display_job_full_description');

// Shortcode: Display Job Short Description
function display_job_short_description() {
    // Get the Job ID from the current post's meta
    $job_id = get_post_meta(get_the_ID(), 'sjw_job_id', true);

    // Check if Job ID exists
    if (empty($job_id)) {
        return '<p style="color:red;">Error: No job ID found for this context.</p>';
    }

    // Retrieve the short job description using the Job ID
    $short_description = get_post_meta($job_id, 'sjw_job_short_description', true);

    // Check if the short description exists
    if (empty($short_description)) {
        return '<p style="color:red;">Error: No short description found for the specified job.</p>';
    }

    // Return the short description (HTML allowed for formatting)
    return wp_kses_post($short_description);
}
add_shortcode('job_short_description', 'display_job_short_description');


// Shortcode: Display Job Title
function display_job_title() {
    $job_id = get_post_meta(get_the_ID(), 'sjw_job_id', true);

    if (empty($job_id)) {
        return '<p style="color:red;">Error: No job ID found for this context.</p>';
    }

    $job_title = get_the_title($job_id);

    if (!$job_title) {
        return '<p style="color:red;">Error: Job not found or invalid post type.</p>';
    }

    return esc_html($job_title);
}
add_shortcode('job_title', 'display_job_title');

// Shortcode: Display Job URL
function display_job_url() {
    // Get the Job ID from the current post's meta
    $job_id = get_post_meta(get_the_ID(), 'sjw_job_id', true);

    // Check if Job ID exists
    if (empty($job_id)) {
        return '<p style="color:red;">Error: No job ID found for this context.</p>';
    }

    // Retrieve the URL of the job post
    $job_url = get_permalink($job_id);

    // Check if the URL is valid
    if (!$job_url) {
        return '<p style="color:red;">Error: Job URL not found or invalid post type.</p>';
    }

    // Return the URL as a clickable link
    return '<a href="' . esc_url($job_url) . '" target="_blank">' . esc_html($job_url) . '</a>';
}
add_shortcode('job_url', 'display_job_url');


// Shortcode: Display Site Admin Email
function display_admin_email() {
    $admin_email = get_option('admin_email');
	// $admin_email = 'hr@recruit-inc.com';

    if (!$admin_email) {
        return '<p style="color:red;">Error: Admin email not configured.</p>';
    }

    return esc_html($admin_email);
}
add_shortcode('admin_email', 'display_admin_email');

// Shortcode: Display HR Email
function display_hr_email() {
    $hr_email = get_option('sjw_hr_email', 'hr@recruit-inc.com'); // Get the HR email, default to 'hr@recruit-inc.com' if not set

    if (!$hr_email) {
        return '<p style="color:red;">Error: HR email not configured.</p>';
    }

    return esc_html($hr_email);
}
add_shortcode('hr_email', 'display_hr_email');


// Shortcode: Generate Confirmation Link
function generate_confirmation_link($atts) {
    $atts = shortcode_atts(
        ['content' => 'Confirm Job Application'], // Default content
        $atts,
        'confirm_link'
    );

    $page_query = new WP_Query([
        'post_type'      => 'page',
        'meta_query'     => [
            [
                'key'     => 'sjw_confirm_page',
                'compare' => 'EXISTS',
            ]
        ],
        'posts_per_page' => 1,
    ]);

    if ($page_query->have_posts()) {
        $page_id = $page_query->posts[0]->ID;
        $confirm_link = add_query_arg(
            ['pid' => get_the_ID()],
            get_the_permalink($page_id)
        );

        return '<a href="' . esc_url($confirm_link) . '" target="_blank">' . esc_html($atts['content']) . '</a>';
    } else {
        return '<p style="color:red;">Error: No page with the meta key "_confirm_page" found.</p>';
    }
}
add_shortcode('confirm_link', 'generate_confirmation_link');

// Render HTML for Shortcode List
function render_shortcode_html() {
    $saved_fields = get_option('sjw_application_form_fields', []);
    $confirm_form_fields = get_option('sjw_confirm_form_fields', []);

    if (!empty($confirm_form_fields)) {
        $saved_fields = array_merge($saved_fields, $confirm_form_fields);
    }

    if (empty($saved_fields)) {
        return '<p>No fields available to display.</p>';
    }

    $html = '<div class="shortcode-list-wrap">';

    $shortcodes = [
        '[job_title]' => '[job_title]',
		'[job_url]' => '[job_url]',
        '[company_name]' => '[company_name]',
        '[admin_email]' => '[admin_email]',
        '[confirm_link content="Test"]' => '[confirm_link_content]',
		'[sjw_candidate_first_name]' => '[sjw_candidate_first_name]',
		'[job_full_description]' => '[job_full_description]',
		'[job_short_description]' => '[job_short_description]',
    ];

    // Retrieve the HR email from the options
    $hr_email = get_option('sjw_hr_email');

    // Only add the HR email shortcode if it's set
    if ($hr_email) {
        $shortcodes['[hr_email]'] = '[hr_email]';
    }


    foreach ($shortcodes as $code => $label) {
        $html .= '<div class="shortcode-list">';
        $html .= '<span class="copy" data-shortcode="' . esc_attr($code) . '">' . esc_html($label) . '</span>';
        $html .= '</div>';
    }

    foreach ($saved_fields as $field) {
        if (isset($field['field_id'])) {
            $field_id = esc_html($field['field_id']);
            $html .= '<div class="shortcode-list">';
            $html .= '<span class="copy" data-shortcode="[' . $field_id . ']">[' . $field_id . ']</span>';
            $html .= '</div>';
        }
    }

    $html .= '</div>';
    return $html;
}
add_shortcode('render_shortcodes_list', 'render_shortcode_html');
