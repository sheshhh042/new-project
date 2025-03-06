<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Searchlog;

class SearchController extends Controller
{
    public function search(Request $request) {
        $keyword = $request->input('keyword');

        if (!empty($keyword)) {
            // Store search keyword in database
            Searchlog::create(['keyword' => $keyword]);

            // Perform search logic (modify based on your needs)
            $results = SomeModel::where('title', 'LIKE', "%$keyword%")->get();

            return view('search.results', compact('results', 'keyword'));
        }

        return redirect()->back()->with('error', 'Please enter a search term.');
    }
}
