<?php
// Enqueue Admin Scripts and Styles
function sjw_setting_enqueue_backend_assets($hook_suffix) {
    // $screen = get_current_screen();

    wp_enqueue_style(
        'sjw_backend_css',
        plugin_dir_url(__FILE__) . '../assets/backend/css/sjw-backend.css',
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'sjw_backend_js',
        plugin_dir_url(__FILE__) . '../assets/backend/js/sjw-backend.js',
        array('jquery', 'jquery-ui-draggable', 'jquery-ui-sortable'),
        '1.0.0',
        true
    );

    // Pass data to the JavaScript file
    wp_localize_script(
        'sjw_backend_js',
        'sjw_ajax_object',
        array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('sjw_nonce_action'))
    );
}
add_action('admin_enqueue_scripts', 'sjw_setting_enqueue_backend_assets');

// Enqueue Frontend Styles and Scripts
function sjw_enqueue_frontend_assets() {
    // Enqueue Styles
    wp_enqueue_style(
        'intlTelInput-css',
        plugin_dir_url(__FILE__) . '../assets/frontend/css/intlTelInput.min.css',
        array(), 
        '3.16.27'
    );
   
    wp_enqueue_style(
        'uikit-css',
        plugin_dir_url(__FILE__) . '../assets/frontend/css/uikit.css',
        array(), // Dependencies
        '3.16.27' // Version
    );

    wp_enqueue_style(
        'sjw-frontend-css',
        plugin_dir_url(__FILE__) . '../assets/frontend/css/sjw-frontend.css',
        array('uikit-css'),
        '1.0.0' 
    );

    wp_enqueue_style(
        'sjw-frontend-all-css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css',
        array(), 
        '1.0.0'  
    );
	
	wp_enqueue_style(
        'sjw-frontend-font-awesome-css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        array(), 
        '1.0.0'  
    );

    wp_enqueue_style(
        'select2-css',
        plugin_dir_url(__FILE__) . '../assets/frontend/css/select2.min.css',
        array(), 
        '4.0.13' // Version for Select2
    );

    // Enqueue Scripts
    wp_enqueue_script(
        'intlTelInput-js',
        plugin_dir_url(__FILE__) . '../assets/frontend/js/intlTelInputWithUtils.min.js',
        array(), 
        '3.16.27', 
        true      
    );

    wp_enqueue_script(
        'uikit-js',
        plugin_dir_url(__FILE__) . '../assets/frontend/js/uikit.min.js',
        array(),
        '3.16.27', 
        true      
    );
   
    wp_enqueue_script(
        'uikit-icons-js',
        plugin_dir_url(__FILE__) . '../assets/frontend/js/uikit-icons.min.js',
        array('uikit-js'), 
        '3.16.27', 
        true       
    );
	
// 	 wp_enqueue_script(
//         'sjw-frontend-conditions-js',
//         plugin_dir_url(__FILE__) . '../assets/frontend/js/frontend-conditions.js',
//         array('jquery'),
//         '1.0.0', 
//         true     
//     );
   
    wp_enqueue_script(
        'sjw-frontend-js',
        plugin_dir_url(__FILE__) . '../assets/frontend/js/sjw-frontend.js',
        array('jquery', 'uikit-js'), 
        '1.0.0',  
        true      
    );

    wp_enqueue_script(
        'select2-js',
        plugin_dir_url(__FILE__) . '../assets/frontend/js/select2.full.min.js',
        array('jquery'), 
        '4.0.13', // Version for Select2
        true      
    );

    wp_enqueue_script(
        'custom-ajax-script',
        plugin_dir_url(__FILE__) . '../assets/frontend/js/custom-ajax.js', 
        array('jquery'), 
        null, 
        true
    );

    wp_localize_script('custom-ajax-script', 'sjw_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajax-job-filter'),
    ));
}
add_action('wp_enqueue_scripts', 'sjw_enqueue_frontend_assets');
