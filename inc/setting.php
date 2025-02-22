<?php
// Register Custom Submenus for a Post Type
function sjw_add_custom_submenus($parent_slug, $menu_title, $capability, $menu_slug, $callback) {
    add_submenu_page(
        $parent_slug,      // Parent slug (e.g., edit.php?post_type=jobs)
        $menu_title,       // The title of the submenu page
        $menu_title,       // The label of the submenu
        $capability,       // The required capability to view this menu
        $menu_slug,        // The unique slug for the menu item
        $callback          // The callback function to render the menu page
    );
}

// Example Usage for Job Post Type Settings Submenu
add_action('admin_menu', function() {
    sjw_add_custom_submenus(
        'edit.php?post_type=sjw_jobs',   // Parent menu slug (Jobs)
        'Settings',                  // Submenu title
//         'manage_options',            // Capability required to access the menu
		'edit_sjw_jobs',     
        'sjw-settings',              // New unique slug for the submenu
        'sjw_setting_page_callback'      // New callback function for rendering the submenu
    );
});

// Settings Page Callback
function sjw_setting_page_callback() {
    ?>
    <div class="wrap">
        <h1>Settings</h1>

        <div class="sjw_setting_tabs">
            <!-- Tab List -->
            <ul class="sjw_setting_tab-list">
                <li class="sjw_setting_tab-item" data-tab="sjw_setting_general">General</li>
                <li class="sjw_setting_tab-item" data-tab="sjw_setting_application_form_fields">Application Form</li>
                <li class="sjw_setting_tab-item" data-tab="sjw_setting_confirm_form">Confirm Form</li>
                <li class="sjw_setting_tab-item" data-tab="sjw_setting_application_export_fields">Application Export Fields</li>
                <li class="sjw_setting_tab-item" data-tab="sjw_setting_email_templates">Email Templates</li>
                <li class="sjw_setting_tab-item" data-tab="sjw_setting_socials">Socials</li>
                <li class="sjw_setting_tab-item" data-tab="sjw_setting_filters">Filters</li>
            </ul>

            <!-- Tab Content -->
            <div class="sjw_setting_tab-content">
                <?php
                // Define tabs and associated file paths
                $tabs = array(
                    'sjw_setting_general'                   => '/setting/setting-general.php', // Inline content
                    'sjw_setting_application_form_fields'   => null, // Inline custom sub-tabs for Application Form
                    'sjw_setting_confirm_form'              => null,
                    'sjw_setting_application_export_fields' => '/setting/application-export-fields.php',
                    'sjw_setting_email_templates'           => null, // Handled separately
                    'sjw_setting_socials'                   => '/setting/socials.php',
                    'sjw_setting_filters'                   => '/setting/filters.php',
                );

                // Render each tab dynamically
                foreach ($tabs as $tab_id => $file_path) {
                    echo '<div class="sjw_setting_tab-panel" id="' . esc_attr($tab_id) . '-content">';
                    echo '<h3>' . esc_html(ucwords(str_replace(array('sjw_setting_', '_'), ' ', $tab_id))) . '</h3>';

                    if ($tab_id === 'sjw_setting_application_form_fields') {
                        sjw_render_application_form_sub_tabs();
                    } elseif ($tab_id === 'sjw_setting_confirm_form') {
                        sjw_render_confirm_form_sub_tabs();
                    } elseif ($file_path) {
                        $file = plugin_dir_path(__FILE__) . $file_path;
                        if (file_exists($file)) {
                            require_once $file;
                        } else {
                            echo '<p>Error: The file "' . esc_html(basename($file_path)) . '" was not found.</p>';
                        }
                    } elseif ($tab_id === 'sjw_setting_email_templates') {
                        sjw_setting_render_email_templates();
                    } else {
                        echo '<p>Content for ' . esc_html(ucwords(str_replace(array('sjw_setting_', '_'), ' ', $tab_id))) . ' goes here.</p>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}

// Render Application Form Sub Tabs
function sjw_render_application_form_sub_tabs() {
    $sub_tabs = array(
        'sjw_application_form' => 'Form',
        'sjw_application_messages' => 'Messages',
    );

    echo '<ul class="sjw_setting_sub-tab-list">';
    foreach ($sub_tabs as $id => $label) {
        echo '<li class="sjw_setting_sub-tab-item" data-subtab="' . esc_attr($id) . '">' . esc_html($label) . '</li>';
    }
    echo '</ul>';

    echo '<div class="sjw_setting_sub-tab-content">';
    foreach ($sub_tabs as $id => $label) {
        $file = plugin_dir_path(__FILE__) . '/setting/application-' . esc_attr(str_replace('sjw_application_', '', $id)) . '.php';
        echo '<div class="sjw_setting_sub-tab-panel" id="' . esc_attr($id) . '-content">';
        if (file_exists($file)) {
            require_once $file;
        } else {
            echo '<p>Error: The file "application-' . esc_html(str_replace('sjw_application_', '', $id)) . '.php" was not found.</p>';
        }
        echo '</div>';
    }
    echo '</div>';
}

// Render Confirm Form Sub Tabs
function sjw_render_confirm_form_sub_tabs() {
    $sub_tabs = array(
        'sjw_confirm_form' => 'Form',
        'sjw_confirm_messages' => 'Messages',
    );

    echo '<ul class="sjw_setting_sub-tab-list">';
    foreach ($sub_tabs as $id => $label) {
        echo '<li class="sjw_setting_sub-tab-item" data-subtab="' . esc_attr($id) . '">' . esc_html($label) . '</li>';
    }
    echo '</ul>';

    echo '<div class="sjw_setting_sub-tab-content">';
    foreach ($sub_tabs as $id => $label) {
        $file = plugin_dir_path(__FILE__) . '/setting/confirm-' . esc_attr(str_replace('sjw_confirm_', '', $id)) . '.php';
        echo '<div class="sjw_setting_sub-tab-panel" id="' . esc_attr($id) . '-content">';
        if (file_exists($file)) {
            require_once $file;
        } else {
            echo '<p>Error: The file "confirm-' . esc_html(str_replace('sjw_confirm_form_', '', $id)) . '.php" was not found.</p>';
        }
        echo '</div>';
    }
    echo '</div>';
}

// Helper Function to Render Email Templates Tab
function sjw_setting_render_email_templates() {
    $email_templates = array(
        'sjw_setting_templates1' => 'Refer A Friend',
        'sjw_setting_templates2' => 'Refer A Friend Candidate',
        'sjw_setting_templates3' => 'Job Apply Email',
        'sjw_setting_templates4' => 'Rejected Email',
        'sjw_setting_templates5' => 'Selected Email',
        'sjw_setting_templates6' => 'Confirm Email',
    );

    echo '<ul class="sjw_setting_sub-tab-list">';
    foreach ($email_templates as $id => $label) {
        echo '<li class="sjw_setting_sub-tab-item" data-subtab="' . esc_attr($id) . '">' . esc_html($label) . '</li>';
    }
    echo '</ul>';

    echo '<div class="sjw_setting_sub-tab-content">';
    foreach ($email_templates as $id => $label) {
        $file = plugin_dir_path(__FILE__) . '/setting/email-' . esc_attr(str_replace('sjw_setting_', '', $id)) . '.php';
        echo '<div class="sjw_setting_sub-tab-panel" id="' . esc_attr($id) . '-content">';
        if (file_exists($file)) {
            require_once $file;
        } else {
            echo '<p>Error: The file "email-' . esc_html(str_replace('sjw_setting_', '', $id)) . '.php" was not found.</p>';
        }
        echo '</div>';
    }
    echo '</div>';
}