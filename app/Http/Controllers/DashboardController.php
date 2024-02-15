<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Cards;
use App\Models\Gallery;
use App\Models\Promotions;
use App\Models\Social;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

        public function index()
        {
            $banner = Banner::all();
            $cards = Cards::all();
            $gallery = Gallery::all();
            $promo = Promotions::all();
            $soci = Social::all();
            // dd($banner, $cards, $gallery, $promo, $soci);
            return view('dashboard.index', compact('banner','cards','gallery','promo'));
        }

}
