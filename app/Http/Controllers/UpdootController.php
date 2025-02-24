<?php


namespace App\Http\Controllers;

use App\Events\VoteUpdated;
use App\Models\Updoot;
use Illuminate\Http\Request;
use App\Models\Posts;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UpdootController extends Controller
{

    public function upvote(Request $request, $postId)
    {
        $post = Posts::findOrFail($postId);

        // Check if user already voted
        $existingVote = Updoot::where('user_id', Auth::id())
            ->where('post_id', $postId)
            ->first();

        if ($existingVote) {
            // If same vote, remove it (toggle)
            if ($existingVote->value === 1) {
                $existingVote->delete();
                $message = 'Vote removed';
            } else {
                // If different vote, update it
                $existingVote->update(['value' => 1]);
                $message = 'Vote updated to upvote';
            }
        } else {
            // Create new vote
            Updoot::create([
                'user_id' => Auth::id(),
                'post_id' => $postId,
                'value' => 1
            ]);
            $message = 'Upvoted successfully';
        }

        // Get new vote count
        $newCount = $post->updoots()->sum('value');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'newCount' => $newCount
            ]);
        }
        broadcast(new VoteUpdated($postId, $newCount, Auth::id()));

        return back();
    }

    public function downvote(Request $request, $postId)
    {
        $post = Posts::findOrFail($postId);

        // Check if user already voted
        $existingVote = Updoot::where('user_id', Auth::id())
            ->where('post_id', $postId)
            ->first();

        if ($existingVote) {
            // If same vote, remove it (toggle)
            if ($existingVote->value === -1) {
                $existingVote->delete();
                $message = 'Vote removed';
            } else {
                // If different vote, update it
                $existingVote->update(['value' => -1]);
                $message = 'Vote updated to downvote';
            }
        } else {
            // Create new vote
            Updoot::create([
                'user_id' => Auth::id(),
                'post_id' => $postId,
                'value' => -1
            ]);
            $message = 'Downvoted successfully';
        }

        // Get new vote count
        $newCount = $post->updoots()->sum('value');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'newCount' => $newCount
            ]);
        }
        broadcast(new VoteUpdated($postId, $newCount, Auth::id()));
        return back();
    }


    public function showData() {
        return redirect()->route('posts.vote');
    }
}
