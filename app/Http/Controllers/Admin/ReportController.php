<?php

namespace App\Http\Controllers\Admin;

use DB;
use Excel;
use Illuminate\Support\Str;

class ReportController extends CrudController
{
    const CSV = 'export-csv';
    const PDF = 'export-pdf';
    const PREVIEW = 'preview';

    public function report()
    {
        if (!restrictTo('admin', 'reports')) {
            abort(401);
        }

        return view('admin.reports');
    }

    public function action($action, $type)
    {
        $class = 'App\\Exports\\' . ucfirst(Str::camel($action)) . 'Export';
        $model = new $class;

        switch ($type) {
            default:

            case self::CSV:
                return Excel::download($model, 'data.csv');
                break;

            case self::PDF:
                return Excel::download($model, 'data.pdf', 'Mpdf'); // Dompdf | Mpdf
                break;

            case self::PREVIEW:
                $model->setLimit(10);

                $raw = Excel::raw($model, 'Csv');
                $data = array_filter(explode(PHP_EOL, str_replace('"', '', $raw)));
                foreach ($data as &$row) {
                    $row = explode(';', $row);
                }

                return $data;
                break;
        }

        return $data;
    }

    private function parseResults($query)
    {
        $data = DB::select(DB::raw($query));

        // Parse all json fields
        foreach ($data as &$row) {
            foreach ($row as $key => $value) {
                $json = json_decode($value);
                $row->{$key} = json_last_error() ? $value : $json;
            }
        }

        return $data;
    }
}
