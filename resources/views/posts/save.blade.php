<!DOCTYPE html>
<html>
<head>
    <title>Post Saves</title>
</head>
<body>
<h1>Post Saves</h1>
<ul>
    @foreach ($postSaves as $postSave)
    <li>User ID: {{ $postSave->user_id }}, Post ID: {{ $postSave->post_id }}</li>
    @endforeach
</ul>
</body>
</html>
