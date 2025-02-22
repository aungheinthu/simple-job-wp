<div>
    <h2>Custom Email Template 4 Settings</h2>
    <div>
        <?php echo do_shortcode('[render_shortcodes_list]'); ?>
    </div>
    <form id="template_4_form" class="template_form" method="post">
        <?php
        render_common_email_template_fields([
            'email_from' => get_option('sjw_template_4_email_from', ''),
            'email_to' => get_option('sjw_template_4_email_to', ''),
            'subject' => get_option('sjw_template_4_subject', ''),
            'headers' => get_option('sjw_template_4_headers', ''),
            'message_body' => get_option('sjw_template_4_message_body', ''),
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
