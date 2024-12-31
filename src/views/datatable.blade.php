
@if(SUHK\DataFinder\Helpers\ConfigGlobal::validateConfigFile($config_file_name))

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
    {{-- var table_cols_configuration = {!! json_encode(config('filter_configurations.'.$table_name.'.table_headers')) !!}; --}}
    <script type="text/javascript">
        var config_file_name = @json($config_file_name);
        var columns = @json(SUHK\DataFinder\Helpers\ConfigParser::getTableColumnsConfiguation($config_file_name));
        var liveSearchFilterRoute = '{{ route("liveSearchTableRender") }}';
    </script>
    <script type="text/javascript" src="{{ asset('vendor/datafinder/assets/js/datafinder-datatable-init.js') }}"></script>
@else
    <p>{{ SUHK\DataFinder\Helpers\ConfigGlobal::$file_not_exisit_message }}</p>
@endif