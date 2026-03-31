@extends('layouts.app')

@section('title', 'Actividad del Usuario')
@section('header', 'Actividad del Usuario')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>Actividad del Usuario</h5>
                <p>Total: {{ $actividad['total_actividades'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
