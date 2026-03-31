@extends('layouts.app')

@section('title', 'Resumen Fiscal')
@section('header', 'Resumen Fiscal')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>Resumen Fiscal</h5>
                <p>RFC: {{ $resumen['rfc'] ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
