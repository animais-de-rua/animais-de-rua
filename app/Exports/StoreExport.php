<?php

namespace App\Exports;

use App\Helpers\EnumHelper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StoreExport extends Export implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Validate data
        $validatedData = request()->validate([
            'status' => 'nullable|in:' . EnumHelper::keys('store.order', ','),
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'order.column' => 'required|in:' . join(',', [
                'reference',
                'created_at',
                'receipt',
                'expense',
                'invoice',
                'price',
                'price_no_vat',
                'discount',
                'discount_no_vat',
                'expense',
                'total_no_vat',
                'quantity',
            ]),
            'order.direction' => 'required|in:ASC,DESC',
        ]);

        // Store variables
        $status = $this->input('status');
        $start = $this->input('start');
        $end = $this->input('end');
        $orderColumn = $this->input('order.column');
        $orderDirection = $this->input('order.direction');

        // Set conditions
        $conditions = [
            'o.id = op.store_order_id',
            'op.store_product_id = p.id',
        ];

        if ($status) {
            $conditions[] = "o.status = '$status'";
        }

        if ($start) {
            $conditions[] = "o.created_at >= '$start'";
        }

        if ($end) {
            $conditions[] = "o.created_at <= '$end'";
        }

        // Merge conditions
        $conditions = join(' AND ', $conditions);

        // Query
        $query = "SELECT
                o.reference,
                o.created_at,
                o.recipient,
                o.receipt,
                o.invoice,
                SUM(p.price) as price,
                SUM(p.price_no_vat) as price_no_vat,
                SUM(op.discount) as discount,
                SUM(op.discount_no_vat) as discount_no_vat,
                o.expense,
                SUM(p.price_no_vat) - SUM(op.discount_no_vat) - o.expense as total_no_vat,
                SUM(op.quantity) as quantity,
                GROUP_CONCAT(p.name SEPARATOR '; ') as products
            FROM store_orders o, store_orders_products op, store_products p
            WHERE $conditions
            GROUP BY o.id
            ORDER BY $orderColumn $orderDirection";

        // Add Limit
        $query .= $this->appendLimit();

        return $this->collectResults($query);
    }

    public function headings(): array
    {
        return [
            __('Reference'),
            __('Date'),
            __('Recipient'),
            __('Receipt'),
            __('Invoice'),
            __('Price'),
            __('Price') . ' (' . __('no VAT') . ')',
            __('Discount'),
            __('Discount') . ' (' . __('no VAT') . ')',
            __('Expense'),
            __('Total') . ' (' . __('no VAT') . ')',
            __('Quantity'),
            __('Products'),
        ];
    }
}
