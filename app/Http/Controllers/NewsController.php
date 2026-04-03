<?php
namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function show($slug)
    {
        $news = News::where('slug', $slug)
                    ->where('is_active', 1)
                    ->firstOrFail();

        return view('news-details', compact('news'));
    }
}