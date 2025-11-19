<div class="flex-fill p-2" @if(isset($filter['visibility']) && !$filter['visibility']) hidden @endif>
    <label>
        {{ $filter['label'] }}:
    </label>
    <div>
        @if($data && is_array($data))
            @if(isset($filter['name']))
                <select id="{{ isset($filter['id']) ? $filter['id'] : '' }}"
                    name="{{ $filter['name'] }}"
                    class="form-control {{ isset($filter['id']) ? $filter['id'] : '' }}-datafinder-select2 data-finder-filters"
                    multiple
                    @isset($filter['filter_through_join']) filter_through_join="{{ $filter['filter_through_join'] }}" @endisset
                    @isset($filter['join_table']) join_table="{{ $filter['join_table'] }}" @endisset
                    @isset($filter['conditional_operator']) conditional_operator="{{ $filter['conditional_operator'] }}" @endisset
                    @isset($filter['column_name']) column_name="{{ $filter['column_name'] }}" @endisset {{-- column_name is used for usecases like value is append from model or mutator, but you want to search or filter on its value in db directly. --}}
                    >
                    @foreach($data as $key => $value)
                        <option value="{{ $key }}" @if(isset($filter['selected']) && $key == $filter['selected']) selected @endif>
                            {{ $value }}
                        </option>
                    @endforeach

                </select>
            @else
                'name' attribute is required!
            @endif
        @else
            Wrong Data Passed!
        @endif
    </div>
</div>
@push('df-filters-scripts')
    <script type="text/javascript">
        $('.{{$filter['id']}}-datafinder-select2').select2({
            width: '100%', // need to override the changed default
            placeholder: @json(isset($filter['placeholder']) ? $filter['placeholder'] : 'Select Filter'), // Placeholder text
        });
    </script>
@endpush
