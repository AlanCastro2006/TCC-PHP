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

<div class="accordion" id="accordionExample">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Accordion Item #1
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Accordion Item #2
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Accordion Item #3
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
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
                <!-- Botão para detalhes -->
                <a href="{{ route('cards.details', $card->id) }}" class="btn btn-info mt-3">Ver Detalhes</a>
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