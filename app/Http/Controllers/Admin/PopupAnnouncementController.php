<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupAnnouncement;
use Illuminate\Http\Request;

class PopupAnnouncementController extends Controller
{
    /**
     * Display listing.
     */
    public function index()
    {
        return view('admin.popup-announcements.index');
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.popup-announcements.create');
    }

    /**
     * Store popup.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'required|string|max:255|unique:popup_announcements,slug',
            'description'       => 'required',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'button_text'       => 'nullable|string|max:100',
            'button_link'       => 'nullable|string|max:255',
            'background_color'  => 'nullable|string|max:20',
            'text_color'        => 'nullable|string|max:20',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'priority'          => 'required|integer|min:1',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {

            $data['image'] = store_uploaded_file(
                $request->file('image'),
                'popup'
            );
        }

        $data['show_on_login'] = $request->boolean('show_on_login');
        $data['show_on_dashboard'] = $request->boolean('show_on_dashboard');
        $data['show_once_per_day'] = $request->boolean('show_once_per_day');
        $data['status'] = $request->boolean('status');

        PopupAnnouncement::create($data);

        return redirect()
            ->route('admin.popup-announcements.index')
            ->with('success', 'Popup created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(PopupAnnouncement $popupAnnouncement)
    {
        return view(
            'admin.popup-announcements.edit',
            compact('popupAnnouncement')
        );
    }

    /**
     * Update popup.
     */
    public function update(Request $request, PopupAnnouncement $popupAnnouncement)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'required|string|max:255|unique:popup_announcements,slug,' . $popupAnnouncement->id,
            'description'       => 'required',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'button_text'       => 'nullable|string|max:100',
            'button_link'       => 'nullable|string|max:255',
            'background_color'  => 'nullable|string|max:20',
            'text_color'        => 'nullable|string|max:20',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'priority'          => 'required|integer|min:1',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {

            delete_uploaded_file($popupAnnouncement->image);

            $data['image'] = store_uploaded_file(
                $request->file('image'),
                'popup'
            );
        }

        $data['show_on_login'] = $request->boolean('show_on_login');
        $data['show_on_dashboard'] = $request->boolean('show_on_dashboard');
        $data['show_once_per_day'] = $request->boolean('show_once_per_day');
        $data['status'] = $request->boolean('status');

        $popupAnnouncement->update($data);

        return redirect()
            ->route('admin.popup-announcements.index')
            ->with('success', 'Popup updated successfully.');
    }

    /**
     * Delete popup.
     */
    public function destroy(PopupAnnouncement $popupAnnouncement)
    {
        delete_uploaded_file($popupAnnouncement->image);

        $popupAnnouncement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Popup deleted successfully.'
        ]);
    }
}