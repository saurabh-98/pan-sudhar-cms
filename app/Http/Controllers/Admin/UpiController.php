<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UpiSetting;
use Illuminate\Http\Request;

class UpiController extends Controller
{
    public function index(Request $request)
    {
        $upis = UpiSetting::latest()->get();

        if($request->ajax()){
            return response()->json($upis); 
        }

        return view('admin.upi.index', compact('upis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'upi_id' => 'required',
            'name' => 'nullable'
        ]);

        UpiSetting::create($request->all());

        return back()->with('success','UPI Added');
    }

    public function activate($id)
    {
        UpiSetting::query()->update(['is_active' => 0]);

        UpiSetting::where('id',$id)->update([
            'is_active' => 1
        ]);

        return back()->with('success','UPI Activated');
    }

    public function update(Request $request, $id)
    {
        UpiSetting::where('id',$id)->update([
            'upi_id' => $request->upi_id,
            'name' => $request->name
        ]);

        return response()->json(['success'=>true]);
    }

    public function delete($id)
    {
        UpiSetting::where('id',$id)->delete();

        return response()->json(['success'=>true]);
    }
}