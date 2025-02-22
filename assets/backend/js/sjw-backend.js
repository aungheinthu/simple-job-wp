document.addEventListener('DOMContentLoaded', function() {
    const tabItems = document.querySelectorAll('.sjw_setting_tab-item');
    const tabPanels = document.querySelectorAll('.sjw_setting_tab-panel');
    const subTabItems = document.querySelectorAll('.sjw_setting_sub-tab-item');
    const subTabPanels = document.querySelectorAll('.sjw_setting_sub-tab-panel');
    const subTabLists = document.querySelector('.sjw_setting_sub-tab-list');

    // Function to switch tabs
    function showTab(targetTab) {
        // Hide all tab panels
        tabPanels.forEach(panel => panel.style.display = 'none');
        // Remove active class from all tabs
        tabItems.forEach(tab => tab.classList.remove('active'));
        // Show the selected tab panel
        document.getElementById(targetTab + '-content').style.display = 'block';
        
        // Mark the tab as active
        document.querySelector(`[data-tab="${targetTab}"]`).classList.add('active');
        
        // Check if the current tab has sub-tabs and initialize the first one
        const currentTabPanel = document.getElementById(targetTab + '-content');
        const currentSubTabList = currentTabPanel?.querySelector('.sjw_setting_sub-tab-list');
        if (currentSubTabList) {
            const firstSubTab = currentSubTabList.querySelector('.sjw_setting_sub-tab-item');
            if (firstSubTab) {
                showSubTab(firstSubTab.getAttribute('data-subtab'));
            }
        }
    }

    // Function to switch sub-tabs for the current main tab
    function showSubTab(targetSubTab) {
        subTabPanels.forEach(panel => panel.style.display = 'none');
        subTabItems.forEach(tab => tab.classList.remove('active'));
        document.getElementById(targetSubTab + '-content').style.display = 'block';
        document.querySelector(`[data-subtab="${targetSubTab}"]`).classList.add('active');
    }

    // Event listeners for main tabs
    tabItems.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = tab.getAttribute('data-tab');
            showTab(targetTab);
        });
    });

    // Event listeners for sub-tabs
    subTabItems.forEach(subTab => {
        subTab.addEventListener('click', function() {
            const targetSubTab = subTab.getAttribute('data-subtab');
            showSubTab(targetSubTab);
        });
    });

    // Initialize the first tab and sub-tab
    // if (document.getElementById('sjw_setting_general')) {
        showTab('sjw_setting_general');
    // }
});

document.addEventListener('DOMContentLoaded', function () {
    // Initialize the sortable list for social platforms
    const sortableList = document.getElementById('sjw-sortable-socials');
    
    // Check if the sortable list exists
    if (sortableList) {
        // Make the list sortable using jQuery UI
        jQuery(sortableList).sortable({
            placeholder: 'ui-state-highlight', // Placeholder for sorting
            update: function(event, ui) {
                // Trigger when the order of items is changed
                const sortedIds = jQuery(sortableList).sortable('toArray');
                // Save the new order to the hidden field
                document.getElementById('sjw-sorted-socials').value = sortedIds.join(',');
            }
        });
        jQuery(sortableList).disableSelection();
    }

    // Handle form submission
    if (document.getElementById('sjw-socials')) {
        document.getElementById('sjw-socials').addEventListener('submit', function(event) {
            // Prevent default form submission to handle via AJAX
            event.preventDefault();

            // Create a new FormData object from the form
            const formData = new FormData(this);

            // Add nonce to the form data for security
            formData.append('nonce', sjw_ajax_object.nonce); // Use localized nonce
            formData.append('action', 'sjw_save_socials'); // The AJAX action

            // Make the AJAX request using jQuery
            jQuery.ajax({
                url: sjw_ajax_object.ajax_url, // Use localized AJAX URL
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    jQuery('#sjw-socials').find('.spinner').addClass('is_active');
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response.data.message); // Display success message
                    } else {
                        console.log(response.data.message); // Display error message
                    }
                },
                error: function(xhr, status, error) {
                    console.log('An error occurred while saving the settings.'); // Handle error
                },
                complete: function() {
                    jQuery('#sjw-socials').find('.spinner').removeClass('is_active');
                }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.shortcode-list .copy').forEach(button => {
        button.addEventListener('click', function () {
            const shortcode = this.dataset.shortcode;
            navigator.clipboard.writeText(shortcode).then(() => {
                // alert('Shortcode copied to clipboard: ' + shortcode);
            });
        });
    });

    // Handle email template form submission (for any template form)
    document.querySelectorAll('.template_form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            // Prevent default form submission to handle via AJAX
            event.preventDefault();

            // Get the template number from the form ID (e.g., template_1_form)
            const templateNumber = this.id.split('_')[1];

            // Create a new FormData object from the form
            const formData = new FormData(this);

            // Add nonce and template number to the form data for security
            formData.append('nonce', sjw_ajax_object.nonce); // Use localized nonce
            formData.append('action', 'sjw_save_template_form'); // The AJAX action
            formData.append('template_number', templateNumber); // Add template number

            // Log the FormData keys and values to inspect them
            for (const pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

           
            var form_id = this.id;

            // Make the AJAX request using jQuery
            jQuery.ajax({
                url: sjw_ajax_object.ajax_url, // Use localized AJAX URL
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    
                    jQuery("#"+form_id).find('.spinner').addClass('is_active');
                    
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response.data.message); // Display success message
                    } else {
                        console.log(response.data.message); // Display error message
                    }
                },
                error: function(xhr, status, error) {
                    console.log('An error occurred while saving the settings.'); // Handle error
                },
                complete: function() {
                    jQuery("#"+form_id).find('.spinner').removeClass('is_active');
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Generic AJAX form submission handler
    function handleAjaxFormSubmission(formId, action) {
        const form = document.getElementById(formId);
        if (!form) return; // Ensure the form exists

        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);
            formData.append('nonce', sjw_ajax_object.nonce); // Add nonce for security
            formData.append('action', action); // Set the AJAX action

            for (const pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

            // Make AJAX request
            jQuery.ajax({
                url: sjw_ajax_object.ajax_url, // Localized AJAX URL
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    jQuery(form).find('.spinner').addClass('is_active'); // Show spinner
                },
                success: function (response) {
                    if (response.success) {
                        console.log(response.data.message); // Display success message
                    } else {
                        console.log(response.data.message); // Display error message
                    }
                },
                error: function () {
                    console.log('An error occurred while saving the settings.'); // Handle error
                },
                complete: function () {
                    jQuery(form).find('.spinner').removeClass('is_active'); // Hide spinner
                },
            });
        });
    }

    // Initialize forms with their respective actions
    handleAjaxFormSubmission('application_messages_form', 'sjw_save_application_messages');
    handleAjaxFormSubmission('confirm_messages_form', 'sjw_save_confirm_messages');
});

// document.addEventListener('DOMContentLoaded', function () {
//     setTimeout(() => {
//         // Function to sync TinyMCE editor content to its associated textarea
//         function syncToTextarea(editorID) {
//             const textarea = document.getElementById(editorID); // Associated textarea
//             if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorID)) {
//                 const updatedContent = tinyMCE.get(editorID).getContent(); // Get content from the TinyMCE editor
//                 textarea.value = updatedContent; // Update the textarea
//             }
//         }

//         // Function to attach event listeners to a TinyMCE editor
//         function attachEditorListeners(editorID) {
//             if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorID)) {
//                 const editorInstance = tinyMCE.get(editorID);

//                 // Sync to textarea on keyup, change, or input events
//                 editorInstance.on('keyup change input', function () {
//                     syncToTextarea(editorID);
//                 });

//                 // Sync to textarea when the editor loses focus
//                 editorInstance.on('blur', function () {
//                     syncToTextarea(editorID);
//                 });
//             }
//         }

//         // Initialize synchronization for both editors
//         attachEditorListeners('sjw_application_thank_you_message'); // For Application Thank You Message
//         attachEditorListeners('sjw_confirm_thank_you_message'); // For Confirm Thank You Message
//     }, 1000);
// });

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        // Function to sync TinyMCE editor content to its associated textarea
        function syncToTextarea(textarea) {
            if (textarea && typeof tinyMCE !== 'undefined' && tinyMCE.get(textarea.id)) {
                const updatedContent = tinyMCE.get(textarea.id).getContent(); // Get content from the TinyMCE editor
                textarea.value = updatedContent; // Update the textarea
            }
        }

        // Function to attach event listeners to a TinyMCE editor
        function attachEditorListeners(textarea) {
            if (textarea && typeof tinyMCE !== 'undefined' && tinyMCE.get(textarea.id)) {
                const editorInstance = tinyMCE.get(textarea.id);

                // Sync to textarea on keyup, change, or input events
                editorInstance.on('keyup change input', function () {
                    syncToTextarea(textarea);
                });

                // Sync to textarea when the editor loses focus
                editorInstance.on('blur', function () {
                    syncToTextarea(textarea);
                });
            }
        }

        // Get all textareas with specific name attributes
        const textareas = document.querySelectorAll(
            'textarea[name="sjw_application_thank_you_message"], textarea[name="sjw_confirm_thank_you_message"], textarea[name="message_body"]'
        );

        // Attach TinyMCE listeners to each textarea
        textareas.forEach((textarea) => {
            attachEditorListeners(textarea);
        });
    }, 1000);
});

jQuery(document).ready(function ($) {
    // Handle visibility of Options field based on Field Type
    $(document).on('change', '.field-type-select', function () {
        const $parent = $(this).closest('.dynamic-field');
        const selectedType = $(this).val();
        const $optionsField = $parent.find('.field-options');
        const $optionsLabel = $parent.find('.options-label');

        if (selectedType === 'select' || selectedType === 'checkbox' || selectedType === 'radio' ) {
            $optionsField.show();
            $optionsLabel.show();
        } else {
            $optionsField.hide();
            $optionsLabel.hide();
        }
    });

    // Handle visibility of Conditional Logic fields based on checkbox
    $(document).on('change', '.enable-condition', function () {
        const $parent = $(this).closest('.dynamic-field');
        const $conditionFields = $parent.find('.condition-fields');
        if ($(this).is(':checked')) {
            $conditionFields.slideDown();
        } else {
            $conditionFields.slideUp();
        }
    });

    // Add new field dynamically within a specific form section
    $(document).on("click", ".sjw-add-field-button", function () {
        const $formContainer = $(this).closest('form').find('.sjw-dynamic-fields-container');
        const index = $formContainer.find('.dynamic-field').length;
        const newFieldHtml = `
            <div class="dynamic-field">
                <!-- Part 1: Field Selection and Basic Details -->
                <div class="part-1">
                    <span class="drag-handle dashicons dashicons-move"></span>
                    <select name="field_type[]" required class="field-type-select">
                        <option value="" disabled selected>Select Field Type</option>
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="email">Email</option>
                        <option value="tel">Phone</option>
                        <option value="radio">Radio</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="select">Select</option>
                        <option value="true_false">True/False</option>
						<option value="acceptance">Acceptance</option>
                        <option value="file">File</option>
                    </select>
                    <input type="text" class="field_id" name="field_id[]" placeholder="Enter your field_id here" required>
                    <input type="text" name="field_label[]" placeholder="Field Label" required>
                    <input type="text" name="field_placeholder[]" placeholder="Placeholder (for text/textarea)">
                    <input type="hidden" name="field_status[]" value="custom">
                    <input type="hidden" name="field_position[]" value="${index}">
                </div>

                <!-- Part 2: Options (Visible for select/checkbox fields) -->
                <div class="part-2">
                    <label class="options-label" style="display: none;">Options (for select/checkbox):</label>
                    <input type="text" name="field_options[]" placeholder="Comma-separated values" class="field-options" style="display: none;">
                </div>

                <!-- Part 3: Required & Conditional Logic -->
                <div class="part-3">
                    <label>
                        <input type="hidden" name="field_enable_require[${index}]" value="off">
                        <input type="checkbox" name="field_enable_require[${index}]" class="enable-require" value="on">
                        Enable Required
                    </label>
                    <label>
                        <input type="hidden" name="field_enable_condition[${index}]" value="off">
                        <input type="checkbox" name="field_enable_condition[${index}]" class="enable-condition">
                        Enable Conditional Logic
                    </label>

                    <!-- Conditional Logic Fields -->
                    <div class="condition-fields" style="display: none;">
                        <div class="condition-fields-wrapper">
                            <label>
                                Show If (Condition Field):
                                <select name="field_condition_field[]">
                                    <option value="">None</option>
                                    <!-- Loop through saved fields to populate condition fields -->
                                </select>
                            </label>
                            <label>
                                Condition:
                                <select name="field_condition_operator[]">
                                    <option value="equals">Equals</option>
                                    <option value="not_equals">Not Equals</option>
                                    <option value="greater_than">Greater Than</option>
                                    <option value="less_than">Less Than</option>
                                    <option value="contains">Contains</option>
                                    <option value="not_contains">Not Contains</option>
                                </select>
                            </label>
                            <label>
                                Condition Value:
                                <input type="text" name="field_condition_value[]" placeholder="Condition Value">
                            </label>
                        </div>
                    </div>

                    <!-- Remove Button -->
                    <button type="button" class="remove-field button-link-delete">Remove</button>
                </div>
            </div>`;

        // Append the new field to the current form container
        $formContainer.append(newFieldHtml);
    });

    // Remove field dynamically from the specific form
    $(document).on("click", ".remove-field", function () {
        $(this).closest(".dynamic-field").remove();
    });

    // Remove form section dynamically
    $(document).on("click", ".remove-form", function () {
        $(this).closest(".dynamic-form").remove();
    });

    // Enable sorting of fields within a specific form
    $('.sjw-dynamic-fields-container').sortable({
        handle: ".drag-handle",  // Drag handle selector
        update: function () {
            // Update field positions after sorting
            $(".dynamic-field").each(function (index) {
                $(this).find('input[name="field_position[]"]').val(index);  // Update position
            });
        }
    });

    // Handle Application form submission
    if (document.getElementById('sjw_application_form')) {
        document.getElementById('sjw_application_form').addEventListener('submit', function(event) {
            // Prevent default form submission to handle via AJAX
            event.preventDefault();

            // Create a new FormData object from the form
            const formData = new FormData(this);

            let fields = [];

            // Loop through the dynamic fields and collect data
            $(this).find(".dynamic-field").each(function(index) {
                // Build the field object dynamically based on the formData values
                const field = {
                    type: $(this).find('[name^="field_type"]').val(), // Get the type for the current field
                    label: $(this).find('[name^="field_label"]').val(), // Get the label for the current index
                    placeholder: $(this).find('[name^="field_placeholder"]').val(), // Get the placeholder
                    options: $(this).find('[name^="field_options"]').val(), // Get the options
                    enable_require: $(this).find('[name^="field_enable_require"]').is(':checked') ? 'on' : 'off', // Check for the 'require' condition
                    enable_condition: $(this).find('[name^="field_enable_condition"]').is(':checked') ? 'on' : 'off', // Check for the 'condition' condition
                    condition_field: $(this).find('[name^="field_condition_field"]').val(), // Get the condition field
                    condition_operator: $(this).find('[name^="field_condition_operator"]').val(), // Get the condition operator
                    condition_value: $(this).find('[name^="field_condition_value"]').val(), // Get the condition value
                    position: index, // Set the position
                    field_id: $(this).find('[name^="field_id"]').val(), // Get the field id
                    status: $(this).find('[name^="field_status"]').val() // Get the status
                };

                // Push the field object into the fields array
                fields.push(field);
            });

            console.log(fields); // Log the fields array
            

            // Add nonce to the form data for security
            formData.append('nonce', sjw_ajax_object.nonce);
            formData.append('action', 'sjw_save_application_form_fields'); // The AJAX action
            formData.append('fields', JSON.stringify(fields)); // Add fields array as JSON
            
            // Perform the AJAX request using jQuery
            jQuery.ajax({
                url: sjw_ajax_object.ajax_url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Show the loading spinner before sending the request
                    jQuery('#sjw_application_form').find('.spinner').addClass('is_active');
                },
                success: function(response) {
                    // // Handle successful response
                    // if (response.success) {
                    //     console.log(response.data.message); // Success message
                    // } else {
                    //     console.log(response.data.message); // Error message
                    // }
                },
                error: function(xhr, status, error) {
                    // Handle errors during the request
                    console.error('An error occurred while saving the settings:', error);
                },
                complete: function() {
                    // Hide the loading spinner after the request is complete
                    jQuery('#sjw_application_form').find('.spinner').removeClass('is_active');
                }
            });
        });
    }

    // Handle Application and Confirm form submission
    if (document.getElementById('sjw_confirm_form')) {
        document.getElementById('sjw_confirm_form').addEventListener('submit', function(event) {
            // Prevent default form submission to handle via AJAX
            event.preventDefault();

            // Create a new FormData object from the form
            const formData = new FormData(this);

            let fields = [];

            // Loop through the dynamic fields and collect data
            $(this).find(".dynamic-field").each(function(index) {
                // Build the field object dynamically based on the formData values
                const field = {
                    type: $(this).find('[name^="field_type"]').val(), // Get the type for the current field
                    label: $(this).find('[name^="field_label"]').val(), // Get the label for the current index
                    placeholder: $(this).find('[name^="field_placeholder"]').val(), // Get the placeholder
                    options: $(this).find('[name^="field_options"]').val(), // Get the options
                    enable_require: $(this).find('[name^="field_enable_require"]').is(':checked') ? 'on' : 'off', // Check for the 'require' condition
                    enable_condition: $(this).find('[name^="field_enable_condition"]').is(':checked') ? 'on' : 'off', // Check for the 'condition' condition
                    condition_field: $(this).find('[name^="field_condition_field"]').val(), // Get the condition field
                    condition_operator: $(this).find('[name^="field_condition_operator"]').val(), // Get the condition operator
                    condition_value: $(this).find('[name^="field_condition_value"]').val(), // Get the condition value
                    position: index, // Set the position
                    field_id: $(this).find('[name^="field_id"]').val(), // Get the field id
                    status: $(this).find('[name^="field_status"]').val() // Get the status
                };

                // Push the field object into the fields array
                fields.push(field);
            });

            console.log(fields); // Log the fields array
            

            // Add nonce to the form data for security
            formData.append('nonce', sjw_ajax_object.nonce);
            formData.append('action', 'sjw_save_confirm_form_fields'); // The AJAX action
            formData.append('fields', JSON.stringify(fields)); // Add fields array as JSON
            
            // Perform the AJAX request using jQuery
            jQuery.ajax({
                url: sjw_ajax_object.ajax_url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Show the loading spinner before sending the request
                    jQuery('#sjw_confirm_form').find('.spinner').addClass('is_active');
                },
                success: function(response) {
                    // // Handle successful response
                    // if (response.success) {
                    //     console.log(response.data.message); // Success message
                    // } else {
                    //     console.log(response.data.message); // Error message
                    // }
                },
                error: function(xhr, status, error) {
                    // Handle errors during the request
                    console.error('An error occurred while saving the settings:', error);
                },
                complete: function() {
                    // Hide the loading spinner after the request is complete
                    jQuery('#sjw_confirm_form').find('.spinner').removeClass('is_active');
                }
            });
        });
    }

    // Handle Application and Confirm form submission
    if (document.getElementById('sjw_general_form')) {
        document.getElementById('sjw_general_form').addEventListener('submit', function(event) {
            // Prevent default form submission to handle via AJAX
            event.preventDefault();

            // Create a new FormData object from the form
            const formData = new FormData(this);

            // Add nonce to the form data for security
            formData.append('nonce', sjw_ajax_object.nonce);
            formData.append('action', 'sjw_save_general_form_fields'); // The AJAX action
            
            // Perform the AJAX request using jQuery
            jQuery.ajax({
                url: sjw_ajax_object.ajax_url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Show the loading spinner before sending the request
                    jQuery('#sjw_general_form').find('.spinner').addClass('is_active');
                },
                success: function(response) {
                    // // Handle successful response
                    // if (response.success) {
                    //     console.log(response.data.message); // Success message
                    // } else {
                    //     console.log(response.data.message); // Error message
                    // }
                },
                error: function(xhr, status, error) {
                    // Handle errors during the request
                    console.error('An error occurred while saving the settings:', error);
                },
                complete: function() {
                    // Hide the loading spinner after the request is complete
                    jQuery('#sjw_general_form').find('.spinner').removeClass('is_active');
                }
            });
        });
    }

    // Handle filters form submission
    if (document.getElementById('sjw_filters')) {
        document.getElementById('sjw_filters').addEventListener('submit', function(event) {
            // Prevent default form submission to handle via AJAX
            event.preventDefault();

            // Create a new FormData object from the form
            const formData = new FormData(this);

            // Add nonce to the form data for security
            formData.append('nonce', sjw_ajax_object.nonce);
            formData.append('action', 'sjw_save_filters_form_fields'); // The AJAX action

            // Log form data to console
            console.log('Form Data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Perform the AJAX request using jQuery
            jQuery.ajax({
                url: sjw_ajax_object.ajax_url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Show the loading spinner before sending the request
                    jQuery('#sjw_filters').find('.spinner').addClass('is_active');
                },
                success: function(response) {
                    console.log('AJAX Response:', response); // Log response to console
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred while saving the settings:', error);
                },
                complete: function() {
                    // Hide the loading spinner after the request is complete
                    jQuery('#sjw_filters').find('.spinner').removeClass('is_active');
                }
            });
        });
    }

});

jQuery(document).ready(function () {
    // Initialize sortable on #selected_fields
    jQuery("#selected-fields").sortable({
        connectWith: "#selected-fields",
        placeholder: "sortable-placeholder",
        update: function (event, ui) {
            // Get the updated array of selected fields
            var selectedFields = jQuery("#selected_fields")
                .children()
                .map(function () {
                    return jQuery(this).attr("data-id");
                })
                .get(); // Convert to an array

            console.log("Selected Fields:", selectedFields);

            // Ensure trash icon is added after sorting
            selectedFields.forEach(function (element) {
                var field = jQuery("[data-id='" + element + "']");
                if (field.find(".dashicons-trash").length === 0) {
                    field.append(
                        '<span class="delete-icon dashicons dashicons-trash" data-id="' +
                            element +
                            '"></span>'
                    );
                }
            });
        },
    }).disableSelection();

    // Make .sortable-item draggable and connect to #selected_fields
    jQuery("#available-fields .sortable-item").draggable({
        helper: "clone", // Clone the item while dragging (optional but gives a better user experience)
        connectToSortable: "#selected-fields",
        stop: function (event, ui) {
            // Remove trash icon if item is returned to the original list
            if (ui.helper.parent().attr("id") !== "selected-fields") {
                jQuery(ui.helper).find(".dashicons-trash").remove();
            }
        },
    });

    // Remove an item from #selected_fields when trash icon is clicked
    jQuery(document).on("click", "#selected-fields .sortable-item .dashicons-trash", function () {
        var parent = jQuery(this).parent();
        parent.remove();
    });

    // Handle Application and Confirm form submission
    if (document.getElementById('sjw_export_fields_form')) {
        document.getElementById('sjw_export_fields_form').addEventListener('submit', function(event) {
            // Prevent default form submission to handle via AJAX
            event.preventDefault();

            // Create a new FormData object from the form
            const formData = new FormData(this);

            const selectedFields = jQuery("#selected-fields")
            .children()
            .map(function() {
                return {
                    id: jQuery(this).attr("data-id"),          
                    type: jQuery(this).attr("data-type"),
                    name: jQuery(this).find('input').val()
                };
            })
            .get(); // Convert to an array

            console.log(selectedFields);
            

            // Add nonce to the form data for security
            formData.append('nonce', sjw_ajax_object.nonce);
            formData.append('action', 'sjw_save_application_export_fields'); // The AJAX action
            formData.append('fields', JSON.stringify(selectedFields)); // Add fields array as JSON
            
            // Perform the AJAX request using jQuery
            jQuery.ajax({
                url: sjw_ajax_object.ajax_url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Show the loading spinner before sending the request
                    jQuery('#sjw_export_fields_form').find('.spinner').addClass('is_active');
                },
                success: function(response) {
                    // Handle successful response
                    if (response.success) {
                        console.log(response.data.fields_received); // Success message
                    } else {
                        console.log(response.data.message); // Error message
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors during the request
                    console.error('An error occurred while saving the settings:', error);
                },
                complete: function() {
                    // Hide the loading spinner after the request is complete
                    jQuery('#sjw_export_fields_form').find('.spinner').removeClass('is_active');
                }
            });
        });
    }
});

