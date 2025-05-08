<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MostSearch;

class MostSearchController extends Controller
{
    public function index()
    {
        $mostSearched = MostSearch::orderBy('total_searches', 'desc')->get();
        return view('most-searches.index', compact('mostSearched'));
    }

    public function store(Request $request)
    {
        $searchTerm = $request->input('search_term');
        
        $mostSearch = MostSearch::firstOrCreate(
            ['search_term' => $searchTerm],
            ['total_searches' => 0]
        );
        
        $mostSearch->increment('total_searches');
        
        return response()->json(['success' => true]);
    }
}
