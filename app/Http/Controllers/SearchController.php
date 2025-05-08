<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Searchlog;
use App\Models\Research;
use Illuminate\Support\Facades\DB;
use App\Models\SearchHistory;


class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $filter = $request->input('filter');
        $department = $request->input('department');

        $query = Research::query();

        // Log the keyword into the Searchlog table
        if (!empty($keyword)) {
            Searchlog::updateOrCreate(
                ['keyword' => $keyword],
                ['count' => \DB::raw('count + 1')] // Increment the count
            );

            // Filter by keyword using Soundex
            $query->whereRaw("SOUNDEX(research_title) = SOUNDEX(?)", [$keyword])
                  ->orWhereRaw("SOUNDEX(author) = SOUNDEX(?)", [$keyword]);
        }

        // Apply additional filters for year range
        if (!empty($filter)) {
            $years = explode('-', $filter);
            if (count($years) === 2) {
                $query->whereBetween('date', ["{$years[0]}-01-01", "{$years[1]}-12-31"]);
            }
        }

        // Filter by department
        if (!empty($department)) {
            $query->where('department', $department);
        }

        // Retrieve the filtered research data
        $researches = $query->get();

        // Retrieve the top 10 search logs for the search history dropdown
        $searchHistories = SearchHistory::orderBy('created_at', 'desc')->get();
        
        // Pass the data to the view
        return view('research.index', compact('researches', 'department', 'searchHistories'));
    }
    public function index()
{
    // Get departments data
    $departments = Research::select('subject_area', DB::raw('count(*) as total'))
        ->groupBy('subject_area')
        ->orderBy('total', 'desc')
        ->get();

    $totalResearch = Research::count();

    // Get most searched keywords with proper date field
    $mostSearchedKeywords = DB::table('searchlogs')
        ->select(
            'keyword',
            DB::raw('count(*) as total'),
            DB::raw('min(created_at) as first_searched_at') // Ensure this field exists
        )
        ->groupBy('keyword')
        ->orderBy('total', 'desc')
        ->take(5)
        ->get();

    return view('keywords.index', compact(
        'departments',
        'totalResearch',
        'mostSearchedKeywords'
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
}