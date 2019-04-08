<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <div class="container">
            <div><h1> {{$post->title}} </h1></div>
            <div><img src="/storage/cover_images/{{$post->cover_image}}"></div>
            <div><p> {{$post->body}} </p></div>
            <div><small>Written on {{$post->created_at}} by {{@$post->user['name']}} </small></div>
        </div>
    </body>
</html>