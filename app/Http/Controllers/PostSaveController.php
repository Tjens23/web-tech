<?php

namespace App\Http\Controllers;

use App\Models\PostSave;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PostSaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postSaves = PostSave::all();
        return view('post_saves', compact('postSaves'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // In PostSaveController.php
    public function store(Request $request, $postId)
    {
        $userId = Auth::id();

        $exists = PostSave::where('user_id', $userId)
            ->where('post_id', $postId)
            ->exists();

        if ($exists) {
            PostSave::where('user_id', $userId)
                ->where('post_id', $postId)
                ->delete();
            return response()->json(['saved' => false]);
        }

        PostSave::create([
            'user_id' => $userId,
            'post_id' => $postId
        ]);

        return response()->json(['saved' => true]);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $postSave = PostSave::findOrFail($id);
        return response()->json($postSave);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $postSave = PostSave::findOrFail($id);
        $postSave->delete();
        return response()->json(null, 204);
    }
}
