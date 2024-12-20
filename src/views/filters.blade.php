@if(count(SUHK\DataFinder\Helpers\ConfigParser::getFiltersConfiguation($config_file_name)) > 0)
    <div class="row filters-on-print">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group p-2 pl-3 pr-3">
                        {{-- <input name="model_path" type="hidden" value="{{$filters_configuration['model_path']}}"/>
                        <input name="index_path" type="hidden" value="{{$filters_configuration['index_path']}}"/>
                        <input name="controller_path" type="hidden" value="{{$filters_configuration['controller_path']}}"/> --}}
                        <div class="row">
                            {{-- @dd(SUHK\DataFinder\Helpers\ConfigParser::getFiltersConfiguation($config_file_name)) --}}
                            @foreach(SUHK\DataFinder\Helpers\ConfigParser::getFiltersConfiguation($config_file_name) as $filter)  
                                {{-- @if ($filter['visibility']) --}}
                                    @if ($filter['type'] == 'select')
                                        <div class="col-md-2" @if(!$filter['visibility']) hidden @endif>
                                            <label>
                                                {{ $filter['label'] }}:
                                            </label>
                                            <div>
                                                {!! Form::select($filter['column_name'], $filter['value'], $filter['selected']??null, ['id' => $filter['id'], 'class' => 'form-control datafinder-select2 data-filters', 'multiple', 'data-placeholder' => '--- Select User ---', 'filter_through_join' => $filter['filter_through_join'], 'join_table' => $filter['join_table'], 'conditional_operator' => $filter['conditional_operator']]) !!}
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-2" @if(!$filter['visibility']) hidden @endif>
                                            <label>
                                                {{ $filter['label'] }}:
                                            </label>
                                            <div>
                                                <input type="{{ $filter['type'] }}" 
                                                @if($filter['value_type'] == 'ROUTE_PARAM')
                                                    value="{{ \Illuminate\Support\Facades\Route::current()->parameter($filter['value']) }}" 
                                                @elseif($filter['value_type'] == 'CUSTOM')
                                                    value="{{ $filter['value'] }}" 
                                                @endif

                                                name="{{ $filter['column_name'] }}" id="{{ $filter['id'] }}" data-date-format="YYYY-MM-DD" class="form-control data-filters" filter_through_join="{{ $filter['filter_through_join'] }}" join_table="{{ $filter['join_table'] }}" conditional_operator="{{ $filter['conditional_operator'] }}">
                                            </div>
                                        </div>
                                    @endif
                                {{-- @endif --}}
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4 text-center">
                                <button type="button" id="btn-data-finder-filter" class="btn btn-success shadow rounded-0"{{--  onclick="getFilterData('{{$filters_configuration['route']}}')" --}}>
                                    <i class="fa fa-filter fa-sm">
                                    </i> 
                                    Filter
                                </button>
                                <a class="btn btn-warning shadow rounded-0 pl-2" href="">
                                    <i class="fa fa-recycle fa-sm">
                                    </i> 
                                    Clear Filter
                                </a>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- 
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{ asset('js/filters/filters.js')  }}">></script> --}}
    <script type="text/javascript">
        $('.datafinder-select2').select2({
            width: 'resolve' // need to override the changed default
        });
    </script>
@endif