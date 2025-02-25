<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200">
<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Search Results</h1>
    @if($users->isEmpty())
    <p>No users found.</p>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($users as $user)
        <div class="bg-white rounded-md shadow-md p-4 flex items-center">
            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'default-profile.png' }}" alt="Profile Picture" class="w-16 h-16 rounded-full mr-4">
            <a href="{{ route('user.profile', ['username' => $user->username]) }}" class="text-lg font-semibold">{{ $user->username }}</a>
        </div>
        @endforeach
    </div>
    @endif
</div>
</body>
</html>
