<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use function Laravel\Prompts\password;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($role = null)
    {
        $roles = Roles::orderBy('created_at', 'desc')->get();
        $users = User::where('name', '!=', 'Supervisor')->orderBy('created_at', 'desc')->get();
        return Inertia::render('Users/Users', ['users' => $users, 'roles' => $roles]);
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
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => '',
            'role_id' => 'required'
        ]);

        $existingUser = User::where('email', $validateData['email'])->first();
        if ($existingUser) {
            return redirect()->back()->with(['message' => '422']);
        }

        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'role' => $validateData['role'],
            'password' => Hash::make($validateData['password']),
        ]);

        $user->assignRole($validateData['role_id']);

        return redirect()->back()->with('message', 'Utilisateur créé avec succès!');
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
       
        
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'role' => '',
            'role_id' => '',
        ]);

        $user->update([
            'role' => $validatedData['role']
        ]);

        $user->syncRoles($validatedData['role_id']);
        return redirect()->back()->with('message', 'Utilisateur mis à jour avec succès!');
    }
    

    /** Function update avtive users **/
    public function updateActiveUsers($id, $checked)
    {
        // $user = User::findOrFail($id);
        // if ($checked === "1") {
        //     $user->active = 0;
        // }
        // else {
        //     $user->active = 1;
        // }
        // $user->save();
        // return redirect()->back()->with('message', 'Utilisateur mis à jour active avec succès!');
        $user = User::findOrFail($id);
        $user->active = $checked === "1" ? 0 : 1;
        $user->save();
        return redirect()->back()->with('message', 'Utilisateur mis à jour active avec succès!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
        $user = User::findOrFail($id);
        // dd($user);
        $user->roles()->detach();
        $user->delete();
        return redirect()->back()->with('message', 'Utilisateur supprimé avec succès!');
    }
    
}
