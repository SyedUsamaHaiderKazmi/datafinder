<div class="col-md-2" @if(!$filter['visibility']) hidden @endif>
    <label>
        {{ $filter['label'] }}:
    </label>
    <div>
        <input type="{{ $filter['type'] }}" 
        @if($data && !is_array($data))
            value="{{ $data }}" 
        @elseif(is_null($data) || empty($data))
            placeholder="Enter value to filter" 
        @else
            placeholder="Wrong Data Passed!" 
        @endif
        name="{{ $filter['column_name'] }}" id="{{ $filter['id'] }}" 
        @if($filter['type'] == 'date') 
            data-date-format="YYYY-MM-DD" 
        @endif
        class="form-control data-filters" 
        filter_through_join="{{ $filter['filter_through_join'] }}"
        join_table="{{ $filter['join_table'] }}"
        conditional_operator="{{ $filter['conditional_operator'] }}">
    </div>
</div>