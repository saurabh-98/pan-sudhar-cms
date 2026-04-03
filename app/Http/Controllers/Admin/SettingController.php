<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /* =========================
       LOGO FORM
    ========================= */
    public function logoForm()
    {
        return view('admin.logo.index', [
            'logo'       => $this->getSetting('logo'),
            'type'       => $this->getSetting('logo_type', 'image'),
            'logo_text'  => $this->getSetting('logo_text'),
            'color'      => $this->getSetting('color', '#ff5722'),
            'font_size'  => $this->getSetting('font_size', 28),
            'updated_at' => Setting::where('key', 'logo')->first()?->updated_at
        ]);
    }

    /* =========================
       SAVE LOGO (IMAGE + TEXT)
    ========================= */
    public function saveLogo(Request $request)
    {
        $type = $request->type ?? 'image';

        /* ===== IMAGE LOGO ===== */
        if ($type === 'image') {

            $request->validate([
                'logo' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048'
            ]);

            $oldLogo = $this->getSetting('logo');

            if ($request->hasFile('logo')) {

                // delete old
                if ($oldLogo && File::exists(public_path($oldLogo))) {
                    File::delete(public_path($oldLogo));
                }

                // upload
                $file = $request->file('logo');
                $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/logo'), $filename);

                $path = 'uploads/logo/' . $filename;

                // save settings
                $this->setSetting('logo_type', 'image');
                $this->setSetting('logo', $path);
            }

        }

        /* ===== TEXT LOGO ===== */
        else {

            $request->validate([
                'logo_text' => 'required|string|max:100'
            ]);

            $this->setSetting('logo_type', 'text');
            $this->setSetting('logo_text', $request->logo_text);
            $this->setSetting('color', $request->color ?? '#ff5722');
            $this->setSetting('font_size', $request->font_size ?? 28);
        }

        // clear cache
        cache()->forget('settings');

        return redirect()->route('admin.logo.form')
            ->with('success', 'Logo updated successfully');
    }

    /* =========================
       DELETE LOGO
    ========================= */
    public function deleteLogo()
    {
        $logo = $this->getSetting('logo');

        if ($logo && File::exists(public_path($logo))) {
            File::delete(public_path($logo));
        }

        // remove all logo related settings
        Setting::whereIn('key', [
            'logo',
            'logo_type',
            'logo_text',
            'color',
            'font_size'
        ])->delete();

        cache()->forget('settings');

        return back()->with('success', 'Logo deleted successfully');
    }

    /* =========================
       HELPER FUNCTIONS
    ========================= */

    private function getSetting($key, $default = null)
    {
        return Setting::where('key', $key)->value('value') ?? $default;
    }

    private function setSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}