<?php
namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::active()->orderBy('name')->get();
        return view('your.view', compact('departments'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
        ]);
    
        Department::create($validated);
    
        return back()->with('success', 'Department created successfully!');
    }
    public function create()
{
    $departments = Department::all(); // or Department::active()->orderBy('name')->get();
    return view('research.create', compact('departments'));
}

}