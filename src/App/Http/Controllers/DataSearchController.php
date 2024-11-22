<?php

namespace SUHK\DataFinder\App\Http\Controllers;

use Illuminate\Http\Request;
use SUHK\DataFinder\Helpers\Globals;

class DataSearchController extends Controller
{

    public function liveSearchTableRender(Request $request)
    {
        // dd($request->toArray());
        $MODEL = $request->model;
        $table_name = $request->table_name;
        $columns = Globals::getTableColumnsConfiguation($table_name);
        $totalData = 0;

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]['data'];
        $dir = $request->input('order.0.dir');
        $filters = $request->filters;

        $query = $MODEL::query();
        $has_joins = \config('filter_configurations.' . $table_name . '.table_has_joins');
        if ($has_joins) {
            $select = \config('filter_configurations.' . $table_name . '.joins.select');
            foreach (\config('filter_configurations.' . $table_name . '.joins.tables') as $key => $join) {
                $query->leftjoin($key, $join['reference_in_current'], $join['conditional_sign'], $join['reference_in_join']);
            }
            $this->getSelectQuery($query, $select);
            // $query->select($this->getSelectQuery($select))->distinct();
            // dd($this->getSelectQuery($select));
        }
        // sums of columns

        $search = $request->input('search.value');
        if (empty($search)) {

            $query->where(function ($query) use ($filters, $has_joins, $table_name) {
                foreach ($filters as $key => $filter) {
                    // dd($filters);
                    $query->where(function ($multiQuery) use ($filter, $key, $has_joins, $table_name) {
                        foreach ($filter as $subFilterKey => $value) {
                            if ($value['search_through_join'] == 'true' && $value['join_table'] != null) {
                                $multiQuery->orWhere($has_joins ? ($value['join_table'] . '.' . $key) : $key, $value['conditional_operator'] == null ? '=' : $value['conditional_operator'], $value['value']);
                            } else {
                                if ($value['type'] == 'date') {
                                    if (!is_null($value['value'])) {
                                        $multiQuery->whereDate($has_joins ? ($table_name . '.' . $key) : $key, $value['conditional_operator'] == null ? '=' : $value['conditional_operator'], $value['value']);
                                    }
                                } else {
                                    $multiQuery->orWhere($has_joins ? ($table_name . '.' . $key) : $key, $value['conditional_operator'] == null ? '=' : $value['conditional_operator'], $value['value']);
                                }
                            }
                        }
                    });
                }
            });
        } else {

            $searchable_columns = Globals::getTableSearchableColumns($table_name);

            $query->where(function ($query) use ($filters, $has_joins, $table_name) {
                foreach ($filters as $key => $filter) {
                    $query->where(function ($multiQuery) use ($filter, $key, $has_joins, $table_name) {
                        foreach ($filter as $subFilterKey => $value) {
                            if ($value['search_through_join'] == 'true' && $value['join_table'] != null) {
                                $multiQuery->orWhere($has_joins ? ($value['join_table'] . '.' . $key) : $key, $value['conditional_operator'] == null ? '=' : $value['conditional_operator'], $value['value']);
                            } else {
                                if ($value['type'] == 'date') {
                                    if (!is_null($value['value'])) {
                                        $multiQuery->whereDate($has_joins ? ($table_name . '.' . $key) : $key, $value['conditional_operator'] == null ? '=' : $value['conditional_operator'], $value['value']);
                                    }
                                } else {

                                    $multiQuery->orWhere($has_joins ? ($table_name . '.' . $key) : $key, $value['conditional_operator'] == null ? '=' : $value['conditional_operator'], $value['value']);
                                }
                            }
                        }
                    });
                    // $query->where($key, $filter);
                }
            })->where(function ($query) use ($searchable_columns, $search) {
                foreach ($searchable_columns as $key => $column) {
                    $query->orWhere($column['table_name'] . '.' . $column['column_name'], 'LIKE', "%{$search}%");
                }
            });
        }
        // dd($query->orderBy($order, $dir)->get()->unique('id')->toArray());

        $totalData = $query->orderBy($order, $dir)->get()->unique('id')->count();

        $records = $query->orderBy($order, $dir)->get()->unique('id')->slice($start)->take($limit);
        // dd($start, $order, $dir, $query->count());
        $totalFiltered = $totalData;

        $data = array();
        // dd($records->toArray());
        if (!empty($records)) {
            // on joins sql add same parent row multiple times according to the number of rows in child table. SO we had to do below thing.
            $ids_only = $records->pluck('id');
            $added_ids = [];
            // This above array will be used to compare and see if same aray is not being added to draw on table.
            foreach ($records as $record) {
                $arrayRecord = $record->toArray();

                if (\config('filter_configurations.' . $table_name . '.row_has_buttons')) {
                    $record->options = "<div class='btn-group' role='group' aria-label='Basic example'>";
                    foreach (\config('filter_configurations.' . $table_name . '.table_row_buttons') as $key => $button) {

                        $route = str_replace('{row_id}', $record->id, $request->routes[$button['route_key']]) ?? '#';
                        $record->options .= "<a href='" . $route . (!is_null($button['tooltip']) ? "' title='" . $button['tooltip'] : '') . "' class='btn btn-sm shadow' style='background-color:" . $button['bgColor'] . "; color:" . $button['color'] . "';>" . (!is_null($button['icon']) ? "<i class='" . $button['icon'] . "'></i>" : "") . "</a>";

                    }
                }
                $data[] = $record->toArray();
                array_push($added_ids, $record->id);

            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,

        );

        echo json_encode($json_data);
    }

    public function getSelectQuery($query, $select)
    {
        $select_raw = [];
        foreach ($select as $table_name => $table_columns) {
            if ($table_columns['selective_columns'] == 'true') {
                foreach ($table_columns['columns'] as $column_name => $value) {
                    if (isset($value['sum'])) {
                        foreach ($value['sum'] as $sum) {
                            if (isset($sum['where'])) {
                                $sum_raw_query = 'SUM(CASE WHEN ' . str_replace(['lessthan', 'greaterthan'], ['<', '>'], $sum['where']['when_clause']) . ' THEN ' . $table_name . '.' . $column_name . ' ELSE ' . $sum['where']['else_clause'] . ' END' . ') as ' . $sum['as'];
                            } else {
                                $sum_raw_query = 'SUM(' . $table_name . '.' . $column_name . ') as ' . $sum['as'];
                            }
                            // dd(\DB::raw($sum_raw_query));
                            // array_push($select_raw, \DB::raw($sum_raw_query));
                            // $query->addSelect(\DB::raw($sum_raw_query));
                        }
                    }
                    if (isset($value['count'])) {
                        // will be written in future as per the need.
                    }
                    $raw_query = $table_name . '.' . $column_name . ($value['as'] ? ' as ' . $value['as'] : '');
                    $query->addSelect($raw_query);
                    // array_push($select_raw, $raw_query);
                }
            } else {
                $query->addSelect($table_name . '.*'); /* WIll get all the data for all columns if the array is empty*/
                // array_push($select_raw, $table_name . '.*');
            }
        }
        // dd($query->toSql());
        return $select_raw;
    }

}
