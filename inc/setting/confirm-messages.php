<div class="wrap">
    <form id="confirm_messages_form" method="post">
        <table class="form-table">
            <!-- Thank You Message -->
            <tr>
                <th>
                    <label for="sjw_confirm_thank_you_message">Thank You Message</label>
                </th>
                <td>
                    <?php
                    $sjw_confirm_thank_you_message = get_option('sjw_confirm_thank_you_message', 'Thank you for your submission! We will get back to you soon.');
                    wp_editor(
                        $sjw_confirm_thank_you_message,
                        'sjw_confirm_thank_you_message', // ID of the editor
                        [
                            'textarea_name' => 'sjw_confirm_thank_you_message', // Name attribute for form submission
                            'textarea_rows' => 16,
                            'media_buttons' => true, // Enable "Add Media" button for adding images/files
                            // 'teeny' => false, // Disable simplified toolbar for full functionality
                            // 'tinymce' => [
                            //     'toolbar1' => 'formatselect,bold,italic,underline,strikethrough,bullist,numlist,alignleft,aligncenter,alignright,link,unlink,undo,redo,code',
                            //     'toolbar2' => 'blockquote,hr,forecolor,backcolor,removeformat,charmap,outdent,indent,table',
                            //     'block_formats' => 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre',
                            //     'extended_valid_elements' => '*[*]', // Allow all HTML tags and attributes
                            // ],
                            'quicktags' => true, // Enable the "Text" (HTML) tab for raw editing
                            'editor_height' => 288, 
                        ]
                    );
                    ?>
                    <p class="description">Compose the thank-you message with full HTML access, including custom tags and attributes.</p>
                </td>
            </tr>

            <!-- Redirect Page -->
            <tr>
                <th>
                    <label for="sjw_confirm_redirect_page">Redirect Page</label>
                </th>
                <td>
                <?php
                $sjw_confirm_redirect_page = get_option('sjw_confirm_redirect_page', '');

                // Fetch all published pages
                $pages = get_pages([
                    'post_status' => 'publish',
                ]);

                // Filter pages to include only those that do not have the 'sjw_confirm_page' meta key
                $filtered_pages = array_filter($pages, function($page) {
                    // Get the meta value for 'sjw_confirm_page' (returns false if not found)
                    $confirm_page_meta = get_post_meta($page->ID, 'sjw_confirm_page', true);
                    
                    // Include page if 'sjw_confirm_page' does not exist (empty or false)
                    return empty($confirm_page_meta);
                });

                ?>

                <select id="sjw_confirm_redirect_page" name="sjw_confirm_redirect_page">
                    <option value="">— Select a Page —</option>
                    <?php foreach ($filtered_pages as $page): ?>
                        <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($page->ID, $sjw_confirm_redirect_page); ?>>
                            <?php echo esc_html($page->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                    <p class="description">Select the page to redirect users to after form submission.</p>
                </td>
            </tr>
        </table>

        <div class="sjw-submit-button-wrap">
            <button class="button button-primary button-large">Save Changes</button>
            <span class="spinner"></span>
        </div>
    </form>
</div>
