<?php

namespace App\Http\Controllers;
use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $totalResearch = Research::count();
        $departments = Research::select('subject_area', DB::raw('count(*) as total'))
            ->groupBy('subject_area')
            ->get();
        $mostSearchedKeywords = DB::table('search_logs')
            ->select('keyword', DB::raw('count(*) as total'))
            ->groupBy('keyword')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('report.index', compact('totalResearch', 'departments', 'mostSearchedKeywords'));
    }
}
