<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Http\Requests;
use URL;
use Sitemap;

class SitemapsController extends Controller
{
    //
    public function index()
    {
        Sitemap::addSitemap(route('sitemaps.category'));
        Sitemap::addSitemap(URL::route('sitemaps.novels'));
        return Sitemap::index();
    }

    public function category()
    {
        $categories = [
            'xuanhuan', 'xiuzhen', 'dushi', 'lishi', 'wangyou', 'kehuan', 'other'
        ];
        foreach ($categories as $category){
            Sitemap::addTag(route('category', $category), '', '', '0.9');
        }
        Sitemap::addTag(route('top'), '', '', '0.9');
        Sitemap::addTag(route('over'), '', '', '0.9');
        Sitemap::addTag(route('release'), '', '', '0.9');
        Sitemap::addTag(route('authors'), '', '', '0.9');
        return Sitemap::render();
    }

    public function novels()
    {
        $novels = Novel::all();
        foreach($novels as $novel){
            Sitemap::addTag(route('book', $novel->id), $novel->updated_at, 'daily', '0.8');
        }
        return Sitemap::render();
    }
}
