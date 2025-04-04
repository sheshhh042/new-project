<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Searchlog;
use App\Models\Research;

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
        $searchHistories = Searchlog::orderByDesc('count')->take(10)->get();

        // Pass the data to the view
        return view('research.index', compact('researches', 'department', 'searchHistories'));
    }
}