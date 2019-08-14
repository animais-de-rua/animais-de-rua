<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\StoreOrder;
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
            $user_id = $this->input('user_id');

            // Common user cannot edit a shipped order
            if (!is('admin', 'store orders') && StoreOrder::find($id)->shipment_date) {
                return $validator->errors()->add('sent', __("You don't have the permissions to update an order already shipped."));
            }

            // Validate user has all the products
            $products = json_decode($this->input('products'));
            foreach ($products as $product) {
                $product_id = $product->id;
                $product_quantity = $product->pivot->quantity;

                // Stock quantity
                $quantity_stock = StoreStock::where('user_id', $user_id)
                    ->where('store_product_id', $product_id)
                    ->sum('quantity');

                // Sent quantity
                $quantity_sent = StoreOrder::join('store_orders_products', 'store_orders_products.store_order_id', '=', 'store_orders.id')
                    ->where('store_product_id', $product_id)
                    ->where('user_id', $user_id);

                // In case it is an edit, itens on that order doesn't count
                if ($id) {
                    $quantity_sent = $quantity_sent->where('id', '<>', $id);
                }

                $quantity_sent = $quantity_sent->sum('quantity');

                // Total normalized
                $total = max(0, $quantity_stock - $quantity_sent);

                if ($product_quantity > $total) {
                    $username = User::select('name')->where('id', $user_id)->first()->name;

                    $validator->errors()->add('products', trans_choice(__('store_order_assing_error'), $total, [
                        'amount' => $product_quantity,
                        'product' => $product->name,
                        'user' => $username,
                        'quantity' => $total,
                    ]));
                }
            }

            return $validator->errors();
        });
    }
}
