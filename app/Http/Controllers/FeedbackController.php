<?php

namespace App\Http\Controllers;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbackCounts = Feedback::select('feedback_type')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('feedback_type')
            ->pluck('total', 'feedback_type');
            
        return view('index', compact('feedbackCounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'required|string',
            'email' => 'required|email|unique:feedback,email',
            'feedback_type' => 'required|string',
            'message' => 'required',
            'feedback_position' => 'required|string',
        ]);

        Feedback::create($validated);

        return redirect('/')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Таны саналыг амжилттай илгээлээ!'
            ]);
    }
}
