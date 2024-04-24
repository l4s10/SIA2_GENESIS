@extends('adminlte::page')

@section('title', 'Seguimiento de Solicitud Vehicular')

@section('content_header')
<h1>Línea del Tiempo de Solicitud de Salida Vehicular </h1>    
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
                                        <div class="d-inline-flex flex-column px-2 py-1 text-md-end
                                            @if($evento['estado'] == 'INGRESADO')
                                                bg-ingresado text-dark
                                            @elseif($evento['estado'] == 'EN REVISIÓN')
                                                bg-en-revision text-dark
                                            @elseif($evento['estado'] == 'POR APROBAR')
                                                bg-por-aprobar text-dark
                                            @elseif($evento['estado'] == 'POR AUTORIZAR')
                                                bg-por-autorizar text-dark
                                            @elseif($evento['estado'] == 'POR RENDIR')
                                                bg-por-rendir text-dark
                                            @elseif($evento['estado'] == 'TERMINADO')
                                                bg-terminado text-dark
                                            @elseif($evento['estado'] == 'RECHAZADO')
                                                bg-rechazado text-dark
                                            @endif
                                            border border-primary rounded-2">
                                            <span class="fw-bold">{{ date('d-m-Y H:i:s', strtotime($evento['fecha'])) }}</span>
                                            <span><strong>ESTADO:</strong> {{ $evento['estado'] }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-content timeline-indicator">
                                        <div class="card border-0 shadow">
                                            <div class="card-body p-xl-4">
                                                <h2 class="card-title mb-2"><strong>{{ $evento['mensaje'] }}</strong></h2>
                                                @if(isset($evento['requiriente']))
                                                <p class="card-text m-0"><strong>REQUIRIENTE:</strong> {{ $evento['requiriente'] }}</p>
                                                @endif
                                                @if(isset($evento['revisor']))
                                                <p class="card-text m-0"><strong>REVISOR:</strong> {{ $evento['revisor'] }}</p>
                                                @endif
                                                @if(isset($evento['jefe']))
                                                <p class="card-text m-0"><strong>JEFE:</strong> {{ $evento['jefe'] }}</p>
                                                @endif
                                                @if(isset($evento['conductor']))
                                                <p class="card-text m-0"><strong>CONDUCTOR:</strong> {{ $evento['conductor'] }}</p>
                                                @endif
                                                @if(isset($evento['motivo']))
                                                <p class="card-text m-0"><strong>MOTIVO:</strong> {{ $evento['motivo'] }}</p>
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
    <style>
            .bg-ingresado {
            background-color: #FFA600;
            color: #000000;
        }

        .bg-en-revision {
            background-color: #0064a0;
            color: #000000;
        }

        .bg-por-aprobar {
            background-color: #F7F70B;
            color: #000000;
        }

        .bg-por-autorizar {
            background-color: #0CB009;
            color: #000000;
        }

        .bg-por-rendir {
            background-color: #FFFFFF;
            color: #000000;
        }

        .bg-rechazado {
            background-color: #F70B0B;
            color: #000000;
        }

        .bg-terminado {
            background-color: #d9d9d9;
            color: #000000;
        }

        
    </style>
    
@stop