<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaUserController extends Controller
{
    public function index()
    {
        $data = User::all();
        return view('pages.admin.k-user.index',compact('data'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer',
            'division' => 'nullable|string',
            'active' => 'required|integer',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Nullable image validation
        ]);

        // Create a new user instance
        $user = new User();
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->role = $validatedData['role'];
        $user->division = $validatedData['division'];
        $user->active = $validatedData['active'];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '_' . $file->getClientOriginalName(); // Create a unique filename without additional path info
            $file->storeAs('avatars/', $filename, 'public'); // Store in storage/app/public/avatars/img
            $user->profile = 'avatars/' . $filename;  // Save filename to the database
        } else {
            $user->profile = null; // Set to null if no image is uploaded
        }

        // Save the user
        $user->save();

        // Redirect or return response
        return redirect()->back()->with('success', 'User created successfully.');
    }

    // Show the edit profile view
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:0,1',
            'active' => 'required|in:0,1',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update the user's basic information
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->active = $request->active;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the user record
        $user->save();

        // Redirect back with a success message
        return redirect()->route('direktur.k-user')->with('success', 'User details updated successfully.');
    }

    // Update the user's password
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('direktur.k-user')->with('success', 'Password updated successfully.');
    }

    // Delete the specified user
    public function destroy(User $user,$id)
    {
        $user= User::find($id);
        $user->delete();
        return redirect()->route('direktur.k-user')->with('success', 'User deleted successfully.');
    }
}
