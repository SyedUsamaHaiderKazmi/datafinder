
@if(SUHK\DataFinder\Helpers\ConfigGlobal::validateConfigFile($config_file_name))

    <div class="row" style="margin-top: 10px;">
        <a href="" id="file_path" hidden></a>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white hide" id="df-{{ SUHK\DataFinder\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'dom_table_id')}}-errors">
                    <div id="error-message" style="display:none; color: red; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb;">
                        <!-- Error message will be displayed here -->
                    </div>
                </div>
                <div class="card-body">
                   <table
                   id="df-{{ SUHK\DataFinder\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'dom_table_id') }}" 
                   class="table table-striped table-bordered {{ SUHK\DataFinder\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'responsive') == true ? ' table-responsive' : '' }}" >
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
    @section('scripts')
        @stack('df-filters-scripts')
        <script type="text/javascript">

            var config_file_name = @json($config_file_name);
            var columns = @json(SUHK\DataFinder\Helpers\ConfigParser::getTableColumnsConfiguation($config_file_name));
            var liveSearchFilterRoute = '{{ route("liveSearchTableRender") }}';

            var datafinder_table_id = @json(SUHK\DataFinder\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'dom_table_id'));
            var default_per_page = @json(SUHK\DataFinder\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'default_per_page'));

            if(default_per_page == null || default_per_page == 0 || default_per_page == '') {
                default_per_page = 10;
            }
            var allow_per_page_options = @json(SUHK\DataFinder\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'allow_per_page_options'));
            
            if(allow_per_page_options == null) {
                allow_per_page_options = false;
            }
            
            var per_page_options = @json(SUHK\DataFinder\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'per_page_options'));

        </script>
        <script type="text/javascript" src="{{ asset('vendor/datafinder/assets/js/datafinder-datatable-init.js') }}"></script>
    @endsection
@else
    <p>{{ SUHK\DataFinder\Helpers\ConfigGlobal::$file_not_exisit_message }}</p>
@endif