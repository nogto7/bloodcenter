<?php

namespace App\Http\Controllers;
use App\Models\Feedback;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        // Админаас тохируулсан сонголтуудтай тулгаж шалгана (form-той ижил эх сурвалж)
        $types = SiteSetting::list('feedback_types', ['Санал', 'Хүсэлт', 'Талархал']);
        $positions = SiteSetting::list('feedback_positions');

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'required|string',
            'email' => 'required|email',
            'feedback_type' => ['required', 'string', Rule::in($types)],
            'message' => 'required',
            'feedback_position' => count($positions)
                ? ['required', 'string', Rule::in($positions)]
                : ['nullable', 'string'],
        ]);

        Feedback::create($validated);

        return redirect('/')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Таны саналыг амжилттай илгээлээ!'
            ]);
    }
}
