// document.addEventListener("DOMContentLoaded", function () {
//     const form = document.getElementById("dynamic-form");
//     if (!form) return;

//     // Function to evaluate a condition
//     function evaluateCondition(value, operator, conditionValue) {
//         switch (operator) {
//             case "equals":
//                 return value === conditionValue;
//             case "not_equals":
//                 return value !== conditionValue;
//             case "greater_than":
//                 return parseFloat(value) > parseFloat(conditionValue);
//             case "less_than":
//                 return parseFloat(value) < parseFloat(conditionValue);
//             case "contains":
//                 return value.includes(conditionValue);
//             case "not_contains":
//                 return !value.includes(conditionValue);
//             default:
//                 return false;
//         }
//     }

//     // Function to get the value of a field based on its type
//     function getFieldValue(field) {
//         if (field.type === "checkbox" || field.type === "radio") {
//             return field.checked ? field.value : ""; // Real value if checked, blank if unchecked
//         }
//         if (field.type === "select-one" || field.type === "select-multiple") {
//             return Array.from(field.selectedOptions)
//                 .map(option => option.value)
//                 .join(",");
//         }
//         return field.value; // Default for text, number, etc.
//     }

//     // Function to toggle visibility of fields based on conditions
//     function toggleFields(value, fieldName) {
//         const fields = form.querySelectorAll(".form-field");

//         fields.forEach((field) => {
//             const conditionFieldName = field.getAttribute("data-condition-field");
//             const conditionOperator = field.getAttribute("data-condition-operator");
//             const conditionValue = field.getAttribute("data-condition-value");

//             if (conditionFieldName === fieldName && conditionOperator && conditionValue) {
//                 const shouldShow = evaluateCondition(value, conditionOperator, conditionValue);

//                 // Toggle field visibility based on condition evaluation
//                 field.style.display = shouldShow ? "block" : "none";
//             }
//         });
//     }

//     // Attach event listeners to fields that control visibility
//     const allFields = form.querySelectorAll("input, select, textarea");
//     allFields.forEach((field) => {
//         field.addEventListener("change", function () {
//             if (field.type === "radio") {
//                 // Handle all radio buttons in the same group
//                 const radioGroup = form.querySelectorAll(`[name="${field.name}"]`);
//                 radioGroup.forEach(radio => {
//                     const value = getFieldValue(radio);
//                     toggleFields(value, field.name);
//                 });
//             } else {
//                 const value = getFieldValue(field);
//                 toggleFields(value, field.name);
//             }
//         });
//     });

//     // Initial evaluation to apply conditions on page load
//     allFields.forEach((field) => {
//         if (field.type === "radio") {
//             const checkedRadio = form.querySelector(`[name="${field.name}"]:checked`);
//             const value = checkedRadio ? getFieldValue(checkedRadio) : "";
//             toggleFields(value, field.name);
//         } else {
//             const value = getFieldValue(field);
//             toggleFields(value, field.name);
//         }
//     });
// });
