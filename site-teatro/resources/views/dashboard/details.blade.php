@extends('layout')

@section('title', $card->name)

@section('content')
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
    <p><strong>Dias da Semana:</strong> {{ implode(', ', $daysArray) }}</p>
    <p><strong>Texto:</strong> {{ $card->texto }}</p>
    <p><strong>Elenco:</strong> {{ $card->elenco }}</p>
    <p><strong>Direção:</strong> {{ $card->direcao }}</p>
    <p><strong>Figurino:</strong> {{ $card->figurino }}</p>
    <p><strong>Cenografia:</strong> {{ $card->cenografia }}</p>
    <p><strong>Iluminação:</strong> {{ $card->iluminacao }}</p>
    <p><strong>Sonorização:</strong> {{ $card->sonorizacao }}</p>
    <p><strong>Produção:</strong> {{ $card->producao }}</p>
    <p><strong>Costureira:</strong> {{ $card->costureira }}</p>
    <p><strong>Assistente Cenográfico:</strong> {{ $card->assistente_cenografia }}</p>
    <p><strong>Cenotécnico:</strong> {{ $card->cenotecnico }}</p>
    <p><strong>Consultoria de Design:</strong> {{ $card->consultoria_design }}</p>
    <p><strong>Co-Produção:</strong> {{ $card->co_producao }}</p>
    <p><strong>Agradecimentos:</strong> {{ $card->agradecimentos }}</p>



    @if($card->ticket_link)
    <a href="{{ $card->ticket_link }}" class="btn btn-primary" target="_blank">Comprar Ingressos</a>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection
