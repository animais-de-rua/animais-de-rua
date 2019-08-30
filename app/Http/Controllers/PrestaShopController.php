<?php

namespace App\Http\Controllers;

use Config;
use PrestaShopWebservice;

class PrestaShopController extends Controller
{
    public function getProducts()
    {
        $url = 'https://animaisderua.org/store';

        $webService = new PrestaShopWebservice($url, Config::get('app.prestashop_secret'), false);

        $productsXML = $webService->get([
            'resource' => 'products',
            'display' => 'full',
            'filter[id_category_default]' => Config::get('app.prestashop_categories'),
            'limit' => 6,
        ])->products->children();

        $count = 0;
        $products = [];
        foreach ($productsXML as $product) {
            $nameLanguage = $product->xpath('name/language[@id=1]');
            $descriptionLanguage = $product->xpath('description/language[@id=1]');
            $idImage = (string) $product->id_default_image;

            $products[] = (object) [
                'id' => (int) $product->id,
                'name' => (string) $nameLanguage[0],
                'price' => (float) $product->price,
                'image' => "$url/$idImage-home_default/prestashop-$idImage.jpg",
                'description' => (string) $descriptionLanguage[0],
                'url' => $url . '/index.php?controller=product&id_product=' . $product->id,
            ];
        }

        return $products;
    }
}
