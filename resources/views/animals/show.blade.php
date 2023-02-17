@extends('layouts.app')

@section('content')
    <div class="profile container-fluid d-flex flex-column">
        <img class="w-50 rounded-circle align-self-center" src="{{ $animal->photo }}">
        <h1 class="align-self-center py-4">{{ $animal->name }}, {{ $animal->age }}</h1>
        <p class="align-self-center">
            {{ $animal->description }}
        </p>
    </div>

    <div class="photos">
        <!-- Aquí irían las fotos que el animal tiene asociadas a el -->
    </div>


@endsection
