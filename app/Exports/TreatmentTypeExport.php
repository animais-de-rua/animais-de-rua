<?php

namespace App\Exports;

use App\Helpers\EnumHelper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TreatmentTypeExport extends Export implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Validate data
        $validatedData = request()->validate([
            'status' => 'nullable|in:' . EnumHelper::keys('treatment.status', ','),
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'headquarter' => 'nullable|exists:headquarters,id',
            'district' => 'nullable|exists:territories,id',
            'county' => 'nullable|exists:territories,id',
            'parish' => 'nullable|exists:territories,id',
            'protocol' => 'nullable|exists:territories,id',
            'vet' => 'nullable|exists:vets,id',
            'order.column' => 'required|in:' . join(',', array_keys(self::order())),
            'order.direction' => 'required|in:ASC,DESC',
        ]);

        // Store variables
        $status = $this->input('status');
        $start = $this->input('start');
        $end = $this->input('end');
        $headquarter = $this->input('headquarter');
        $district = $this->input('district');
        $county = $this->input('county');
        $parish = $this->input('parish');
        $protocol = $this->input('protocol');
        $vet = $this->input('vet');
        $orderColumn = $this->input('order.column');
        $orderDirection = $this->input('order.direction');

        // Set conditions
        $conditions = [
            't.treatment_type_id = tt.id',
            't.appointment_id = a.id',
            'a.process_id = p.id',
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
            $conditions[] = "p.id = '$vet%'";
        }

        if ($status) {
            $conditions[] = "t.status = '$status'";
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
        $conditions = join(' AND ', $conditions);

        // Query
        $query = "SELECT tt.name, SUM(t.expense) expense, SUM(t.affected_animals) affected_animals, SUM(t.affected_animals_new) affected_animals_new
            FROM treatments t, treatment_types tt, appointments a, processes p, vets v
            WHERE $conditions
            GROUP BY t.treatment_type_id
            ORDER BY $orderColumn $orderDirection";

        // Add Limit
        $query .= $this->appendLimit();

        return $this->collectResults($query);
    }

    public static function order(): array
    {
        return [
            'name' => __('Name'),
            'expense' => __('Expense'),
            'affected_animals' => __('Affected Animals'),
            'affected_animals_new' => __('New affected Animals'),
        ];
    }

    public function headings(): array
    {
        return [
            __('Name'),
            __('Expense'),
            __('Affected Animals'),
            __('New'),
        ];
    }
}
