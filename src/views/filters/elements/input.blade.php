{{--
    DataFinder Input Filter Element
    
    Renders a text/date/number input filter.
    Uses df-filter class for FilterManager targeting.
    
    @param array $filter - Filter configuration
    @param mixed $data - Filter default value
    
    @package SUHK\DataFinder
    @since 2.0.0
--}}

<div class="df-filter-wrapper flex-fill m-auto p-2" 
     @if(isset($filter['visibility']) && !$filter['visibility']) hidden @endif>
    
    {{-- Filter Label --}}
    <label for="{{ $filter['id'] ?? 'df-filter-' . $filter['name'] }}" class="df-filter-label">
        {{ $filter['label'] }}:
    </label>
    
    @isset($filter['name'])
        {{-- 
            Input Element
            - Uses df-filter class for FilterManager
            - Data attributes for filter metadata
            - Supports text, date, number, etc.
        --}}
        <input 
            type="{{ $filter['type'] }}" 
            id="{{ $filter['id'] ?? 'df-filter-' . $filter['name'] }}"
            name="{{ $filter['name'] }}"
            class="df-filter df-input form-control"
            {{-- Value handling --}}
            @if($data && !is_array($data))
                value="{{ $data }}"
            @elseif(is_null($data) || empty($data) || $data === false)
                placeholder="{{ $filter['placeholder'] ?? 'Enter value to filter' }}"
            @else
                placeholder="Invalid data type" 
                disabled
            @endif
            {{-- Date format for date inputs --}}
            @if($filter['type'] === 'date') 
                data-date-format="YYYY-MM-DD"
            @endif
            {{-- Filter metadata as data attributes --}}
            data-column="{{ $filter['column_name'] ?? $filter['name'] }}"
            data-operator="{{ $filter['conditional_operator'] ?? '=' }}"
            @if(isset($filter['filter_through_join']) && $filter['filter_through_join'])
                data-join-filter="true"
                data-join-table="{{ $filter['join_table'] ?? '' }}"
            @endif
            data-filter-type="{{ $filter['type'] ?? 'text' }}"
            {{-- Legacy attributes for backward compatibility --}}
            @isset($filter['filter_through_join']) filter_through_join="{{ $filter['filter_through_join'] }}" @endisset
            @isset($filter['join_table']) join_table="{{ $filter['join_table'] }}" @endisset
            @isset($filter['conditional_operator']) conditional_operator="{{ $filter['conditional_operator'] }}" @endisset
            @isset($filter['column_name']) column_name="{{ $filter['column_name'] }}" @endisset
        >
    @else
        <span class="text-danger">'name' attribute is required!</span>
    @endisset
</div>
