<!-- resources/views/departments/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Subject Areas</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
            <i class="fas fa-plus"></i> Add New
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            @foreach($departments as $department)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>{{ $department->name }}</span>
                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>

@include('partials.add_department_modal')
@endsection