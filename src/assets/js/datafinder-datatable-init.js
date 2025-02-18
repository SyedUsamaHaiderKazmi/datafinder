var datatable;
var filters = {};
var errorList = []; // Array to hold error messages

$(document).ready(function () {
    // setupFilterObject();
    // to disable the popup of datatable and show errors in custom list
    DataTable.ext.errMode = 'none';
    // init datatable
    datatable = $('#df-'+ datafinder_table_id).DataTable({
        'info': true,
        'paging': true,
        'pageLength': default_per_page,
        "processing": true,
        "serverSide": true,
        "lengthChange": allow_per_page_options,
        "ajax":{
            "url": liveSearchFilterRoute,
            "dataType": "json",
            "type": "POST",
            "data":function(data){
                setupFilterObject();
                data._token = $("input[name='_token']").val();
                data.config_file_name = config_file_name;
                data.filters = filters;
            },
            "error": function(xhr, status, error) {
                errorList.push(`${xhr.responseJSON.errors}`);
                updateErrorDisplay();
            },
        },
       "buttons": [
            {
                "text": '<i class="fa fa-recycle"></i> | Reload',
                "className": 'btn btn-primary btn-sm margin-right-5',
                action: function ( e, dt, node, config ) {
                    dt.ajax.reload();
                }
            },
        ],
       "columns": columns,
    });
    addEventToFilters();
    addEventsToDatatable(datatable);

    $('#btn-data-finder-filter').on('click', function(e){
        dataTableReload(e);
    });

});
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

function addEventsToDatatable(datatable){
    datatable.on('dt-error', function(e, settings, techNote, message) {
        errorList.push(`${message}`);
        updateErrorDisplay();
    });
    datatable.on('preXhr', function(e, settings, data) {
        errorList = [];
    });
    datatable.on('xhr', function(e, settings, json, xhr) {
        if (json.errors.length > 0) {
            json.errors.forEach((error, index) => {
                errorList.push(`${error}`);
            });
        }
        updateErrorDisplay();
    });
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
