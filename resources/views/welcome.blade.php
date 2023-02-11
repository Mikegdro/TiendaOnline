<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            <form method="POST" enctype="multipart/form-data" action="{{ url('/saveImage') }}">
                @csrf
                @method('POST')

                <input type="file" name="photo">
                <input type="submit">
            </form>

            <br/>


            <img src="{{ asset("storage/images/chula.jpg") }}">
        </div>
    </body>
</html>
