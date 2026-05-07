<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FreelancingCategory;

class FreelancingController extends Controller
{
    public function index()
    {
        $categories = FreelancingCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('user.freelancing.index', compact('categories'));
    }
}
