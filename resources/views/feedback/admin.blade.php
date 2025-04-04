@extends('layouts.app')

@section('title', '')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="bi bi-chat-square-text me-2"></i>User Feedback</h3>
                        <p class="mb-0 text-white-50">Below is the list of feedback submitted by users</p>
                    </div>
                </div>
                <div class="card-body">
                    @if($feedbacks->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-text display-4 text-muted"></i>
                            <h4 class="mt-3 text-muted">No feedback available</h4>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Rating</th>
                                        <th>Feedback</th>
                                        <th>Date Submitted</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feedbacks as $feedback)
                                    <tr class="{{ $feedback->is_read ? '' : 'table-warning' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-primary text-white rounded-circle me-3">
                                                    {{ substr($feedback->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $feedback->user->name }}</h6>
                                                    <small class="text-muted">{{ $feedback->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="star-rating-static">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $feedback->rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <div class="feedback-preview">
                                                {{ Str::limit($feedback->feedback, 50) }}
                                                @if(strlen($feedback->message) > 50)
                                                <a href="#" class="text-primary read-more" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $feedback->id }}">Read more</a>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $feedback->is_read ? 'success' : 'warning' }}">
                                                {{ $feedback->is_read ? 'Read' : 'Unread' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="#" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $feedback->id }}">
                                                    <i class="bi bi-eye"></i>View
                                                </a>
                                                <form action="{{ route('admin.feedback.markAsRead', $feedback->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success me-2">
                                                        <i class="bi bi-check2"></i>Mark as Read
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.feedback.destroy', $feedback->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Feedback Detail Modal -->
                                    <div class="modal fade" id="feedbackModal{{ $feedback->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-primary text-white">
                                                    <h5 class="modal-title">Feedback Details</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <h6>User Information</h6>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar bg-primary text-white rounded-circle me-3" style="width: 50px; height: 50px; line-height: 50px; font-size: 1.5rem;">
                                                                        {{ substr($feedback->user->name, 0, 1) }}
                                                                    </div>
                                                                    <div>
                                                                        <p class="mb-0 fw-bold">{{ $feedback->user->name }}</p>
                                                                        <p class="mb-0 text-muted">{{ $feedback->user->email }}</p>
                                                                        <p class="mb-0 small text-muted">Submitted: {{ $feedback->created_at->format('M d, Y h:i A') }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <h6>Rating</h6>
                                                                <div class="star-rating-static fs-3">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        @if($i <= $feedback->rating)
                                                                            ★
                                                                        @else
                                                                            ☆
                                                                        @endif
                                                                    @endfor
                                                                    <span class="ms-2">({{ $feedback->rating }}/5)</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <h6>Feedback Message</h6>
                                                        <div class="p-3 bg-light rounded">
                                                            {{ $feedback->message }}
                                                        </div>
                                                    </div>
                                                    @if($feedback->allow_contact)
                                                    <div class="alert alert-info">
                                                        <i class="bi bi-info-circle me-2"></i>User has allowed follow-up contact.
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <form action="{{ route('admin.feedback.markAsRead', $feedback->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary">
                                                            Mark as Read
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $feedbacks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
    }
    .star-rating-static {
        color: #ffc107;
        letter-spacing: 2px;
    }
    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        font-weight: bold;
    }
    .feedback-preview {
        position: relative;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .modal-content {
        border-radius: 0.5rem;
    }
</style>
@endsection