// Initialize intlTelInput for all phone fields
const inputs = document.querySelectorAll(".phone-field");

inputs.forEach((input) => {
	const iti = window.intlTelInput(input, {
		initialCountry: "sg", // Set the initial country to Singapore
		countrySearch: false, // Disable the search bar in the dropdown
		customPlaceholder: function (selectedCountryPlaceholder) {
			// Customize placeholder format
			return "Enter your phone (e.g., " + selectedCountryPlaceholder + ")";
		},
	});

	// Attach the intlTelInput instance to the input element for later use
	input.iti = iti;

	// Add an error message container below the .iti.iti--allow-dropdown container
	const itiContainer = input.closest(".iti.iti--allow-dropdown");
	if (itiContainer) {
		const errorMessage = document.createElement("div");
		errorMessage.className = "error-message";
		errorMessage.style.color = "red";
		errorMessage.style.fontSize = "12px";
		errorMessage.style.marginTop = "5px";
		errorMessage.style.display = "none"; // Hidden by default
		itiContainer.parentNode.insertBefore(errorMessage, itiContainer.nextSibling);
	}

	// Validate phone number on blur
	input.addEventListener("blur", function () {
		const isValid = iti.isValidNumber();
		const errorMessage = itiContainer.nextSibling; // Get the error message container

		// Only show the error if the field has a value and is invalid
		if (input.value && !isValid) {
			input.classList.add("error");
			errorMessage.style.display = "block";
			errorMessage.textContent = "Please enter a valid phone number.";
			console.error(`Invalid phone number: ${iti.getNumber()}`);
		} else {
			input.classList.remove("error");
			errorMessage.style.display = "none";
            console.log(`Correct phone number: ${iti.getNumber()}`);
            console.log(iti);
		}
	});

	// Clear error on input
	input.addEventListener("input", function () {
		const errorMessage = itiContainer.nextSibling; // Get the error message container
		input.classList.remove("error");
		errorMessage.style.display = "none";
	});
});

document.addEventListener("DOMContentLoaded", function () {
    // Function to handle form submission via AJAX
    function handleFormSubmission(formId, action) {
        const form = document.getElementById(formId);
		const thankYouMessage = document.getElementById("sjw-thank-you-message");
		const formTitle = document.getElementById("sjw-form-title");
		
        if (!form) return;

        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Validate phone fields
            const phoneFields = form.querySelectorAll(".phone-field");
            let hasError = false;
            let firstErrorElement = null;

            phoneFields.forEach((input) => {
                const iti = input.iti; // Retrieve the intlTelInput instance
                const isValid = iti.isValidNumber(); // Validate the number
                const itiContainer = input.closest(".iti.iti--allow-dropdown");
                const errorMessage = itiContainer.nextSibling; // Get the error message container

                // Only show the error if the field has a value and is invalid
                if (input.value && !isValid) {
                    hasError = true;
                    input.classList.add("error");
                    errorMessage.style.display = "block";
                    errorMessage.textContent = "Please enter a valid phone number.";
                    console.error(`Invalid phone number: ${iti.getNumber()}`);

                    // Set the first error element
                    if (!firstErrorElement) {
                        firstErrorElement = errorMessage;
                    }
                } else {
                    input.classList.remove("error");
                    errorMessage.style.display = "none";
                    console.log(`Correct phone number: ${iti.getNumber()}`);
                }
            });

            // Stop form submission if there are errors
            if (hasError) {
                // alert("Please correct the phone number errors before submitting.");

                // Scroll to the first error message
                if (firstErrorElement) {
                    firstErrorElement.scrollIntoView({
                        behavior: "smooth",
                        block: "center", // Scroll to center of the screen
                    });
                }
                return;
            }

            // Proceed with form submission if validation passes
            const formData = new FormData(form); // Collect form data
            formData.append("action", action); // Add AJAX action

			var phoneFieldAalues = [];
            // Update phone fields with country code
            phoneFields.forEach((input) => {
                const iti = input.iti;
                const fullNumber = iti.getNumber(); // Get the full number with country code
                phoneFieldAalues.push({ name: input.name, value: fullNumber });
            });
			
            // Disable all submit buttons within the form during submission
            const submitButtons = form.querySelectorAll('[type="submit"]');
            submitButtons.forEach((button) => {
                button.disabled = true; // Disable the button
                button.classList.add("sjw-buttonload"); // Add the loading class
            });


            // Consolidate array fields into a single string
            const entries = {};
            for (const [key, value] of formData.entries()) {
                if (key.endsWith("[]")) {
                    const baseKey = key.replace("[]", ""); // Remove `[]` from key
                    if (!entries[baseKey]) {
                        entries[baseKey] = [];
                    }
                    entries[baseKey].push(value); // Collect values in an array
                } else {
                    entries[key] = value; // Handle non-array fields
                }
            }
			
			 // Update entries with phoneFieldAalues
            phoneFieldAalues.forEach(({ name, value }) => {
                console.log(name, value);
                entries[name] = value;
            });

            // Convert array fields to comma-separated strings
            for (const key in entries) {
                if (Array.isArray(entries[key])) {
                    entries[key] = entries[key].join(", "); // Join array values with a comma
                }
            }

            // Replace original FormData with consolidated entries
            const newFormData = new FormData();
            for (const [key, value] of Object.entries(entries)) {
                newFormData.append(key, value);
            }

            // Log the updated FormData
            for (const [key, value] of newFormData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // Perform the AJAX request
            fetch(sjw_ajax_object.ajax_url, {
                method: "POST",
                // body: formData,
                body: newFormData,
            })
            .then((response) => response.json())
            .then((data) => {
                // Enable all submit buttons after submission
                submitButtons.forEach((button) => {
                    button.disabled = false;
                    button.classList.remove("sjw-buttonload");
                });

                if (data.success) {
                    console.log(data);
                    // alert("Form submitted successfully!");
                    if (formTitle) {
                        formTitle.style.display = "none";
                    }
                    form.style.display = "none";
                    thankYouMessage.style.display = "block";
                    thankYouMessage.scrollIntoView({
                        behavior: "smooth",
                        block: "center", // Center the message in the viewport
                    });

                    setTimeout(() => {
                        if(data.data.redirect_page_url){
                            window.location.href = data.data.redirect_page_url;
                        }
                    }, 1000);


                    form.reset(); // Reset the form
                    phoneFields.forEach((input) => {
                        const itiContainer = input.closest(".iti.iti--allow-dropdown");
                        const errorMessage = itiContainer.nextSibling;
                        errorMessage.style.display = "none"; // Hide error messages
                    });
                } else {
                // alert("Error: " + (data.message || "Unknown error"));
                }
            })
            .catch((error) => {
                // Enable all submit buttons after an error
                submitButtons.forEach((button) => {
                    button.disabled = false;
                    button.classList.remove("sjw-buttonload");
                });
                console.error("An unexpected error occurred:", error);
                // alert("An unexpected error occurred.");
            });
        });
    }

    // Attach form handlers
    handleFormSubmission("sjw_application_form", "submit_application_form");
    handleFormSubmission("sjw_confirm_form", "submit_confirm_form");

    /////////////////////////

    // Get the form
    const form = document.querySelector(".sjw-form");
    if (!form) return;

    // Function to evaluate a condition
    function evaluateCondition(value, operator, conditionValue) {
        switch (operator) {
            case "equals":
                return value === conditionValue;
            case "not_equals":
                return value !== conditionValue;
            case "greater_than":
                return parseFloat(value) > parseFloat(conditionValue);
            case "less_than":
                return parseFloat(value) < parseFloat(conditionValue);
            case "contains":
                return value.includes(conditionValue);
            case "not_contains":
                return !value.includes(conditionValue);
            default:
                return false;
        }
    }

    // Function to get the value of a field based on its type
    function getFieldValue(field) {
        if (field.type === "checkbox" || field.type === "radio") {
            return field.checked ? field.value : ""; // Real value if checked, blank if unchecked
        }
        if (field.type === "select-one" || field.type === "select-multiple") {
            return Array.from(field.selectedOptions)
                .map(option => option.value)
                .join(",");
        }
        return field.value; // Default for text, number, etc.
    }
    
    function toggleFields(value, fieldName) {
        const fields = form.querySelectorAll(".form-field");
    
        fields.forEach((field) => {
            const conditionFieldName = field.getAttribute("data-condition-field");
            const conditionOperator = field.getAttribute("data-condition-operator");
            const conditionValue = field.getAttribute("data-condition-value");
            const isRequire = field.getAttribute("data-is-require");
            const enableCondition = field.getAttribute("data-enable-condition");
    
            if (conditionFieldName === fieldName && conditionOperator && conditionValue && enableCondition == 'on') {
                if (isRequire === "required") {
                    const checkboxes = field.querySelectorAll("input[type='checkbox']");
                    
                    checkboxes.forEach((checkbox) => {
                        checkbox.addEventListener("change", () => {
                            // Check if any checkbox is selected
                            const isAnyChecked = Array.from(checkboxes).some((cb) => cb.checked);
                
                            // Update the "required" attribute based on the selection
                            checkboxes.forEach((cb) => {
                                if (isAnyChecked) {
                                    cb.removeAttribute("required"); // Remove "required" if any is checked
                                } else {
                                    cb.setAttribute("required", "required"); // Add "required" if none is checked
                                }
                            });
                        });
                    });
                }
                
                const shouldShow = evaluateCondition(value, conditionOperator, conditionValue);

                // Manage the "required" attribute for applicable child elements
                const inputs = field.querySelectorAll("input");
                const textareas = field.querySelectorAll("textarea");
                const selects = field.querySelectorAll("select");

                // Combine all query results into a single list
                const elements = [...inputs, ...textareas, ...selects];

                elements.forEach((element) => {
                    if (shouldShow && isRequire === "required") {
                        element.setAttribute("required", "required");
                    } else if (!shouldShow && isRequire === "required") {
                        element.removeAttribute("required");
                    }
                });
    
                // Toggle field visibility based on condition evaluation
                field.style.display = shouldShow ? "block" : "none";
            }
        });
    }

    // Attach event listeners to fields that control visibility
    const allFields = form.querySelectorAll("input, select, textarea");
    allFields.forEach((field) => {
        field.addEventListener("change", function () {
            if (field.type === "radio") {
                // Handle all radio buttons in the same group
                const radioGroup = form.querySelectorAll(`[name="${field.name}"]`);
                radioGroup.forEach(radio => {
                    const value = getFieldValue(radio);
                    if(value !== ""){
                        toggleFields(value, field.name);
                    }
                });
            } else {
                const value = getFieldValue(field);
                toggleFields(value, field.name);
            }
        });
    });

    // Initial evaluation to apply conditions on page load
    allFields.forEach((field) => {
        if (field.type === "radio") {
            const checkedRadio = form.querySelector(`[name="${field.name}"]:checked`);
            const value = checkedRadio ? getFieldValue(checkedRadio) : "";
            toggleFields(value, field.name);
        } else {
            const value = getFieldValue(field);
            toggleFields(value, field.name);
        }
    });

});