@extends('layout') {{--Vai puxar do layout --}}

@section('title', 'Página Inicial') {{-- Define o título da página como "Página Inicial" --}}

@section('content') {{--essa section injeta o conteúdo no content do layout --}}

    {{-- Breadcrumb - Caminho do pão --}}
    <div class="col-lg-8 mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Home</li>
                <li class="breadcrumb-item"><a href="/sobre">Sobre</a></li>
                <li class="breadcrumb-item"><a href="/seu-projeto">Seu projeto</a></li>
                <li class="breadcrumb-item"><a href="/contatos">Contatos</a></li>
                @if(auth()->guard('adm')->check())
                <li class="breadcrumb-item"><a href="/cards">Adicionar Cards</a></li>
                @endif
            </ol>
        </nav>
    </div>

   <div id="search-container" class="col-md12">
        <h1>Busque uma apresentação</h1>
        <form action="">
            <!--Campo de pesquisa-->
            <input type="text" id="search" name="search" class="form-control" placeholder="Procurar...">
        </form>
   </div>

   <div id="cards-container" class="col-md-12">
        <h2>Próximas Apresentações</h2>
        <p>Veja as apresentações dos próximos dias</p>
        <div id="cards-container" class="row">
            @forelse($cards as $card) 
            <div class="card col-md-3">
                <div class="card-body">
                    <!-- Detalhes do card -->
                    <h5 class="card-title">{{ $card->name}}</h5>
                    <p class="card-date">{{ $card->date}}</p>
                    <p class="card-local">{{ $card->local}}</p>
                    <img src="{{ asset('img/cards/' . $card->img) }}" class="card-img-top" alt="{{ $card->name }}">
                    @if($card->ticket_link)
                    <a href="{{ $card->ticket_link }}" class="btn btn-primary" target="_blank">Comprar Ingressos</a>
                @endif
                </div>
            </div>
            @empty
            <div class="col-md-12 text-center">
                <p>Nenhuma apresentação encontrada.</p>
            </div>
            @endforelse
        </div>
   </div>

   <!-- Mensagem de Sucesso -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Mensagem de Erro -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

   <footer>
        <!-- Formulário de Contato -->
        <div class="container mt-5">
            <h2>Fale Conosco</h2>
        <form action="{{ route('contact.send') }}" method="POST">
            @csrf
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Seu Email" required>
                    <label for="email">Seu Email</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" placeholder="Sua Mensagem" id="message" name="message" style="height: 100px" required></textarea>
                    <label for="message">Sua Mensagem</label>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
   </footer>

@endsection {{-- Fim da seção content --}}