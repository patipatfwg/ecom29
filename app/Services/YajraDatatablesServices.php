<?php

namespace App\Services;

use Yajra\Datatables\Datatables as Datatables;
use Yajra\Datatables\Request as  YajraRequest;
use Collection;
use Request;

class YajraDatatablesServices
{
    public function __construct(Datatables $datatables)
    {
        $this->datatables = $datatables;

        // Fix Yajra : when click next page don't have data
        // - Yajra -> Request -> isPaginationable()
        // - Request default use request()->get('start') & request()->get('length')
        // - We will set start & length = null
        $inputs              = Request::input();
        $inputs['start']     = null;
        $inputs['length']    = null;
        $yajraRequest        = new YajraRequest;
        $yajraRequest->replace($inputs);
        $datatables->request = $yajraRequest;
    }

    public function getDatatablesByCollection(array $datas, array $columnField, $rowStartNo=null)
    {
        $summary = count($datas) - 1; // -1 summary count

        // Set column
        $column = $this->setColumnForQueryCollection($datas, $columnField, $rowStartNo);

        // Get query
        $query = $this->getQueryCollection($column);

        // Set datatables
        $datatables = $this->datatables->usingCollection($query);

        // Set Entries      
        $datatables = $datatables->with([
                'recordsTotal'    => $summary,
                'recordsFiltered' => $summary
            ]);

        return $datatables;
    }

    /**
     * [setColumnForQueryCollection : Create format column variable for getQueryCollection]
     * @param  array   $data       [Column Config : [columm name => field of database] ]
     * @return array               [data colum mapping field]
     */
    private function setColumnForQueryCollection(array $datas, array $columnField, $rowStartNo=null)
    {
        // Converting an stdClass to array
        $datas = json_decode(json_encode($datas), true);
        unset($datas['summary_count']);

        $columns = [];
        $i = 0;
        foreach ($datas as $key => $data) {

            // Add row no
            if (!empty($rowStartNo) && is_numeric($rowStartNo)) {
                $columns[$i]['row_no'] = $rowStartNo++;
            }

            $data = array_dot($data);
            foreach ($columnField as $columnName => $fieldName) {
                $columns[$i][$columnName] = (isset($data[$fieldName])) ? $data[$fieldName] : '-';
            }

            $i++;
        }

        return $columns;
    }

    /**
     * [getQueryCollection : Create query to collection format]
     * @param  array   $column     [ colume of getColumnForQueryCollection() ]
     * @return array   [description]
     */
    private function getQueryCollection(array $columns)
    {
        // Create collection data query for DataTable
        $query = new Collection;

        foreach ($columns as $column) {
            $query->push($column);
        }

        return $query;
    }

    /**
     * For jenssegers/mongodb
     * Set datas of query
     */
    public function setDataJenssegersEloquent($query, array $columnField)
    {
        $datas = [];
        $i = 0;
        foreach ($query as $data) {
            foreach ($columnField as $key => $value) {
                if (isset($data->$key)) {
                    $datas[$i][$key] = $data->$key;
                }
            }
          $i++;
        }

        // Set summary datas
        $datas['summary_count'] = count($datas);

        return $datas;
    }
}
