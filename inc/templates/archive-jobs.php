<?php
// Include the header
get_header();

?>
<?php 
    $sjw_enable_search_bar = get_option('sjw_enable_search_bar', 'no');
    $sjw_enable_category_filter = get_option('sjw_enable_category_filter', 'no');
    $sjw_enable_type_filter = get_option('sjw_enable_type_filter', 'no');
    $sjw_enable_location_filter = get_option('sjw_enable_location_filter', 'no');
?>
<!-- <div id="sjw-job-filter-banner">
    <?php // echo do_shortcode('[elementor-template id="4543"]'); ?>
</div> -->

<!-- <div id="sjw-job-filter-banner">
    <div class="uk-background-cover uk-background-muted uk-height-medium uk-panel uk-flex uk-flex-center uk-flex-middle" style="background-image: url(https://getuikit.com/docs/images/dark.jpg);">
        <h1 class="uk-h1 uk-animation-slide-top">New Job Opportunities <?php // echo do_shortcode('[currentdate format="Y M"]'); ?></h1>
    </div>
</div> -->

<div id="sjw-job-filter-container" class="site-main container uk-container uk-container-large uk-margin-large-top uk-margin-large-bottom">

    <div id="sjw-job-filter" class="uk-margin-medium uk-padding uk-background-muted">
        <form class="uk-grid-medium uk-grid" uk-grid>
            
            <?php if ($sjw_enable_search_bar === 'yes'): ?>
            <div class="uk-width-1-1">
                <input class="uk-input" type="text" placeholder="Keyword" id="sjw-search-bar" aria-label="Keyword" name="keyword">
            </div>
            <?php endif; ?>

            <div class="uk-width-4-5@s">
                <div class="uk-grid-medium uk-grid" uk-grid>
                    <?php if ($sjw_enable_category_filter === 'yes'): ?>
                    <div class="uk-width-1-3@s">
                        <select class="uk-select" id="sjw-job-category-filter" name="job-category">
                            <option value="">Job Function</option>
                            <?php
                            $categories = get_terms(array(
                                'taxonomy'   => 'sjw_job_function',
                                'hide_empty' => false,
                            ));
                            foreach ($categories as $category) {
                                echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if ($sjw_enable_type_filter === 'yes'): ?>
                    <div class="uk-width-1-3@s">
                        <select class="uk-select" id="sjw-job-type-filter" name="job-type">
                            <option value="">Job Type</option>
                            <?php
                            $categories = get_terms(array(
                                'taxonomy'   => 'sjw_job_type',
                                'hide_empty' => false,
                            ));
                            foreach ($categories as $category) {
                                echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if ($sjw_enable_location_filter === 'yes'): ?>
                    <div class="uk-width-1-3@s">
                        <!-- <input class="uk-input" id="sjw-location-filter" type="text" placeholder="Location" aria-label="Location" name="location"> -->
                        <select class="uk-select" id="sjw-job-location-filter" name="job-location">
                            <option value="">Job Location</option>
                            <?php
                            $categories = get_terms(array(
                                'taxonomy'   => 'sjw_job_location',
                                'hide_empty' => false,
                            ));
                            foreach ($categories as $category) {
                                echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="uk-width-expand">
                <a href="#" id="sjw-filter-button" class="uk-button uk-button-primary uk-width-1-1"><i class="fa-solid fa-search uk-margin-small-right"></i> Search</a>
            </div>
        </form>
    </div>

    <div id="sjw-job-results" class="uk-grid uk-grid-medium" uk-grid>
    <!-- Filtered posts will appear here -->
    </div>

    <div id="sjw-pagination" class="uk-margin-top">
    <!-- Pagination links will appear here -->
    </div>
</div>
<?php
// Include the footer
get_footer();
?>
