@if(($filter['value_type'] == 'PHP_VARIABLE' && isset($phpVariables[$filter['value']])) || ($filter['value_type'] == 'CUSTOM' && isset($filter['value'])))
    <div class="col-md-2" @if(!$filter['visibility']) hidden @endif>
        <label>
            {{ $filter['label'] }}:
        </label>
        <div>
            <select id="{{ isset($filter['id']) ? $filter['id'] : '' }}"
                name="{{ isset($filter['column_name']) ? $filter['column_name'] : '' }}"
                class="form-control datafinder-select2 data-filters"
                multiple
                placeholder="{{ isset($filter['placeholder']) ? $filter['placeholder'] : 'Select Filter' }}"
                filter_through_join="{{ isset($filter['filter_through_join']) ? $filter['filter_through_join'] : '' }}"
                join_table="{{ isset($filter['join_table']) ? $filter['join_table'] : '' }}"
                conditional_operator="{{ isset($filter['conditional_operator']) ? $filter['conditional_operator'] : '' }}"
                >
                @foreach(
                    ($filter['value_type'] == 'PHP_VARIABLE' && isset($phpVariables) && isset($phpVariables[$filter['value']])) 
                        ? $phpVariables[$filter['value']] 
                        : (($filter['value_type'] == 'CUSTOM' && isset($filter['value'])) ? $filter['value'] : [])
                    as $key => $value)
                    <option value="{{ $key }}" @if($key == $filter['selected']) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach

            </select>
        </div>
    </div>
@else
    <div class="col-md-2" @if(!$filter['visibility']) hidden @endif>
        <label>
            {{ $filter['label'] }}:
        </label>
        <div>
            Wrong Data Passed!
        </div>
    </div>
@endif