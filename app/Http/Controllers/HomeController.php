<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featured = Product::query()
            ->where('is_published', true)
            ->with(['category', 'variants'])
            ->latest()
            ->take(8)
            ->get();

        $heroSlides = HeroSlide::query()
            ->active()
            ->ordered()
            ->get();

        return view('user.home', [
            'featured' => $featured,
            'heroSlides' => $heroSlides,
        ]);
    }
}
