@if(count(SUHK\DataFinder\App\Helpers\ConfigParser::getFiltersConfiguation($config_file_name)) > 0)
    <div class="d-flex flex-column flex-wrap {{-- sticky-top --}} bg-white">
        <div class="d-flex flex-row flex-wrap mb-auto p-2">
            @foreach(SUHK\DataFinder\App\Helpers\ConfigParser::getFiltersConfiguation($config_file_name) as $filter)  
                @if($filter['value_type'] == 'ROUTE_PARAM' && !is_null(\Illuminate\Support\Facades\Route::current()->parameter($filter['value'])))
                    @php
                        $data = \Illuminate\Support\Facades\Route::current()->parameter($filter['value']);
                    @endphp
                @elseif($filter['value_type'] == 'QUERY_PARAM' && !is_null(request()->query($filter['value'])))
                    @php
                        $data = request()->query($filter['value']);
                    @endphp
                @elseif($filter['value_type'] == 'PHP_VARIABLE' && isset($phpVariables) && isset($phpVariables[$filter['value']]))
                    @php
                        $data = $phpVariables[$filter['value']];
                    @endphp
                @elseif($filter['value_type'] == 'CUSTOM' && isset($filter['value']))
                    @php
                        $data = $filter['value'];
                    @endphp
                @else
                    {{-- wrong value set for filter --}}
                    @php
                        $data = false;
                    @endphp
                @endif

                @include('datafinder::filters.elements', ['data' => $data])
            @endforeach
        </div>
        <div class="align-self-center mt-auto mb-3">
            <button type="button" id="btn-data-finder-filter" class="btn btn-success shadow m-auto">
                Filter Data
            </button>
            <a class="btn btn-danger shadow m-auto" href="">
                Clear Filter
            </a>
        </div>
    </div>
@endif