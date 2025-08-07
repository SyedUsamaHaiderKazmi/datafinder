<div class="col-md-2" @if(!$filter['visibility']) hidden @endif>
    <label>
        {{ $filter['label'] }}:
    </label>
    <div>
        @if($data && is_array($data))
            <select id="{{ isset($filter['id']) ? $filter['id'] : '' }}"
                name="{{ isset($filter['column_name']) ? $filter['column_name'] : '' }}"
                class="form-control {{ isset($filter['id']) ? $filter['id'] : '' }}-datafinder-select2 data-filters"
                multiple
                filter_through_join="{{ isset($filter['filter_through_join']) ? $filter['filter_through_join'] : '' }}"
                join_table="{{ isset($filter['join_table']) ? $filter['join_table'] : '' }}"
                conditional_operator="{{ isset($filter['conditional_operator']) ? $filter['conditional_operator'] : '' }}"
                >
                @foreach($data as $key => $value)
                    <option value="{{ $key }}" @if(isset($filter['selected']) && $key == $filter['selected']) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach

            </select>
        @else
            Wrong Data Passed!
        @endif
    </div>
</div>
@push('df-filters-scripts')
    <script type="text/javascript">
        $('.{{$filter['id']}}-datafinder-select2').select2({
            width: 'resolve', // need to override the changed default
            placeholder: @json(isset($filter['placeholder']) ? $filter['placeholder'] : 'Select Filter'), // Placeholder text
        });
    </script>
@endpush
