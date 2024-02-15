@extends('layouts.master')

@section('content')

<h1 align="center">{{ $title }}</h1>
@if(empty($actors))
<p>No se han encontrado actores</p>
@else
<p></p>
<div align="center">
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Fecha de nacimiento</th>
            <th>País</th>
            <th>Imagen</th>
        </tr>

        @foreach($actors as $actor)

        <tr>
            <td>{{$actor['name']}}</td>
            <td>{{$actor['surname']}}</td>
            <td>{{$actor['birthdate']}}</td>
            <td>{{$actor['country']}}</td>
            <td><img src="{{ $actor['img_url']}}" style="width: 100px; height: 120px;" /></td>
        </tr>
        @endforeach
    </table>
</div>
@endif
@endsection