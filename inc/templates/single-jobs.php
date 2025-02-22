<?php
/**
 * Custom Single Template for Jobs Post Type
 */

// Load the header
get_header(); ?>

<?php

function get_job_type_terms($post_id) {
    $job_types = get_the_terms($post_id, 'job_type');
    if ($job_types) {
        $terms = array();
        foreach ($job_types as $job_type) {
            $terms[] = $job_type->name;
        }
        return implode(', ', $terms);
    } else {
        return '';
    }
}

// echo do_shortcode('[confirm_link content="Test"]');
?>

<div class="site-main container uk-container uk-container-large uk-margin-large-top uk-margin-large-bottom">
    <div class="uk-card uk-card-default uk-width-1-1@m">
    <?php
    while (have_posts()) :
        the_post();

        ?>
		<?php
        // Get custom fields
        $company_name   = get_post_meta(get_the_ID(), 'sjw_job_company_name', true);
        $start_date     = get_post_meta(get_the_ID(), 'sjw_job_start_date', true);
        $full_description        = get_post_meta(get_the_ID(), 'sjw_job_full_description', true);

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

        <div class="uk-card-header">
            <!-- <h1 class="sjw-job-title"><?php // echo get_the_title(); ?> <span class="sjw-up-to-date">Up to Date (<?php // echo $current_date_for_label; ?>)</span></h1> -->
            <h1 class="sjw-job-title"><?php echo get_the_title(); ?></h1>
        </div>

        <div class="uk-card-body">
            <div class="uk-flex uk-flex-wrap uk-flex-left uk-flex-middle">
                <div class="uk-flex uk-flex-middle">
                    <i class="fa-solid fa-briefcase uk-margin-small-right"></i>
                    <?php echo esc_html($job_type_names); ?>
                </div>
                <div class="uk-flex uk-flex-middle uk-margin-left">
                    <i class="fa-solid fa-location-dot uk-margin-small-right"></i>
                    <?php echo esc_html($job_location_names); ?>
                </div>
<!--                 <div class="uk-flex uk-flex-middle uk-margin-left">
                    <i class="fa-solid fa-calendar-check uk-margin-small-right"></i>
                    <?php // echo esc_html($time_diff); ?>
                </div> -->
            </div>

            <div class="sjw-job-description uk-margin-top">
                <?php echo wp_kses_post(wpautop($full_description));  ?>
            </div>

            
            <?php 
            $selected_socials = get_option('sjw_selected_socials', []); // Fetch saved options
            if (!empty($selected_socials)) { ?>
                <div class="uk-margin-medium-top">
                    <span><b>Share: </span></p>
                    <!-- AddToAny BEGIN -->
                    <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                        <?php foreach ($selected_socials as $social): ?>
                            <a class="<?php echo esc_attr($social); ?>"></a>
                        <?php endforeach; ?>
                    </div>
                    <script defer src="https://static.addtoany.com/menu/page.js"></script>
                    <!-- AddToAny END -->
                </div>
            <?php } ?>
            
        </div>
        <?php
    endwhile;
    ?>
    </div>
    <div class="uk-card uk-card-default uk-card-body uk-margin-top uk-width-1-1@m">
        <div>
            <?php echo do_shortcode('[sjw_application_form]'); ?>
        </div>
    </div>
</div>


<?php
// Load the footer
get_footer();
