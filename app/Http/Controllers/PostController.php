<?php
namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts = Posts::with('comments.user')->get();
        return view('home', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Posts::create([
            'title' => $request->title,
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('home')->with('success', 'Post created successfully.');
    }

    public function destroy($id)
    {
        $post = Posts::findOrFail($id);
        $post->delete();
        return redirect()->route('home')->with('success', 'Post deleted successfully.');
    }

    public function show($id)
    {
        $post = Posts::with('comments.user')->findOrFail($id);
        return view('posts.show', compact('post'));
    }
}
