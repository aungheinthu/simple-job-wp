<?php

// Create the Recruiter role and assign capabilities
function create_recruiter_role() {
    // Check if the role already exists
    if (!get_role('recruiter')) {
        // Add the new role
        add_role(
            'recruiter', // Role slug
            'Recruiter', // Role display name
            array(
                'read' => true, // Basic capabilities
            )
        );

        // Assign custom capabilities to the Recruiter and Administrator roles
        // add_capabilities_to_roles();
    }
}
add_action('init', 'create_recruiter_role');

// Register Custom Post Types: Jobs and Applications
function sjw_register_post_types() {
    // Reusable Labels Function
    function sjw_get_post_type_labels($singular, $plural, $menu_name = '') {
        return array(
            'name'               => $plural,
            'singular_name'      => $singular,
            'menu_name'          => $menu_name ?: $plural,
            'all_items'          => "All $plural",
            'add_new'            => "Add New",
            'add_new_item'       => "Add New $singular",
            'edit_item'          => "Edit $singular",
            'new_item'           => "New $singular",
            'view_item'          => "View $singular",
            'search_items'       => "Search $plural",
            'not_found'          => "No $plural found",
            'not_found_in_trash' => "No $plural found in Trash",
        );
    }

    // Register Jobs Post Type
    $job_args = array(
        'labels'             => sjw_get_post_type_labels('Job', 'Jobs', 'Simple Job WP'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'has_archive'        => true,
        'capability_type'    => 'sjw_job', // Custom capability type
        'hierarchical'       => false,
        'rewrite'            => array('slug' => 'jobs'),
        'supports'           => array('title'),
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-clipboard',
        'capabilities'       => array(
            'edit_post'          => 'edit_sjw_job',
            'read_post'          => 'read_sjw_job',
            'delete_post'        => 'delete_sjw_job',
            'edit_posts'         => 'edit_sjw_jobs',
            'edit_others_posts'  => 'edit_others_sjw_jobs',
            'publish_posts'      => 'publish_sjw_jobs',
            'read_private_posts' => 'read_private_sjw_jobs',
            'delete_posts'       => 'delete_sjw_jobs',
            'delete_others_posts' => 'delete_others_sjw_jobs',
            'edit_private_posts' => 'edit_private_sjw_jobs',
        ),
        'map_meta_cap'       => true, // Enable meta capability mapping
    );

    register_post_type('sjw_jobs', $job_args);

    // Register Applications Post Type
    $application_args = array(
        'labels'             => sjw_get_post_type_labels('Application', 'Applications'),
        'public'             => false,
        'publicly_queryable' => false,
        'query_var'          => true,
        'has_archive'        => true,
        'capability_type'    => 'sjw_application', // Custom capability type
        'hierarchical'       => false,
        'rewrite'            => array('slug' => 'applications'),
        'supports'           => array('editor'),
        'show_in_rest'       => true,
        'show_ui'            => true,
        'show_in_menu'       => 'edit.php?post_type=sjw_jobs',
        'capabilities'       => array(
            'edit_post'          => 'edit_sjw_application',
            'read_post'          => 'read_sjw_application',
            'delete_post'        => 'delete_sjw_application',
            'edit_posts'         => 'edit_sjw_applications',
            'edit_others_posts'  => 'edit_others_sjw_applications',
            'publish_posts'      => 'publish_sjw_applications',
            'read_private_posts' => 'read_private_sjw_applications',
            'delete_posts'       => 'delete_sjw_applications',
            'delete_others_posts' => 'delete_others_sjw_applications',
            'create_posts'       => false, // Disable creating new Applications from admin
        ),
        'map_meta_cap'       => true, // Enable meta capability mapping
    );

    register_post_type('sjw_applications', $application_args);
}
add_action('init', 'sjw_register_post_types');

add_action('init', 'add_capabilities_to_roles');
function add_capabilities_to_roles() {
    // Get the recruiter role
    $recruiter_role = get_role('recruiter');
    // Get the administrator role
    $admin_role = get_role('administrator');

    // Add ALL capabilities for the 'sjw_jobs' post type
    $job_capabilities = array(
        'edit_sjw_jobs', // Edit own posts
        'edit_others_sjw_jobs', // Edit others' posts
        'publish_sjw_jobs', // Publish posts
        'read_private_sjw_jobs', // Read private posts
        'delete_sjw_jobs', // Delete own posts
        'delete_others_sjw_jobs', // Delete others' posts
        'read_sjw_jobs', // Read posts
        'create_sjw_jobs', // Create new posts
        'edit_private_sjw_jobs', // Edit private posts
        'delete_private_sjw_jobs', // Delete private posts
    );

    // Add ALL capabilities for the 'sjw_applications' post type
    $application_capabilities = array(
        'edit_sjw_applications', // Edit own posts
        'edit_others_sjw_applications', // Edit others' posts
        'publish_sjw_applications', // Publish posts
        'read_private_sjw_applications', // Read private posts
        'delete_sjw_applications', // Delete own posts
        'delete_others_sjw_applications', // Delete others' posts
        'read_sjw_applications', // Read posts
        'create_sjw_applications', // Create new posts
        'edit_private_sjw_applications', // Edit private posts
        'delete_private_sjw_applications', // Delete private posts
    );

    // Assign capabilities to the administrator role (optional, as admins already have all caps)
    if ($admin_role) {
        foreach ($job_capabilities as $cap) {
            $admin_role->add_cap($cap);
        }
        foreach ($application_capabilities as $cap) {
            $admin_role->add_cap($cap);
        }
    }

    // Assign capabilities to the recruiter role
    if ($recruiter_role) {
        foreach ($job_capabilities as $cap) {
            $recruiter_role->add_cap($cap);
        }
        foreach ($application_capabilities as $cap) {
            $recruiter_role->add_cap($cap);
        }
    }
}
// // Register Custom Post Types: Jobs and Applications
// function sjw_register_post_types() {
//     // Reusable Labels Function
//     function sjw_get_post_type_labels($singular, $plural, $menu_name = '') {
//         return array(
//             'name'               => $plural,
//             'singular_name'      => $singular,
//             'menu_name'          => $menu_name ?: $plural,
//             'all_items'          => "All $plural",
//             'add_new'            => "Add New",
//             'add_new_item'       => "Add New $singular",
//             'edit_item'          => "Edit $singular",
//             'new_item'           => "New $singular",
//             'view_item'          => "View $singular",
//             'search_items'       => "Search $plural",
//             'not_found'          => "No $plural found",
//             'not_found_in_trash' => "No $plural found in Trash",
//         );
//     }

//     // Register Jobs Post Type
//     $job_args = array(
//         'labels'             => sjw_get_post_type_labels('Job', 'Jobs', 'Simple Job WP'),
//         'public'             => true,
//         'publicly_queryable' => true,
//         'show_ui'            => true,
//         'show_in_menu'       => true,
//         'query_var'          => true,
//         'has_archive'        => true,
//         'capability_type'    => 'post',
// // 		'capability_type'    => ['sjw_job'],
//         'hierarchical'       => false,
//         'rewrite'            => array('slug' => 'jobs'),
//         'supports'           => array('title',),
//         'show_in_rest'       => true,
//         'menu_icon'          => 'dashicons-clipboard',
//     );

//     register_post_type('sjw_jobs', $job_args);

//     // Register Applications Post Type
//     $application_args = array(
//         'labels'             => sjw_get_post_type_labels('Application', 'Applications'),
//         'public'             => false,
//         'publicly_queryable' => false,
//         'query_var'          => true,
//         'has_archive'        => true,
//         'capability_type'    => 'post',
// //         'capability_type'    => ['sjw_application'],
//         'hierarchical'       => false,
//         'rewrite'            => array('slug' => 'applications'),
//         'supports'           => array('editor'),
//         'show_in_rest'       => true,
//         'show_ui'            => true,
//         'show_in_menu'       => 'edit.php?post_type=sjw_jobs',
//         'capabilities'       => array(
//             'create_posts'       => false, // Disable creating new Applications from admin
//         ),
//         'map_meta_cap'       => true,
//     );

//     register_post_type('sjw_applications', $application_args);
// }
// add_action('init', 'sjw_register_post_types');

// function create_recruiter_role() {
//     // Check if the role already exists
//     if (!get_role('recruiter')) {
//         // Add the new role
//         add_role(
//             'recruiter', // Role slug
//             'Recruiter', // Role display name
//             array(
//                 'read' => true, // Basic capabilities
//             )
//         );
//     }
// }
// add_action('init', 'create_recruiter_role');

// add_action('init', 'add_capabilities_to_recruiter_role');
// function add_capabilities_to_recruiter_role() {
//     // Get the recruiter role
//     $role = get_role('recruiter');

//     if ($role) {
//         // Add capabilities for the 'sjw_jobs' post type
//         $role->add_cap('read_sjw_jobs');
//         $role->add_cap('edit_sjw_jobs');
//         $role->add_cap('edit_others_sjw_jobs');
//         $role->add_cap('publish_sjw_jobs');
//         $role->add_cap('read_private_sjw_jobs');
//         $role->add_cap('delete_sjw_jobs');
//         $role->add_cap('delete_others_sjw_jobs');

//         // Add capabilities for the 'sjw_applications' post type
//         $role->add_cap('edit_sjw_applications');
//         $role->add_cap('edit_others_sjw_applications');
//         $role->add_cap('publish_sjw_applications');
//         $role->add_cap('read_private_sjw_applications');
//         $role->add_cap('delete_sjw_applications');
//         $role->add_cap('delete_others_sjw_applications');
//     }
// }

// Register Custom Taxonomies: Job Type and Job Category
function sjw_register_taxonomies() {
    // Reusable Taxonomy Labels Function
    function sjw_get_taxonomy_labels($singular, $plural) {
        return array(
            'name'          => $plural,
            'singular_name' => $singular,
        );
    }

    // Register Job Type Taxonomy
    register_taxonomy(
        'sjw_job_type',
        ['sjw_jobs', 'sjw_applications'],
        array(
            'hierarchical'      => true,
            'labels'            => sjw_get_taxonomy_labels('Job Type', 'Job Types'),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_menu'      => $post_type !== 'sjw_applications',
            'rewrite'           => array('slug' => 'job-type'),
        )
    );

    // Add default terms (job types)
    $default_job_types = array(
        'Permanent',
        'Contract/Temporary',
        'Part-time',
        'Freelance',
        'Internship/Others',
    );

    foreach ($default_job_types as $job_type) {
        // Check if the job type already exists, if not insert it
        if (!term_exists($job_type, 'sjw_job_type')) {
            wp_insert_term($job_type, 'sjw_job_type');
        }
    }

    // Register Job Category Taxonomy
    register_taxonomy(
        'sjw_job_function',
        ['sjw_jobs', 'sjw_applications'],
        array(
            'hierarchical'      => true,
            'labels'            => sjw_get_taxonomy_labels('Job Function', 'Job Functions'),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_menu'      => $post_type !== 'sjw_applications',
            'rewrite'           => array('slug' => 'job-function'),
        )
    );

    // Add default terms (job categories)
    $default_categories = array(
        'Engineering',
        'Finance and Accounting',
        'Human Resources',
        'Information Technology (IT)',
        'Legal and Compliance',
        'Logistics and Service Operations',
        'Medical',
        'Sales and Marketing',
        'Others'
    );

    foreach ($default_categories as $category) {
        // Check if the category already exists, if not insert it
        if (!term_exists($category, 'sjw_job_function')) {
            wp_insert_term($category, 'sjw_job_function');
        }
    }

    // Register Job Location Taxonomy
    register_taxonomy(
        'sjw_job_location',
        ['sjw_jobs', 'sjw_applications'],
        array(
            'hierarchical'      => true,
            'labels'            => sjw_get_taxonomy_labels('Job Location', 'Job Locations'),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_menu'      => $post_type !== 'sjw_applications',
            'rewrite'           => array('slug' => 'job-location'),
        )
    );

    // Add default terms (job locations)
    $default_locations = array(
        'North',
        'South',
        'East',
        'West',
        'Central/Town',
        'Various locations'
    );

    foreach ($default_locations as $location) {
        // Check if the location already exists, if not insert it
        if (!term_exists($location, 'sjw_job_location')) {
            wp_insert_term($location, 'sjw_job_location');
        }
    }
}
add_action('init', 'sjw_register_taxonomies');

// Hook the function to the plugin activation process
function sjw_plugin_activation() {
    sjw_register_post_types();
    sjw_register_taxonomies();
}
// register_activation_hook(__FILE__, 'sjw_plugin_activation');

add_action('add_meta_boxes', function ($post_type) {
    // Check if we're on the 'sjw_applications' post type
    if ($post_type === 'sjw_applications') {
        // Remove the 'sjw_job_type' taxonomy meta box from 'sjw_applications' edit page
        remove_meta_box('sjw_job_typediv', 'sjw_applications', 'side');
        remove_meta_box('sjw_job_functiondiv', 'sjw_applications', 'side');
        remove_meta_box('sjw_job_locationdiv', 'sjw_applications', 'side');
    }

    if ($post_type === 'sjw_jobs') {
        remove_meta_box('astra_settings_meta_box', 'sjw_jobs', 'side');
        remove_meta_box('wpseo_meta', 'sjw_jobs', 'normal');
        
    }
}, 99);

// Disable the block editor (Gutenberg) for 'sjw_applications' custom post type
add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {
    if ('sjw_applications' === $post_type) {
        return false; // Disable the block editor (use classic editor instead)
    }
    return $use_block_editor;
}, 10, 2);

// Register Meta Boxes
function sjw_register_meta_boxes() {
    // Define fields for Job Details meta box
    $job_details_fields = array(
        // array(
        //     'key'     => 'sjw_job_company_name',
        //     'label'   => 'Company Name',
        //     'type'    => 'text',
        //     'grid'    => 'span 1',
        //     'required' => false,
        // ),
        // array(
        //     'key'     => 'sjw_job_company_website',
        //     'label'   => 'Company Website URL',
        //     'type'    => 'url',
        //     'grid'    => 'span 1',
        //     'required' => false,
        // ),
        // array(
        //     'key'     => 'sjw_job_location',
        //     'label'   => 'Location',
        //     'type'    => 'text',
        //     'grid'    => 'span 1',
        //     'required' => true,
        // ),
        array(
            'key'     => 'sjw_job_start_date',
            'label'   => 'Start Date',
            'type'    => 'date',
            'grid'    => 'span 1',
            'required' => true,
        ),
        array(
            'key'     => 'sjw_job_closed_date',
            'label'   => 'Closed Date',
            'type'    => 'date',
            'grid'    => 'span 1',
            'required' => false,
        ),
        array(
            'key'     => 'sjw_job_short_description',
            'label'   => 'Short Description',
            'type'    => 'textarea',
            'grid'    => 'span 2',
            'required' => true,
        ),
        array(
            'key'     => 'sjw_job_full_description',
            'label'   => 'Full Description',
            'type'    => 'editor',
            'grid'    => 'span 2',
            'required' => true,
        ),
    );

    // Define fields for Application Status meta box
    $application_status_fields = array(
        array(
            'key'     => 'sjw_application_status',
            'label'   => 'Application Status',
            'type'    => 'select',
            'options' => array(
                'new' => 'New',
                'screening' => 'Screening',
                'not-suitable' => 'Not Suitable',
                'shortlist' => 'Shortlist for Interview',
                'hire' => 'Hire',
                'reject' => 'Reject',
                'kiv' => 'KIV for future similar roles',
            ),
            'grid'    => 'span 2',
            'required' => true,
        ),
    );

    // Register Job Details meta box
    sjw_add_meta_box('sjw_jobs', 'Job Details', $job_details_fields);

    // Register Application Status meta box
    sjw_add_meta_box('sjw_applications', 'Application Status', $application_status_fields);
}
add_action('add_meta_boxes', 'sjw_register_meta_boxes');

// General Function to Add Meta Box
function sjw_add_meta_box($post_type, $title, $fields) {
    add_meta_box(
        $post_type . '_meta_box',
        $title,
        'sjw_render_meta_box',
        $post_type,
        ($post_type == 'sjw_applications') ? 'side' : 'normal',
        'default',
        array(
            'fields' => $fields,
        )
    );
}

// General Meta Box Rendering Function
function sjw_render_meta_box($post, $meta_box) {
    // Nonce for security
    wp_nonce_field('job_meta_nonce_action', 'job_meta_nonce');

    // Get the fields for this meta box
    $fields = isset($meta_box['args']['fields']) ? $meta_box['args']['fields'] : array();

    // Add a CSS grid container
    echo '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">';

    foreach ($fields as $field) {
        $value = get_post_meta($post->ID, $field['key'], true);
        $gridSpan = $field['grid'];
        $required = isset($field['required']) && $field['required'] ? 'required' : ''; // Set 'required' attribute if true
        $required_label = isset($field['required']) && $field['required'] ? ' <span style="color: red;">*</span>' : ''; // Add '*' for required fields

        echo "<div style='grid-column: {$gridSpan};'>";
        echo "<label for='{$field['key']}' style='display: block; margin-bottom: 5px;'>{$field['label']}{$required_label}</label>";

        switch ($field['type']) {
            case 'text':
            case 'url':
            case 'date':
                echo "<input type='{$field['type']}' id='{$field['key']}' name='{$field['key']}' value='" . esc_attr($value) . "' style='width: 100%;' {$required} />";
                break;

            case 'textarea':
                echo "<textarea id='{$field['key']}' name='{$field['key']}' rows='3' style='width: 100%;' {$required}>" . esc_textarea($value) . "</textarea>";
                break;

            case 'editor':
                wp_editor(
                    $value,
                    $field['key'],
                    array(
                        'textarea_name' => $field['key'],
                        'textarea_rows' => 12,
                        'media_buttons' => true,
                    )
                );
                break;

            case 'select':
                echo "<select id='{$field['key']}' name='{$field['key']}' style='width: 100%;' {$required}>";
                foreach ($field['options'] as $key => $label) {
                    echo "<option value='" . esc_attr($key) . "' " . selected($value, $key, false) . ">{$label}</option>";
                }
                echo "</select>";
                break;

            default:
                echo "<input type='text' id='{$field['key']}' name='{$field['key']}' value='" . esc_attr($value) . "' style='width: 100%;' {$required} />";
                break;
        }
        echo "</div>";
    }

    echo '</div>';
}


// Save Meta Box Data
function sjw_save_meta_boxes($post_id) {
    // Security checks
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $post_type = get_post_type($post_id);

    // General logic for saving meta box data for any post type
    $meta_boxes = sjw_get_meta_boxes($post_type); // Retrieve meta boxes for the current post type

    if ($meta_boxes && isset($_POST['job_meta_nonce']) && wp_verify_nonce($_POST['job_meta_nonce'], 'job_meta_nonce_action')) {
        foreach ($meta_boxes as $meta_box) {
            $fields = $meta_box['fields']; // Get fields for the current meta box

            foreach ($fields as $field) {
                $field_key = $field['key'];
                if (isset($_POST[$field_key])) {
                    $value = $_POST[$field_key];

                    // Sanitize and save only if the value is not empty
                    if (!empty($value)) {
                        $value = sanitize_meta_value($field, $value); // Sanitize the value
                        update_post_meta($post_id, $field_key, $value);
                    } elseif (get_post_meta($post_id, $field_key, true)) {
                        // Keep the previous value if the new one is empty
                        // You can optionally do something else here, like delete the meta or leave it as is
                        update_post_meta($post_id, $field_key, get_post_meta($post_id, $field_key, true));
                    }
                }
            }
        }
    }
}
add_action('save_post', 'sjw_save_meta_boxes');

// Function to get meta boxes based on post type
function sjw_get_meta_boxes($post_type) {
    // Define meta boxes for 'sjw_jobs' and 'sjw_applications' (expandable for other post types)
    $meta_boxes = array();

    if ($post_type === 'sjw_jobs') {
        $meta_boxes[] = array(
            'fields' => array(
                // array('key' => 'sjw_job_company_name', 'type' => 'text'),
                // array('key' => 'sjw_job_company_website', 'type' => 'url'),
                // array('key' => 'sjw_job_location', 'type' => 'text'),
                array('key' => 'sjw_job_start_date', 'type' => 'date'),
                array('key' => 'sjw_job_closed_date', 'type' => 'date'),
                array('key' => 'sjw_job_short_description', 'type' => 'textarea'),
                array('key' => 'sjw_job_full_description', 'type' => 'editor'),
            ),
        );
    }

    if ($post_type === 'sjw_applications') {
        $meta_boxes[] = array(
            'fields' => array(
                array('key' => 'sjw_application_status', 'type' => 'select', 'options' => array(
                    'new' => 'New',
                    'screening' => 'Screening',
                    'not-suitable' => 'Not Suitable',
                    'shortlist' => 'Shortlist for Interview',
                    'hire' => 'Hire',
                    'reject' => 'Reject',
                    'kiv' => 'KIV for future similar roles',
                )),
            ),
        );
    }

    return $meta_boxes;
}

// Function to sanitize and process field values before saving
function sanitize_meta_value($field, $value) {
    switch ($field['type']) {
        case 'url':
            return esc_url_raw($value);
        case 'textarea':
            return sanitize_textarea_field($value);
        case 'editor':
            return wp_kses_post($value);
        case 'select':
        case 'text':
        case 'date':
            return sanitize_text_field($value);
        default:
            return sanitize_text_field($value);
    }
}

// Automatically Remove Expired Jobs
function sjw_remove_expired_jobs() {
    $today = date('Y-m-d');
    $args = array(
        'post_type'  => 'sjw_jobs',
        'meta_query' => array(
            array(
                'key'     => 'sjw_job_closed_date',
                'value'   => $today,
                'compare' => '<',
                'type'    => 'DATE',
            ),
        ),
        'posts_per_page' => -1,
    );

    $expired_jobs = get_posts($args);

    foreach ($expired_jobs as $job) {
        wp_trash_post($job->ID);
    }
}
add_action('wp', 'sjw_remove_expired_jobs');

// Remove the 'View' action from the 'sjw_applications' post type
function sjw_remove_view_action_for_applications($actions, $post) {
    // Check if the post is of type 'sjw_applications'
    if ('sjw_applications' === $post->post_type) {
        // Remove the 'view' action
        unset($actions['view']);
    }
    return $actions;
}
add_filter('post_row_actions', 'sjw_remove_view_action_for_applications', 10, 2);

// General Function to Add Custom Taxonomy Filters
function sjw_add_taxonomy_filter($post_types = array(), $taxonomies = array()) {
    global $typenow;

    // Ensure this only applies to the specified post types
    if (in_array($typenow, $post_types)) {
        foreach ($taxonomies as $taxonomy) {
            $taxonomy_obj = get_taxonomy($taxonomy);

            if (!$taxonomy_obj) {
                continue; // Skip if the taxonomy does not exist
            }

            $taxonomy_name = $taxonomy_obj->labels->name;

            // Get terms for the current taxonomy
            $terms = get_terms(array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
            ));

            if (!empty($terms)) {
                echo '<select name="' . $taxonomy . '" id="' . $taxonomy . '" class="postform">';
                echo '<option value="">' . __('All ' . $taxonomy_name) . '</option>';
                foreach ($terms as $term) {
                    $selected = (isset($_GET[$taxonomy]) && $_GET[$taxonomy] == $term->slug) ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . '</option>';
                }
                echo '</select>';
            }
        }
    }
}

// Example Usage for 'applications' and 'jobs' post types, and 'job_type' and 'job_category' taxonomies
add_action('restrict_manage_posts', function() {
    sjw_add_taxonomy_filter(
        array('sjw_applications', 'sjw_jobs'),  // Post types to apply the filters
        array('sjw_job_type', 'sjw_job_function', 'sjw_job_location')  // Taxonomies to add filters for
    );
});

// Add custom rendering to the post editing screen
function sjw_custom_render_editor($post) {
    // Ensure this function only applies to the 'applications' post type
    if ($post->post_type !== 'sjw_applications') {
        return;
    }

    // Retrieve the dynamic form fields data
    $sjw_application_form_fields = get_option('sjw_application_form_fields');
    $sjw_confirm_form_fields = get_option('sjw_confirm_form_fields');

    // Fetch all post meta for the current post
    $meta_data = get_post_meta($post->ID);

    // var_dump($meta_data);

    // Start rendering the custom editor section
    echo '<div class="custom-editor-section">';

    // Check if dynamic form fields data exists
    if (!empty($sjw_application_form_fields)) {
        echo '<table class="widefat striped">';
       
        echo '<tbody>';

        // Loop through the dynamic form fields and display associated meta values
        foreach ($sjw_application_form_fields as $field) {
            $field_id = $field['field_id'] ?? '';
            $label = $field['label'] ?? 'Unknown Field';

            // Check if the current field is the resume field
            if ($field_id === 'sjw_resume') {
                // Output resume URL if available
                $attachment_id = $meta_data['sjw_resume'][0] ?? '';
                $resume_url = wp_get_attachment_url($attachment_id);
                $value = $resume_url 
                    ? '<a href="' . esc_url($resume_url) . '" target="_blank">View Resume</a>' 
                    : '—'; // Default placeholder if no resume is available
            }else if($field_id === 'sjw_refer_a_friend'){
                // For other fields, fetch and sanitize the value
                $value = isset($meta_data[$field_id]) && !empty($meta_data[$field_id][0]) 
                ? esc_html($meta_data[$field_id][0]) 
                : 'No'; // Default placeholder for missing values
            } else {
                // // For other fields, fetch and sanitize the value
                // $value = isset($meta_data[$field_id]) && !empty($meta_data[$field_id][0]) 
                //     ? esc_html($meta_data[$field_id][0]) 
                //     : '—'; // Default placeholder for missing values

                // For other fields, fetch and sanitize the value
                $value = isset($meta_data[$field_id]) && !empty($meta_data[$field_id][0]) 
                ? $meta_data[$field_id][0] 
                : '—'; // Default placeholder for missing values

                // Check if the value is numeric
                if (is_numeric($value) && preg_match('/^\d+$/', $value)) { 
                $attachment_post = get_post($value);

                if ($attachment_post && $attachment_post->post_type === 'attachment') {
                    // Fetch the attachment URL if valid
                    $attachment_url = wp_get_attachment_url($value) ?: '—'; // Use the URL if valid, or default
                    
                    // If the attachment URL is valid, wrap it in an <a> tag
                    if ($attachment_url !== '—') {
                        $value = '<a href="' . esc_url($attachment_url) . '" target="_blank">' . esc_html($attachment_url) . '</a>';
                    } else {
                        $value = 'Invalid attachment';
                    }
                } else {
                    // If not a valid attachment, set a fallback message
                    $value = 'Invalid attachment';
                }
                } else {
                    // If the value is not numeric, sanitize it as a string
                    $value = esc_html($value);
                }
            }

            // Render the table row
            echo '<tr>
                    <td><strong>' . esc_html($label) . '</strong></td>
                    <td>' . $value . '</td>
                </tr>';
        }


        if (!empty($sjw_confirm_form_fields)) {
             // Loop through the dynamic form fields and display associated meta values
            foreach ($sjw_confirm_form_fields as $field) {
                $field_id = $field['field_id'] ?? '';
                $label = $field['label'] ?? 'Unknown Field';
                $value = isset($meta_data[$field_id]) && !empty($meta_data[$field_id][0]) 
                    ? esc_html($meta_data[$field_id][0]) 
                    : '—';// Default placeholder for missing values

                    echo '<tr>
                        <td><strong>' . esc_html($label) . '</strong></td>
                        <td>' . $value . '</td>
                    </tr>';
            }
        }
       

        echo '</tbody></table>';
    } else {
        // Fallback if no form fields are available
        echo '<p>No dynamic form fields found for this application.</p>';
    }

    echo '</div>';
}

add_action('edit_form_after_title', 'sjw_custom_render_editor');


// Add or modify columns for the "applications" post type
function sjw_custom_applications_columns($columns) {
    // var_dump($columns);
    // Remove the "Date" column
    unset($columns['date']);
    unset($columns['title']);
    unset($columns['taxonomy-sjw_job_type']);
    unset($columns['taxonomy-sjw_job_function']);
    unset($columns['taxonomy-sjw_job_location']);

    // Add custom columns
    $columns['sjw_job_applied_for'] = 'Job Applied For';
    $columns['sjw_job_type'] = 'Job Types';
    $columns['sjw_job_categories'] = 'Job Functions';
    $columns['sjw_application_status'] = 'Application Status';
    $columns['sjw_resume'] = 'Resume';
    $columns['sjw_nric'] = 'NRIC';
    $columns['sjw_application_date'] = 'Date';  // Adding the Date column

    return $columns;
}
add_filter('manage_sjw_applications_posts_columns', 'sjw_custom_applications_columns');

// Add or modify columns for the "sjw_jobs" post type
function sjw_custom_jobs_columns($columns) {
    // Unset all columns except the ones you want to keep
    foreach ($columns as $key => $value) {
        if (!in_array($key, array('cb', 'title', 'taxonomy-sjw_job_type', 'taxonomy-sjw_job_function', 'taxonomy-sjw_job_location', 'date'))) {
            unset($columns[$key]);
        }
    }

    return $columns;
}
add_filter('manage_sjw_jobs_posts_columns', 'sjw_custom_jobs_columns', 999);

// Populate custom columns with data
function sjw_custom_applications_columns_content($column, $post_id) {
    switch ($column) {
        case 'sjw_job_applied_for':
            // // Output the job title the applicant applied for
            // $job_id = get_post_meta($post_id, 'sjw_job_id', true); // Assuming you store the job ID
            // // var_dump($job_id);
            // if ($job_id) {
            //     $job = get_post($job_id);
            //     echo esc_html($job ? $job->post_title : '—');
            // } else {
            //     echo '—';
            // }
            // Output the job title the applicant applied for
            $job_id = get_post_meta($post_id, 'sjw_job_id', true); // Assuming you store the job ID

            if ($job_id) {
                $job = get_post($job_id);
                if ($job) {
                    $job_title = esc_html($job->post_title);
                    $job_link = esc_url(get_permalink($job_id)); // Generate the permalink for the job
                    echo '<a href="' . $job_link . '" target="_blank" rel="noopener noreferrer">' . $job_title . '</a>';
                } else {
                    echo '—';
                }
            } else {
                echo '—';
            }

            break;

        case 'sjw_job_type':
            // Output the categories associated with the application
            $terms = get_the_term_list($post_id, 'sjw_job_type', '', ', ', '');
            echo $terms ? $terms : '—';
            break;

        case 'sjw_job_categories':
            // Output the categories associated with the application
            $terms = get_the_term_list($post_id, 'sjw_job_function', '', ', ', '');
            echo $terms ? $terms : '—';
            break;

        case 'sjw_application_status':
            // $options = array(
            //     'not_any' => 'Not Any',
            //     'new' => 'New',
            //     'in-process' => 'In Process',
            //     'shortlist' => 'Shortlist for Interview',
            //     'reject' => 'Reject',
            //     'selected' => 'Selected'
            // );

            // // Output applicant status from post meta
            // $status = get_post_meta($post_id, 'sjw_application_status', true);
            // echo esc_html($status ? ucfirst($options[$status]) : '—');

            $options = array(
                'new' => 'New',
                'screening' => 'Screening',
                'not-suitable' => 'Not Suitable',
                'shortlist' => 'Shortlist for Interview',
                'hire' => 'Hire',
                'reject' => 'Reject',
                'kiv' => 'KIV for future similar roles',
            );
            
            // Fetch applicant status
            $status = get_post_meta($post_id, 'sjw_application_status', true);
            
            // Determine display name
            $display_status = $status && isset($options[$status]) ? $options[$status] : '—';
            
            // Output the status in the desired HTML format
            if ($status && isset($options[$status])) {
                echo '<div class="sjw-status" data-status="' . esc_attr($status) . '">' . esc_html($display_status) . '</div>';
            } else {
                echo '<div class="sjw-status" data-status="not_any">—</div>';
            }
            
            break;

        case 'sjw_resume':
            // Output resume URL if available
            $attachment_id = get_post_meta($post_id, 'sjw_resume', true);
            $resume_url = wp_get_attachment_url($attachment_id);
            if ($resume_url) {
                echo '<a href="' . esc_url($resume_url) . '" target="_blank">View Resume</a>';
            } else {
                echo '—';
            }
            break;

        case 'sjw_application_date':
            // Output the application date (post date)
            $date = get_the_date('Y-m-d', $post_id); // You can customize the date format
            echo esc_html($date);
            break;

        case 'sjw_nric':
            // Output the NRIC
            $jobapp_nric = get_post_meta($post_id, 'sjw_nric', true);
            if($jobapp_nric){
                echo esc_html($jobapp_nric);
            }else {
                echo '—';
            }
            
            break;
    }
}
add_action('manage_sjw_applications_posts_custom_column', 'sjw_custom_applications_columns_content', 10, 2);

add_filter('post_row_actions', 'sjw_remove_all_but_edit_trash', 99, 2);

function sjw_remove_all_but_edit_trash($actions, $post) {
    // Check if the post type is your custom post type (e.g., 'sjw_applications')
    if ($post->post_type === 'sjw_applications') {
        // Keep only 'edit' and 'trash' actions
        $allowed_actions = ['edit', 'trash'];
        $actions = array_filter($actions, function($key) use ($allowed_actions) {
            return in_array($key, $allowed_actions, true);
        }, ARRAY_FILTER_USE_KEY);
    }
    return $actions;
}


// Add a dropdown filter for _application_status in the admin post list
function add_application_status_filter() {
    global $typenow;

    // Ensure this only applies to the 'applications' post type
    if ( $typenow == 'sjw_applications' ) {
        $options = array(
            'new' => 'New',
            'screening' => 'Screening',
            'not-suitable' => 'Not Suitable',
            'shortlist' => 'Shortlist for Interview',
            'hire' => 'Hire',
            'reject' => 'Reject',
            'kiv' => 'KIV for future similar roles',
        );

        $selected_status = isset( $_GET['sjw_application_status'] ) ? $_GET['sjw__application_status'] : '';

        echo '<select name="sjw_application_status" id="sjw_application_status">';
        echo '<option value="">' . __( 'All Statuses', 'textdomain' ) . '</option>';
        foreach ( $options as $key => $label ) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr( $key ),
                selected( $selected_status, $key, false ),
                esc_html( $label )
            );
        }
        echo '</select>';
    }


}
add_action( 'restrict_manage_posts', 'add_application_status_filter' );

add_action( 'admin_init', 'sjw_remove_yoast_seo_admin_filters', 20 );
function sjw_remove_yoast_seo_admin_filters() {
    global $typenow, $wpseo_meta_columns;

    // Only apply this to the 'sjw_jobs' post type
    if ( $typenow === 'sjw_jobs' && $wpseo_meta_columns ) {
        // Remove the SEO filter dropdown
        remove_action( 'restrict_manage_posts', array( $wpseo_meta_columns, 'posts_filter_dropdown' ) );

        // Remove the Readability filter dropdown
        remove_action( 'restrict_manage_posts', array( $wpseo_meta_columns, 'posts_filter_dropdown_readability' ) );
    }
}

// Modify the query to filter posts by _application_status
function sjw_filter_posts_by_application_status( $query ) {
    global $pagenow;

    // Check if we're in the admin area, on the post list page, and for the 'applications' post type
    if ( is_admin() && $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sjw_applications' ) {
        if ( isset( $_GET['sjw_application_status'] ) && $_GET['sjw_application_status'] != '' ) {
            $query->query_vars['meta_query'] = array(
                array(
                    'key'     => 'sjw_application_status',
                    'value'   => sanitize_text_field( $_GET['sjw_application_status'] ),
                    'compare' => '=',
                ),
            );
        }
    }
}
add_action( 'pre_get_posts', 'sjw_filter_posts_by_application_status' );

// Save post meta when the post is saved
function sjw_save_application_meta($post_id) {
    // Check if this is an "applications" post
    if ('sjw_applications' !== get_post_type($post_id)) {
        return;
    }

    $status = get_post_meta($post_id, 'sjw_application_status', true);

    if($status == 'reject'){
		send_email_template(4);
    }elseif($status == 'shortlist'){
		send_email_template(5);
    }
}
add_action('save_post', 'sjw_save_application_meta');

// Validate Job Location, Job Type, and Job Function taxonomies
function sjw_validate_taxonomies_required($post_id, $post, $update) {
// 	// bail out if this is an autosave
// 	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
// 		return;
// 	}
	
    // Replace 'sjw_jobs' with your custom post type
    if ($post->post_type === 'sjw_jobs') {
		
        // Define the taxonomies to validate
        $taxonomies = array(
            'sjw_job_type',     // Job Type taxonomy
            'sjw_job_function',  // Job Function taxonomy
            'sjw_job_location', // Job Location taxonomy
        );

        // Loop through each taxonomy and check if a term is selected
        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_post_terms($post_id, $taxonomy, array('fields' => 'ids'));

            // If no term is selected, prevent saving the post
            if (empty($terms)) {
                // Remove the save_post action to avoid infinite loop
                remove_action('save_post', 'sjw_validate_taxonomies_required', 10);

                // Update the post status to draft
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_status' => 'draft',
                ));

                // Add an admin notice
                set_transient('sjw_taxonomy_required_error', $taxonomy, 5);

                // Restore the save_post action
                add_action('save_post', 'sjw_validate_taxonomies_required', 10, 3);

                // Stop further processing
                break;
            }
        }
    }
}
add_action('save_post', 'sjw_validate_taxonomies_required', 10, 3);

// Display admin notice for required taxonomies
function sjw_taxonomy_required_admin_notice() {
    $taxonomy = get_transient('sjw_taxonomy_required_error');

    if ($taxonomy) {
        // Get the taxonomy label for the error message
        $taxonomy_object = get_taxonomy($taxonomy);
        $taxonomy_label = $taxonomy_object ? $taxonomy_object->labels->singular_name : 'Taxonomy';

        ?>
        <div class="error">
            <p><?php printf(__('Error: %s is required. Please select a %s before saving.', 'textdomain'), $taxonomy_label, strtolower($taxonomy_label)); ?></p>
        </div>
        <?php
        delete_transient('sjw_taxonomy_required_error');
    }
}
add_action('admin_notices', 'sjw_taxonomy_required_admin_notice');
