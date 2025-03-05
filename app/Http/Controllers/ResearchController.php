<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\Searchlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ResearchController extends Controller
{
    public function index()
    {
        $researches = Research::all();
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
        'research_file' => 'required|file|mimes:pdf|max:2048', // Validate PDF file
    ]);

    // Store file in 'public/research_files' folder
    $filePath = $request->file('research_file')->store('public/research_files');

    // Save research data
    Research::create([
        'date' => $request->date,
        'research_title' => $request->research_title,
        'author' => $request->author,
        'location' => $request->location,
        'subject_area' => $request->subject_area,
        'file_path' => str_replace('public/', 'storage/', $filePath),
    ]);

    return redirect()->route('research')->with('success', 'Research Title added successfully');
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
        // Validate the request, including the optional file
        $request->validate([
            'date' => 'required|date',
            'research_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'subject_area' => 'required|string|max:255',
            'file_path' => 'nullable|mimes:pdf|max:20480' // Allow updating file, but it's optional
        ]);

        // Prepare data for updating
        $data = $request->only(['date', 'research_title', 'author', 'location', 'subject_area']);

        // Check if a new file is uploaded
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($research->file_path && Storage::exists('public/' . $research->file_path)) {
                Storage::delete('public/' . $research->file_path);
            }

            // Upload new file
            $filePath = $request->file('file')->store('research_files', 'public');
            $data['file_path'] = $filePath;
        }

        // Update the research entry
        $research->update($data);

        return redirect()->route('dashboard')->with('success', 'Research updated successfully.');
    }

    public function destroy(Research $research)
    {
        $research->delete();

        return redirect()->route('dashboard')->with('success', 'Research deleted successfully.');
    }

    public function view($id)
    {
        $research = Research::findOrFail($id);
        return view('research.view', compact('research'));
    }

    public function userView()
    {
        $researches = Research::all();
        return view('research.user_view', compact('researches'));
    }

    public function department($department)
    {
        // Fetch research records filtered by department (subject area)
        $researches = Research::where('subject_area', $department)->get();

        // Return the filtered research data to the department view
        return view('research.department', compact('researches', 'department'));
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $filter = $request->input('filter');

        // Record the searches keyword
        SearchLog::create(['keyword' => $keyword]);

        $query = Research::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('Research_Title', 'LIKE', "%$keyword%")
                    ->orWhere('Author', 'LIKE', "%$keyword%")
                    ->orWhere('Location', 'LIKE', "%$keyword%")
                    ->orWhere('subject_area', 'LIKE', "%$keyword%")
                    ->orWhere('date', 'LIKE', "%$keyword%");
            });
        }

        if ($filter) {
            $query->where('subject_area', $filter);
        }

        $researches = $query->get();

        return view('research.index', compact('researches'));
    }

    public function viewFile($id)
    {
        $research = Research::findOrFail($id);

        // Check if file exists
        if (!$research->file_path || !Storage::exists("public/{$research->file_path}")) {
            return abort(404, 'File not found');
        }

        // Serve the file so it opens in the browser
        return response()->file(storage_path("app/public/{$research->file_path}"));
    }

    public function show($id)
    {
        $research = Research::findOrFail($id);
    
        // Convert file path from "public/" to "storage/"
        $research->file_path = str_replace('public/', 'storage/', $research->file_path);
    
        return view('research.show', compact('research'));
    }
}