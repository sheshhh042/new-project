<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeywordController extends Controller
{

        public function index()
        {
            // Retrieve all search terms from the search_logs table
            $searches = DB::table('search_logs')
                ->select('keyword', DB::raw('count(*) as total'))
                ->groupBy('keyword')
                ->orderByDesc('total') // Order by the most searched
                ->get();
    
            return view('keywords.index', compact('searches'));
        }
    
    public function create()
    {
        return view('keywords.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255|unique:search_logs,keyword',
        ]);

        DB::table('search_logs')->insert([
            'keyword' => $request->keyword,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('keywords.index')->with('success', 'Keyword added successfully.');
    }
}