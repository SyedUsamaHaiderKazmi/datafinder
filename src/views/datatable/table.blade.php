{{--
    DataFinder Table Structure Component
    
    Renders the HTML structure for the DataTable.
    Uses scoped classes and IDs for multi-instance support.
    
    @param array $frontend_config - Frontend configuration options
    
    @package SUHK\DataFinder
    @since 2.0.0
--}}

<div class="d-flex flex-column flex-wrap mt-3 mb-3 p-2 bg-white">
    
    {{-- 
        Error Container (scoped to this instance)
        Uses df-errors class for ErrorHandler targeting
    --}}
    <div class="df-errors flex-fill bg-white" style="display: none;">
        <div class="df-error-message" style="display: none;">
            {{-- Error messages will be rendered here by ErrorHandler --}}
        </div>
    </div>
    
    {{-- 
        DataTable Container
        Uses df-table class for Instance targeting
    --}}
    <div class="flex-fill">
        <table 
            id="df-{{ $frontend_config['dom_table_id'] }}" 
            class="df-table table table-striped table-hover table-bordered {{ $frontend_config['responsive'] ? 'responsive' : '' }} {{ $frontend_config['class'] ?? '' }}"
            style="text-align: center; vertical-align: middle; width: 100%;">
            <thead style="text-align: center; vertical-align: middle;">
                {{-- Column headers rendered by DataTables --}}
            </thead>
            <tbody>
                {{-- Data rows rendered by DataTables --}}
            </tbody>
            <tfoot>
                {{-- Footer rendered by DataTables if enabled --}}
            </tfoot>
        </table>
    </div>
    
</div>
