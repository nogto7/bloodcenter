<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

class AdminFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbackList = DB::table('feedback as f')->select('f.*')->paginate(15);
        return view('admin.feedback.index', compact('feedbackList'));
    }

    public function destroy($id)
    {
        Feedback::findOrFail($id)->delete();
        return redirect('/admin/feedback')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Ирүүлсэн саналыг амжилттай устгалаа!'
            ]);
    }

    
}
