<?php
// Get the current form fields
$sjw_application_form_fields = get_option('sjw_application_form_fields');
$sjw_confirm_form_fields = get_option('sjw_confirm_form_fields');
$sjw_application_export_fields = get_option('sjw_application_export_fields', []);
$dynamic_fields = [];

// If the data is not set, initialize it as empty
if (!is_array($sjw_application_form_fields)) {
    $sjw_application_form_fields = [];
}

if (!is_array($sjw_confirm_form_fields)) {
    $sjw_confirm_form_fields = [];
}

// Merge the two arrays
$dynamic_fields = array_merge($sjw_application_form_fields, $sjw_confirm_form_fields);

// Output or use the merged array as needed
// var_dump($dynamic_fields); // This is for debugging, you can remove it in production
?>


<form method="post" action="" name="sjw_export_fields" id="sjw_export_fields_form" class="job-app-form">

    <!-- Container for fields -->
    <div class="field-container">

        <!-- Column 1: Available Fields -->
        <div class="field-column">
            <h2>Available Fields</h2>
            <div id="available-fields" class="sortable">
                <?php
                $static_fields = [
                    ['id' => 'job_applied_for', 'type' => 'job_title', 'label' => 'Job Applied For'],
                    ['id' => 'sjw_application_status', 'type' => 'meta_data', 'label' => 'Application Status'],
                    ['id' => 'sjw_job_type', 'type' => 'taxonomy', 'label' => 'Job Type'],
                    ['id' => 'sjw_job_function', 'type' => 'taxonomy', 'label' => 'Job Function'],
                    ['id' => 'job_applied_date', 'type' => 'job_applied_date', 'label' => 'Job Applied Date']
                ];

                foreach ($static_fields as $field) {
                    echo '<div id="' . esc_attr($field['id']) . '" class="sortable-item" data-type="' . esc_attr($field['type']) . '" data-id="' . esc_attr($field['id']) . '">
                            <div class="child-wrap">
                                <span class="drag-handle dashicons dashicons-move ui-sortable-handle"></span>
                                <input type="text" name="available_fields[]" value="' . esc_attr($field['label']) . '">
                            </div>
                            <span class="delete-icon dashicons dashicons-trash" data-id="' . esc_attr($field['id']) . '"></span>
                        </div>';
                }
                ?>

                <!-- Dynamic Fields -->
                <?php
                foreach ($dynamic_fields as $field) {
                    echo '<div id="' . esc_attr($field['field_id']) . '" class="sortable-item" data-type="meta_data" data-id="' . esc_attr($field['field_id']) . '">
                            <div class="child-wrap">
                                <span class="drag-handle dashicons dashicons-move ui-sortable-handle"></span>
                                <input type="text" name="available_fields[]" value="' . esc_html($field['label']) . '">
                            </div>
                            <span class="delete-icon dashicons dashicons-trash" data-id="' . esc_attr($field['field_id']) . '"></span>
                        </div>';
                }
                ?>
            </div>
        </div>

        <!-- Column 2: Selected Fields -->
        <div class="field-column">
            <h2>Selected Fields</h2>
            <div id="selected-fields" class="sortable">
                <!-- Selected Fields -->
                <?php
                foreach ($sjw_application_export_fields as $field) {
                    echo '<div id="' . esc_attr($field['id']) . '" class="sortable-item" data-type="' . esc_attr($field['type']) . '" data-id="' . esc_attr($field['id']) . '">
                            <div class="child-wrap">
                                <span class="drag-handle dashicons dashicons-move ui-sortable-handle"></span>
                                <input type="text" name="selected_fields[]" value="' . esc_html($field['name']) . '">
                            </div>
                            <span class="delete-icon dashicons dashicons-trash" data-id="' . esc_attr($field['id']) . '"></span>
                        </div>';
                }
                ?>
            </div>
        </div>

    </div>
    <br>

    <div class="sjw-submit-button-wrap">
        <button id="sjw_save_export_fields" class="button button-primary button-large">Save Changes</button>
        <span class="spinner"></span>
    </div>
</form>

<?php
