<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OfferController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index()
    {
        $offers = $this->offerService->getAll();
        return view('admin.offers.index', compact('offers'));
    }

    // ✅ STORE (WITH IMAGE)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048'
        ]);

        $data = $request->all();

        // 🔥 IMAGE UPLOAD
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = 'offer_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/offers'), $name);

            $data['image'] = 'uploads/offers/' . $name;
        }

        $this->offerService->store($data);

        return response()->json(['success' => true]);
    }

    public function list()
    {
        $offers = $this->offerService->getAll();
        return response()->json($offers);
    }

    // ✅ UPDATE (WITH IMAGE REPLACE)
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048'
        ]);

        $data = $request->all();

        $offer = $this->offerService->find($id); // make sure this exists

        // 🔥 IMAGE UPDATE
        if ($request->hasFile('image')) {

            // delete old image
            if ($offer && $offer->image && File::exists(public_path($offer->image))) {
                File::delete(public_path($offer->image));
            }

            $file = $request->file('image');
            $name = 'offer_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/offers'), $name);

            $data['image'] = 'uploads/offers/' . $name;
        }

        $this->offerService->update($id, $data);

        return response()->json(['success' => true]);
    }

    // ✅ DELETE (WITH IMAGE DELETE)
    public function delete($id)
    {
        $offer = $this->offerService->find($id);

        // delete image
        if ($offer && $offer->image && File::exists(public_path($offer->image))) {
            File::delete(public_path($offer->image));
        }

        $this->offerService->delete($id);

        return response()->json(['success' => true]);
    }
}