<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AffectedAnimalsExport extends Export implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Validate data
        $validatedData = request()->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'headquarter' => 'nullable|exists:headquarters,id',
            'district' => 'nullable|exists:territories,id',
            'county' => 'nullable|exists:territories,id',
            'parish' => 'nullable|exists:territories,id',
            'protocol' => 'nullable|exists:territories,id',
            'vet' => 'nullable|exists:vets,id',
        ]);

        // Store variables
        $start = $this->input('start');
        $end = $this->input('end');
        $headquarter = $this->input('headquarter');
        $district = $this->input('district');
        $county = $this->input('county');
        $parish = $this->input('parish');
        $protocol = $this->input('protocol');
        $vet = $this->input('vet');

        // Set conditions
        $male_ids = [6, 7, 8];
        $female_ids = [30, 31, 32, 33];
        $conditions = [
            't.appointment_id = a.id',
            'a.process_id = p.id',
            'treatment_type_id IN ('.implode(', ', array_merge($male_ids, $female_ids)).')',
            'p.territory_id = tr.id',
            'p.headquarter_id = h.id',
            't.vet_id = v.id',
        ];

        if ($district) {
            $territory_id = $parish ?: $county ?: $district;
            $conditions[] = "p.territory_id LIKE '$territory_id%'";
        }

        if ($protocol) {
            $conditions[] = "p.territory_id LIKE '$protocol%'";
        }

        if ($vet) {
            $conditions[] = "v.id = '$vet'";
        }

        if ($start) {
            $conditions[] = "t.date >= '$start'";
        }

        if ($end) {
            $conditions[] = "t.date <= '$end'";
        }

        if ($headquarter) {
            $conditions[] = "p.headquarter_id = '$headquarter'";
        }

        // Merge conditions
        $conditions = implode(' AND ', $conditions);

        $query = "SELECT treatment_type_id as id, p.specie, SUM(t.affected_animals) as total
            FROM `treatments` t, `appointments` a, `processes` p, `territories` tr, `headquarters` h, `vets` v
            WHERE $conditions
            GROUP BY treatment_type_id, specie";

        $data = DB::select(DB::raw($query));

        $results = [
            ['specie' => __('cat'), 'gender' => ucfirst(__('male')), 'total' => 0],
            ['specie' => __('cat'), 'gender' => ucfirst(__('female')), 'total' => 0],
            ['specie' => __('dog'), 'gender' => ucfirst(__('male')), 'total' => 0],
            ['specie' => __('dog'), 'gender' => ucfirst(__('female')), 'total' => 0],
        ];

        foreach ($data as $entry) {
            $index = ($entry->specie == 'dog') * 2 + in_array($entry->id, $female_ids);
            $results[$index]['total'] += $entry->total;
        }

        return collect($results);
    }

    public function headings(): array
    {
        return [
            __('Specie'),
            ucfirst(__('gender')),
            __('Total'),
        ];
    }
}
