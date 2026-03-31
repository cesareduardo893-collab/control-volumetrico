@extends('layouts.app')

@section('title', 'Editar Contribuyente')
@section('header', 'Editar Contribuyente')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('contribuyentes.update', $contribuyente['id'] ?? 1) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">RFC</label>
                        <input type="text" class="form-control" name="rfc" value="{{ $contribuyente['rfc'] ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
