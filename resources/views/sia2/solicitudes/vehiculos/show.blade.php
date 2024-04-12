@extends('adminlte::page')

@section('title', 'Seguimiento de Solicitud Vehicular')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
        </div>
    </div>
@stop

@section('content')
    <div class="container">
        <!-- Timeline -->
        <section class="bsb-timeline-7 bg-light py-3 py-md-5 py-xl-8">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-10 col-md-12 col-xl-10 col-xxl-9">
                        <ul class="timeline">
                            @foreach($eventos as $evento)
                            <li class="timeline-item">
                                <div class="timeline-body">
                                    <div class="timeline-meta">
                                        <div class="d-inline-flex flex-column px-2 py-1 text-success-emphasis bg-success-subtle border border-success-subtle rounded-2 text-md-end">
                                            <span class="fw-bold">{{ date('d-m-Y H:i:s', strtotime($evento['fecha'])) }}</span>
                                            <span><strong>ESTADO:</strong> {{ $evento['estado'] }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-content timeline-indicator">
                                        <div class="card border-0 shadow">
                                            <div class="card-body p-xl-4">
                                                <h2 class="card-title mb-2"><strong>{{ $evento['mensaje'] }}</strong></h2>
                                                @if(isset($evento['usuario']))
                                                <p class="card-text m-0"><strong>USUARIO:</strong> {{ $evento['usuario'] }}</p>
                                                @endif
                                                @if(isset($evento['detalle']))
                                                <p class="card-text m-0"><strong>DETALLE:</strong> {{ $evento['detalle'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- Fin del Timeline -->
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/tutorials/timelines/timeline-7/assets/css/timeline-7.css">
@stop