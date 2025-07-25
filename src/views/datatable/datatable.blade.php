@if((!isset($custom_datatable) || !$custom_datatable) && isset($config_file_name) && SUHK\DataFinder\App\Helpers\ConfigGlobal::validateConfigFile($config_file_name))
    @include('datafinder::datatable.table', [
            'dom_table_id' => SUHK\DataFinder\App\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'dom_table_id'),
            'responsive' => (SUHK\DataFinder\App\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'dom_table_id') == true) ? 'table-responsive' : ''
        ])
    @push('df-datatable')
        <script type="text/javascript">
            let config_file_name = @json($config_file_name);

            columns = @json(SUHK\DataFinder\App\Helpers\ConfigParser::getTableColumnsConfiguation($config_file_name));
            live_search_filter_route = '{{ route("liveSearchTableRender") }}';
            datafinder_table_id = @json(SUHK\DataFinder\App\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'dom_table_id'));

            let default_per_page = @json(SUHK\DataFinder\App\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'default_per_page'));
            if(default_per_page == null || default_per_page == 0 || default_per_page == '') {
                default_per_page = 10;
            }
            
            let allow_per_page_options = @json(SUHK\DataFinder\App\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'allow_per_page_options'));
            if(allow_per_page_options == null) {
                allow_per_page_options = false;
            }
            
            let per_page_options = @json(SUHK\DataFinder\App\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'per_page_options') ?? '');
            
            let exportable = @json(SUHK\DataFinder\App\Helpers\ConfigGlobal::getValueFromFile($config_file_name, 'exportable'));
        </script>
    @endpush    
    {{-- var table_cols_configuration = {!! json_encode(config('filter_configurations.'.$table_name.'.table_headers')) !!}; --}}
    
@elseif(isset($custom_datatable) && $custom_datatable)
    @include('datafinder::datatable.table', [
            'dom_table_id' => $dom_table_id,
            'responsive' => (isset($responsive) && $responsive == true) ? 'table-responsive' : ''
        ])
    @push('df-datatable-custom')
        <script type="text/javascript">
            custom_datatable = true;
            datafinder_table_id = @json($dom_table_id);
        </script>
    @endpush
@else
    <p style="border-left: 0.2rem solid red;background: #ffdfdf;margin: 1rem;padding: 0.1rem 1rem;color: #565656;">{{ SUHK\DataFinder\App\Helpers\ConfigGlobal::$file_not_exist_message }}</p>
@endif

@section('df-scripts')
    @stack('df-filters-scripts')
    <script type="module">
        {{-- import {XLSXExport} from './vendor/datafinder/assets/js/export/abstract/export.js'; --}}
        import Exporter from '/vendor/datafinder/assets/js/export/services/Exporter.js';
        window.Exporter = Exporter;

    </script>
    <script type="text/javascript">

        let columns = null;
        let live_search_filter_route = null;
        let datafinder_table_id = null;
        let custom_datatable = false;

    </script>
    @stack('df-datatable')
    @stack('df-datatable-custom')
    <script type="text/javascript" src="{{ url('vendor/datafinder/assets/js/datafinder-datatable-init.js') }}"></script>
    <script type="text/javascript" src="{{ url('vendor/datafinder/assets/js/datafinder-datatable-helper.js') }}"></script>
    <script type="text/javascript" src="{{ url('vendor/datafinder/assets/js/datafinder-datatable-constants.js') }}"></script>
@endsection