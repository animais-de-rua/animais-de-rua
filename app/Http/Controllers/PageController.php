<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Backpack\PageManager\app\Models\Page;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index($slug)
    {
        $page = Page::findBySlug($slug);

        if (!$page)
            abort(404);

        $this->data['title'] = $page->title;
        $this->data['page'] = $page->withFakes();

        return view('pages.'.$page->template, $this->data);
    }
}