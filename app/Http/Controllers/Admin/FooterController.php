<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FooterService;

class FooterController extends Controller
{
    protected $footerService;

    public function __construct(FooterService $footerService)
    {
        $this->footerService = $footerService;
    }

    /* =========================
       VIEW
    ========================= */
    public function index()
    {
        $data = $this->footerService->getFooterData();
        return view('admin.footer.index', $data);
    }

    /* =========================
       LIST (DATATABLE)
    ========================= */
    public function list()
    {
        return response()->json(
            $this->footerService->getAllLinks()
        );
    }

    /* =========================
       STORE
    ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required',
            'name' => 'required',
            'url' => 'required',
        ]);

        $this->footerService->createLink($request);

        return response()->json(['success' => true]);
    }

    /* =========================
       UPDATE
    ========================= */
    public function update(Request $request, $id)
    {
        $request->validate([
            'section' => 'required',
            'name' => 'required',
            'url' => 'required',
        ]);

        $this->footerService->updateLink($id, $request);

        return response()->json(['success' => true]);
    }

    /* =========================
       DELETE
    ========================= */
    public function delete($id)
    {
        $this->footerService->deleteLink($id);

        return response()->json(['success' => true]);
    }

    /* =========================
       SETTINGS
    ========================= */
    public function storeSetting(Request $request)
    {
        $this->footerService->updateSettings($request);

        return back()->with('success', 'Settings updated');
    }
}