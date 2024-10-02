<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Roles::with('permissions', 'users')->orderBy('created_at', 'desc')->get();
        $permissions = Permission::all();
        return Inertia::render('Roles/Roles', ['roles' => $roles, 'permissions' => $permissions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'permissions' => '  ',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Roles::firstOrCreate(['name' => $validatedData['name']], ['guard_name' => 'web']);

        $permissions = Permission::find($validatedData['permissions']);
        $role->syncPermissions($permissions);

        return redirect()->back()->with('message', 'Role updated successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($roleId)
    {
        $role = Roles::findOrFail($roleId);

        $role->permissions()->detach();

        $role->delete();

        return redirect()->back()->with('message', 'Role deleted successfully!');
    }
}
