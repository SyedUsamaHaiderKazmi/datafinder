function setupFilterObject() {
    filters = {}; // Initialize filter object
    const allowedFilters = document.getElementsByClassName('data-filters'); // Get all elements with the 'data-filters' class

    Array.from(allowedFilters).forEach((filterElement) => {
        // Initialize filter entry if it doesn't exist
        if (!filters[filterElement.name]) {
            filters[filterElement.name] = {};
        }

        if (filterElement.selectedOptions) { // If the element is a select dropdown
            Array.from(filterElement.selectedOptions).forEach((selectedOption, index) => {
                // Store selected options in the filter object
                filters[filterElement.name][index] = {
                    value: selectedOption.value,
                    type: filterElement.type,
                    filter_through_join: filterElement.hasAttribute('filter_through_join'),
                    join_table: filterElement.getAttribute('join_table'),
                    conditional_operator: filterElement.getAttribute('conditional_operator')
                };
            });
        } else {
            // For non-select elements, store the value directly
            const length = Object.keys(filters[filterElement.name]).length;
            filters[filterElement.name][length] = {
                value: filterElement.value,
                type: filterElement.type,
                filter_through_join: filterElement.hasAttribute('filter_through_join'),
                join_table: filterElement.getAttribute('join_table'),
                conditional_operator: filterElement.getAttribute('conditional_operator')
            };
        }
    });
}

function addEventToFilters() {
    const allowedFilters = document.getElementsByClassName('data-filters');

    Array.from(allowedFilters).forEach((filterElement) => {
        // Bind change event to each filter element
        $('#' + filterElement.id).on('change', function(event) {
            dataTableReload(event); // Trigger the dataTableReload function when a change occurs
        });
    });
}

function dataTableReload(event) {
    datatable.ajax.reload();
}

// Function to update the error list display in the UI
function updateErrorDisplay() {
    const errorContainer = $('#df-' + datafinder_table_id + '-errors');
    const errorMessageDiv = errorContainer.children('div#error-message');

    // Clear existing error messages
    errorMessageDiv.empty();

    // If there are any errors, show them
    if (errorList.length > 0) {
        // Create an ordered list to display errors
        let errorListHTML = '<p style="margin: 0"><b>Following issue(s) found:</b></p><br><ol style="margin: 0">';
        errorList.forEach(function (errorMsg) {
            errorListHTML += `<li>${errorMsg}</li>`;
        });
        errorListHTML += '</ol>';

        // Display the error list
        errorMessageDiv.append(errorListHTML).show();
        errorContainer.show();
    } else {
        // Hide the error container if no errors
        errorMessageDiv.hide();
        errorContainer.hide();
    }
}