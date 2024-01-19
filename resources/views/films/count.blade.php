@extends('layouts.master')

<h1>{{ $title }}</h1>
@section('content')
@if (empty($films))
<FONT COLOR="red">No se ha encontrado ninguna película</FONT>
@else
<FONT COLOR="black">Hay un total de {{ $films }} películas</FONT>
@endif
@endsection