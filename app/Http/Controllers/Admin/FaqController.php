<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort')->get();
        return view('admin.faq.index', compact('faqs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required',
            'answer' => 'nullable'
        ]);

        $data['sort'] = Faq::max('sort') + 1;

        Faq::create($data);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question' => 'required',
            'answer' => 'nullable'
        ]);

        $faq->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(['success' => true]);
    }

    public function show(Faq $faq)
    {
        return response()->json($faq);
    }
}