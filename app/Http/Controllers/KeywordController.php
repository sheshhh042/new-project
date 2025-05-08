<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SearchHistory;
use App\Models\Research;
use App\Models\Searchlog;

class KeywordController extends Controller
{
    public function index()
    {
        // Get departments data
        $departments = Research::select('subject_area', DB::raw('count(*) as total'))
            ->groupBy('subject_area')
            ->orderBy('total', 'desc')
            ->get();
    
        $totalResearch = Research::count();
        
        // Get total number of searches
        $totalSearches = Searchlog::count();
        
        // Get all search terms for the table (paginated)
        $searches = Searchlog::select(
                'keyword',
                DB::raw('count(*) as total'),
                DB::raw('min(created_at) as first_searched_at')
            )
            ->groupBy('keyword')
            ->orderBy('total', 'desc')
            ->paginate(10   );  // Paginate with 10 items per page
    
        // Get top 5 most searched keywords for the card
        $mostSearchedKeywords = $searches->take(5);

        $mostSearchedKeywords = searchlog::count();
    
        return view('keywords.index', compact(
            'departments',
            'totalResearch',
            'mostSearchedKeywords',
            'totalSearches',
            'searches',  // Pass the paginated results to the view
            
        ));
    }
    public function trackSearch(Request $request)
{
    $validated = $request->validate([
        'keyword' => 'required|string|max:255'
    ]);

    // Update or create search record
    $search = SearchHistory::updateOrCreate(
        ['keyword' => $validated['keyword']],
        ['count' => DB::raw('count + 1')]
    );

    return response()->json([
        'success' => true,
        'count' => $search->count
    ]);
}
    
    // ... rest of your controller methods
}