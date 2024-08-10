@extends('layout') {{--Vai puxar do layout --}}

@section('title', 'Adicionar Card') {{-- Define o título da página como "Adicionar Card" --}}

@section('content') {{--essa section injeta o conteúdo no content do layout --}}
<div class="container">
        <div class="row justify-content-center">

            {{-- Breadcrumb - Caminho do pão --}}
            <div class="col-lg-8 mt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="/">Home</a></li>
                      <li class="breadcrumb-item"><a href="/cards">Cards</a></li>
                      <li class="breadcrumb-item active" aria-current="page">Novo</li>
                    </ol>
                </nav>
            </div>

            <div class="col-lg-8">
                <div class="card shadow">

                    <div class="card-header bg-success text-white">
                        <h1 class="fw-bold">
                            {{-- Se existe um ID, usar "Alterar Card", senão usar "Novo Card" --}}
                            {{ isset($card->id) ? "Alterar Card" : "Novo Card" }}
                        </h1>
                    </div>

                    <div class="card-body px-5 py-4">

                        {{-- PUT - Altera o recurso inteiro, PATCH - Altera parte do recurso --}}
                        {{-- Verificando se o ID existe, se existir fazer a alteração/update do tipo com o ID indicado através do método put --}}
                        @if (isset($card->id))
                            <form action="/cards/{{$card->id}}" method="POST" enctype="multipart/form-data">
                            @method('put')
                        @else
                            <form action="/cards/new" method="POST" enctype="multipart/form-data">
                        @endif

                            @csrf {{-- Token para assinar de onde veio os dados, para evitar o ataque de falsificação de tela (sem isso não funciona) --}}


                        {{--Nome "nome"--}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nome" value="{{$card->name ?? null}}">
                            <label for="floatingInput">Nome</label>
                        </div>

                        {{--Date "date"--}}
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="date" id="date" placeholder="Data" value="{{$card->date ?? null}}">
                            <label for="floatingInput">Data</label>
                        </div>

                        {{--Local "local"--}}
                        <div class="form-floating mb-3">
                            {{-- value é para quando for editar ele buscar o id no servidor para editar  --}}
                            <input type="text" class="form-control" name="local" id="local" placeholder="Local" value="{{$card->local ?? null}}">
                            <label for="floatingInput">Local</label>
                        </div>

                        {{--Imagem "img"--}}
                        <div class="form-floating mb-3">
                        <input type="file" class="form-control" name="img" id="img" placeholder="Imagem" value="{{$card->img ?? null}}">
                        <label for="floatingInput">Imagem</label>
                        </div>

                            {{-- Botões --}}
                            <div class="d-flex justify-content-end gap-1">
                                <button class="btn btn-secondary" type="reset">Limpar</button>
                                <button class="btn btn-success" type="submit">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div> <!--Fim do card-->
            </div> <!-- Fim da col -->
        </div> <!-- Fim da row -->
    </div> <!-- Fim do container -->



{{--Verifica se variável já está definido--}}
{{-- Ctrl + ; faz um comentário --}}
@isset($success)

<style>
    /*Pop up*/
    .toast{
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: rgba(red, green, blue, 0.96);
        /*animação para mostrar o pop-up (forwads serve para rodar apenas uma vez, caso contrário a animação ficaria em loop)*/
        animation: fade 3s forwards, fade 1s forwards;
        /*Delay de uma animação para a outra*/
        animation-delay: 0s, 3s;
        /*Entrada e saída da animação*/
        animation-direction: normal, reverse;
    }

    /*Animção*/
    @keyframes fade {
        from { opacity: 0; display: none;}/*Animação que leva da opacidade 0 para a um e depois some, fazendo o pop-up aparecer e depois sumir*/
        to { opacity: 1;}
    }
</style>
<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">✅Sucesso!</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Card cadastrado com sucesso
    </div>
  </div>
  @endisset

@endsection