<div class="col-md-2" @if(!$filter['visibility']) hidden @endif>
    <label>
        {{ $filter['label'] }}:
    </label>
    <div>
        <input type="{{ $filter['type'] }}" 
        @if($filter['value_type'] == 'ROUTE_PARAM')
            value="{{ \Illuminate\Support\Facades\Route::current()->parameter($filter['value']) }}" 
        @elseif($filter['value_type'] == 'PHP_VARIABLE')
            @if(isset($phpVariables[$filter['value']]))
                value="{{ $phpVariables[$filter['value']] }}"
            @else
                placeholder="Wrong Data Passed!" 
            @endif
        @elseif($filter['value_type'] == 'CUSTOM')
            value="{{ $filter['value'] }}" 
        @endif

        name="{{ $filter['column_name'] }}" id="{{ $filter['id'] }}" @if($filter['type'] == 'date') data-date-format="YYYY-MM-DD" @endif class="form-control data-filters" filter_through_join="{{ $filter['filter_through_join'] }}" join_table="{{ $filter['join_table'] }}" conditional_operator="{{ $filter['conditional_operator'] }}">
    </div>
</div>