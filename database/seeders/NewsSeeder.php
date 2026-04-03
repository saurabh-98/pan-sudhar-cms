<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        News::truncate();

        for ($i = 1; $i <= 5; $i++) {

            $title = "Latest Food News $i";

            News::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => 'This is demo content for news section with rich text.',
                'meta_title' => $title,
                'meta_description' => 'SEO description for news',
                'image' => 'news/demo.jpg',
                'is_active' => 1
            ]);
        }
    }
}