<div class="row filters-on-print">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="form-group">
                    {{-- <input name="model_path" type="hidden" value="{{$filters_configuration['model_path']}}"/>
                    <input name="index_path" type="hidden" value="{{$filters_configuration['index_path']}}"/>
                    <input name="controller_path" type="hidden" value="{{$filters_configuration['controller_path']}}"/> --}}
                    <div class="row"> 
                        @foreach(config('filter_configurations.'.$table_name.'.filters') as $filter)  
                            @if ($filter['visibility'])
                                @if ($filter['type'] == 'select')
                                    <div class="col-md-2">
                                        <label>
                                            {{ $filter['label'] }}:
                                        </label>
                                        <div>
                                            {!! Form::select($filter['table_column_name'], $filter['value'], $filter['selected']??null, ['id' => $filter['id'], 'class' => 'form-control select2 data-filters', 'multiple', 'data-placeholder' => '--- Select User ---', 'filter_through_join' => $filter['filter_through_join'], 'join_table' => $filter['join_table'], 'conditional_operator' => $filter['conditional_operator']]) !!}
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-2">
                                        <label>
                                            {{ $filter['label'] }}:
                                        </label>
                                        <div>
                                            <input type="{{ $filter['type'] }}" value="{{ $filter['value'] }}" name="{{ $filter['table_column_name'] }}" id="{{ $filter['id'] }}" data-date-format="YYYY-MM-DD" class="form-control data-filters" filter_through_join="{{ $filter['filter_through_join'] }}" join_table="{{ $filter['join_table'] }}" conditional_operator="{{ $filter['conditional_operator'] }}">
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4 text-center">
                            <button type="submit" class="btn btn-success"{{--  onclick="getFilterData('{{$filters_configuration['route']}}')" --}}>
                                <i class="mdi mdi-filter">
                                </i>
                                Filter
                            </button>
                            <a class="btn btn-secondary" href="">
                                <i class="mdi mdi-recycle">
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