<div>
    <h2>Custom Email Template 5 Settings</h2>
    <div>
        <?php echo do_shortcode('[render_shortcodes_list]'); ?>
    </div>
    <form id="template_5_form" class="template_form" method="post">
        <?php
        render_common_email_template_fields([
            'email_from' => get_option('sjw_template_5_email_from', ''),
            'email_to' => get_option('sjw_template_5_email_to', ''),
            'subject' => get_option('sjw_template_5_subject', ''),
            'headers' => get_option('sjw_template_5_headers', ''),
            'message_body' => get_option('sjw_template_5_message_body', ''),
        ]);
        ?>
        <div class="email-template-section">
            <div class="sjw-submit-button-wrap">
                <button class="button button-primary button-large">Save Changes</button>
                <span class="spinner"></span>
            </div>
        </div>
    </form>
</div>
