<?php

namespace App\Http\Controllers;

use App\Models\Research;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    // Display all researches
    public function index()
    {
        $researches = Research::all(); // Fetch all research entries without pagination
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
    ]);

    $cleanTitle = trim(strtolower($request->research_title));
    $existingResearch = Research::whereRaw('LOWER(TRIM(research_title)) = ?', [$cleanTitle])->first();

    if ($existingResearch) {
        return redirect()->back()->withInput()->with('error', 'This research title already exists.');
    }

    $filePath = $request->file('research_file')->store('public/research_files');
    Research::create([
        'date' => $request->date,
        'research_title' => $request->research_title,
        'author' => $request->author,
        'location' => $request->location,
        'subject_area' => $request->subject_area,
        'file_path' => str_replace('public/', 'storage/', $filePath),
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
            'research_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'subject_area' => 'required|string|max:255',
            'research_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $cleanTitle = trim(strtolower($request->research_title));
        $existingResearch = Research::whereRaw('LOWER(TRIM(research_title)) = ?', [$cleanTitle])
            ->where('id', '!=', $research->id)
            ->first();

        if ($existingResearch) {
            return redirect()->back()->withInput()->with('error', 'This research title already exists.');
        }

        $data = $request->only(['date', 'research_title', 'author', 'location', 'subject_area']);
        if ($request->hasFile('research_file')) {
            if ($research->file_path && Storage::exists('public/' . $research->file_path)) {
                Storage::delete('public/' . $research->file_path);
            }

            $filePath = $request->file('research_file')->store('public/research_files');
            $data['file_path'] = str_replace('public/', 'storage/', $filePath);
        }

        $research->update($data);

        return redirect()->route('research.index')->with('success', 'Research updated successfully.');
    }

    public function destroy(Research $research)
    {
        if ($research->file_path && Storage::exists('public/' . $research->file_path)) {
            Storage::delete('public/' . $research->file_path);
        }

        $research->delete();

        return redirect()->route('research.index')->with('success', 'Research deleted successfully.');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $filter = $request->input('filter');

        if (!empty($keyword)) {
            SearchLog::create(['keyword' => $keyword]);
        }

        $query = Research::query();
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('research_title', 'LIKE', "%$keyword%")
                    ->orWhere('author', 'LIKE', "%$keyword%")
                    ->orWhere('location', 'LIKE', "%$keyword%")
                    ->orWhere('subject_area', 'LIKE', "%$keyword%")
                    ->orWhere('date', 'LIKE', "%$keyword%");
            });
        }

        if (!empty($filter)) {
            $years = explode('-', $filter);
            if (count($years) === 2) {
                $query->whereBetween('date', ["{$years[0]}-01-01", "{$years[1]}-12-31"]);
            }
        }

        $researches = $query->get();

        if ($request->ajax()) {
            return view('research.partials.research_list', compact('researches'))->render();
        }

        return view('research.index', compact('researches'));
    }

    public function viewFile($id)
    {
        $research = Research::findOrFail($id);

        if (!$research->file_path || !Storage::exists("public/{$research->file_path}")) {
            return abort(404, 'File not found');
        }
        dd($research);

        return response()->file(storage_path("app/public/{$research->file_path}"));
    }

    // Display researches by department
    public function department($department)
    {
        $researches = Research::where('subject_area', $department)->get();
        return view('research.department', compact('researches', 'department'));
    }
}
