<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">MyDogPlace</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">

                        </li>

                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
        <div class="relative flex flex-col items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">

            <div class="container-fluid">

                <div class="container-fluid py-3 d-flex p-2 justify-content-evenly">
                    <div class="">
                        Name
                        <a href="{{ $order['animal.name']['asc'] }}">&#x25b4;</a>
                        <a href="{{ $order['animal.name']['desc'] }}">&#x25be;</a>
                    </div>
                    <div class="">
                        Description
                        <a href="{{ $order['animal.description']['asc'] }}">&#x25b4;</a>
                        <a href="{{ $order['animal.description']['desc'] }}">&#x25be;</a>
                    </div>
                    <div class="d-flex align-items-center">
                        <label>Age</label>
                        <input type="range">
                        <button class="btn btn-primary" href="{{ $order['animal.age']['desc'] }}">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                    <div class="">
                        Race
                        <a href="{{ $order['animal.race']['asc'] }}">&#x25b4;</a>
                        <a href="{{ $order['animal.race']['desc'] }}">&#x25be;</a>
                    </div>
                </div>


                @if(isset($animals))
                    <div class="row row-cols-1 row-cols-lg-4 row-cols-md-3 row-cols-sm-2">
                        @foreach($animals as $animal)
                            <div class="col pb-5">
                                <div class="card">
                                    <img src="{{ $animal->photo }}" class="card-img-top image" alt="perro llamado {{ $animal->name }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $animal->name }}</h5>
                                        <p class="card-text">{{ $animal->description }}</p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">Age: {{ $animal->age }}</li>
                                        <li class="list-group-item">Race: {{ $animal->race }}</li>
                                        <li class="list-group-item">A third item</li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </body>

    <style>
        .image {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</html>
