<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->username }}'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/profiletabs.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-200">
<nav class="bg-white border-b border-gray-300">
    <div class="container mx-auto px-4 py-2 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-red-500 text-3xl font-bold">SocialConnect</a>
        <div class="relative">
            <form action="{{ route('search.user') }}" method="GET">
                <input type="text" name="query" placeholder="Search for a user" class="bg-gray-100 rounded-full py-1 px-4 w-64">
                <button type="submit" class="absolute right-3 top-2 text-gray-400"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div>
            @auth
            <span class="mr-4">Welcome, {{ Auth::user()->username }}</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded-full hover:bg-blue-600">Log Out</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-1 rounded-full hover:bg-blue-600">Log In</a>
            <a href="{{ route('register') }}" class="bg-gray-300 text-blue-500 px-4 py-1 rounded-full ml-2 hover:bg-gray-400">Sign Up</a>
            @endauth
        </div>
    </div>
</nav>

<main class="container mx-auto mt-8 flex">
    <div class="w-2/3 pr-4">
        <div class="bg-white rounded-md shadow-md mb-4 p-4">
            <div class="flex items-center mb-4">
                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}"
                     alt="Profile Picture"
                     class="w-16 h-16 rounded-full mr-4">
                <div>
                    <h2 class="text-2xl font-semibold">{{ $user->username }}</h2>
                    <p class="text-sm text-gray-500">Joined {{ $user->created_at->diffForHumans() }}</p>
                    <p class="text-sm text-gray-500">{{ $user->getFollowerCount() }} followers</p>
                </div>
                <div class="ml-auto">
                    @auth
                    @if(Auth::user()->id !== $user->id)
                    @if(Auth::user()->isFollowing($user->id))
                    <button class="bg-gray-500 text-white px-4 py-1 rounded-full" disabled>Following</button>
                    @else
                    <form action="{{ route('follow', $user->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-1 rounded-full hover:bg-green-600">Follow</button>
                    </form>
                    @endif
                    @else
                    <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 text-white px-4 py-1 rounded-full hover:bg-yellow-600">Edit Profile</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded-full hover:bg-red-600">Delete Profile</button>
                    </form>
                    @endif
                    @endauth
                </div>
            </div>
            <p class="text-gray-700">{{ $user->bio }}</p>
        </div>

        <div class="bg-white rounded-md shadow-md p-4">
            <div class="tabs">
                <button class="tab-link active" data-tab="posts">Posts</button>
                <button class="tab-link" data-tab="saved-posts">Saved Posts</button>
            </div>
            <div id="posts" class="tab-content active">
                <h2 class="text-lg font-semibold mb-2">Posts by {{ $user->username }}</h2>
                @if($user->posts && count($user->posts) > 0)
                @foreach ($user->posts as $post)
                <div class="bg-gray-100 rounded-md p-4 mb-4">
                    <h3 class="text-xl font-semibold">{{ $post->title }}</h3>
                    <p class="text-gray-700">{{ $post->content }}</p>
                    <div class="text-sm text-gray-500 mt-2">
                        <span class="mr-4"><i class="far fa-comment"></i> {{ $post->comments_count }} comments</span>
                        <span class="mr-4"><i class="fas fa-share"></i> Share</span>
                        <span><i class="far fa-bookmark"></i> Save</span>
                    </div>
                </div>
                @endforeach
                @else
                <p>No posts available.</p>
                @endif
            </div>
            <div id="saved-posts" class="tab-content">
                <h2 class="text-lg font-semibold mb-2">Saved Posts</h2>
                @if($user->savedPosts && count($user->savedPosts) > 0)
                @foreach ($user->savedPosts as $post)
                <div class="bg-gray-100 rounded-md p-4 mb-4">
                    <h3 class="text-xl font-semibold">{{ $post->title }}</h3>
                    <p class="text-gray-700">{{ $post->content }}</p>
                    <div class="text-sm text-gray-500 mt-2">
                        <span class="mr-4"><i class="far fa-comment"></i> {{ $post->comments_count }} comments</span>
                        <span class="mr-4"><i class="fas fa-share"></i> Share</span>
                        <span><i class="far fa-bookmark"></i> Save</span>
                    </div>
                </div>
                @endforeach
                @else
                <p>No saved posts available.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="w-1/3 pl-4">
        <div class="bg-white rounded-md shadow-md p-4 mb-8">
            <h2 class="text-lg font-semibold mb-2">About {{ $user->username }}</h2>
            <p class="text-sm mb-4">{{ $user->bio }}</p>
        </div>
    </div>
</main>

<footer class="bg-white mt-8 py-4 border-t border-gray-300">
    <div class="container mx-auto text-center text-sm text-gray-500">
        <p>&copy; 2024 SocialConnect. All rights reserved.</p>
    </div>
</footer>

<script>
    document.querySelectorAll('.tab-link').forEach(button => {
        button.addEventListener('click', () => {
            const tabContent = document.querySelectorAll('.tab-content');
            tabContent.forEach(content => content.classList.remove('active'));

            document.querySelectorAll('.tab-link').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const tab = button.getAttribute('data-tab');
            document.getElementById(tab).classList.add('active');
        });
    });
</script>
</body>
</html>
