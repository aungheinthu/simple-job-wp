<div class="wrap">
    <form id="sjw_general_form" method="post">
        <!-- Job Posts Per Page -->
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="sjw_posts_per_page">Jobs Posts Per Page</label>
                </th>
                <td>
                    <input
                        type="number"
                        id="sjw_posts_per_page"
                        name="sjw_posts_per_page"
                        value="<?php echo esc_attr(get_option('sjw_posts_per_page', 12)); ?>"
                        min="1"
                        class="small-text"
                    />
                    <p class="description">Set the number of job posts to display per page. Default is 12.</p>
                </td>
            </tr>

            <!-- Job Archive Page -->
            <tr>
                <th scope="row">
                    <label for="sjw_archive_page_id">Jobs Archive Page</label>
                </th>
                <td>
                    <select id="sjw_archive_page_id" name="sjw_archive_page_id">
                        <option value="">— Select a Page —</option>
                        <?php
                        $pages = get_pages(['post_status' => 'publish']);
                        $selected_page = get_option('sjw_archive_page_id', '');
                        foreach ($pages as $page) {
                            $selected = selected($page->ID, $selected_page, false);
                            echo "<option value='" . esc_attr($page->ID) . "' $selected>" . esc_html($page->post_title) . "</option>";
                        }
                        ?>
                    </select>
                    <p class="description">Select the page to be used as the Job Archive page.</p>
                </td>
            </tr>

            <!-- HR Email -->
            <tr>
                <th scope="row">
                    <label for="sjw_hr_email">HR Email</label>
                </th>
                <td>
                    <input
                        type="email"
                        id="sjw_hr_email"
                        name="sjw_hr_email"
                        value="<?php echo esc_attr(get_option('sjw_hr_email', '')); ?>"
                        class="regular-text"
                    />
                    <p class="description">Enter the HR email address to be used for job inquiries.</p>
                </td>
            </tr>

            <!-- Theme Color -->
            <tr>
                <th scope="row">
                    <label for="sjw_theme_color">Theme Color</label>
                </th>
                <td>
                    <input
                        type="color"
                        id="sjw_theme_color"
                        name="sjw_theme_color"
                        value="<?php echo esc_attr(get_option('sjw_theme_color', '#000000')); ?>"
                    />
                    <p class="description">Select the theme color for your site.</p>
                </td>
            </tr>

            <!-- Title Color -->
            <tr>
                <th scope="row">
                    <label for="sjw_title_color">Title Color</label>
                </th>
                <td>
                    <input
                        type="color"
                        id="sjw_title_color"
                        name="sjw_title_color"
                        value="<?php echo esc_attr(get_option('sjw_title_color', '#000000')); ?>"
                    />
                    <p class="description">Select the color for the job titles.</p>
                </td>
            </tr>

            <!-- Text Color -->
            <tr>
                <th scope="row">
                    <label for="sjw_text_color">Text Color</label>
                </th>
                <td>
                    <input
                        type="color"
                        id="sjw_text_color"
                        name="sjw_text_color"
                        value="<?php echo esc_attr(get_option('sjw_text_color', '#000000')); ?>"
                    />
                    <p class="description">Select the default text color for job posts.</p>
                </td>
            </tr>

            <!-- Allowed File Extensions -->
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="sjw_file_extensions">Allowed File Extensions</label>
                    </th>
                    <td>
                        <?php
                        $available_extensions = ['pdf', 'docx', 'jpg', 'png', 'txt'];
                        $selected_extensions = get_option('sjw_file_extensions', ['pdf', 'docx']); // Default selected extensions

                        foreach ($available_extensions as $extension) {
                            $checked = in_array($extension, $selected_extensions) ? 'checked' : '';
                            echo "<label>
                                    <input type='checkbox' name='sjw_file_extensions[]' value='" . esc_attr($extension) . "' $checked />
                                    " . strtoupper($extension) . "
                                </label><br />";
                        }
                        ?>
                        <p class="description">Select the file extensions allowed for uploads.</p>
                    </td>
                </tr>
            </table>

        <div class="sjw-submit-button-wrap">
            <button class="button button-primary button-large">Save Changes</button>
            <span class="spinner"></span>
        </div>
    </form>
</div>