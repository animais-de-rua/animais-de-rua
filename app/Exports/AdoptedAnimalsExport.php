<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdoptedAnimalsExport extends Export implements FromCollection, WithHeadings
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
        ]);

        // Store variables
        $start = $this->input('start');
        $end = $this->input('end');
        $headquarter = $this->input('headquarter');
        $district = $this->input('district');
        $county = $this->input('county');
        $parish = $this->input('parish');
        $protocol = $this->input('protocol');

        // Set conditions
        $conditions = [
            "a.status = 'closed'",
            'a.process_id = p.id',
            'p.territory_id = t.id',
            'p.headquarter_id = h.id',
        ];

        if ($district) {
            $territory_id = $parish ?: $county ?: $district;
            $conditions[] = "p.territory_id LIKE '$territory_id%'";
        }

        if ($protocol) {
            $conditions[] = "p.territory_id LIKE '$protocol%'";
        }

        if ($start) {
            $conditions[] = "a.adoption_date >= '$start'";
        }

        if ($end) {
            $conditions[] = "a.adoption_date <= '$end'";
        }

        if ($headquarter) {
            $conditions[] = "p.headquarter_id = '$headquarter'";
        }

        // Merge conditions
        $conditions = join(' AND ', $conditions);

        $query = "SELECT p.specie, COUNT(*) total
            FROM `adoptions` a, `processes` p, `territories` t, `headquarters` h
            WHERE $conditions
            GROUP BY p.specie";

        // Collect results
        $data = $this->collectResults($query);

        // Translate specie attribute
        foreach ($data as &$item) {
            $item->specie = ucfirst(__($item->specie));
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            __('Specie'),
            __('Total'),
        ];
    }
}
