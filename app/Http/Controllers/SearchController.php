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

    // Filter by keyword using Soundex
    if (!empty($keyword)) {
        $query->whereRaw("SOUNDEX(research_title) = SOUNDEX(?)", [$keyword])
              ->orWhereRaw("SOUNDEX(author) = SOUNDEX(?)", [$keyword]);
    }

    // Apply additional filters for year range and department
    if (!empty($filter)) {
        $years = explode('-', $filter);
        if (count($years) === 2) {
            $query->whereBetween('date', ["{$years[0]}-01-01", "{$years[1]}-12-31"]);
        }
    }

    if (!empty($department)) {
        $query->where('department', $department);
    }

    $researches = $query->get();

    return view('research.index', compact('researches', 'department'));
}
}