<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research;

class ResearchSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $suggestions = Research::searchSuggestions($query)
            ->take(5)
            ->pluck('Research_Title')
            ->toArray();

        return response()->json($suggestions);
    }
}