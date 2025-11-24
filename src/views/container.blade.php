<div class="df-container">
    @if(isset($config_file_name))
    	@php
    		$config_file_name = $config_file_name . '/main';
		@endphp
    	@if(SUHK\DataFinder\App\Helpers\ConfigGlobal::validateConfigFile($config_file_name))
	    	@php
	    		$frontend_config = SUHK\DataFinder\App\Helpers\ConfigParser::getPrimaryFrontendOptions($config_file_name)
	    	@endphp
		    @include('datafinder::filters.filters', ['frontend_config' => $frontend_config])
		    @include('datafinder::datatable.datatable', ['frontend_config' => $frontend_config])

		    <script>
		    	let frontend_config = @json($frontend_config);
		    	let config_file_name = @json($config_file_name);
		    </script>
	    @else
		    <p style="border-left: 0.2rem solid red;background: #ffdfdf;margin: 1rem;padding: 0.1rem 1rem;color: #565656;">{{ SUHK\DataFinder\App\Helpers\ConfigGlobal::$file_not_exist_message }}</p>
		@endif
	@else
	    <p style="border-left: 0.2rem solid red;background: #ffdfdf;margin: 1rem;padding: 0.1rem 1rem;color: #565656;">{{ SUHK\DataFinder\App\Helpers\ConfigGlobal::$config_file_path_missed_message }}</p>
	@endif
</div>