@if(SUHK\DataFinder\Helpers\ConfigGlobal::validateConfigFile($config_file_name))
    @if(count(SUHK\DataFinder\Helpers\ConfigParser::getFiltersConfiguation($config_file_name)) > 0)
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach(SUHK\DataFinder\Helpers\ConfigParser::getFiltersConfiguation($config_file_name) as $filter)  
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
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4 text-center">
                        <button type="button" id="btn-data-finder-filter" class="btn btn-success shadow rounded-0"{{--  onclick="getFilterData('{{$filters_configuration['route']}}')" --}}>
                            Filter
                        </button>
                        <a class="btn btn-danger shadow rounded-0 pl-2" href="">
                            Clear Filter
                        </a>
                    </div>
                    <div class="col-md-4">
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <p>{{ SUHK\DataFinder\Helpers\ConfigGlobal::$file_not_exisit_message }}</p>
@endif