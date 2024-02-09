<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['user' => $user], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $adminRole = Role::where('name', 'Administrador')->first();
            if (!$adminRole) {
                $adminRole = Role::create(['name' => 'Administrador']);
            }

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            $user->assignRole($adminRole);

            return response()->json(['message' => 'Usuario creado con éxito', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();


            $adminRole = Role::where('name', 'Administrador')->first();
            if (!$adminRole) {

                $adminRole = Role::create(['name' => 'Administrador']);
            }

            $user = User::findOrFail($id);


            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            if (!$user->hasRole($adminRole)) {
                $user->assignRole($adminRole);
            }

            return response()->json(['message' => 'Usuario actualizado con éxito', 'user' => $user], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado con éxito'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método para asignar roles al usuario
    private function assignRoles($user, $roles)
{
    $user->roles()->detach();
    foreach ($roles as $roleName) {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $user->assignRole($role);
        }
    }
}
}
