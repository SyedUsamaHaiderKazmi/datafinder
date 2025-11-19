@section('df-styles')
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/datafinder/assets/styles/df-style.css') }}">
@endsection

{{-- @if(!isset($custom_datatable) || !$custom_datatable) --}}
    @include('datafinder::datatable.table')
    @push('df-datatable')
        <script type="module">
            {{-- import {XLSXExport} from './vendor/datafinder/assets/js/export/abstract/export.js'; --}}
            import Exporter from '/vendor/datafinder/assets/js/export/services/Exporter.js';
            window.Exporter = Exporter;

        </script>
        <script type="text/javascript">

            let columns = null;
            let live_search_filter_route = null;
            let export_route = null;

        </script>
        <script type="text/javascript">

            columns = @json(SUHK\DataFinder\App\Helpers\ConfigParser::getTableColumnsConfiguation($config_file_name));
            @if(SUHK\DataFinder\App\Helpers\ConfigParser::tableHasRowButtons($config_file_name))
                columns.push({
                    title: 'Actions',
                    data: 'actions',
                    orderable: false
                });
            @endif

            live_search_filter_route = frontend_config.allow_custom_route ? frontend_config.custom_data_route : '{{ route("df.data") }}';
            export_route = frontend_config.allow_custom_route ? frontend_config.custom_export_route : '{{ route("df.export.init") }}';
            datafinder_table_id = frontend_config.dom_table_id;

            let full_text_search = frontend_config.full_text_search;
            
            let default_per_page = frontend_config.default_per_page;
            
            let allow_per_page_options = frontend_config.allow_per_page_options;
            
            let per_page_options = frontend_config.per_page_options;
            
            let exportable = frontend_config.exportable
            ;
        </script>
    @endpush    
    {{-- var table_cols_configuration = {!! json_encode(config('filter_configurations.'.$table_name.'.table_headers')) !!}; --}}
    
{{-- @elseif(isset($custom_datatable) && $custom_datatable)
    @include('datafinder::datatable.table')
    @push('df-datatable-custom')
        <script type="text/javascript">
            custom_datatable = frontend_config.allow_custom_route;
            datafinder_table_id = frontend_config.dom_table_id;
        </script>
    @endpush
@endif --}}
@section('df-scripts')
    <script type="text/javascript">
        {{-- let custom_datatable = frontend_config.allow_custom_route; --}}
    </script>
    @stack('df-filters-scripts')
    @stack('df-datatable')
    {{-- @stack('df-datatable-custom') --}}
    <script type="text/javascript" src="{{ url('vendor/datafinder/assets/js/datafinder-datatable-init.js') }}"></script>
    <script type="text/javascript" src="{{ url('vendor/datafinder/assets/js/datafinder-datatable-helper.js') }}"></script>
    <script type="text/javascript" src="{{ url('vendor/datafinder/assets/js/datafinder-datatable-constants.js') }}"></script>
@endsection