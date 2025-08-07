let datatable = null;
let filters = {};
let errorList = []; // Array to hold error messages

$(document).ready(function () {
    // setupFilterObject();
    // to disable the popup of datatable and show errors in custom list
    DataTable.ext.errMode = 'none';
    // init datatable
    let config = custom_datatable != true ? getDatatableConfig() : datafinder_custom_config;
   
    datatable = $('#df-'+ datafinder_table_id).DataTable(config);
    addEventToFilters();
    addEventsToDatatable(datatable);

    $('#btn-data-finder-filter').on('click', function(e){
        dataTableReload(e);
    });

});

function getDatatableConfig(){
    return {
        info: true,
        paging: true,
        layout: {
            top2End: 'buttons',
            topEnd: 'search',
        },
        searching: full_text_search,
        search: {
            return: true,
        },
        pageLength: default_per_page,
        processing: true,
        serverSide: true,
        lengthChange: allow_per_page_options,
        lengthMenu: per_page_options,
        ajax:{
            url: live_search_filter_route,
            dataType: "json",
            type: "POST",
            data: function(data){
                setupFilterObject();
                data._token = $("input[name='_token']").val();
                data.config_file_name = config_file_name;
                data.filters = filters;
            },
            error: function(xhr, status, error) {
                fnResetReloadButtonView()
                errorList.push(`${xhr.responseJSON.errors}`);
                updateErrorDisplay();
            },
        },
        buttons: setupToolbar(),
        columns: columns,
        initComplete: function(setting, json){
            // datatable.buttons().add(buttons);
        },
    };
}

addEventsToDatatable = (dt) => {
    dt.on('dt-error', (e, settings, techNote, message) => {
        fnResetReloadButtonView()
        errorList.push(`${message}`);
        updateErrorDisplay();
    });
    dt.on('preXhr', (e, settings, data) => {
        errorList = [];
        updateErrorDisplay();
    });
    dt.on('xhr', (e, settings, json, xhr) => {
        if (json != null && json.errors != undefined) {
            if (json.errors.length > 0) {
                json.errors.forEach((error, index) => {
                    errorList.push(`${error}`);
                });
            }
            updateErrorDisplay();
        }
    });
}

