@extends('layout')

@section('title', $card->name)

@section('content')
<div class="container mt-5">
    <h1>{{ $card->name }}</h1>
    <img src="{{ asset('img/cards/' . $card->img) }}" alt="{{ $card->name }}" class="img-fluid">
    <p><strong>Local:</strong> {{ $card->local }}</p>
    <p><strong>Classificação:</strong> {{ $card->classification }}</p>
    <p><strong>Descrição:</strong> {{ $card->description }}</p>
    <p><strong>Duração:</strong> {{ $card->duration }}</p>
    @if($card->season_start && $card->season_end)
    Temporada: De {{ \Carbon\Carbon::parse($card->season_start)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($card->season_end)->format('d/m/Y') }}
@endif
    <p><strong>Dias da Semana:</strong> {{ implode(', ', $daysArray) }}</p>


    @if($card->ticket_link)
    <a href="{{ $card->ticket_link }}" class="btn btn-primary" target="_blank">Comprar Ingressos</a>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection
