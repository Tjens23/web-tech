<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;
use App\Models\Posts;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function index($postId)
    {
        $post = Posts::with('comments')->findOrFail($postId);
        return view('comments.index', compact('post'));
    }


    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $post = Posts::find($postId);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found.'
            ], 404);
        }

        Comments::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        session()->flash('success', 'Comment added successfully.');
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.'
        ]);
    }


    public function update(Request $request, $commentId)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $comment = Comments::find($commentId);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $comment->update([
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Comment updated successfully.']);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy($commentId)
    {
        $comment = Comments::find($commentId);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
