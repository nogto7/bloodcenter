<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    /** Нүүр хуудасны тохиргооны түлхүүрүүд */
    public const CONTACT_KEYS = [
        'contact_address',
        'contact_phone',
        'contact_email',
        'contact_work_hours',
        'feedback_types',
        'feedback_positions',
    ];

    public function edit()
    {
        $settings = SiteSetting::whereIn('key', self::CONTACT_KEYS)
            ->pluck('value', 'key');

        return view('admin.settings.contact', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'contact_address' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:200',
            'contact_email' => 'nullable|email|max:200',
            'contact_work_hours' => 'nullable|string|max:1000',
            'feedback_types' => 'nullable|string|max:2000',
            'feedback_positions' => 'nullable|string|max:2000',
        ]);

        foreach (self::CONTACT_KEYS as $key) {
            SiteSetting::set($key, $data[$key] ?? null);
        }

        return back()->with('success', 'Тохиргоо хадгалагдлаа');
    }
}
