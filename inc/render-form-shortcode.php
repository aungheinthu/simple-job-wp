<?php
// Render frontend form for application
add_shortcode('sjw_application_form', 'render_sjw_application_form');
function render_sjw_application_form() {
    $form_fields = get_option('sjw_application_form_fields', []);

    if (empty($form_fields)) {
        return '<p>No form fields available.</p>';
    }

    ob_start();
    ?>
	<div id="sjw-thank-you-message" class="uk-card uk-card-primary uk-card-body sjw-thank-you-message" style="display: none;">
        <?php
        // Retrieve the thank-you message from the database
        $thank_you_message = get_option('sjw_application_thank_you_message', 'Thank you for your submission! We will get back to you soon.');
        
        // Display the message with proper escaping
        echo wp_kses_post($thank_you_message); // Allow safe HTML in the message
        ?>
    </div>


    <form class="sjw-form" id="sjw_application_form" method="post" action="">
        <input type="hidden" name="sjw_job_id" value="<?php echo get_the_ID() ?>">

        <?php foreach ($form_fields as $field): ?>
            <?php
            $is_required = ($field['enable_require'] === 'on') ? 'required' : '';
            ?>
            <div class="uk-margin form-field" 
                data-enable-condition="<?php echo esc_attr($field['enable_condition']); ?>"
                data-condition-field="<?php echo esc_attr($field['condition_field']); ?>" 
                data-condition-operator="<?php echo esc_attr($field['condition_operator']); ?>" 
                data-condition-value="<?php echo esc_attr($field['condition_value']); ?>"
                data-is-require="<?php echo $is_required; ?>"
                style="display: <?php echo ($field['enable_condition'] === 'on' && !empty($field['condition_field'])) ? 'none' : 'block'; ?>;">
                <?php if($field['type'] != 'acceptance'): ?>
                <label class="uk-form-label" for="<?php echo esc_attr($field['field_id']); ?>">
                    <?php echo esc_html($field['label']); ?>
                    <?php if ($is_required): ?>
                        <span style="color: red;">*</span>
                    <?php endif; ?>
                </label>
				<?php endif; ?>
                <div class="uk-form-controls uk-display-block uk-width-1-1" <?php echo ($field['type'] === 'file') ? 'uk-form-custom="target: true"' : ''; ?>>
                    <?php switch ($field['type']):
                        case 'true_false': ?>
                            <input type="hidden" name="<?php echo esc_attr($field['field_id']); ?>" value="False"> <!-- Default value -->
                            <label>
                                <input class="uk-checkbox" type="checkbox" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" value="True" <?php echo $is_required; ?>>
                            </label>
                            <?php break;
                        case 'text': ?>
                            <input class="uk-input" type="text" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" placeholder="<?php echo esc_attr($field['placeholder']); ?>" <?php echo $is_required; ?>>
                            <?php break;
                        case 'textarea': ?>
                            <textarea rows="7" class="uk-textarea" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" placeholder="<?php echo esc_attr($field['placeholder']); ?>" <?php echo $is_required; ?>></textarea>
                            <?php break;
                        case 'email': ?>
                            <input class="uk-input" type="email" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" placeholder="<?php echo esc_attr($field['placeholder']); ?>" <?php echo $is_required; ?>>
                            <?php break;
                        case 'tel': ?>
                            <input class="uk-input phone-field" type="tel" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" <?php echo $is_required; ?>>
                            <?php break;
						case 'acceptance': ?>
							<div class="uk-margin">
                            <input type="hidden"
										   name="<?php echo esc_attr($field['field_id']); ?>" 
										   value="No">
								<label class="uk-flex uk-flex-middle">
									<input class="uk-checkbox" type="checkbox" id="<?php echo esc_attr($field['field_id']); ?>" 
										   name="<?php echo esc_attr($field['field_id']); ?>" 
										   value="Yes" checked <?php echo $is_required; ?>>
									<span class="uk-margin-small-left"> 
                                        <?php echo wp_kses_post($field['label']); ?> 
                                        <?php if ($is_required): ?>
                                            <span style="color: red;"> *</span>
                                        <?php endif; ?>
                                    </span> 
								</label>
							</div>
							<?php break;
                        case 'radio': 
                            $options = explode(',', $field['options']); ?>
                            <div class="uk-form-controls uk-grid-small uk-child-width-auto uk-grid">
                                <?php foreach ($options as $index => $option): ?>
                                    <label>
                                        <input class="uk-radio" type="radio" name="<?php echo esc_attr($field['field_id']); ?>" value="<?php echo esc_attr(trim($option)); ?>" <?php echo $is_required; ?> <?php if ($index === 0) echo 'checked'; ?>>
                                        <?php echo esc_html(trim($option)); ?>
                                    </label><br>
                                <?php endforeach; ?>
                            </div>
                            <?php break;
                        case 'radio': 
                            $options = explode(',', $field['options']); ?>
                           <div class="uk-form-controls uk-grid-small uk-child-width-auto uk-grid">
                               <?php foreach ($options as $option): ?>
                                   <label>
                                       <input class="uk-radio" type="radio" name="<?php echo esc_attr($field['field_id']); ?>" value="<?php echo esc_attr(trim($option)); ?>" <?php echo $is_required; ?>>
                                       <?php echo esc_html(trim($option)); ?>
                                   </label><br>
                               <?php endforeach; ?>
                           </div>
                           <?php break;
                        case 'select': 
                            $options = explode(',', $field['options']); ?>
                            <select class="uk-select" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" <?php echo $is_required; ?>>
                                <?php foreach ($options as $option): ?>
                                    <option value="<?php echo esc_attr(trim($option)); ?>"><?php echo esc_html(trim($option)); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php break;
                        case 'file': 
                            // Get allowed file extensions, defaulting to 'pdf' and 'docx'
                            $allowed_extensions = get_option('sjw_file_extensions', ['pdf', 'docx']);
                            
                            // Generate the accept attribute by joining the extensions with commas
                            $accept_attribute = '.' . implode(',.', $allowed_extensions);
                        ?>
                                <input type="file" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" required aria-label="Custom controls" <?php echo $is_required; ?> accept="<?php echo esc_attr($accept_attribute); ?>">
                                <input class="uk-input" type="text" placeholder="Select file (<?php echo esc_attr($accept_attribute); ?>)" aria-label="Custom controls" disabled>
                            <?php break;
                    endswitch; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="uk-margin-top uk-flex uk-flex-left" uk-margin>
            <button type="submit" class="uk-button uk-button-primary">
				<div class="uk-flex uk-flex-center uk-flex-middle">
					<i class="fa fa-spinner fa-spin"></i> Submit
				</div>
			</button>
        </div>
    </form>
    <?php
    return ob_get_clean();
}


add_shortcode('sjw_confirm_form', 'render_sjw_confirm_form');
function render_sjw_confirm_form() {
    $form_fields = get_option('sjw_confirm_form_fields', []);

    if (empty($form_fields)) {
        return '<p>No form fields available.</p>';
    }

    ob_start();
    ?>
	<div id="sjw-thank-you-message" class="uk-card uk-card-primary uk-card-body sjw-thank-you-message" style="display: none;">
        <?php
        // Retrieve the thank-you message from the database
        $thank_you_message = get_option('sjw_confirm_thank_you_message', 'Thank you for your submission! We will get back to you soon.');
        
        // Display the message with proper escaping
        echo wp_kses_post($thank_you_message); // Allow safe HTML in the message
        ?>
	</div>
    <form class="sjw-form" id="sjw_confirm_form" method="post" action="">
        
        <input type="hidden" name="application_id" value="<?php echo $_GET['pid']; ?>">

        <?php foreach ($form_fields as $field): ?>
            <?php
            $is_required = ($field['enable_require'] === 'on') ? 'required' : '';
            ?>
            <div class="uk-margin form-field" 
                data-enable-condition="<?php echo esc_attr($field['enable_condition']); ?>"
                data-condition-field="<?php echo esc_attr($field['condition_field']); ?>" 
                data-condition-operator="<?php echo esc_attr($field['condition_operator']); ?>" 
                data-condition-value="<?php echo esc_attr($field['condition_value']); ?>"
                data-is-require="<?php echo $is_required; ?>"
                style="display: <?php echo ($field['enable_condition'] === 'on' && !empty($field['condition_field'])) ? 'none' : 'block'; ?>;">
                <?php if($field['type'] != 'acceptance'): ?>
                <label class="uk-form-label" for="<?php echo esc_attr($field['field_id']); ?>">
                    <?php echo wp_kses_post($field['label']); ?>
                    <?php if ($is_required): ?>
                        <span style="color: red;">*</span>
                    <?php endif; ?>
                </label>
				<?php endif; ?>
                <div class="uk-form-controls uk-display-block uk-width-1-1" <?php echo ($field['type'] === 'file') ? 'uk-form-custom="target: true"' : ''; ?>>
                    <?php switch ($field['type']):
                        case 'true_false': ?>
                            <input type="hidden" name="<?php echo esc_attr($field['field_id']); ?>" value="False"> <!-- Default value -->
                            <label>
                                <input class="uk-checkbox" type="checkbox" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" value="True" <?php echo $is_required; ?>>
                            </label>
                            <?php break;
                        case 'text': ?>
                            <input class="uk-input" type="text" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" placeholder="<?php echo esc_attr($field['placeholder']); ?>" <?php echo $is_required; ?>>
                            <?php break;
                        case 'textarea': ?>
                            <textarea rows="7" class="uk-textarea" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" placeholder="<?php echo esc_attr($field['placeholder']); ?>" <?php echo $is_required; ?>></textarea>
                            <?php break;
                        case 'email': ?>
                            <input class="uk-input" type="email" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" placeholder="<?php echo esc_attr($field['placeholder']); ?>" <?php echo $is_required; ?>>
                            <?php break;
                        case 'tel': ?>
                            <input class="uk-input phone-field" type="tel" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" <?php echo $is_required; ?>>
                            <?php break;
						case 'acceptance': ?>
							<div class="uk-margin">
                            <input type="hidden"
										   name="<?php echo esc_attr($field['field_id']); ?>" 
										   value="No">
								<label class="uk-flex uk-flex-middle">
									<input class="uk-checkbox" type="checkbox" id="<?php echo esc_attr($field['field_id']); ?>" 
										   name="<?php echo esc_attr($field['field_id']); ?>" 
										   value="Yes" checked <?php echo $is_required; ?>>
									<span class="uk-margin-small-left"> 
                                        <?php echo esc_html($field['label']); ?> 
                                        <?php if ($is_required): ?>
                                            <span style="color: red;"> *</span>
                                        <?php endif; ?>
                                    </span> 
								</label>
							</div>
							<?php break;
                        case 'checkbox': 
                             $options = explode(',', $field['options']); ?>
                            <div class="uk-form-controls uk-grid-small uk-child-width-auto uk-grid">
                                <?php foreach ($options as $option): ?>
                                    <label>
                                        <input class="uk-checkbox" type="checkbox" name="<?php echo esc_attr($field['field_id']); ?>[]" value="<?php echo esc_attr(trim($option)); ?>" <?php echo $is_required; ?>>
                                        <?php echo esc_html(trim($option)); ?>
                                    </label><br>
                                <?php endforeach; ?>
                            </div>
                            <?php break;
                        case 'radio': 
                            $options = explode(',', $field['options']); ?>
                            <div class="uk-form-controls uk-grid-small uk-child-width-auto uk-grid">
                                <?php foreach ($options as $index => $option): ?>
                                    <label>
                                        <input class="uk-radio" type="radio" name="<?php echo esc_attr($field['field_id']); ?>" value="<?php echo esc_attr(trim($option)); ?>" <?php echo $is_required; ?> <?php if ($index === 0) echo 'checked'; ?>>
                                        <?php echo esc_html(trim($option)); ?>
                                    </label><br>
                                <?php endforeach; ?>
                            </div>
                            <?php break;
                        case 'select': 
                            $options = explode(',', $field['options']); ?>
                            <select class="uk-select" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" <?php echo $is_required; ?>>
                                <?php foreach ($options as $option): ?>
                                    <option value="<?php echo esc_attr(trim($option)); ?>"><?php echo esc_html(trim($option)); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php break;
                        case 'file': 
                            // Get allowed file extensions, defaulting to 'pdf' and 'docx'
                            $allowed_extensions = get_option('sjw_file_extensions', ['pdf', 'docx']);
                            
                            // Generate the accept attribute by joining the extensions with commas
                            $accept_attribute = '.' . implode(',.', $allowed_extensions);
                        ?>
                                <input type="file" id="<?php echo esc_attr($field['field_id']); ?>" name="<?php echo esc_attr($field['field_id']); ?>" required aria-label="Custom controls" <?php echo $is_required; ?> accept="<?php echo esc_attr($accept_attribute); ?>">
                                <input class="uk-input" type="text" placeholder="Select file (<?php echo esc_attr($accept_attribute); ?>)" aria-label="Custom controls" disabled>
                            <?php break;
                    endswitch; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="uk-margin-top uk-flex uk-flex-left" uk-margin>
            <button type="submit" class="uk-button uk-button-primary uk-width-1-1">
				<div class="uk-flex uk-flex-center uk-flex-middle">
					<i class="fa fa-spinner fa-spin"></i> Submit
				</div>
			</button>
        </div>
    </form>
    <?php
    return ob_get_clean();
}