@if ($filter['type'] == 'select')
    @include('datafinder::filters.elements.select', ['data' => $data])
@elseif ($filter['type'] == 'text' || $filter['type'] == 'date' || $filter['type'] == 'time' || $filter['type'] == 'month')
    @include('datafinder::filters.elements.input', ['data' => $data])
@endif