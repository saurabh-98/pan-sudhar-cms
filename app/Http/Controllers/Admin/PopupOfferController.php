<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PopupOfferService;
use App\DTO\PopupOfferDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PopupOfferController extends Controller
{
    protected $service;

    public function __construct(PopupOfferService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.popup.index');
    }

    public function list()
    {
        return response()->json($this->service->getAll());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = 'popup_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/popup'), $name);

            $imagePath = 'uploads/popup/' . $name;
        }

        $dto = PopupOfferDTO::fromRequest($request, $imagePath);

        $this->service->store($dto);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $popup = $this->service->getAll()->find($id);

        $imagePath = $popup->image;

        if ($request->hasFile('image')) {

            if ($popup->image && File::exists(public_path($popup->image))) {
                File::delete(public_path($popup->image));
            }

            $file = $request->file('image');
            $name = 'popup_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/popup'), $name);

            $imagePath = 'uploads/popup/' . $name;
        }

        $dto = PopupOfferDTO::fromRequest($request, $imagePath);

        $this->service->update($id, $dto);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $this->service->delete($id);

        return response()->json(['success' => true]);
    }
}