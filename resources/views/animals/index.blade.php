@extends('layouts.app')

@section('content')

    <div class="relative flex flex-col items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        @php
            $string = '';

            if( app('request')->input('race') ) {
                $string .= '&race=' . str_replace('+', ' ' , app('request')->input('race'));
            }

            if( app('request')->input('from') ) {
                $string .= '&from=' . str_replace('+', ' ' , app('request')->input('from'));
            }

            if( app('request')->input('to') ) {
                $string .= '&to=' . str_replace('+', ' ' , app('request')->input('to'));
            }


        @endphp
        <div class="container-fluid">
            <div class="container-fluid d-flex justify-content-evenly py-4">
                <div class="">
                    Name
                    <a href="{{  $order['animal.name']['asc'] . $string }}">&#x25b4;</a>
                    <a href="{{ $order['animal.name']['desc'] . $string }}">&#x25be;</a>
                </div>
                <div class="">
                    Description
                    <a href="{{ $order['animal.description']['asc'] . $string }}">&#x25b4;</a>
                    <a href="{{ $order['animal.description']['desc'] . $string }}">&#x25be;</a>
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

                        <div class="accordion-body ">

                            <form>
                                <!-- Form Body -->
                                <div class="d-flex flex-sm-row flex-column align-items-center justify-content-evenly">
                                    <div class="d-flex flex-column align-items-center">
                                        <label>Age</label>
                                        <div class="d-flex align-content-center flex-column flex-sm-row">
                                            <input type="text" name="from">
                                            -
                                            <input type="text" name="to">
                                        </div>
                                    </div>
                                    <div class="">
                                        Race
                                        <select id="race" name="race">
                                            @foreach($races as $race)
                                                <option value="{{ $race }}">{{ $race }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Form Button -->
                                <div class="w-full d-flex justify-content-center py-4">
                                    <button type="submit" class="btn btn-primary py-2 px-12" href="{{ $order['animal.age']['desc'] }}">
                                        Aplicar Filtros
                                    </button>
                                </div>
                            </form>
                            <!-- Para cambiar datos de todos los objetos -->
                            <a href="{{ url('cambiarDatos') }}">Cambiar datos</a>
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
        /* * {
            background-color: #1a202c;
            color: #9ca3af;
        } */
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
