<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Inertia\Inertia;

class SystemSettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings', [
            'watermark' => SystemSetting::get('watermark_path') ? Storage::disk('public')->url(SystemSetting::get('watermark_path')) : null,
        ]);
    }

    public function updateWatermark(Request $request)
    {
        $request->validate([
            'watermark' => 'required|image|mimes:png|max:2048', // Must be PNG
        ]);

        if ($request->hasFile('watermark')) {
            $path = $request->file('watermark')->store('settings', 'public');
            
            // Delete old watermark if exists
            $oldPath = SystemSetting::get('watermark_path');
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            SystemSetting::updateOrCreate(
                ['key' => 'watermark_path'],
                ['value' => $path]
            );
        }

        return redirect()->back()->with('message', 'Watermark updated successfully.');
    }
}
