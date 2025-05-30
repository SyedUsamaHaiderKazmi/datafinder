setupFilterObject = () => {
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

addEventToFilters = () => {
    const allowedFilters = document.getElementsByClassName('data-filters');

    Array.from(allowedFilters).forEach((filterElement) => {
        // Bind change event to each filter element
        $('#' + filterElement.id).on('change', function(event) {
            dataTableReload(event); // Trigger the dataTableReload function when a change occurs
        });
    });
}

dataTableReload = (event) => {
    datatable.ajax.reload();
}

// Function to update the error list display in the UI
updateErrorDisplay = () => {
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

const setupToolbar = () => {
    return [
        btn_export, btn_reload
    ];
}


fnExport = (event, datatable, ext) => {
    let params = datatable.ajax.params();
    errorList = [];
    updateErrorDisplay();
    params.exportable = exportable;
    params.export_extension = ext;
    params.offset = 0;

    let btn_span = event.target;

    let btn_custom_elements = {
        spinner: fnProcessSpinElement(btn_span),
        span_completion_percentage: fnCompletionPercentageElement(btn_span, '')
    }

    const exporter = new Exporter({
        filename: `${config_file_name}_${new Date().toISOString()}.${ext}`,
        sheetName: 'Data',
        extension: ext,
        multiSheet: false, // Set to true if each chunk should be a new sheet
        metadata: {
            Author: 'Data Finder',
            Manager: 'SUHK',
            Company: 'SUHK'
        }
    });

    // event.target.lastChild.firstChild.hidden = !event.target.lastChild.firstChild.hidden;

    const fetchChunk = (offset) => {
        params.offset = offset;
        $.ajax({
            url: "export/init",
            type: "POST",
            data: params,
            success: function (response) {
                const { data, next_offset, completed_percentage } = response;
                try {
                    exporter.appendData(data, { isFinalChunk: next_offset === null });
                    btn_custom_elements.span_completion_percentage.textContent = completed_percentage;
                    
                    if (next_offset !== null) {
                        fetchChunk(next_offset);
                    } else {
                        fnResetExportButtonView(btn_custom_elements);
                    }
                }
                catch(error) {
                    fnResetExportButtonView(btn_custom_elements);
                    errorList.push(`${error.message}`)
                }
                updateErrorDisplay();

            },
            error: function (error) {
                fnResetExportButtonView(btn_custom_elements);
                // event.target.lastChild.firstChild.hidden = !event.target.lastChild.firstChild.hidden;
                errorList.push(`${error.responseJSON.exception}: ${error.responseJSON.message}`)
                updateErrorDisplay();
            }
        });
    };

    fetchChunk(0);
};

fnProcessSpinElement = (btn) => {
    // Create a unique spinner and add it to the button
    const button = btn; // fallback to button if span/img clicked
    let spinner = document.createElement('img');
    spinner.src = "vendor/datafinder/assets/svgs/spinner.svg";
    spinner.alt = "Exporting...";
    spinner.style.height = "16px";
    spinner.style.marginRight = "5px";
    spinner.classList.add('export-spin');
    button.prepend(spinner);

    return spinner;
}
fnCompletionPercentageElement = (btn, completed_percentage) => {
    // Create a unique spinner and add it to the button
    const button = btn; // fallback to button if span/img clicked
    let span = document.createElement('span');
    span.text = completed_percentage;
    span.style.float = "right";
    button.append(span);
    return span;
}
fnResetExportButtonView = (elements) => {
    for (let key in elements) {
        const el = elements[key];
        if (el instanceof Element && el.parentNode) {
            el.remove();
        }
    }
}
fnResetReloadButtonView = () => {
    if (document.getElementById('reload-spin') && document.getElementById('reload-spin').hidden == false) {
        document.getElementById('reload-spin').hidden = true;
    }
}