<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function profile($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('users.profile', compact('user'));
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        return view('users.edit', compact('user'));
    }



    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found.');
        }

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'bio' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ensure only valid images are uploaded
        ]);

        // Update fields
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        if ($request->filled('bio')) {
            $user->bio = $request->bio;
        }

        // ✅ Profile Picture Upload Fix
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $profilePicture = $request->file('profile_picture');
            $profilePictureName = time() . '_' . $profilePicture->getClientOriginalName();
            $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePictureName, 'public');
            $user->profile_picture = $profilePicturePath;
        }

        $user->save();

        return redirect()->route('home')->with('success', 'User updated successfully.');
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return redirect()->route('home')->with('success', 'User deleted successfully.');
        }

        return redirect()->route('home')->with('error', 'User not found.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('username', 'LIKE', "%{$query}%")->get();

        return view('search', compact('users'));
    }

    public function follow($id)
    {
        $follower = Auth::user();
        $user = User::findOrFail($id);

        $follower->follows()->attach($user->id);

        // Create follow notification
        app(NotificationController::class)->createFollowNotification($user, $follower);

        return back()->with('success', 'You are now following ' . $user->username);
    }
}




