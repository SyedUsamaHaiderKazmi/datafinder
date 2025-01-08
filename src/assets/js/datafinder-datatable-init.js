var datatable;
var filters = {};

$(document).ready(function () {
    // setupFilterObject();
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
                // data.model = model_path;
                // data.table_name = table_name;
                data.config_file_name = config_file_name;
                data.filters = filters;
                // data.routes = routes;
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
    addChangeEvent();

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

function addChangeEvent() {
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
