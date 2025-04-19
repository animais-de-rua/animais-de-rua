<?php

namespace App\Exports;

use App\Helpers\EnumHelper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DonationExport extends Export implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Validate data
        $validatedData = request()->validate([
            'type' => 'nullable|in:'.EnumHelper::keys('donation.type', ','),
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'headquarter' => 'nullable|exists:headquarters,id',
            'protocol' => 'nullable|exists:protocols,id',
            'group' => 'nullable|in:'.implode(',', array_keys(self::group())),
            'order.column' => 'required|in:'.implode(',', array_keys(self::order())),
            'order.direction' => 'required|in:ASC,DESC',
        ]);

        // Store variables
        $type = $this->input('type');
        $start = $this->input('start');
        $end = $this->input('end');
        $headquarter_id = $this->input('headquarter');
        $protocol_id = $this->input('protocol');
        $group = $this->input('group');
        $orderColumn = $this->input('order.column');
        $orderDirection = $this->input('order.direction');
        $filtered_by_process = $this->input('group') == 'process_id';

        // Set conditions
        $conditions = [
            'd.process_id = processes.id',
        ];

        $selects = [];
        if (! $filtered_by_process) {
            array_push($selects, ! $type || $type == 'headquarter' ? 'headquarters.name as headquarter_name' : '');
            array_push($selects, ! $type || $type == 'private' ? 'godfathers.name as godfather_name' : '');
            array_push($selects, ! $type || $type == 'protocol' ? 'protocols.name as protocol_name' : '');
            $selects = implode(', ', array_filter($selects)).',';
        } else {
            $selects = '';
        }

        if ($type) {
            $conditions[] = "d.type = '$type'";
        }

        if ($start) {
            $conditions[] = "d.date >= '$start'";
        }

        if ($end) {
            $conditions[] = "d.date <= '$end'";
        }

        if ($headquarter_id) {
            $conditions[] = "d.headquarter_id = $headquarter_id";
        }

        if ($protocol_id) {
            $conditions[] = "d.protocol_id = $protocol_id";
        }

        // Merge conditions
        $conditions = implode(' AND ', $conditions);

        // Group by
        $value = 'value';
        if (isset($group)) {
            $group = "GROUP BY d.$group";
            $value = 'SUM(value)';
        }

        // Query
        $query = "SELECT
                d.type,
                processes.name as process_name,
                $selects
                $value,
                date
            FROM processes, donations d
            LEFT JOIN headquarters ON d.headquarter_id = headquarters.id
            LEFT JOIN godfathers ON d.godfather_id = godfathers.id
            LEFT JOIN protocols ON d.protocol_id = protocols.id
            WHERE $conditions
            $group
            ORDER BY $orderColumn $orderDirection";

        // Add Limit
        $query .= $this->appendLimit();

        // Collect results
        $data = $this->collectResults($query);

        // Translate type attribute
        foreach ($data as &$item) {
            $item->type = ucfirst(__($item->type));
        }

        return $data;
    }

    public static function order(): array
    {
        return [
            'date' => __('Date'),
            'value' => __('Value'),
            'type' => __('Type'),
        ];
    }

    public static function group(): array
    {
        return [
            '' => '',
            'process_id' => ucfirst(__('process')),
            'godfather_id' => ucfirst(__('godfather')),
            'headquarter_id' => ucfirst(__('headquarter')),
            'protocol_id' => ucfirst(__('protocol')),
        ];
    }

    public function headings(): array
    {
        if ($this->input('type')) {
            $filtered = $this->input('type');
            if ($filtered == 'private') {
                $filtered = 'godfather';
            }

            return array_filter([
                __('Type'),
                ucfirst(__('process')),
                ucfirst(__($filtered)),
                __('Value'),
                __('Date'),
            ]);
        } else {
            $filtered_by_process = $this->input('group') == 'process_id';

            return array_filter([
                __('Type'),
                ucfirst(__('process')),
                $filtered_by_process ? '' : ucfirst(__('headquarter')),
                $filtered_by_process ? '' : ucfirst(__('godfather')),
                $filtered_by_process ? '' : ucfirst(__('protocol')),
                __('Value'),
                __('Date'),
            ]);
        }
    }
}
