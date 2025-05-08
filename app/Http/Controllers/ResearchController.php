<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\SearchHistory;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ResearchController extends Controller
{
    // Display all researches
    public function index(Request $request)
    {
        $researches = Research::query()
        ->latest()
        ->paginate(10);
            
        // Fetch all researches
        $researches = Research::orderBy('research_title', 'asc')
            ->orderBy('date', 'desc') // Secondary sort by date
            ->get();

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
        $request->validate([
            'date' => 'required|date',
            'research_title' => 'required|string|max:500|unique:research,research_title', // Ensure title is unique
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
    
        $filePath = $request->file('research_file')->store('research_files', 'public');
    
        Research::create([
            'date' => $request->date,
            'research_title' => $request->research_title,
            'author' => $request->author,
            'location' => $request->location,
            'subject_area' => $request->subject_area,
            'file_path' => $filePath,
            'reference_id' => $request->reference_id,
            'keywords' => implode(',', array_map('trim', explode(',', $request->keywords))),
        ]);
    
        return redirect()->route('research.index')->with('success', 'Research added successfully.');
    }

    public function edit(Research $research)
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

        return view('research.edit', compact('research', 'departments'));
    }
    public function update(Request $request, Research $research)
    {
        $request->validate([
            'date' => 'required|date',
            'research_title' => 'required|string|max:500|unique:research,research_title,' . $research->id, // Exclude current record
            'author' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'subject_area' => 'required|string|max:255',
            'research_file' => 'nullable|file|mimes:pdf|max:2048',
            'reference_id' => 'nullable|string|max:255',
            'keywords' => ['required', 'string', function ($attribute, $value, $fail) {
                $keywords = explode(',', $value);
                if (count($keywords) > 5) {
                    $fail('You can only provide up to 5 keywords.');
                }
            }],
        ]);
    
        $data = $request->only([
            'date', 'research_title', 'author', 'location', 'subject_area', 'reference_id'
        ]);
        $data['keywords'] = implode(',', array_map('trim', explode(',', $request->keywords)));
    
        if ($request->hasFile('research_file')) {
            if ($research->file_path && Storage::exists($research->file_path)) {
                Storage::delete($research->file_path);
            }
            $data['file_path'] = $request->file('research_file')->store('research_files', 'public');
        }
    
        $research->update($data);
    
        return redirect()->route('research.index')->with('success', 'Research updated successfully.');
    }

    public function destroy(Research $research)
    {
        // Optional: Delete associated file if exists
        if ($research->file_path && Storage::exists($research->file_path)) {
            Storage::delete($research->file_path);
        }
        
        $research->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Research deleted successfully'
        ]);
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
    // Validate the search request
    $this->validateSearchRequest($request);

    $keyword = $request->input('keyword');
    $filter = $request->input('filter');
    $department = $request->input('department');

    // Track search history (only for general searches)
    if (!empty($keyword) && empty($department)) {
        SearchHistory::updateOrCreate(
            ['keyword' => $keyword],
            ['count' => \DB::raw('count + 1')]
        );
    }

    $request->validate([
        'keyword' => 'nullable|string|max:255',
        'department' => 'nullable|string|max:255'
    ]);

    $query = Research::query();

    // FIRST apply department filter if exists
    if ($request->has('department')) {
        $query->where('subject_area', $request->department);
    }

    // Keyword search within the current department
    if (!empty($keyword)) {
        $query->where(function ($q) use ($keyword) {
            $q->where('research_title', 'LIKE', "%$keyword%")
                ->orWhere('author', 'LIKE', "%$keyword%")
                ->orWhere('location', 'LIKE', "%$keyword%")
                ->orWhere('subject_area', 'LIKE', "%$keyword%")
                ->orWhere('date', 'LIKE', "%$keyword%")
                ->orWhere('reference_id', 'LIKE', "%$keyword%")
                ->orWhere('keywords', 'LIKE', "%$keyword%");
        });
    }

    // Year range filter
    if (!empty($filter)) {
        $years = explode('-', $filter);
        if (count($years) === 2) {
            $query->whereBetween('date', ["{$years[0]}-01-01", "{$years[1]}-12-31"]);
        }
    }

    $researches = $query->orderBy('date', 'desc')->get();
    $searchHistories = SearchHistory::orderBy('count', 'desc')->get();

    return view('research.index', compact('researches', 'searchHistories', 'keyword', 'filter', 'department'));
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
    $researches = Research::where('subject_area', $department)
                         ->orderBy('date', 'desc')
                         ->get();
    
    // Pass the department to view to maintain context
    return view('research.department', [
        'researches' => $researches,
        'department' => $department,
        'searchHistories' => SearchHistory::orderBy('count', 'desc')->get()
    ]);
}

    private function validateSearchRequest(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable|string|max:255',
            'filter' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);
    }
}
