<?php
/**
 * Send an HTML email using a common template.
 *
 * @param string $from The sender email address.
 * @param string $to The recipient email address.
 * @param string $subject The email subject.
 * @param string $body_content The main body content of the email (HTML allowed).
 * @param array $headers Additional headers (optional).
 * @param array $attachments Attachments to include (optional).
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_common_email($from, $to, $subject, $body_content, $header) {
	// Get the site title
    $site_title = get_bloginfo('name');
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . $site_title . ' <' . $from . '>';

    // Parse the header string into individual lines if it's a string
    if (is_string($header)) {
        $header_lines = explode("\n", $header);
        foreach ($header_lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $headers[] = $line;
            }
        }
    } elseif (is_array($header)) {
        // If it's already an array, merge it with existing headers
        $headers = array_merge($headers, $header);
    }

    // debug_logger('send_common_email', $headers);

    wp_mail( $to, $subject, $body_content, $headers );
}

function send_email_template($template_number) {
    $to = do_shortcode(get_option("sjw_template_{$template_number}_email_to", ''));
    $from = do_shortcode(get_option("sjw_template_{$template_number}_email_from", ''));
    $header = do_shortcode(get_option("sjw_template_{$template_number}_headers", ''));
    $subject = do_shortcode(get_option("sjw_template_{$template_number}_subject", ''));
    $body_content = wpautop(do_shortcode(get_option("sjw_template_{$template_number}_message_body", '')));

    send_common_email($from, $to, $subject, $body_content, $header);
}