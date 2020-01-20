<?php

namespace App\Http\Requests;

use App\Helpers\EnumHelper;
use App\Http\Requests\Request;
use App\Models\StoreOrder;
use App\Models\StoreProduct;
use App\Models\StoreStock;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reference' => 'required_without:id',
            'recipient' => 'required_without:id',
            'address' => 'required_without:id',
            'user_id' => 'required_without:id|exists:users,id',
            'shipment_date' => 'nullable|date',
            'expense' => 'nullable|numeric|min:0|max:1000000',
            'status' => 'in:' . EnumHelper::keys('store.order', ','),
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $id = $this->input('id');
            $user_id = $this->input('user_id') ?: StoreOrder::find($id)->user_id;
            $user_name = null;

            // Validate user has all the products
            $products = json_decode($this->input('products'));
            foreach ($products as $product) {
                $quantity = $product->pivot->quantity;
                $store_product_id = $product->pivot->store_product_id ?? null;

                if ($quantity > 0 && $store_product_id) {
                    $product = StoreProduct::find($store_product_id);

                    // Stock quantity
                    $quantity_stock = StoreStock::where('user_id', $user_id)
                        ->where('store_product_id', $product->id)
                        ->sum('quantity');

                    // Sent quantity
                    $quantity_sent = StoreOrder::join('store_orders_products', 'store_orders_products.store_order_id', '=', 'store_orders.id')
                        ->where('store_product_id', $product->id)
                        ->where('user_id', $user_id);

                    // In case it is an edit, itens on that order doesn't count
                    if ($id) {
                        $quantity_sent = $quantity_sent->where('id', '<>', $id);
                    }

                    $quantity_sent = $quantity_sent->sum('quantity');

                    // Total normalized
                    $total = max(0, $quantity_stock - $quantity_sent);

                    if ($quantity > $total) {
                        $user_name = $user_name ?: User::select('name')->where('id', $user_id)->first()->name;

                        $validator->errors()->add('products', trans_choice(__('store_order_assing_error'), $total, [
                            'amount' => $quantity,
                            'product' => $product->name,
                            'user' => $user_name,
                            'quantity' => $total,
                        ]));
                    }
                }
            }

            // Invoice required when status == shipped
            if ($this->input('status') == 'shipped') {
                if (!strlen($this->input('invoice'))) {
                    $validator->errors()->add('invoice', __('Invoice is required when the order is shipped.'));
                }

                if (!$this->input('shipment_date')) {
                    $validator->errors()->add('shipment_date', __('Shipment Date is required when the order is shipped.'));
                }
            }

            return $validator->errors();
        });
    }
}
