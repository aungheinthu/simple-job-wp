<?php
function sjw_filter_jobs() {
    check_ajax_referer('ajax-job-filter', 'nonce');

    $job_category = isset($_POST['job_category']) ? sanitize_text_field($_POST['job_category']) : '';
    $job_type = isset($_POST['job_type']) ? sanitize_text_field($_POST['job_type']) : '';
    $job_location = isset($_POST['job_location']) ? sanitize_text_field($_POST['job_location']) : '';
    $search_keyword = isset($_POST['search_keyword']) ? sanitize_text_field($_POST['search_keyword']) : '';
    $page = isset($_POST['page']) ? absint($_POST['page']) : 1;


    $sjw_posts_per_page = get_option('sjw_posts_per_page', '');

    $args = array(
        'post_type'      => 'sjw_jobs',
        'post_status'    => 'publish',
        'posts_per_page' => ( $sjw_posts_per_page ) ? $sjw_posts_per_page : 12,
        'paged'          => $page,
        's'              => $search_keyword, // Search by keyword
    );

    // Build taxonomy query
    $tax_query = array('relation' => 'AND');

    if (!empty($job_category)) {
        $tax_query[] = array(
            'taxonomy' => 'sjw_job_function',
            'field'    => 'term_id',
            'terms'    => $job_category,
        );
    }

    if (!empty($job_type)) {
        $tax_query[] = array(
            'taxonomy' => 'sjw_job_type',
            'field'    => 'term_id',
            'terms'    => $job_type,
        );
    }

    if (!empty($job_location)) {
        $tax_query[] = array(
            'taxonomy' => 'sjw_job_location',
            'field'    => 'term_id',
            'terms'    => $job_location,
        );
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // Customize job output here 
            // Get custom fields
            $company_name   = get_post_meta(get_the_ID(), 'sjw_job_company_name', true);
            $start_date     = get_post_meta(get_the_ID(), 'sjw_job_start_date', true);
            $short_description        = get_post_meta(get_the_ID(), 'sjw_job_short_description', true);

        
            $start_timestamp = strtotime(get_the_date('Y-m-d H:i:s'));
            $current_date = new DateTime();
			$current_date_for_label = $current_date->format('Y-m-d');
            $current_date = $current_date->format('Y-m-d H:i:s');
            $current_timestamp = strtotime($current_date);
            $time_diff = human_time_diff($current_timestamp, $start_timestamp);

            // Get job type terms
            $job_types = get_the_terms(get_the_ID(), 'sjw_job_type');
            $job_type_names = $job_types ? implode(', ', wp_list_pluck($job_types, 'name')) : '';

            // Get job location terms
            $job_locations = get_the_terms(get_the_ID(), 'sjw_job_location');
            $job_location_names = $job_locations ? implode(', ', wp_list_pluck($job_locations, 'name')) : '';
            ?>
            <div class="uk-width-1-1@s sjw-job-card">
                <div class="uk-card  uk-card-small uk-card-default">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-bottom" uk-grid>
                            <div class="uk-width-expand uk-width-1-1 uk-width-3-4@m">
                                <h3 class="uk-card-title sjw-job-card-title uk-margin-remove-bottom">
                                    <!-- <a href="<?php // the_permalink(); ?>"><?php the_title(); ?> <span class="sjw-up-to-date">Up to Date (<?php // echo $current_date_for_label; ?>)</span></a>  -->
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
                                </h3>
                                <?php if($company_name): ?>
                                <div class="uk-margin-small-top">
                                    <i class="fa-solid fa-building uk-margin-small-right"></i>
                                    <?php echo esc_html($company_name); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="uk-width-1-1 uk-flex uk-flex-right@m uk-width-1-4@m">
                                <a href="<?php the_permalink(); ?>" class="uk-button uk-button-primary">Apply Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-flex uk-flex-wrap uk-flex-left uk-flex-middle">
                            <?php if($job_type_names): ?>
                            <div class="uk-flex uk-flex-middle uk-margin-right">
                                <i class="fa-solid fa-briefcase uk-margin-small-right"></i>
                                <?php echo esc_html($job_type_names); ?>
                            </div>
                            <?php endif; ?>
                            <div class="uk-flex uk-flex-middle uk-margin-right">
                                <i class="fa-solid fa-location-dot uk-margin-small-right"></i>
                                <?php echo esc_html($job_location_names); ?>
                            </div>
<!--                             <div class="uk-flex uk-flex-middle uk-margin-right">
                                <i class="fa-solid fa-calendar-check uk-margin-small-right"></i>
                                <?php // echo esc_html($time_diff); ?>
                            </div> -->
                        </div>
                        <div class="sjw-job-description uk-margin-top">
                            <?php echo esc_html($short_description); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
        wp_reset_postdata();
    } else {
        echo '<p>No jobs found.</p>';
    }

    $jobs_html = ob_get_clean();

    $pagination_args = array(
        'base' => '%_%',
        'format' => '?paged=%#%',
        'current' => $page,
        'total' => $query->max_num_pages,
        'show_all' => false,
        'end_size' => 1,
        'mid_size' => 2,
        'prev_next' => true,
        'prev_text' => 'prev',
        'next_text' => 'next',
        'type' => 'array',
    );

    ob_start();

    $pagination = paginate_links($pagination_args);

    foreach ($pagination as $key => $value) {
        // Check if the value is empty or contains unwanted text
        if (empty($value) || strpos($value, 'next') !== false || strpos($value, 'prev') !== false) {
            unset($pagination[$key]);  // Remove the unwanted entry
        }
    }

    // var_dump($pagination);
    
    if ($pagination) {
        $current_page = max(1, $page); // Current page number
        $total_pages  = $query->max_num_pages; // Total number of pages

        echo '<nav aria-label="Pagination">';
        echo '<ul class="uk-pagination uk-flex-center" uk-margin>';

        // "Previous" Link
        echo $current_page > 1
            ? '<li><a href class="page-link" data-page="' . ($current_page - 1) . '"><span uk-pagination-previous></span></a></li>'
            : '<li class="uk-disabled uk-flex uk-flex-center"><span class="uk-flex" uk-pagination-previous></span></li>';

        // Page Links
        foreach ($pagination as $page_link) {
            if (strpos($page_link, 'current') !== false) {
                // Active page link
                echo '<li class="uk-active"><span aria-current="page">' . strip_tags($page_link) . '</span></li>';
            } elseif (strpos($page_link, 'dots') !== false) {
                // Ellipsis
                echo '<li class="uk-disabled uk-flex uk-flex-center"><span class="uk-flex" uk-icon="more"></span></li>';
            } else {
                // Normal page link
                $page_number = strip_tags($page_link);
                echo '<li>' . str_replace('<a', '<a class="page-link" data-page="' . $page_number . '"', $page_link) . '</li>';
            }
        }

        // "Next" Link
        echo $current_page < $total_pages
            ? '<li><a href class="page-link" data-page="' . ($current_page + 1) . '"><span uk-pagination-next></span></a></li>'
            : '<li class="uk-disabled uk-flex uk-flex-center"><span class="uk-flex" uk-pagination-next></span></li>';

        echo '</ul>';
        echo '</nav>';
    }

    $pagination_html = ob_get_clean();


    wp_send_json_success(array(
        'jobs'       => $jobs_html,
        'pagination' => $pagination_html,
    ));

    wp_die();
}
add_action('wp_ajax_sjw_filter_jobs', 'sjw_filter_jobs');
add_action('wp_ajax_nopriv_sjw_filter_jobs', 'sjw_filter_jobs');


add_action('wp_ajax_submit_application_form', 'handle_application_form_submission');
add_action('wp_ajax_nopriv_submit_application_form', 'handle_application_form_submission');
function handle_application_form_submission() {
    // Process submitted data
    if (!empty($_POST) || !empty($_FILES)) {
        $data = $_POST; // Regular form fields
        $files = $_FILES; // File uploads if any
        $job_id = isset($data['sjw_job_id']) ? intval($data['sjw_job_id']) : 0;

        // Validate job_id
        if (!$job_id || get_post_type($job_id) !== 'sjw_jobs') {
            wp_send_json_error(['message' => 'Invalid Job ID']);
        }

        // Insert a new "applications" post
        $post_id = wp_insert_post([
            'post_type'   => 'sjw_applications',
            'post_title'  => '', // No title
            'post_status' => 'publish',
            'post_content' => '', // Optional: add content if needed
        ]);

        if ($post_id) {
            // Save the application status meta
            update_post_meta($post_id, 'sjw_application_status', 'new', true);

            update_post_meta($post_id, 'sjw_resume', '', true);
            // Handle file uploads
            if (!empty($files)) {
                foreach ($files as $file_key => $file_value) {
                    if (!empty($file_value['name'])) {
                        // Upload the file using WordPress function
                        $uploaded_file = wp_handle_upload($file_value, ['test_form' => false]);

                        if (isset($uploaded_file['file'])) {
                            // Get the attachment ID
                            $attachment = [
                                'guid'           => $uploaded_file['url'],
                                'post_mime_type' => $uploaded_file['type'],
                                'post_title'     => sanitize_file_name($file_value['name']),
                                'post_content'   => '',
                                'post_status'    => 'inherit',
                            ];

                            // Insert the file as an attachment in the media library
                            $attachment_id = wp_insert_attachment($attachment, $uploaded_file['file'], $post_id);

                            // Generate attachment metadata (thumbnails, etc.)
                            if (!is_wp_error($attachment_id)) {
                                wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']);
                                update_post_meta($post_id, $file_key, $attachment_id); // Save attachment ID as meta
                            }
                        } else {
                            wp_send_json_error(['message' => 'File upload failed.']);
                        }
                    }
                }
            }

            // Save meta fields (non-file fields)
            foreach ($data as $key => $value) {
				// if( $key == 'sjw_refer_a_friend'){
				// 	update_post_meta($post_id, $key, ($value) ? sanitize_text_field($value) : 'No');
				// }else{
					update_post_meta($post_id, $key, sanitize_text_field($value));
				// }
                
            }

            // Fetch and assign taxonomies from the job post
            $taxonomies = ['sjw_job_type', 'sjw_job_function']; // List your taxonomy slugs here
            foreach ($taxonomies as $taxonomy) {
                $terms = wp_get_post_terms($job_id, $taxonomy, ['fields' => 'ids']);
                if (!is_wp_error($terms) && !empty($terms)) {
                    wp_set_object_terms($post_id, $terms, $taxonomy);
                }
            }

            // Get the post object for the new post
            $new_post = get_post($post_id);

            // Make sure the new post exists
            if ($new_post) {
                // Change the global post to the new post
                global $post;
                $post = $new_post;

                // debug_logger('sjw_refer_a_friend', $data['sjw_refer_a_friend']);

				if ($data['sjw_refer_a_friend'] == 'True') {

					// Send template 1 email
					send_email_template(1);

					// Send template 2 email
					send_email_template(2);
				} else {
					// Send template 3 email
					send_email_template(3);
				}

                wp_reset_postdata(); // Reset the global post object to the original post
            }      

            // Retrieve the redirect page ID
            $redirect_page_id = get_option('sjw_application_redirect_page', '');

            // Convert the page ID to a URL
            $redirect_page_url = !empty($redirect_page_id) ? get_permalink($redirect_page_id) : '';

            wp_send_json_success([
                'message' => 'Application created successfully.',
                'redirect_page_url' => $redirect_page_url
            ]);
        } else {
            wp_send_json_error(['message' => 'Failed to create application post.']);
        }
    } else {
        wp_send_json_error(['message' => 'No data received.']);
    }

    wp_die();
}

add_action('wp_ajax_submit_confirm_form', 'handle_confirm_form_submission');
add_action('wp_ajax_nopriv_submit_confirm_form', 'handle_confirm_form_submission');

function handle_confirm_form_submission() {
    // Process submitted data
    if (!empty($_POST)) {
        $data = $_POST; // Regular form fields
        $application_id = isset($data['application_id']) ? intval($data['application_id']) : 0;

        // Validate job_id
        if (!$application_id || get_post_type($application_id ) !== 'sjw_applications') {
            wp_send_json_error(['message' => 'Invalid Job ID']);
        }

        if ($application_id ) {
            // Save meta fields (non-file fields)
            foreach ($data as $key => $value) {
                update_post_meta($application_id , $key, sanitize_text_field($value));
            }
            
            $new_post = get_post($application_id);

            // Make sure the new post exists
            if ($new_post) {
                // Change the global post to the new post
                global $post;
                $post = $new_post;

                   send_email_template(6);
				
                wp_reset_postdata(); // Reset the global post object to the original post
            }

            // Retrieve the redirect page ID
            $redirect_page_id = get_option('sjw_application_redirect_page', '');

            // Convert the page ID to a URL
            $redirect_page_url = !empty($redirect_page_id) ? get_permalink($redirect_page_id) : '';

            wp_send_json_success([
                'message' => 'Application created successfully.',
                'redirect_page_url' => $redirect_page_url
            ]);
        } else {
            wp_send_json_error(['message' => 'Failed to create application post.']);
        }
    } else {
        wp_send_json_error(['message' => 'No data received.']);
    }

    wp_die();
}