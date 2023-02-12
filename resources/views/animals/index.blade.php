@extends('layouts.app')

@section('content')

    <div class="relative flex flex-col items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">

        <div class="container-fluid">
            <div class="container-fluid d-flex justify-content-evenly py-4">
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
            </div>
            <div class="accordion pb-5 px-4">
                <div class="accordion-item">
                    <div class="accordion-header" id="headerOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Filtros
                        </button>
                    </div>

                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" >
                        <div class="accordion-body d-flex flex-sm-row flex-column align-items-center justify-content-evenly">

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
                            <!-- Para cambiar datos de todos los objetos -->
{{--                            <a href="{{ url('cambiarDatos') }}">Cambiar datos</a>--}}
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($animals))
                <div class="row row-cols-1 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 px-4">
                    @foreach($animals as $animal)
                        <div class="col pb-5">
                            <div class="card" onclick="window.location='{{ url('single/'. $animal->id) }}'">
                                <img src="{{ $animal->photo }}" class="card-img-top image" alt="perro llamado {{ $animal->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $animal->name }}</h5>
                                    <p class="card-text">{{ $animal->description }}</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Age: {{ $animal->age }}</li>
                                    <li class="list-group-item">Race: {{ $animal->race }}</li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $animals->onEachSide(2)->links() }}

            @endif

        </div>
    </div>

    <style>
        * {
            background-color: #1a202c;
            color: #9ca3af;
        }
        .image {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .card {
            transition: all .2s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 3px 3px #3d3d3d60;
            cursor: pointer;
        }
    </style>
@endsection
