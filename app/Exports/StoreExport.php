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
            'products.*' => 'nullable|exists:store_products,id',
            'volunteer' => 'nullable|exists:users,id',
            'order.column' => 'required|in:' . join(',', array_keys(self::order())),
            'order.direction' => 'required|in:ASC,DESC',
        ]);

        // Store variables
        $status = $this->input('status');
        $start = $this->input('start');
        $end = $this->input('end');
        $products = $this->input('products');
        $volunteer = $this->input('volunteer');
        $orderColumn = $this->input('order.column');
        $orderDirection = $this->input('order.direction');

        // Set conditions
        $conditions = [
            'o.id = op.store_order_id',
            'op.store_product_id = p.id',
            'o.user_id = u.id',
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

        if ($products && sizeof(array_filter($products))) {
            $products = join(',', $products);
            $conditions[] = "p.id IN ($products)";
        }

        if ($volunteer) {
            $conditions[] = "o.user_id = '$volunteer'";
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
                SUM(p.price * op.quantity) as price,
                SUM(p.price_no_vat * op.quantity) as price_no_vat,
                SUM(op.discount) as discount,
                SUM(op.discount_no_vat) as discount_no_vat,
                o.expense,
                SUM(p.price * op.quantity) - SUM(op.discount) - o.expense as total,
                SUM(p.price_no_vat * op.quantity) - SUM(op.discount_no_vat) - o.expense as total_no_vat,
                SUM(op.quantity) as quantity,
                u.name as volunteer,
                GROUP_CONCAT(CONCAT(op.quantity, ' ', p.name) SEPARATOR ', ') as products
            FROM store_orders o, store_orders_products op, store_products p, users u
            WHERE $conditions
            GROUP BY o.id
            ORDER BY $orderColumn $orderDirection";

        // Add Limit
        $query .= $this->appendLimit();

        return $this->collectResults($query);
    }

    public static function order(): array
    {
        return [
            'created_at' => __('Date'),
            'reference' => __('Reference'),
            'receipt' => __('Receipt'),
            'expense' => __('Expense'),
            'invoice' => __('Invoice'),
            'price' => __('Price'),
            'price_no_vat' => __('Price') . ' (' . __('no VAT') . ')',
            'discount' => __('Discount'),
            'discount_no_vat' => __('Discount') . ' (' . __('no VAT') . ')',
            'expense' => __('Expense'),
            'total' => __('Total'),
            'total_no_vat' => __('Total') . ' (' . __('no VAT') . ')',
            'quantity' => __('Quantity'),
            'user_id' => __('Volunteer'),
        ];
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
            __('Total'),
            __('Total') . ' (' . __('no VAT') . ')',
            __('Quantity'),
            __('Volunteer'),
            __('Products'),
        ];
    }
}
