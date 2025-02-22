<?php
function render_common_email_template_fields($options = []) {
    $defaults = [
        'email_to' => '',
        'email_from' => '',
        'subject' => '',
        'headers' => '',
        'message_body' => '',
    ];

    $options = wp_parse_args($options, $defaults);

    ?>

    <div class="email-template-section">
        <label for="email_from"><strong>Email From:</strong></label>
        <input 
            type="text" 
            id="email_from" 
            name="email_from" 
            class="regular-text"
            value="<?php echo esc_attr($options['email_from']); ?>" 
        />
        <p class="description">Enter the sender email address. This will appear as the "From" address in the email.</p>
    </div>

    <div class="email-template-section">
        <label for="email_to"><strong>Email To:</strong></label>
        <input 
            type="text" 
            id="email_to" 
            name="email_to" 
            class="regular-text"
            value="<?php echo esc_attr($options['email_to']); ?>" 
        />
        <p class="description">Enter the recipient email address. Separate multiple addresses with commas.</p>
    </div>

    <div class="email-template-section">
        <label for="subject"><strong>Subject:</strong></label>
        <input 
            type="text" 
            id="subject" 
            name="subject" 
            class="regular-text"
            value="<?php echo esc_attr($options['subject']); ?>" 
        />
    </div>

    <div class="email-template-section">
        <label for="headers"><strong>Additional Headers:</strong></label>
        <textarea 
            id="headers" 
            name="headers" 
            rows="5" 
            class="large-text"
        ><?php echo esc_textarea($options['headers']); ?></textarea>
        <p class="description">Add any additional headers for the email, such as <code>Reply-To:</code>, etc.</p>
    </div>

    <div class="email-template-section">
        <label for="message_body"><strong>Message Body:</strong></label>
        <!-- <textarea 
            id="message_body" 
            name="message_body" 
            rows="18" 
            class="large-text"
        ><?php // echo esc_textarea($options['message_body']); ?></textarea>
        -->
        <?php
        wp_editor(
            $options['message_body'], // Content to pre-fill
            'message_body_'. random_int(0, 999),           // Unique ID for the editor
            [
                'textarea_name' => 'message_body', // Name of the <textarea>
                'textarea_rows' => 10,            // Number of rows visible in the editor
                'media_buttons' => true,          // Show the 'Add Media' button
                'editor_class' => 'large-text',   // Additional classes for the editor
                'editor_height' => 288, 
            ]
        );
        ?>

        

    </div>
    <?php
}

// Register the AJAX action for logged-in users
add_action('wp_ajax_sjw_save_template_form', 'handle_sjw_save_template_form');

function handle_sjw_save_template_form() {
    // Verify nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sjw_nonce_action')) {
        wp_send_json_error(['message' => 'Nonce verification failed']);
    }

    // Get the template number from the request
    $template_number = isset($_POST['template_number']) ? intval($_POST['template_number']) : 1;

    // Get the form data based on the template number
    $email_to       = sanitize_text_field($_POST['email_to']);
    $email_from     = sanitize_text_field($_POST['email_from']);
    $subject        = wp_unslash($_POST['subject']);
    $headers        = sanitize_textarea_field($_POST['headers']);
	// $message_body   = sanitize_textarea_field($_POST['message_body']);
    $message_body = wp_unslash($_POST['message_body']);
    $message_body = wp_kses($message_body, wp_kses_allowed_html('post'));

    // Save the values in WordPress options for the relevant template
    update_option("sjw_template_{$template_number}_email_to", $email_to);
    update_option("sjw_template_{$template_number}_email_from", $email_from);
    update_option("sjw_template_{$template_number}_subject", $subject);
    update_option("sjw_template_{$template_number}_headers", $headers);
    update_option("sjw_template_{$template_number}_message_body", $message_body);

    // Send a success response
    wp_send_json_success([
        'message' => "Template {$template_number} settings saved successfully."
    ]);
}

