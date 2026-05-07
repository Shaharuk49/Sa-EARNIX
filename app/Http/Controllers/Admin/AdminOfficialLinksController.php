<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficialLink;
use Illuminate\Http\Request;

class AdminOfficialLinksController extends Controller
{
    public function index()
    {
        $links = OfficialLink::orderBy('id')->get();
        return view('admin.official-links.index', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:100',
            'url'      => 'required|url|max:500',
            'key_name' => 'required|string|max:100|unique:official_links,key_name',
        ]);
        OfficialLink::create([
            'key_name' => $request->key_name,
            'title'    => $request->title,
            'url'      => $request->url,
            'is_active'=> true,
        ]);
        return back()->with('success', 'Link added.');
    }

    public function update(Request $request, OfficialLink $officialLink)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'url'   => 'required|url|max:500',
        ]);
        $officialLink->update($request->only('title', 'url'));
        return back()->with('success', 'Link updated.');
    }

    public function destroy(OfficialLink $officialLink)
    {
        $officialLink->delete();
        return back()->with('success', 'Link removed.');
    }
}
