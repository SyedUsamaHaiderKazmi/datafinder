<div class="flex-fill m-auto p-2" @if(isset($filter['visibility']) && !$filter['visibility']) hidden @endif>
    <label>
        {{ $filter['label'] }}:
    </label>
    @isset($filter['name'])
        <input type="{{ $filter['type'] }}" 
            @if($data && !is_array($data))
                value="{{ $data }}" 
            @elseif(is_null($data) || empty($data))
                placeholder="{{ empty($filter['placeholder']) ? 'Enter value to filter' : $filter['placeholder']}}" 
            @else
                placeholder="Wrong Data Passed!" disabled 
            @endif
            name="{{ $filter['name'] }}" id="{{ $filter['id'] ?? null }}" 
            @if($filter['type'] == 'date') 
                data-date-format="YYYY-MM-DD" 
            @endif
            class="form-control data-finder-filters" 
            @isset($filter['filter_through_join']) filter_through_join="{{ $filter['filter_through_join'] }}" @endisset
            @isset($filter['join_table']) join_table="{{ $filter['join_table'] }}" @endisset
            @isset($filter['conditional_operator']) conditional_operator="{{ $filter['conditional_operator'] }}" @endisset
            @isset($filter['column_name']) column_name="{{ $filter['column_name'] }}" @endisset {{-- column_name is used for usecases like value is append from model or mutator, but you want to search or filter on its value in db directly. --}}
        >
    @else
        'name' attribute is required!
    @endisset
</div>