@extends('layouts.app')
@section('title', 'Editar Tanque')
@section('header', 'Editar Tanque')
@section('content')
<div class="card"><div class="card-body"><form method="POST" action="{{ route('tanques.update', $tanque['id'] ?? 1) }}">@csrf @method('PUT')<button class="btn btn-primary">Guardar</button></form></div></div>
@endsection
