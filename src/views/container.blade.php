{{--
    DataFinder Container Component
    
    This is the main entry point for DataFinder.
    It creates a scoped container for the DataTable and filters.
    
    @param string $config_file_name - Path to the configuration file (required)
    @param array $phpVariables - Optional PHP variables to pass to filters
    
    @package SUHK\DataFinder
    @since 2.0.0
--}}

@if(isset($config_file_name))
    @php
        $config_path = $config_file_name . '/main';
    @endphp
    
    @if(SUHK\DataFinder\App\Helpers\ConfigGlobal::validateConfigFile($config_path))
        @php
            // Get frontend configuration
            $frontend_config = SUHK\DataFinder\App\Helpers\ConfigParser::getPrimaryFrontendOptions($config_path);
            
            // Generate unique container ID for this instance
            $container_id = 'df-container-' . $frontend_config['dom_table_id'];
            
            // Get columns configuration
            $columns_config = SUHK\DataFinder\App\Helpers\ConfigParser::getTableColumnsConfiguation($config_path);
            $has_row_buttons = SUHK\DataFinder\App\Helpers\ConfigParser::tableHasRowButtons($config_path);
            
            // Build configuration array for JavaScript
            $js_config = [
                'containerId' => $container_id,
                'configFile' => $config_path,
                'columns' => $columns_config,
                'hasRowButtons' => $has_row_buttons,
                'frontendConfig' => $frontend_config,
            ];
        @endphp
        
        {{-- 
            Main Scoped Container
            All filters and datatable are scoped to this container
            This allows multiple DataFinder instances per page
        --}}
        <div id="{{ $container_id }}" 
             class="df-container" 
             data-config="{{ $config_path }}"
             data-table-id="{{ $frontend_config['dom_table_id'] }}">
            
            {{-- Include filters component (scoped to this container) --}}
            @include('datafinder::filters.filters', [
                'config_file_name' => $config_path,
                'frontend_config' => $frontend_config,
                'phpVariables' => $phpVariables ?? []
            ])
            
            {{-- Include datatable component (scoped to this container) --}}
            @include('datafinder::datatable.datatable', [
                'config_file_name' => $config_path,
                'frontend_config' => $frontend_config
            ])
        </div>

        {{-- Instance-specific JavaScript initialization --}}
        @push('df-scripts')
            <script type="module">
                // Import DataFinder
                import DataFinder from '/vendor/datafinder/assets/js/DataFinder.js';
                
                // Wait for DOM to be ready
                document.addEventListener('DOMContentLoaded', function() {
                    // Get configuration from PHP
                    const config = @json($js_config);
                    
                    // Add actions column if row buttons exist
                    const columns = [...config.columns];
                    if (config.hasRowButtons) {
                        columns.push({
                            title: 'Actions',
                            data: 'actions',
                            orderable: false,
                            searchable: false
                        });
                    }
                    
                    // Initialize DataFinder instance
                    DataFinder.init(config.containerId, {
                        configFile: config.configFile,
                        columns: columns,
                        perPage: config.frontendConfig.default_per_page,
                        perPageOptions: config.frontendConfig.per_page_options,
                        allowPerPageChange: config.frontendConfig.allow_per_page_options,
                        searching: config.frontendConfig.full_text_search,
                        responsive: config.frontendConfig.responsive,
                        exportable: config.frontendConfig.exportable,
                        exportableByChunk: config.frontendConfig.exportable_by_chunk,
                        chunkSize: config.frontendConfig.exportable_chunk_size,
                        dataRoute: config.frontendConfig.allow_custom_route 
                            ? config.frontendConfig.custom_data_route 
                            : '{{ route("df.data") }}',
                        exportRoute: config.frontendConfig.allow_custom_route 
                            ? config.frontendConfig.custom_export_route 
                            : '{{ route("df.export.init") }}',
                        allowCustomRoute: config.frontendConfig.allow_custom_route,
                    });
                    
                    // Optional: Store instance reference for external access
                    window['DataFinderInstance_' + config.frontendConfig.dom_table_id] = DataFinder.get(config.containerId);
                });
            </script>
        @endpush

    @else
        {{-- Configuration file not found error --}}
        <div class="df-error-container">
            <p class="df-error-message" style="border-left: 0.2rem solid red; background: #ffdfdf; margin: 1rem; padding: 0.5rem 1rem; color: #565656;">
                <strong>DataFinder Error:</strong> {{ SUHK\DataFinder\App\Helpers\ConfigGlobal::$file_not_exist_message }}
                <br><small>Config path: {{ $config_path }}</small>
            </p>
        </div>
    @endif
    
@else
    {{-- Config file name not provided error --}}
    <div class="df-error-container">
        <p class="df-error-message" style="border-left: 0.2rem solid red; background: #ffdfdf; margin: 1rem; padding: 0.5rem 1rem; color: #565656;">
            <strong>DataFinder Error:</strong> {{ SUHK\DataFinder\App\Helpers\ConfigGlobal::$config_file_path_missed_message }}
        </p>
    </div>
@endif
