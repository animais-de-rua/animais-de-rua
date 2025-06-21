<?php

namespace App\Http\Controllers;

use Automattic\WooCommerce\Client as WooCommerce;

class WooCommerceController extends Controller
{
    /**
     * @return array<object>
     */
    public function getProducts(): array
    {
        $wooCommerce = new WooCommerce(
            url: config('woocommerce.store_url'),
            consumer_key: config('woocommerce.consumer_key'),
            consumer_secret: config('woocommerce.consumer_secret'),
            options: [
                'version' => 'wc/v3',
            ],
        );

        $result = $wooCommerce->get('products', [
            'status' => 'publish',
            'tag' => config('woocommerce.tags'),
            'per_page' => config('woocommerce.limit', 6),
        ]);

        return collect($result)
            ->map(function (array $product): object {
                $img = $product['images'][0]['src'] ?? null;
                $img = preg_replace('/(.+)\.(\w+)$/', '${1}-300x300.${2}', $img);
                $img = str_replace('-scaled', '', $img);

                return (object) [
                    'id' => (int) $product['id'],
                    'name' => (string) $product['name'],
                    'price' => (float) $product['price'],
                    'image' => $img,
                    'description' => (string) $product['short_description'],
                    'url' => $product['permalink'],
                ];
            })
            ->toArray();
    }
}
