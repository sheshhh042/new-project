<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    // Store feedback from users
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    // Display feedback for admin
           public function index()
    {
        // Retrieve feedback data with pagination
        $feedbacks = Feedback::with('user')->latest()->paginate(10);
    
        return view('feedback.admin', compact('feedbacks'));
    }
        public function userFeedback()
    {
        return view('feedback.user');
    }
        public function markAsRead(Feedback $feedback)
    {
        // Update the feedback's `is_read` status to true
        $feedback->update(['is_read' => true]);
    
        return redirect()->route('feedback.admin')->with('success', 'Feedback marked as read.');
    }
}