<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for profile picture
        ]);

        $fields = ['password', 'email', 'username', 'bio', 'profile_picture'];

        foreach ($fields as $field) {
            if ($request->filled($field)) {
                switch ($field) {
                    case 'password':
                        $user->password = Hash::make($request->password);
                        break;
                    case 'email':
                        $user->email = $request->email;
                        break;
                    case 'username':
                        $user->username = $request->username;
                        break;
                    case 'bio':
                        $user->bio = $request->bio;
                        break;
                    case 'profile_picture':
                        $profilePicture = $request->file('profile_picture');
                        $profilePictureName = time() . '_' . $profilePicture->getClientOriginalName();
                        $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePictureName, 'public');
                        $user->profile_picture = $profilePicturePath;
                        break;
                }
            }
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
}




