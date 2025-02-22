<?php

function sjw_add_prefix_data(){
    // delete_option('sjw_application_form_fields');

    $sjw_application_form_fields = get_option('sjw_application_form_fields', []);
    if(empty($sjw_application_form_fields)){
    
        $fields = [
            [
                'type' => 'radio',
                'label' => 'If you would like to refer this job to your friend, please select the checkbox.',
                'placeholder' => '',
                'options' => 'Yes, No',
                'enable_require' => 'on',
                'enable_condition' => 'off',
                'condition_field' => '',
                'condition_operator' => '',
                'condition_value' => '',
                'position' => 0,
                'field_id' => 'sjw_refer_a_friend',
                'status' => 'default',
            ],
            [
                'type' => 'text',
                'label' => 'Referer Name',
                'placeholder' => 'Enter your name',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'on',
                'condition_field' => 'sjw_refer_a_friend',
                'condition_operator' => 'equals',
                'condition_value' => 'Yes',
                'position' => 1,
                'field_id' => 'sjw_referer_name',
                'status' => 'default',
            ],
            [
                'type' => 'email',
                'label' => 'Referer Email',
                'placeholder' => 'Enter your email',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'on',
                'condition_field' => 'sjw_refer_a_friend',
                'condition_operator' => 'equals',
                'condition_value' => 'Yes',
                'position' => 2,
                'field_id' => 'sjw_referer_email',
                'status' => 'default',
            ],
            [
                'type' => 'tel',
                'label' => 'Referer Contact No',
                'placeholder' => 'Enter your contact number',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'on',
                'condition_field' => 'sjw_refer_a_friend',
                'condition_operator' => 'equals',
                'condition_value' => 'Yes',
                'position' => 3,
                'field_id' => 'sjw_referer_contact_no',
                'status' => 'default',
            ],
            [
                'type' => 'text',
                'label' => 'Candidate Name',
                'placeholder' => 'Enter candidate name',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'off',
                'condition_field' => '',
                'condition_operator' => '',
                'condition_value' => '',
                'position' => 4,
                'field_id' => 'sjw_candidate_name',
                'status' => 'default',
            ],
            [
                'type' => 'tel',
                'label' => 'Applicant Contact Number',
                'placeholder' => 'Enter applicant contact number',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'off',
                'condition_field' => '',
                'condition_operator' => '',
                'condition_value' => '',
                'position' => 5,
                'field_id' => 'sjw_applicant_contact_number',
                'status' => 'default',
            ],
            [
                'type' => 'email',
                'label' => 'Email Address',
                'placeholder' => 'Enter email address',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'off',
                'condition_field' => '',
                'condition_operator' => '',
                'condition_value' => '',
                'position' => 6,
                'field_id' => 'sjw_email_address',
                'status' => 'default',
            ],
            [
                'type' => 'text',
                'label' => 'Nationality',
                'placeholder' => 'Enter nationality',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'off',
                'condition_field' => '',
                'condition_operator' => '',
                'condition_value' => '',
                'position' => 7,
                'field_id' => 'sjw_nationality',
                'status' => 'default',
            ],
            [
                'type' => 'text',
                'label' => 'Current Designation and Company',
                'placeholder' => 'Enter current designation and company',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'off',
                'condition_field' => '',
                'condition_operator' => '',
                'condition_value' => '',
                'position' => 8,
                'field_id' => 'sjw_current_designation_and_company',
                'status' => 'default',
            ],
            [
                'type' => 'text',
                'label' => 'Role you would like to refer him/her for',
                'placeholder' => 'Enter role',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'on',
                'condition_field' => 'sjw_refer_a_friend',
                'condition_operator' => 'equals',
                'condition_value' => 'Yes',
                'position' => 9,
                'field_id' => 'sjw_role_to_refer',
                'status' => 'default',
            ],
            [
                'type' => 'file',
                'label' => 'Resume',
                'placeholder' => '',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'off',
                'condition_field' => '',
                'condition_operator' => '',
                'condition_value' => '',
                'position' => 10,
                'field_id' => 'sjw_resume',
                'status' => 'default',
            ],
        ];
        
        update_option('sjw_application_form_fields', $fields);
    }
    
    $sjw_confirm_form_fields= get_option('sjw_confirm_form_fields', []);
    if(empty($sjw_confirm_form_fields)){
        $fields = [
            [
                'type' => 'text',
                'label' => 'Full Name',
                'placeholder' => '',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'off',
                'condition_field' => '',
                'condition_operator' => '',
                'condition_value' => '',
                'position' => 0,
                'field_id' => 'sjw_full_name',
                'status' => 'default',
            ],
            [
                'type' => 'text',
                'label' => 'NRIC',
                'placeholder' => '',
                'options' => '',
                'enable_require' => 'on',
                'enable_condition' => 'off',
                'condition_field' => '',
                'condition_operator' => '',
                'condition_value' => '',
                'position' => 0,
                'field_id' => 'sjw_nric',
                'status' => 'default',
            ],
        ];
        
        update_option('sjw_confirm_form_fields', $fields);
    }

    // Get Theme Color, Title Color, and Text Color
    $sjw_theme_color = get_option('sjw_theme_color', ''); 
    $sjw_title_color = get_option('sjw_title_color', ''); 
    $sjw_text_color = get_option('sjw_text_color', ''); 
    $sjw_application_thank_you_message = get_option('sjw_application_thank_you_message', '');
    $sjw_confirm_thank_you_message = get_option('sjw_confirm_thank_you_message', '');
    $sjw_file_extensions = get_option('sjw_file_extensions', []);

    // Check if Theme Color is set, otherwise update it
    if (empty($sjw_theme_color)) {
        $sjw_theme_color = '#02A8B6'; // Default theme color
        update_option('sjw_theme_color', $sjw_theme_color); // Update the option
    }

    // Check if Title Color is set, otherwise update it
    if (empty($sjw_title_color)) {
        $sjw_title_color = '#000000'; // Default title color (black)
        update_option('sjw_title_color', $sjw_title_color); // Update the option
    }

    // Check if Text Color is set, otherwise update it
    if (empty($sjw_text_color)) {
        $sjw_text_color = '#000000'; // Default text color (black)
        update_option('sjw_text_color', $sjw_text_color); // Update the option
    }

    // Check if the Application Thank You Message is set, otherwise update it
    if (empty($sjw_application_thank_you_message)) {
        $sjw_application_thank_you_message = 'Thank you for your submission! We will get back to you soon.'; // Default message
        update_option('sjw_application_thank_you_message', $sjw_application_thank_you_message); // Update the option
    }

    // Check if the Confirmation Thank You Message is set, otherwise update it
    if (empty($sjw_confirm_thank_you_message)) {
        $sjw_confirm_thank_you_message = 'Your application has been successfully submitted!'; // Default confirmation message
        update_option('sjw_confirm_thank_you_message', $sjw_confirm_thank_you_message); // Update the option
    }

    // Check if the File Extensions are set, otherwise update it
    if (empty($sjw_file_extensions)) {
        $sjw_file_extensions = ['pdf', 'docx']; // Default allowed file extensions
        update_option('sjw_file_extensions', $sjw_file_extensions); // Update the option
    }

    $filter_options = [
        'sjw_enable_category_filter',
        'sjw_enable_type_filter',
        'sjw_enable_location_filter',
        'sjw_enable_search_bar'
    ];

    // Loop through each filter option and save its value
    foreach ($filter_options as $option) {
        if(empty(get_option($option))){
            $value = 'yes'; // Always 'yes' by default
            update_option($option, 'yes');
        }
    }
}

sjw_add_prefix_data();