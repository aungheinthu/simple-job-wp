<form id="sjw-socials" method="post">
    <div>
        <h3>Select and Sort Social Platforms:</h3>
        <ul id="sjw-sortable-socials" class="sortable" style="list-style-type: none; padding: 0;">
            <?php
            // Get the selected socials from the database
            $selected_socials = get_option('sjw_selected_socials', []);

            // Predefined list of social platforms
            $socials = [
                'a2a_button_facebook',
                'a2a_button_whatsapp',
                'a2a_button_wechat',
                'a2a_button_x',
                'a2a_button_line',
                'a2a_button_copy_link',
                'a2a_button_email',
                'a2a_button_linkedin',
            ];

            // If there are selected socials, adjust the list accordingly
            if (!empty($selected_socials)) {
                // Find the difference between the selected and the predefined list
                $diff_result = array_diff($socials, $selected_socials);

                // Merge the selected socials with the ones that are not selected, if any
                if (!empty($diff_result)) {
                    $socials = array_merge($selected_socials, $diff_result);
                } else {
                    $socials = $selected_socials; // If no difference, use the selected socials
                }
            }

            // Output the list of social platforms
            foreach ($socials as $social): ?>
                <li class="social-sortable-item" id="<?php echo esc_attr($social); ?>" style="margin-bottom: 10px; cursor: move;">
                    <label>
                        <input type="checkbox" <?php echo (in_array($social, $selected_socials)) ? 'checked' : ''; ?> name="socials[]" value="<?php echo esc_attr($social); ?>">
                        <?php echo ucfirst(str_replace('a2a_button_', '', $social)); ?>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Hidden input field to store the order of the socials -->
        <input type="hidden" id="sjw-sorted-socials" name="sjw_sorted_socials" value="">
    </div>
    <br>
    <div class="sjw-submit-button-wrap">
        <button id="sjw_save_socials" class="button button-primary button-large">Save Changes</button>
        <span class="spinner"></span>
    </div>
</form>
