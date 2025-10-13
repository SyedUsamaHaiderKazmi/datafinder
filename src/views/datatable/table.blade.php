<div class="row" style="margin-top: 10px;">
    <a href="" id="file_path" hidden></a>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white hide" id="df-{{ $dom_table_id }}-errors">
                <div id="error-message" style="display:none; color: red; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb;">
                    <!-- Error message will be displayed here -->
                </div>
            </div>
            <div class="card-body">
               <table
               id="df-{{ $dom_table_id }}" 
               class="df-table table table-striped table-hover table-bordered {{ $responsive }} {{ $table_classes }}"
               style="text-align: center; vertical-align: middle;">
                    <thead style="text-align: center; vertical-align: middle;">
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot></tfoot>
               </table>
           </div>
       </div>
    </div>
</div>