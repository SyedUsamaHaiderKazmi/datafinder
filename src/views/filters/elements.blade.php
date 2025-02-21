@if ($filter['type'] == 'select')
    @include('datafinder::filters.elements.select', ['data' => $data])
@elseif ($filter['type'] == 'text')
    @include('datafinder::filters.elements.input', ['data' => $data])
@endif