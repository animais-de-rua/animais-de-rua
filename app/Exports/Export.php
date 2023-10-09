<?php

namespace App\Exports;

use DB;

class Export
{
    public $limit = 0;

    public function input($field, $default = null)
    {
        return request()->input($field) ?? $default;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function appendLimit()
    {
        return $this->limit ? ' LIMIT ' . $this->limit : '';
    }

    public function collectResults($query)
    {
        $data = DB::select(DB::raw($query));

        // Parse all json fields
        foreach ($data as &$row) {
            foreach ($row as $key => $value) {
                if ($value && is_string($value) && $value[0] === '{') {
                    $row->{$key} = json_decode($value)->{app()->getLocale()} ?? null;
                }
            }
        }

        return collect($data);
    }
}
