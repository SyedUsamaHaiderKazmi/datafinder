{{--
    DataFinder DataTable Component
    
    Renders the DataTable structure within a scoped container.
    All elements use scoped classes for multi-instance support.
    
    @param string $config_file_name - Configuration file path
    @param array $frontend_config - Frontend configuration options
    
    @package SUHK\DataFinder
    @since 2.0.0
--}}

{{-- Styles Section --}}
@section('df-styles')
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/datafinder/assets/styles/df-style.css') }}">
@endsection

{{-- Include the table structure --}}
@include('datafinder::datatable.table', ['frontend_config' => $frontend_config])

{{-- Scripts Section --}}
@section('df-scripts')
    {{-- Filter scripts stack --}}
    @stack('df-filters-scripts')
    
    {{-- DataTable scripts stack --}}
    @stack('df-datatable')
@endsection
