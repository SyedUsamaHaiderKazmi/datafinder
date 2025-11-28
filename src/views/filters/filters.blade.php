{{--
    DataFinder Filters Component
    
    Renders filter elements within a scoped container.
    All filter elements use df-filter class for FilterManager targeting.
    
    @param string $config_file_name - Configuration file path
    @param array $frontend_config - Frontend configuration options
    @param array $phpVariables - Optional PHP variables for filter values
    
    @package SUHK\DataFinder
    @since 2.0.0
--}}

@php
    $filters = SUHK\DataFinder\App\Helpers\ConfigParser::getFiltersConfiguation($config_file_name);
@endphp

@if(count($filters) > 0)
    <div class="df-filters-container d-flex flex-column flex-wrap bg-white">
        
        {{-- Filter Elements Row --}}
        <div class="df-filters-row d-flex flex-row flex-wrap mb-auto p-2">
            @foreach($filters as $filter)
                @php
                    // Resolve filter value based on value_type
                    $data = false;
                    
                    if ($filter['value_type'] === 'ROUTE_PARAM') {
                        // Get value from route parameter
                        $data = \Illuminate\Support\Facades\Route::current()->parameter($filter['value']);
                    } elseif ($filter['value_type'] === 'QUERY_PARAM') {
                        // Get value from query string
                        $data = request()->query($filter['value']);
                    } elseif ($filter['value_type'] === 'PHP_VARIABLE') {
                        // Get value from passed PHP variables
                        $data = isset($phpVariables[$filter['value']]) ? $phpVariables[$filter['value']] : false;
                    } elseif ($filter['value_type'] === 'CUSTOM') {
                        // Use custom/static value
                        $data = $filter['value'] ?? false;
                    }
                @endphp

                {{-- Render appropriate filter element --}}
                @include('datafinder::filters.elements', [
                    'filter' => $filter,
                    'data' => $data
                ])
            @endforeach
        </div>
        
        {{-- Filter Action Buttons --}}
        <div class="df-filters-actions align-self-center mt-auto mb-3">
            <button type="button" class="df-filter-btn btn btn-success shadow m-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px; vertical-align: text-bottom;">
                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                </svg>
                Filter Data
            </button>
            <button type="button" class="df-clear-btn btn btn-outline-danger shadow m-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px; vertical-align: text-bottom;">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
                Clear Filters
            </button>
        </div>
        
    </div>
@endif
