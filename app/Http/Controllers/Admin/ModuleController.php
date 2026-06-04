<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class ModuleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MODULE LIST
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $modules = Module::with('parent')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        $parents = Module::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $routes = collect(Route::getRoutes())
            ->map(fn ($route) => $route->getName())
            ->filter()
            ->filter(fn ($name) =>
                str_starts_with(
                    $name,
                    'retailer.'
                )
            )
            ->sort()
            ->values();

        return view(
            'admin.modules.index',
            compact(
                'modules',
                'parents',
                'routes'
            )
        );
    }
    
    public function list()
    {
        return response()->json(
            Module::with('parent')->get()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $parents = Module::whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view(
            'admin.modules.create',
            compact('parents')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE MODULE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:modules,slug'
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255'
            ],

            'route_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'parent_id' => [
                'nullable',
                'exists:modules,id'
            ],

            'sort_order' => [
                'nullable',
                'integer'
            ],

            'status' => [
                'required',
                'boolean'
            ],

        ]);

        DB::beginTransaction();

        try {

            Module::create([

                'name'       => $request->name,

                'slug'       => $request->slug
                                ? Str::slug($request->slug)
                                : Str::slug($request->name),

                'icon'       => $request->icon,

                'route_name' => $request->route_name,

                'parent_id'  => $request->parent_id,

                'sort_order' => $request->sort_order ?? 0,

                'status'     => $request->status,

            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.index')
                ->with(
                    'success',
                    'Module created successfully.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW MODULE
    |--------------------------------------------------------------------------
    */

    public function show(string $id)
    {
        $module = Module::with([
            'parent',
            'children'
        ])->findOrFail($id);

        return view(
            'admin.modules.show',
            compact('module')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT FORM
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        $module = Module::findOrFail($id);

        $parents = Module::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();

        return view(
            'admin.modules.edit',
            compact(
                'module',
                'parents'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE MODULE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $id
    ) {

        $module = Module::findOrFail($id);

        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:modules,slug,' . $module->id
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255'
            ],

            'route_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'parent_id' => [
                'nullable',
                'exists:modules,id'
            ],

            'sort_order' => [
                'nullable',
                'integer'
            ],

            'status' => [
                'required',
                'boolean'
            ],

        ]);

        DB::beginTransaction();

        try {

            $module->update([

                'name'       => $request->name,

                'slug'       => Str::slug(
                    $request->slug
                ),

                'icon'       => $request->icon,

                'route_name' => $request->route_name,

                'parent_id'  => $request->parent_id,

                'sort_order' => $request->sort_order ?? 0,

                'status'     => $request->status,

            ]);

            DB::commit();

            return redirect()
                ->route('admin.modules.index')
                ->with(
                    'success',
                    'Module updated successfully.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE MODULE
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        $module = Module::findOrFail($id);

        $hasChildren = Module::where(
            'parent_id',
            $module->id
        )->exists();

        if ($hasChildren) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Please delete child modules first.'
                );
        }

        DB::beginTransaction();

        try {

            $module->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Module deleted successfully.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}