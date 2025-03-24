<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    // Display all researches
    public function index(Request $request)
{
    $researches = Research::orderBy('date', 'desc')->get(); // Change 'desc' to 'asc' for ascending order
    return view('research.index', compact('researches'));
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
        'research_title' => 'required|string|max:255',
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

    $research = new Research();
    $research->date = $request->date;
    $research->research_title = $request->research_title;
    $research->author = $request->author;
    $research->location = $request->location;
    $research->subject_area = $request->subject_area;
    $research->file_path = $filePath;
    $research->reference_id = $request->reference_id;
    $research->keywords = implode(',', array_map('trim', explode(',', $request->keywords))); // Save keywords
    $research->save();

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
            'research_title' => 'required|string|max:255',
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
    
        $data = $request->only(['date', 'location', 'subject_area', 'reference_id']);
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
    
        if (!empty($keyword)) {
            SearchLog::create(['keyword' => $keyword]);
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
                    ->orWhere('reference_id', 'LIKE', "%$keyword%"); // Include reference_id in search
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
            $query->where('department', $department);
        }
    
        $researches = $query->get();
    
        if ($request->ajax()) {
            return view('research.partials.research_list', compact('researches'))->render();
        }
    
        return view('research.index', compact('researches', 'department')); // Pass department to the view
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
