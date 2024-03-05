<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Business;
use App\Models\Cards;
use App\Models\Gallery;
use App\Models\Promotions;
use App\Models\Social;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index(Request $request)
{
    $domain = $request->getHost();

    $business = Business::where('slug', $domain)->first();

    if (!$business) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $slug = $business->slug;
    if ($slug !== $domain) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $banner = Banner::all();
    $cards = Cards::all();
    $gallery = Gallery::all();
    $promo = Promotions::all();
    $soci = Social::all();
    $business = Business::all();


     dd($domain);

    return view('dashboard.index', compact('banner', 'cards', 'gallery', 'promo', 'domain'));
}
}
