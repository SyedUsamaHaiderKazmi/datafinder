@if(SUHK\DataFinder\Helpers\ConfigGlobal::validateConfigFile($config_file_name))
    @if(count(SUHK\DataFinder\Helpers\ConfigParser::getFiltersConfiguation($config_file_name)) > 0)
        <div class="row filters-on-print">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group p-2 pl-3 pr-3">
                            <div class="row">
                                @foreach(SUHK\DataFinder\Helpers\ConfigParser::getFiltersConfiguation($config_file_name) as $filter)  
                                    @if ($filter['type'] == 'select')
                                        @include('datafinder::filters.select')
                                    @elseif ($filter['type'] == 'text')
                                        @include('datafinder::filters.input')
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4 text-center">
                                    <button type="button" id="btn-data-finder-filter" class="btn btn-success shadow rounded-0"{{--  onclick="getFilterData('{{$filters_configuration['route']}}')" --}}>
                                        Filter
                                    </button>
                                    <a class="btn btn-danger shadow rounded-0 pl-2" href="">
                                        Clear Filter
                                    </a>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- 
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>
        <script src="{{ asset('js/filters/filters.js')  }}">></script> --}}
        <script type="text/javascript">
            $('.datafinder-select2').select2({
                width: 'resolve' // need to override the changed default
            });
        </script>
    @endif
@else
    <p>{{ SUHK\DataFinder\Helpers\ConfigGlobal::$file_not_exisit_message }}</p>
@endif