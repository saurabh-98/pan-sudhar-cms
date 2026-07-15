<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupAnnouncement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PopupAnnouncementController extends Controller
{
    /**
     * Display popup page.
     */
    public function index()
    {
        return view('admin.popup-announcements.index');
    }

    /**
     * DataTable List
     */
    public function list()
    {
        $popups = PopupAnnouncement::latest();

        return DataTables::of($popups)

            ->addIndexColumn()

            ->editColumn('image', function ($row) {

                if (!$row->image) {

                    return '<span class="text-muted">No Image</span>';
                }

                return '<img src="' . file_url($row->image) . '"
                            width="80"
                            height="45"
                            class="img-thumbnail">';
            })

            ->editColumn('show_on_login', function ($row) {

                return $row->show_on_login
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-secondary">No</span>';
            })

            ->editColumn('show_on_dashboard', function ($row) {

                return $row->show_on_dashboard
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-secondary">No</span>';
            })

            ->editColumn('show_on_home', function ($row) {

                return $row->show_on_home
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-secondary">No</span>';

            })

            ->editColumn('status', function ($row) {

                return $row->status
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })

            ->addColumn('action', function ($row) {

                return '

                    <button
                        class="btn btn-warning btn-sm editBtn"
                        data-id="' . $row->id . '">

                        <i class="fa fa-edit"></i>

                    </button>

                    <button
                        class="btn btn-danger btn-sm deleteBtn"
                        data-id="' . $row->id . '">

                        <i class="fa fa-trash"></i>

                    </button>

                ';
            })

            ->rawColumns([
                'image',
                'show_on_login',
                'show_on_dashboard',
                 'show_on_home',
                'status',
                'action'
            ])

            ->make(true);
    }

    /**
     * Store Popup
     */
    public function store(Request $request)
    {
        $request->validate([

            'title' => 'required|max:255',

            'slug' => 'required|max:255|unique:popup_announcements,slug',

            'description' => 'required',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'button_text' => 'nullable|max:255',

            'button_link' => 'nullable|max:255',

            'start_date' => 'required|date',

            'end_date' => 'required|date|after_or_equal:start_date',

            'priority' => 'nullable|integer',

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

        $data['show_on_home'] = $request->boolean('show_on_home');

        $data['show_once_per_day'] = $request->boolean('show_once_per_day');

        $data['status'] = $request->boolean('status');

        PopupAnnouncement::create($data);

        return response()->json([

            'success' => true,

            'message' => 'Popup created successfully.'

        ]);
    }

    /**
     * Edit Popup
     */
    public function edit($id)
    {
        $popup = PopupAnnouncement::findOrFail($id);

        $popup->image_url = $popup->image
            ? file_url($popup->image)
            : '';

        return response()->json($popup);
    }

    /**
     * Update Popup
     */
    public function update(Request $request, $id)
    {
        $popup = PopupAnnouncement::findOrFail($id);

        $request->validate([

            'title' => 'required|max:255',

            'slug' => 'required|max:255|unique:popup_announcements,slug,' . $popup->id,

            'description' => 'required',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'button_text' => 'nullable|max:255',

            'button_link' => 'nullable|max:255',

            'start_date' => 'required|date',

            'end_date' => 'required|date|after_or_equal:start_date',

            'priority' => 'nullable|integer',

        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {

            delete_uploaded_file($popup->image);

            $data['image'] = store_uploaded_file(
                $request->file('image'),
                'popup'
            );
        }

        $data['show_on_login'] = $request->boolean('show_on_login');

        $data['show_on_dashboard'] = $request->boolean('show_on_dashboard');

        $data['show_on_home'] = $request->boolean('show_on_home');

        $data['show_once_per_day'] = $request->boolean('show_once_per_day');

        $data['status'] = $request->boolean('status');

        $popup->update($data);

        return response()->json([

            'success' => true,

            'message' => 'Popup updated successfully.'

        ]);
    }

    /**
     * Delete Popup
     */
    public function destroy($id)
    {
        $popup = PopupAnnouncement::findOrFail($id);

        if ($popup->image) {

            delete_uploaded_file($popup->image);
        }

        $popup->delete();

        return response()->json([

            'success' => true,

            'message' => 'Popup deleted successfully.'

        ]);
    }
}