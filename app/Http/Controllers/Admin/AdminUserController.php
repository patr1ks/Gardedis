<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return Inertia::render('Admin/User/Index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->isAdmin = $request->boolean('isAdmin');
        $user->isRestaurant = $request->boolean('isRestaurant');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->isAdmin = $request->boolean('isAdmin');
        $user->isRestaurant = $request->boolean('isRestaurant');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
            'user_id' => $id,
        ]);
    }

    public function showData($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }
}
