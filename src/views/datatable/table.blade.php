<div class="d-flex flex-column flex-wrap mt-3 mb-3 p-2 bg-white">
    <div class="flex-fill bg-white hide" id="df-{{ $frontend_config['dom_table_id'] }}-errors">
        <div id="error-message" style="display:none; color: red; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb;">
            <!-- Error message will be displayed here -->
        </div>
    </div>
    <div class="flex-fill">
       <table
       id="df-{{ $frontend_config['dom_table_id'] }}" 
       class="df-table table table-striped table-hover table-bordered {{ $frontend_config['responsive'] }} {{ $frontend_config['class'] }}"
       style="text-align: center; vertical-align: middle;">
            <thead style="text-align: center; vertical-align: middle;">
            </thead>
            <tbody>
            </tbody>
            <tfoot></tfoot>
       </table>
   </div>
</div>
