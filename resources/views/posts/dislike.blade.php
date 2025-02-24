<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Voting</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>

    <div class="vote-buttons">
        <form action="{{ route('posts.like', $post->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Upvote</button>
        </form>

        <form action="{{ route('posts.dislike', $post->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Downvote</button>
        </form>
    </div>
</div>
</body>
</html>
