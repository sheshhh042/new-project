@extends('layouts.app')

@section('content')
    <div class="container-fluid py-5 px-4">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <!-- Header Section -->
                <div class="text-center mb-4">
                    <h1 class="fw-bold text-primary mb-2" style="font-size: 2rem;">Share Your Feedback</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">We appreciate your thoughts, {{ Auth::user()->name }}!</p>
                    <div class="d-flex justify-content-center">
                        <div class="border-top border-primary w-25 my-3"></div>
                    </div>
                </div>

                <!-- Feedback Form Card - Wide Layout -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-primary py-3">
                        <h2 class="text-white text-center m-0" style="font-size: 1.5rem;">
                            <i class="bi bi-chat-square-text me-2"></i>Tell Us What You Think
                        </h2>
                    </div>
                    <div class="card-body p-4">
              
                        <form action="{{ route('feedback.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Star Rating Column -->
                                <div class="col-lg-5">
                                    <div class="h-100">
                                        <div class="mb-3 text-center"> <!-- Changed to text-center -->
                                            <label class="form-label mb-2" style="font-size: 1.2rem;">How would you rate your experience?</label>
                                            <div class="star-rating d-flex justify-content-center"> <!-- Changed to justify-content-center -->
                                                <input type="radio" id="star5" name="rating" value="5" required>
                                                <label for="star5" title="Excellent">&#9733;</label>
                                                <input type="radio" id="star4" name="rating" value="4">
                                                <label for="star4" title="Very Good">&#9733;</label>
                                                <input type="radio" id="star3" name="rating" value="3">
                                                <label for="star3" title="Good">&#9733;</label>
                                                <input type="radio" id="star2" name="rating" value="2">
                                                <label for="star2" title="Fair">&#9733;</label>
                                                <input type="radio" id="star1" name="rating" value="1">
                                                <label for="star1" title="Poor">&#9733;</label>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2 mx-auto" style="width: 80%;"> <!-- Added mx-auto and width -->
                                                <span class="text-muted small">Poor</span>
                                                <span class="text-muted small">Excellent</span>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-2">
                                            <div class="p-3 bg-light rounded-3">
                                                <h4 class="text-primary" style="font-size: 1.1rem;"><i class="bi bi-info-circle-fill me-2"></i>Your Feedback Matters</h4>
                                                <p class="mb-0 small">We read every submission and use your input to improve our service.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Feedback Text Column -->
                                <div class="col-lg-7">
                                    <div class="h-100 ps-lg-4 border-start-lg">
                                        <div class="mb-3">
                                            <label for="feedback" class="form-label" style="font-size: 1.2rem;">Your Feedback</label>
                                            <textarea name="feedback" id="feedback" class="form-control" 
                                                rows="6" placeholder="What did you like? What can we improve? Please share your detailed thoughts..." required
                                                style="border-radius: 8px; min-height: 180px;"></textarea>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-3 pt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="allowContact" name="allow_contact">
                                                <label class="form-check-label small" for="allowContact">
                                                    Allow us to contact you about this feedback
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                                <i class="bi bi-send-fill me-2"></i> Submit Feedback
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="text-center mt-4 pt-2">
                    <p class="text-muted small">
                        <i class="bi bi-lock-fill me-1"></i>Your feedback is confidential and will be used to improve our services.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Star Rating CSS -->
    <style>
        .star-rating {
            direction: rtl;
            unicode-bidi: bidi-override;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            font-size: 2.5rem;
            color: #e0e0e0;
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 0 4px;
        }
        .star-rating input[type="radio"]:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
            transform: scale(1.1);
        }
        .star-rating input[type="radio"]:checked + label {
            animation: bounce 0.5s;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .border-start-lg {
            border-left: 1px solid #dee2e6;
        }
        @media (max-width: 992px) {
            .border-start-lg {
                border-left: none;
                border-top: 1px solid #dee2e6;
                padding-top: 1.5rem;
                margin-top: 1.5rem;
            }
        }
    </style>
@endsection