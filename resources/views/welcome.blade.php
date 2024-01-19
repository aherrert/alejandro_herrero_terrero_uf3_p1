<!DOCTYPE html>
<html lang="en">
@extends('layouts.master')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies List</title>

    <!-- Add Tailwind CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Include any additional stylesheets or scripts here -->
</head>

<body class="container mx-auto">
    @section('content')
    @if(session('error'))
    <div class="bg-red-500 text-white p-4 mb-4">
        {{ session('error') }}
    </div>
    @endif

    <!-- Your existing form goes here -->
    <form action="{{ route('createFilm') }}" method="post">
        <!-- Form fields -->
    </form>


    <h2 align="center">Lista de Películas</h2  >
    <div class="flex justify-center items-center">
    <ul class="list-disc pl-4">
        <li><a href="/filmout/oldFilms" class="text-blue-500">Pelis antiguas</a></li>
        <li><a href="/filmout/newFilms" class="text-blue-500">Pelis nuevas</a></li>
        <li><a href="/filmout/allFilms" class="text-blue-500">Pelis</a></li>
    </ul>
</div>


    <form action="{{ route('createFilm') }}" method="post" class="mx-auto flex items-center flex-col">
        @csrf <!-- Include CSRF token for security -->
        <label for="name" class="block" align="center">Nombre:
        <input type="text" id="name" name="name" required class="border mb-2"></label>

        <label for="year" class="block" align="center">Año:
        <input type="number" id="year" name="year" required class="border mb-2"></label>

        <label for="genre" class="block" align="center">Género:
        <input type="text" id="genre" name="genre" required class="border mb-2"></label>

        <label for="country" class="block" align="center">País:
        <input type="text" id="country" name="country" required class="border mb-2"></label>

        <label for="duration" class="block" align="center">Duración:
        <input type="number" id="duration" name="duration" required class="border mb-2"></label>

        <label for="url_image" class="block" align="center">Imagen URL:
        <input type="text" id="url_image" name="url_image" required class="border mb-2"></label>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Añadir Película</button>
    </form>

    <!-- Include any additional HTML or Blade directives here -->
    @endsection
</body>



</html>