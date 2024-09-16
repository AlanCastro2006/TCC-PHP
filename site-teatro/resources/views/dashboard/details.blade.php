@extends('layout')

@section('title', $card->name)

@section('content')
<!-- Carrossel de Imagens -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($card->images as $index => $image)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ asset('img/cards/' . $image->image_path) }}" class="d-block w-100" alt="Imagem {{ $index + 1 }}">
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>

<div class="container mt-5">
    <h1>{{ $card->name }}</h1>
    <img src="{{ asset('img/cards/' . $card->img) }}" alt="{{ $card->name }}" class="img-fluid">
    <p><strong>Classificação:</strong> {{ $card->classification }}</p>
    <p><strong>Descrição:</strong> {{ $card->description }}</p>
    <p><strong>Duração:</strong> {{ $card->duration }}</p>
    @if($card->season_start && $card->season_end)
    <p><strong>Temporada:</strong> De {{ \Carbon\Carbon::parse($card->season_start)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($card->season_end)->format('d/m/Y') }}</p>
    @else
    <p><strong>Temporada:</strong> Informação indisponível</p>
    @endif
<!-- Exibição dos horários agrupados por dia -->
@if(!empty($horariosAgrupados))
    <p><strong>Horários:</strong></p>
    @foreach($horariosAgrupados as $dia => $horarios)
        <p><strong>{{ $dia }}:</strong></p>
        <ul>
            @foreach($horarios as $horario)
                <li>{{ \Carbon\Carbon::parse($horario)->format('H:i') }}</li>
            @endforeach
        </ul>
    @endforeach
@else
    <p><strong>Horários:</strong> Não há horários cadastrados.</p>
@endif
    <p><strong>Dias da Semana:</strong> {{ implode(', ', $daysArray) }}</p>
    <p><strong>Texto:</strong> {{ $card->texto }}</p>
    <p><strong>Elenco:</strong> {{ $card->elenco }}</p>
    <p><strong>Direção:</strong> {{ $card->direcao }}</p>
    <p><strong>Figurino:</strong> {{ $card->figurino }}</p>
    <p><strong>Cenografia:</strong> {{ $card->cenografia }}</p>
    <p><strong>Iluminação:</strong> {{ $card->iluminacao }}</p>
    <p><strong>Sonorização:</strong> {{ $card->sonorizacao }}</p>
    <p><strong>Produção:</strong> {{ $card->producao }}</p>
    @if($card->costureira)
    <p><strong>Costureira:</strong> {{ $card->costureira }}</p>
    @endif
    @if($card->assistente_cenografia )
    <p><strong>Assistente Cenográfico:</strong> {{ $card->assistente_cenografia }}</p>
    @endif
    @if($card->cenotecnico)
    <p><strong>Cenotécnico:</strong> {{ $card->cenotecnico }}</p>
    @endif
    @if($card->consultoria_design)
    <p><strong>Consultoria de Design:</strong> {{ $card->consultoria_design }}</p>
    @endif
    @if($card->co_producao)
    <p><strong>Co-Produção:</strong> {{ $card->co_producao }}</p>
    @endif
    @if($card->agradecimentos)
    <p><strong>Agradecimentos:</strong> {{ $card->agradecimentos }}</p>
    @endif




    @if($card->ticket_link)
    <a href="{{ $card->ticket_link }}" class="btn btn-primary" target="_blank">Comprar Ingressos</a>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection