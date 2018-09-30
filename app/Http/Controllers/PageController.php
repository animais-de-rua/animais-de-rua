<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Headquarter;
use App\Models\Page;
use App\Models\Process;

class PageController extends Controller
{
    public function index($slug)
    {
        \Debugbar::disable();

        $page = Page::findBySlug($slug);

        if (!$page) {
            abort(404);
        }

        $this->data = [
            'title' => $page->title,
            'page' => $page->withFakes()
        ];

        if (method_exists($this, $slug)) {
            $this->data = array_merge($this->data, call_user_func(array($this, $slug)));
        }

        return view('pages.' . $page->template, $this->data);
    }

    private function home()
    {
        $processes = Process::select(['name', 'specie', 'history', 'images', 'status', 'created_at'])
            ->where('status', 'waiting_godfather')
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        $campaigns = Campaign::select(['name', 'introduction', 'description', 'image'])
            ->orderBy('lft', 'asc')
            ->get();

        return [
            'processes' => $processes,
            'campaigns' => $campaigns
        ];
    }

    private function association()
    {
        $headquarters = Headquarter::select(['name', 'mail'])->get();

        return [
            'headquarters' => $headquarters
        ];
    }

    private function ced()
    {
        return [];
    }

    private function animals()
    {
        return [];
    }

    private function help()
    {
        return [];
    }

    private function partners()
    {
        return [];
    }

    private function friends()
    {
        return [];
    }
}
