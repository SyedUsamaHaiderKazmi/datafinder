<div class="col-md-2" @if(isset($filter['visibility']) && !$filter['visibility']) hidden @endif>
    <label>
        {{ $filter['label'] }}:
    </label>
    <div>
        <input type="{{ $filter['type'] }}" 
        @if($data && !is_array($data))
            value="{{ $data }}" 
        @elseif(is_null($data) || empty($data))
            placeholder="{{ empty($filter['placeholder']) ? 'Enter value to filter' : $filter['placeholder']}}" 
        @else
            placeholder="Wrong Data Passed!" 
        @endif
        name="{{ $filter['name'] ?? null }}" id="{{ $filter['id'] }}" 
        @if($filter['type'] == 'date') 
            data-date-format="YYYY-MM-DD" 
        @endif
        class="form-control data-finder-filters" 
        column_name="{{ $filter['column_name'] ?? null }}"
        filter_through_join="{{ $filter['filter_through_join'] ?? false }}"
        join_table="{{ $filter['join_table'] ?? null }}"
        conditional_operator="{{ $filter['conditional_operator'] ?? '=' }}">
    </div>
</div>