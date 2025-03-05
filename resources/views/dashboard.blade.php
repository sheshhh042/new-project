@extends('layouts.app')

@section('title','Home Products')

@section('content')
<div class="d-flex align-items-center justify-content-between">
  
    <a href="{{ route('research.create') }}" class="btn btn-primary">Add Researchs</a>
</div>
<hr/>
@if(session()->has('success'))
<div class="alert alert-success" role="alert">
    {{ session()->get('success') }}
</div>
@endif
<table class="table table-hover">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Research Title</th>
            <th>Author</th>
            <th>Location</th>
            <th>Subject Area</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if ($researches->count() > 0)
        @foreach($researches as $research)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $research->date }}</td>
            <td>{{ $research->Research_Title }}</td>
            <td>{{ $research->Author }}</td>
            <td>{{ $research->Location }}</td>
            <td>{{ $research->subject_area }}</td>
            <td>
                <a href="{{ route('research.edit', $research->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('research.destroy', $research->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="7" class="text-center">No Research found</td>
        </tr>
        @endif
    </tbody>
</table>
@endsection

@section('scripts')
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this research?');
    }
</script>
@endsection