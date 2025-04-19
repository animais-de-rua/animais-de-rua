<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbandonedAnimalsExport extends Export implements FromCollection, WithHeadings
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
            "status NOT IN ('closed', 'archived')",
        ];

        if ($district) {
            $territory_id = $parish ?: $county ?: $district;
            $conditions[] = "p.territory_id LIKE '$territory_id%'";
        }

        if ($protocol) {
            $conditions[] = "p.territory_id LIKE '$protocol%'";
        }

        if ($start) {
            $conditions[] = "p.created_at >= '$start'";
        }

        if ($end) {
            $conditions[] = "p.created_at <= '$end'";
        }

        if ($headquarter) {
            $conditions[] = "p.headquarter_id = '$headquarter'";
        }

        // Merge conditions
        $conditions = implode(' AND ', $conditions);

        $query = "SELECT specie, SUM(amount_males + amount_females + amount_other) as total
            FROM `processes` p
            WHERE id NOT IN (
                SELECT DISTINCT process_id
                FROM `appointments`, `treatments`
                WHERE `appointments`.id = `treatments`.appointment_id
                AND process_id IS NOT NULL)
            AND $conditions
            GROUP BY specie";

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
