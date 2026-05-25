<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use App\Services\PageService;
use App\Services\FooterService;
use App\models\Gallery;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected HomeService $homeService;
    protected PageService $pageService;
    protected FooterService $footerService;

    public function __construct(
        HomeService $homeService,
        PageService $pageService,
        FooterService $footerService
    ) {
        $this->homeService = $homeService;
        $this->pageService = $pageService;
        $this->footerService = $footerService;
    }

    /* =========================
       COMMON FOOTER DATA
    ========================= */
    private function footer(): array
    {
        return $this->footerService->getFooterData();
    }

    /* =========================
       HOME PAGE
    ========================= */
    public function index()
    {
        // 🔥 Get all homepage dynamic data
        $data = $this->homeService->getHomePageData();
       

        // Merge with footer
        return view('home', array_merge(
            $data,
            $this->footer()
        ));
    }

    /* =========================
       CMS PAGE
    ========================= */
    public function page(string $slug)
    {
        $page = $this->pageService->getBySlug($slug);

        return view('page', array_merge(
            compact('page'),
            $this->footer()
        ));
    }

     public function gallery()
    {
        /*
        |--------------------------------------------------------------------------
        | GET GALLERY
        |--------------------------------------------------------------------------
        */

        $gallery = Gallery::latest()

            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(

            'gallery-view',

            array_merge(

                compact('gallery'),

                $this->footer()
            )
        );
    }
}