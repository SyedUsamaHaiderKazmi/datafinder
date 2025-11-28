{{--
    DataFinder Select Filter Element
    
    Renders a multi-select dropdown filter with Select2 integration.
    Uses df-filter class for FilterManager targeting.
    
    @param array $filter - Filter configuration
    @param mixed $data - Filter options data (array of key => value)
    
    @package SUHK\DataFinder
    @since 2.0.0
--}}

<div class="df-filter-wrapper flex-fill p-2" 
     @if(isset($filter['visibility']) && !$filter['visibility']) hidden @endif>
    
    {{-- Filter Label --}}
    <label for="{{ $filter['id'] ?? '' }}" class="df-filter-label">
        {{ $filter['label'] }}:
    </label>
    
    <div class="df-filter-input">
        @if($data && is_array($data))
            @if(isset($filter['name']))
                {{-- 
                    Select Element
                    - Uses df-filter class for FilterManager
                    - Data attributes for filter metadata
                    - Select2 integration via class
                --}}
                <select 
                    id="{{ $filter['id'] ?? 'df-filter-' . $filter['name'] }}"
                    name="{{ $filter['name'] }}"
                    class="df-filter df-select form-control {{ $filter['id'] ?? '' }}-datafinder-select2"
                    multiple
                    data-column="{{ $filter['column_name'] ?? $filter['name'] }}"
                    data-operator="{{ $filter['conditional_operator'] ?? '=' }}"
                    @if(isset($filter['filter_through_join']) && $filter['filter_through_join'])
                        data-join-filter="true"
                        data-join-table="{{ $filter['join_table'] ?? '' }}"
                    @endif
                    data-filter-type="{{ $filter['type'] ?? 'select' }}"
                    {{-- Legacy attributes for backward compatibility --}}
                    @isset($filter['filter_through_join']) filter_through_join="{{ $filter['filter_through_join'] }}" @endisset
                    @isset($filter['join_table']) join_table="{{ $filter['join_table'] }}" @endisset
                    @isset($filter['conditional_operator']) conditional_operator="{{ $filter['conditional_operator'] }}" @endisset
                    @isset($filter['column_name']) column_name="{{ $filter['column_name'] }}" @endisset
                >
                    @foreach($data as $key => $value)
                        <option 
                            value="{{ $key }}" 
                            @if(isset($filter['selected']) && $key == $filter['selected']) selected @endif
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            @else
                <span class="text-danger">'name' attribute is required!</span>
            @endif
        @else
            <span class="text-warning">No data available for filter</span>
        @endif
    </div>
</div>

{{-- Select2 Initialization (scoped to this specific select) --}}
@push('df-filters-scripts')
    <script type="text/javascript">
        (function() {
            // Initialize Select2 for this specific filter
            const selectId = '{{ $filter['id'] ?? 'df-filter-' . $filter['name'] }}';
            const $select = $('#' + selectId);
            
            if ($select.length && !$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    width: '100%',
                    placeholder: @json($filter['placeholder'] ?? 'Select option(s)'),
                    allowClear: true,
                    closeOnSelect: false,
                    // Prevent Select2 from opening on clear
                    minimumResultsForSearch: 5
                });
            }
        })();
    </script>
@endpush
