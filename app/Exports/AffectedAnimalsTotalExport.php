<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AffectedAnimalsTotalExport extends Export implements FromCollection, WithHeadings
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
        $conditions = [
            't.appointment_id = a.id',
            'a.process_id = p.id',
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

        $query = "SELECT SUM(t.affected_animals_new) as total
            FROM `treatments` t, `appointments` a, `processes` p, `territories` tr, `headquarters` h, `vets` v
            WHERE $conditions";

        return $this->collectResults($query);
    }

    public function headings(): array
    {
        return [
            __('Total'),
        ];
    }
}
