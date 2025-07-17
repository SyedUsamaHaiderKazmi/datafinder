let btn_reload = {
    text: '<img id="reload-spin" style="mix-blend-mode: difference;" hidden src="/vendor/datafinder/assets/svgs/spinner-light.svg" alt="Icon"> Reload',
    className: 'btn btn-primary btn-sm float-right',
    action: function ( e, dt, node, config ) {
        document.getElementById('reload-spin').hidden = false;
        dt.ajax.reload(() => {
            document.getElementById('reload-spin').hidden = true;
        });
    }
};

let btn_export = {
    extend: 'collection',
    text: 'Export',
    className: 'btn btn-dark btn-sm' + (!exportable?' d-none':''),
    style: "background-color: #007bff; color: white; border-radius: 5px;margin-right: 1rem",  // Inline styling
    buttons: [
        {
            text: 'XLSX',
            className: 'btn btn-default btn-sm',
            action: function ( e, dt, node, config ) {
                fnExport(e, dt, 'xlsx');
            }
        },
        {
            text: 'XLS',
            className: 'btn btn-default btn-sm',
            action: function ( e, dt, node, config ) {
                fnExport(e, dt, 'xls');
            }
        },
        {
            text: 'CSV',
            className: 'btn btn-default btn-sm',
            action: function ( e, dt, node, config ) {
                fnExport(e, dt, 'csv');
            }
        },
    ]
};
