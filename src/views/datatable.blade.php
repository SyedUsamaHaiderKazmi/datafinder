

<div class="row" style="margin-top: 10px;">
    <a href="" id="file_path" hidden></a>
    <div class="col-md-12">
        <div class="card m-b-30">
            <div class="card-body">
               <table class="table table-striped table-bordered nowrap table-responsive" id="liveSearchTable" width="100%">
                    <thead>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot></tfoot>
               </table>
           </div>
       </div>
    </div>
</div>

{{-- Jquery CDN --}}
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>



<script>
    {{-- var table_cols_configuration = {!! json_encode(config('filter_configurations.'.$table_name.'.table_headers')) !!}; --}}
    var config_file_name = {!! json_encode($config_file_name) !!};
    var datatable;
    var filters = {};

    function getTableColumns(){
        var table_cols_configuration = [];
        var useable_columns = @json(config($config_file_name.'.table_headers'));
        $.each(useable_columns, function(key, value){
            if (value['visibility']) {
                table_cols_configuration.push({
                    title: value.title,
                    data: value.data,
                });
            }
        });
        return table_cols_configuration;
    }

    $(document).ready(function () {
        // setupFilterObject();
        datatable = $('#liveSearchTable').DataTable({
            "dom": 'Bfrtip',
            'pageLength': 50,
            "processing": true,
            "serverSide": true,
            "lengthChange": true,
            "ajax":{
                "url": '{{ route("liveSearchTableRender") }}',
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
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // converting to interger to find total
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                var footer = '<tr>'
                this.api().columns().every(function () {
                    var column = this;
                    var sum = column.data().reduce(function(a, b) {
                        var x = parseFloat(a) || 0;
                        var y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
                    footer += '<th>' + (sum==0?'---':sum) + '</th>';
                });
                footer += '</tr>';
                // debugger;
                /*if (table_name == 'admissions') {
                    $('tfoot').html(footer)
                }*/
            },
           "buttons": [
                {
                    "text": '<i class="fa fa-recycle"></i> | Reload',
                    "className": 'btn btn-primary btn-sm margin-right-5',
                    action: function ( e, dt, node, config ) {
                        dt.ajax.reload();
                    }
                },
                /*{
                    "extend": 'collection',
                    "text": '<i class="fa fa-download"></i> | Export',
                    "className": 'btn btn-dark btn-sm',
                    "buttons": [
                        {
                            "text": '<i hidden id="export_loading" class="fa fa-spinner fa-spin"></i> <i class="fa fa-file-excel"> | .XLSX',
                            "className": 'btn btn-default btn-sm',
                            action: function ( e, dt, node, config ) {
                                var params = dt.ajax.params();
                                params.is_export = true;
                                params.export_extension = '.xlsx';
                                document.getElementById('export_loading').hidden = false;
                                $.ajax({
                                    url: '{{ route("liveSearchTableRender") }}',
                                    // dataType: "json",
                                    type: "POST",
                                    data: params,
                                    success: function(data) {
                                        debugger;
                                        document.getElementById('export_loading').hidden = true;
                                        document.getElementById('file_path').href = data;
                                        document.getElementById('file_path').click();
                                    },
                                    error: function(data) {
                                    }
                                });
                            }
                        }
                    ]
                },*/
            ],
           "columns": @json(SUHK\DataFinder\Helpers\ConfigParser::getTableColumnsConfiguation($config_file_name)),
        });
        addChangeEvent();

        $('#btn-data-finder-filter').on('click', function(e){
            dataTableReload(e);
        });

    });

    function setupFilterObject() {
        filters = {};
        var allowedFilters = document.getElementsByClassName('data-filters');
        for (var i = 0; i < allowedFilters.length; i++) {
            if (filters[allowedFilters[i].name] == undefined) {
                filters[allowedFilters[i].name] = {};
            }
            if (allowedFilters[i].selectedOptions) {
                for (var j = 0; j < allowedFilters[i].selectedOptions.length; j++) {
                    filters[allowedFilters[i].name][j] = {};
                    filters[allowedFilters[i].name][j]['value'] = allowedFilters[i].selectedOptions[j].value;
                    filters[allowedFilters[i].name][j]['type'] = allowedFilters[i].type;
                    filters[allowedFilters[i].name][j]['filter_through_join'] = allowedFilters[i].hasAttribute('filter_through_join');
                    filters[allowedFilters[i].name][j]['join_table'] = allowedFilters[i].getAttribute('join_table');
                    filters[allowedFilters[i].name][j]['conditional_operator'] = allowedFilters[i].getAttribute('conditional_operator');
                }
            } else {
                var length = Object.keys(filters[allowedFilters[i].name]).length;
                filters[allowedFilters[i].name][length] = {};
                filters[allowedFilters[i].name][length]['value'] = allowedFilters[i].value;
                filters[allowedFilters[i].name][length]['type'] = allowedFilters[i].type;
                filters[allowedFilters[i].name][length]['filter_through_join'] = allowedFilters[i].hasAttribute('filter_through_join');
                filters[allowedFilters[i].name][length]['join_table'] = allowedFilters[i].getAttribute('join_table');
                filters[allowedFilters[i].name][length]['conditional_operator'] = allowedFilters[i].getAttribute('conditional_operator');
            }
        }
    }

    function addChangeEvent(){

        var allowedFilters = document.getElementsByClassName('data-filters');
        for (var i = 0; i < allowedFilters.length; i++) {
            // Check if the element is a Select2 element

                $('#' + allowedFilters[i].id).on('change', function(event) {
                    debugger;
                    dataTableReload(event);
                });
        }
    }

    function dataTableReload(event) {
        datatable.ajax.reload();
    }


</script>