<?php
/**
 * Export data as .xls or .csv
 *
 * @param array  $data         Array of data to export.
 * @param string $filename     The name of the exported file.
 * @param string $format       The file format ('xls' or 'csv').
 */
function export_data( $data, $filename = 'export', $format = 'xls' ) {
    if ( empty( $data ) || ! in_array( $format, [ 'xls', 'csv' ] ) ) {
        return;
    }

    $filename .= '.' . $format;

    if ( $format === 'xls' ) {
        // Set headers for Excel file
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Add UTF-8 BOM for Excel compatibility
        echo chr(239) . chr(187) . chr(191);

        // Start the table for Excel
        $flag = false;
        foreach ( $data as $row ) {
            if ( !$flag ) {
                // Display column names as first row
                echo implode("\t", array_keys($row)) . "\n";
                $flag = true;
            }

            // Clean up the data (filter tab/line break, etc.)
            array_walk($row, 'filterData');

            // Print row data
            echo implode("\t", array_values($row)) . "\n";
        }
    } elseif ( $format === 'csv' ) {
        // Set headers for CSV file
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen('php://output', 'w');

        // Add CSV header
        fputcsv($output, array_keys($data[0]));

        // Add rows to the CSV
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
    }

    exit;
}

/**
 * Helper function to filter data for Excel export (remove tabs and newlines)
 *
 * @param string $str The data string to clean.
 */
function filterData(&$str) {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

/**
 * Register the custom bulk action for export
 */
function register_export_bulk_action( $bulk_actions ) {
    $bulk_actions['export_to_csv'] = __( 'Export to CSV', 'textdomain' );
    $bulk_actions['export_to_xls'] = __( 'Export to Excel', 'textdomain' );
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-sjw_applications', 'register_export_bulk_action' );

/**
 * Handle the bulk action for exporting to CSV or Excel
 */
function handle_export_bulk_action( $redirect_url, $action, $post_ids ) {
    if ( in_array( $action, [ 'export_to_csv', 'export_to_xls' ] ) ) {
        $export_data = [];

        $jobapp_dynamic_export_fields = get_option('sjw_application_export_fields');

        $dynamic_fields = [];

        // Dynamically add fields based on the `jobapp_dynamic_export_fields` array
        if (!empty($jobapp_dynamic_export_fields) && is_array($jobapp_dynamic_export_fields)) {
            foreach ($jobapp_dynamic_export_fields as $field) {
                $field_id = $field['id'];
                $field_name = $field['name'];
                $field_type = $field['type'];

                $dynamic_fields[$field_name] = function($post_id) use ($field_id, $field_type) {
                    switch ($field_type) {
                        case 'meta_data':
                            if($field_id == 'sjw_resume'){
                                $attachment_id = get_post_meta($post_id, 'sjw_resume', true);
                                $resume_url = wp_get_attachment_url($attachment_id);
                                return $resume_url;
                            }else{
                                return get_post_meta($post_id, $field_id, true);
                            }
                            
                        case 'job_title':
                            $job_id = get_post_meta($post_id, 'sjw_job_id', true);
                            if ($job_id) {
                                $job = get_post($job_id);
                                return esc_html($job ? $job->post_title : '');
                            } 
                            return "";
                        case 'taxonomy':
                            $terms = get_the_terms($post_id, $field_id);
                            if (is_array($terms)) {
                                return implode(', ', wp_list_pluck($terms, 'name'));
                            }
                            return '';
                        case 'job_applied_date':
                            $date = get_post_field('post_date', $post_id); 
                            return date('Y-m-d H:i:s', strtotime($date));
                    }
                };
            }
        }

        // Loop through selected posts and fetch data for each dynamic field
        foreach ( $post_ids as $post_id ) {
            $row = [];
            foreach ( $dynamic_fields as $column_name => $callback ) {
                $row[ $column_name ] = call_user_func( $callback, $post_id );
            }
            $export_data[] = $row;
        }

        // Determine format and export data
        $format = ( $action === 'export_to_csv' ) ? 'csv' : 'xls';

        // Export data
        export_data( $export_data, 'application_status_export', $format );
    }

    return $redirect_url; // Required for WordPress to redirect properly
}
add_filter( 'handle_bulk_actions-edit-sjw_applications', 'handle_export_bulk_action', 10, 3 );
