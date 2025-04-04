<?php
namespace App\Http\Controllers;

use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Count total research entries
        $totalResearch = Research::count();

        // Group research by department and count total for each
        $departments = Research::select('subject_area', DB::raw('count(*) as total'))
            ->groupBy('subject_area')
            ->get();

        // Retrieve the top 5 most searched keywords from the search_logs table
        $mostSearchedKeywords = DB::table('search_logs')
            ->select('keyword', DB::raw('count(*) as total'))
            ->groupBy('keyword')
            ->orderByDesc('total')
            ->take(5) // Limit to top 5 keywords
            ->get();

        // Pass the data to the view
        return view('report.index', compact('totalResearch', 'departments', 'mostSearchedKeywords'));
    }
}