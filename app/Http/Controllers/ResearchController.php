<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\Searchlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    // Display all researches
    public function index()
    {
        $researches = Research::all();
        return view('research.index', compact('researches'));
    }

    // Show the form for creating a new research
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

    // Store a newly created research in the database
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'research_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'subject_area' => 'required|string|max:255',
            'research_file' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Trim and lowercase the research title to prevent case-sensitive duplicates
        $cleanTitle = trim(strtolower($request->research_title));

        // Check if a research title already exists
        $existingResearch = Research::whereRaw('LOWER(TRIM(research_title)) = ?', [$cleanTitle])->first();

        if ($existingResearch) {
            return redirect()->back()->withInput()->with('error', 'This research title already exists.');
        }

        // Store file in 'public/research_files' folder
        $filePath = $request->file('research_file')->store('public/research_files');

        // Save research data
        Research::create([
            'date' => $request->date,
            'research_title' => $request->research_title, // Store original format
            'author' => $request->author,
            'location' => $request->location,
            'subject_area' => $request->subject_area,
            'file_path' => str_replace('public/', 'storage/', $filePath),
        ]);

        return redirect()->route('research')->with('success', 'Research Title added successfully.');
    }

    // Show the form for editing the specified research
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

    // Update the specified research in the database
    public function update(Request $request, Research $research)
    {
        $request->validate([
            'date' => 'required|date',
            'research_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'subject_area' => 'required|string|max:255',
            'research_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Trim and lowercase the research title to prevent duplicates
        $cleanTitle = trim(strtolower($request->research_title));

        // Check if a different research entry already has the same title
        $existingResearch = Research::whereRaw('LOWER(TRIM(research_title)) = ?', [$cleanTitle])
            ->where('id', '!=', $research->id)
            ->first();

        if ($existingResearch) {
            return redirect()->back()->withInput()->with('error', 'This research title already exists.');
        }

        $data = $request->only(['date', 'research_title', 'author', 'location', 'subject_area']);

        // Check if a new file is uploaded
        if ($request->hasFile('research_file')) {
            // Delete old file if exists
            if ($research->file_path && Storage::exists('public/' . $research->file_path)) {
                Storage::delete('public/' . $research->file_path);
            }

            // Upload new file
            $filePath = $request->file('research_file')->store('public/research_files');
            $data['file_path'] = str_replace('public/', 'storage/', $filePath);
        }

        // Update the research entry
        $research->update($data);

        return redirect()->route('dashboard')->with('success', 'Research updated successfully.');
    }

    // Delete the specified research from the database
    public function destroy(Research $research)
    {
        // Delete the associated file if it exists
        if ($research->file_path && Storage::exists('public/' . $research->file_path)) {
            Storage::delete('public/' . $research->file_path);
        }

        $research->delete();

        return redirect()->route('dashboard')->with('success', 'Research deleted successfully.');
    }

    // Display the specified research
    public function view($id)
    {
        $research = Research::findOrFail($id);
        return view('research.view', compact('research'));
    }

    // Display all researches for users
    public function userView()
    {
        $researches = Research::all();
        return view('research.user_view', compact('researches'));
    }

    // Display researches by department
    public function department($department)
    {
        $researches = Research::where('subject_area', $department)->get();
        return view('research.department', compact('researches', 'department'));
    }

    // Search for researches
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $filter = $request->input('filter');

        // Record the search keyword
        SearchLog::create(['keyword' => $keyword]);

        $query = Research::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('research_title', 'LIKE', "%$keyword%")
                    ->orWhere('author', 'LIKE', "%$keyword%")
                    ->orWhere('location', 'LIKE', "%$keyword%")
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

    // View the research file
    public function viewFile($id)
    {
        $research = Research::findOrFail($id);

        // Check if file exists
        if (!$research->file_path || !Storage::exists("public/{$research->file_path}")) {
            return abort(404, 'File not found');
        }

        return response()->file(storage_path("app/public/{$research->file_path}"));
    }

    // Display the specified research
    public function show($id)
    {
        $research = Research::findOrFail($id);
        return view('research.show', compact('research'));
    }
}