<?php

namespace App\Http\Controllers;

use Config;
use Woocommerce;

class WooCommerceController extends Controller
{
    public function getProducts()
    {
        $tags = Config::get('woocommerce.tags');
        $limit = Config::get('woocommerce.limit', 6);

        $result = Woocommerce::get('products', [
            'status' => 'publish',
            'tag' => $tags,
            'per_page' => $limit,
        ]);

        $products = [];
        foreach ($result as $product) {
            $img = $product['images'][0]['src'] ?? null;

            $products[] = (object) [
                'id' => (int) $product['id'],
                'name' => (string) $product['name'],
                'price' => (float) $product['price'],
                'image' => preg_replace('/(.+)\.(\w+)$/', '${1}-300x300.${2}', $img),
                'description' => (string) $product['short_description'],
                'url' => $product['permalink'],
            ];
        }

        return $products;
    }
}
