<?php
$saved_fields = get_option('sjw_confirm_form_fields', []); // Retrieve saved fields for confirmation form
?>
<div class="wrap">
    <form method="POST" action="" id="sjw_confirm_form">
        <div id="sjw_dynamic_fields_container" class="sjw_sortable sjw-dynamic-fields-container">
            <?php if (!empty($saved_fields)) : ?>
                <?php foreach ($saved_fields as $index => $field) : ?>
                    <div class="dynamic-field">
                        <!-- Part 1: Field Selection and Basic Details -->
                        <div class="part-1">
                            <span class="drag-handle dashicons dashicons-move"></span>
                            <select name="field_type[]" required class="field-type-select">
                                <option value="" disabled selected>Select Field Type</option>
                                <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
                                <option value="textarea" <?php selected($field['type'], 'textarea'); ?>>Textarea</option>
                                <option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
                                <option value="tel" <?php selected($field['type'], 'tel'); ?>>Phone</option>
                                <option value="radio" <?php selected($field['type'], 'radio'); ?>>Radio</option>
                                <option value="checkbox" <?php selected($field['type'], 'checkbox'); ?>>Checkbox</option>
                                <option value="select" <?php selected($field['type'], 'select'); ?>>Select</option>
                                <option value="true_false" <?php selected($field['type'], 'true_false'); ?>>True/False</option>
								<option value="acceptance" <?php selected($field['type'], 'acceptance'); ?>>Acceptance</option>
                                <option value="file" <?php selected($field['type'], 'file'); ?>>File</option>
                            </select>
                            <input type="text" class="field_id" name="field_id[]" placeholder="Enter the field ID here" value="<?php echo esc_attr($field['field_id']); ?>" required <?php echo ($field['status'] == 'default') ? 'readonly' : ''; ?>>
                            <input type="text" name="field_label[]" placeholder="Field Label" value="<?php echo esc_attr($field['label']); ?>" required>
                            <input type="text" name="field_placeholder[]" placeholder="Placeholder (for text/textarea)" value="<?php echo esc_attr($field['placeholder']); ?>">
                            <input type="hidden" name="field_status[]" value="<?php echo ($field['status'] == 'default') ? 'default' : 'custom'; ?>">
                            <input type="hidden" name="field_position[]" value="<?php echo $index; ?>">
                        </div>

                        <!-- Part 2: Options (Visible for select/checkbox fields) -->
                        <div class="part-2">
                            <label class="options-label" <?php echo ($field['type'] === 'select' || $field['type'] === 'checkbox' || $field['type'] === 'radio') ? '' : 'style="display: none;"'; ?>>Options (for select/checkbox):</label>
                            <input type="text" name="field_options[]" placeholder="Comma-separated values" value="<?php echo esc_attr($field['options']); ?>" class="field-options" <?php echo ($field['type'] === 'select' || $field['type'] === 'checkbox' || $field['type'] === 'radio') ? '' : 'style="display: none;"'; ?>>
                        </div>

                        <!-- Part 3: Required & Conditional Logic -->
                        <div class="part-3">
                            <label>
                                <input type="hidden" name="field_enable_require[<?php echo $index; ?>]" value="off">
                                <input type="checkbox" name="field_enable_require[<?php echo $index; ?>]" class="enable-require" value="on" <?php checked($field['enable_require'], 'on'); ?>>
                                Enable Required
                            </label>
                            <label>
                                <input type="hidden" name="field_enable_condition[<?php echo $index; ?>]" value="off">
                                <input type="checkbox" name="field_enable_condition[<?php echo $index; ?>]" class="enable-condition" value="on" <?php checked($field['enable_condition'], 'on'); ?>>
                                Enable Conditional Logic
                            </label>

                            <!-- Conditional Logic Fields -->
                            <div class="condition-fields" style="display: <?php echo ($field['enable_condition'] === 'on') ? 'block' : 'none'; ?>;">
                                <div class="condition-fields-wrapper">
                                    <label>
                                        Show If (Condition Field):
                                        <select name="field_condition_field[]">
                                            <option value="">None</option>
                                            <?php foreach ($saved_fields as $key => $condition_field) : ?>
                                                <?php if ($index !== $key) : ?>
                                                    <option value="<?php echo esc_attr($condition_field['field_id']); ?>" <?php selected($field['condition_field'], $condition_field['field_id']); ?>>
                                                        <?php echo esc_html($condition_field['label']); ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    <label>
                                        Condition:
                                        <select name="field_condition_operator[]">
                                            <option value="equals" <?php selected($field['condition_operator'], 'equals'); ?>>Equals</option>
                                            <option value="not_equals" <?php selected($field['condition_operator'], 'not_equals'); ?>>Not Equals</option>
                                            <option value="greater_than" <?php selected($field['condition_operator'], 'greater_than'); ?>>Greater Than</option>
                                            <option value="less_than" <?php selected($field['condition_operator'], 'less_than'); ?>>Less Than</option>
                                            <option value="contains" <?php selected($field['condition_operator'], 'contains'); ?>>Contains</option>
                                            <option value="not_contains" <?php selected($field['condition_operator'], 'not_contains'); ?>>Not Contains</option>
                                        </select>
                                    </label>
                                    <label>
                                        Condition Value:
                                        <input type="text" name="field_condition_value[]" placeholder="Condition Value" value="<?php echo esc_attr($field['condition_value']); ?>">
                                    </label>
                                </div>
                            </div>

                            <!-- Remove Button (if not default) -->
                            <?php if($field['status'] !== 'default'): ?>
                                <button type="button" class="remove-field button-link-delete">Remove</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" class="sjw-add-field-button button">Add Field</button>

        <div class="sjw-submit-button-wrap">
            <button id="sjw_save_confirm_fields" class="button button-primary button-large">Save Changes</button>
            <span class="spinner"></span>
        </div>
    </form>
</div>
