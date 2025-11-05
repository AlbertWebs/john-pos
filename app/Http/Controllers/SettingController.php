<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        return view('settings.index', compact('settings'));
    }

    /**
     * Get a single setting value
     */
    private function getSetting($key, $default = null)
    {
        $setting = DB::table('settings')->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'nullable|email|max:255',
            'admin_email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'paybill_number' => 'nullable|string|max:20',
            'till_number' => 'nullable|string|max:20',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                $oldLogo = $this->getSetting('logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }

                // Store new logo
                $logoPath = $request->file('logo')->store('settings', 'public');
                $this->setSetting('logo', $logoPath);
            }

            // Update other settings
            foreach ($validated as $key => $value) {
                if ($key !== 'logo' && $value !== null && $value !== '') {
                    DB::table('settings')->updateOrInsert(
                        ['key' => $key],
                        [
                            'value' => $value,
                            'type' => 'string',
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            DB::commit();

            return redirect()->route('settings.index')
                ->with('success', 'Settings updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Get all settings as key-value pairs
     */
    private function getSettings()
    {
        return DB::table('settings')->pluck('value', 'key')->toArray();
    }

    /**
     * Set or update a setting
     */
    private function setSetting($key, $value, $type = 'string')
    {
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'updated_at' => now(),
            ]
        );
    }
}
