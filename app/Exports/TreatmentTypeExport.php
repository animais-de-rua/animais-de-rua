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
            'order.column' => 'required|in:' . join(',', [
                'name',
                'expense',
                'affected_animals',
                'affected_animals_new',
            ]),
            'order.direction' => 'required|in:ASC,DESC',
        ]);

        // Store variables
        $status = $this->input('status');
        $start = $this->input('start');
        $end = $this->input('end');
        $headquarter = $this->input('headquarter');
        $orderColumn = $this->input('order.column');
        $orderDirection = $this->input('order.direction');

        // Set conditions
        $conditions = [
            't.treatment_type_id = tt.id',
            't.appointment_id = a.id',
            'a.process_id = p.id',
        ];

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
            FROM treatments t, treatment_types tt, appointments a, processes p
            WHERE $conditions
            GROUP BY t.treatment_type_id
            ORDER BY $orderColumn $orderDirection";

        // Add Limit
        $query .= $this->appendLimit();

        return $this->collectResults($query);
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
