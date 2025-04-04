<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\SearchHistory;
use App\Models\SearchLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    // Display all researches
       public function index(Request $request)
    {
        // Fetch all researches
        $researches = Research::orderBy('date', 'desc')->get();
    
        // Fetch search history
        $searchHistories = SearchHistory::orderBy('count', 'desc')->get();
    
        // Pass data to the view
        return view('research.index', compact('researches', 'searchHistories'));
    }

    public function create()
    {
        $departments = [
            'Comptech',
            'Electronics',
            'Education',
            'BSEd-English',
            'BSEd-Filipino',
            'BSEd-Mathematics',
            'BSEd-Social Studies',
            'Tourism',
            'Hospitality Management'
        ];

        return view('research.create', compact('departments'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'date' => 'required|date',
            'research_title' => 'required|string|max:500',
            'author' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'subject_area' => 'required|string|max:255',
            'research_file' => 'required|file|mimes:pdf|max:2048',
            'reference_id' => 'nullable|string|max:255',
            'keywords' => ['required', 'string', function ($attribute, $value, $fail) {
                $keywords = explode(',', $value);
                if (count($keywords) > 5) {
                    $fail('You can only provide up to 5 keywords.');
                }
            }],
        ]);
    
        // Handle file upload
        $filePath = $request->file('research_file')->store('research_files', 'public');
    
        // Create a new research entry
        Research::create([
            'date' => $request->date,
            'research_title' => $request->research_title,
            'author' => $request->author,
            'location' => $request->location,
            'subject_area' => $request->subject_area,
            'file_path' => $filePath,
            'reference_id' => $request->reference_id,
            'keywords' => implode(',', array_map('trim', explode(',', $request->keywords))), // Trim and store keywords
        ]);
    
        $keywords = array_map('trim', explode(',', $request->keywords));
        foreach ($keywords as $keyword) {
            DB::table('search_logs')->updateOrInsert(
                ['keyword' => $keyword],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // Redirect to the research index page with a success message
        return redirect()->route('research.index')->with('success', 'Research added successfully.');
    }

    public function edit(Research $research)
    {
        $departments = [
            'Comptech', 'Electronics', 'Education', 'BSEd-English',
            'BSEd-Filipino', 'BSEd-Mathematics', 'BSEd-Social Studies',
            'Tourism', 'Hospitality Management'
        ];

        return view('research.edit', compact('research', 'departments'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'reference_id' => 'nullable|string|max:255',
            'date' => 'required|date',
            'research_title' => 'required|string|max:500',
            'author' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'subject_area' => 'required|string|max:255',
            'research_file' => 'nullable|file|mimes:pdf|max:2048',
            'keywords' => ['required', 'string', function ($attribute, $value, $fail) {
                $keywords = explode(',', $value);
                if (count($keywords) > 5) {
                    $fail('You can only provide up to 5 keywords.');
                }
            }],
        ]);
    
        // Find the research entry
        $research = Research::findOrFail($id);
    
        // Handle file upload if a new file is provided
        if ($request->hasFile('research_file')) {
            $filePath = $request->file('research_file')->store('research_files', 'public');
            $research->file_path = $filePath;
        }
    
        // Update the research entry
        $research->update([
            'reference_id' => $request->reference_id,
            'date' => $request->date,
            'research_title' => $request->research_title,
            'author' => $request->author,
            'location' => $request->location,
            'subject_area' => $request->subject_area,
            'keywords' => implode(',', array_map('trim', explode(',', $request->keywords))), // Trim and store keywords
        ]);
    
        // Redirect back with a success message
        return redirect()->route('research.index')->with('success', 'Research updated successfully.');
    }
    public function destroy(Research $research)
    {
        // Soft delete
        $research->delete();
    
        return redirect()->route('research.index')->with('success', 'Research deleted successfully.');
    }
    
    public function recentlyDeleted()
    {
        // Fetch all soft-deleted records
        $researches = Research::onlyTrashed()->get();
        return view('research.recentlyDeleted', compact('researches'));
    }
    
    public function restore($id)
    {
        // Restore soft-deleted entry
        $research = Research::withTrashed()->findOrFail($id);
        $research->restore();
    
        return redirect()->route('research.recentlyDeleted')->with('success', 'Research restored successfully.');
    }
    
    public function permanentDelete($id)
    {
        // Permanently delete soft-deleted entry
        $research = Research::withTrashed()->findOrFail($id);
        if ($research->file_path && Storage::exists($research->file_path)) {
            Storage::delete($research->file_path);
        }
        $research->forceDelete();
    
        return redirect()->route('research.recentlyDeleted')->with('success', 'Research permanently deleted.');
    }

        public function search(Request $request)
        {
            $keyword = $request->input('keyword');
            $filter = $request->input('filter');
            $department = $request->input('department'); // Get department parameter
        
            // Track search history
            if (!empty($keyword)) {
                SearchHistory::updateOrCreate(
                    ['keyword' => $keyword],
                    ['count' => \DB::raw('count + 1')] // Increment the count
                );
            }
        
            $query = Research::query();
        
            // Filter by keyword
            if (!empty($keyword)) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('research_title', 'LIKE', "%$keyword%")
                        ->orWhere('author', 'LIKE', "%$keyword%")
                        ->orWhere('location', 'LIKE', "%$keyword%")
                        ->orWhere('subject_area', 'LIKE', "%$keyword%")
                        ->orWhere('date', 'LIKE', "%$keyword%")
                        ->orWhere('reference_id', 'LIKE', "%$keyword%")
                        ->orWhere('keywords', 'LIKE', "%$keyword%"); // Include keywords in search
                });
            }
        
            // Filter by year range
            if (!empty($filter)) {
                $years = explode('-', $filter);
                if (count($years) === 2) {
                    $query->whereBetween('date', ["{$years[0]}-01-01", "{$years[1]}-12-31"]);
                }
            }
        
            // Filter by department
            if (!empty($department)) {
                $query->where('subject_area', $department);
            }
        
            $researches = $query->get();
        
            // Fetch search history
            $searchHistories = SearchHistory::orderBy('count', 'desc')->get();
        
            // Return the view with the required variables
            return view('research.index', [
                'researches' => $researches,
                'searchHistories' => $searchHistories,
                'keyword' => $keyword,
                'filter' => $filter,
                'department' => $department,
            ]);
        }
    public function viewFile($id)
    {
        $research = Research::findOrFail($id);

        if (!$research->file_path || !Storage::exists($research->file_path)) {
            return abort(404, 'File not found');
        }

        return response()->file(storage_path("app/public/{$research->file_path}"));
    }

    // Display researches by department
    public function department($department)
    {
        $researches = Research::where('subject_area', $department)->get();
        return view('research.department', compact('researches', 'department'));
    }
}
