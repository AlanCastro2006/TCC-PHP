@extends('layout')

@section('title', 'Cards Ativos')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cards</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-9">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCardModal">Adicionar Card</button>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Modal de criação de card -->
            <div class="modal fade" id="createCardModal" tabindex="-1" aria-labelledby="createCardModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createCardModalLabel">Novo Card</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/cards/new" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Nome" value="{{ old('name') }}">
                                    <label for="floatingInput">Nome</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="local" id="local" placeholder="Local" value="{{ old('local') }}">
                                    <label for="floatingInput">Local</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="file" class="form-control" name="img" id="img" placeholder="Imagem">
                                    <label for="floatingInput">Imagem</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="url" class="form-control" name="ticket_link" id="ticket_link" placeholder="Link para compra de ingressos" value="{{ old('ticket_link') }}">
                                    <label for="floatingInput">Link para compra de ingressos</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" name="classification" id="classification">
                                        <option value="Livre" {{ old('classification') == 'Livre' ? 'selected' : '' }}>Livre</option>
                                        <option value="10" {{ old('classification') == '10' ? 'selected' : '' }}>10</option>
                                        <option value="12" {{ old('classification') == '12' ? 'selected' : '' }}>12</option>
                                        <option value="14" {{ old('classification') == '14' ? 'selected' : '' }}>14</option>
                                        <option value="16" {{ old('classification') == '16' ? 'selected' : '' }}>16</option>
                                        <option value="18" {{ old('classification') == '18' ? 'selected' : '' }}>18</option>
                                    </select>
                                    <label for="classification">Classificação</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="description" id="description" placeholder="Descrição">{{ old('description', $card->description ?? '') }}</textarea>
                                    <label for="description">Descrição</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="duration" id="duration" placeholder="Duração" value="{{ old('duration', $card->duration ?? '') }}">
                                    <label for="duration">Duração</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="season" id="season" placeholder="Temporada" value="{{ old('season', $card->season ?? '') }}">
                                    <label for="season">Temporada</label>
                                </div>
                                <div class="form-group">
                                    <label for="days">Dias da Semana</label>
                                    <select name="days[]" id="days" class="form-control" multiple>
                                        <option value="domingo" {{ in_array('domingo', $daysArray) ? 'selected' : '' }}>Domingo</option>
                                        <option value="segunda" {{ in_array('segunda', $daysArray) ? 'selected' : '' }}>Segunda</option>
                                        <option value="terça" {{ in_array('terça', $daysArray) ? 'selected' : '' }}>Terça</option>
                                        <option value="quarta" {{ in_array('quarta', $daysArray) ? 'selected' : '' }}>Quarta</option>
                                        <option value="quinta" {{ in_array('quinta', $daysArray) ? 'selected' : '' }}>Quinta</option>
                                        <option value="sexta" {{ in_array('sexta', $daysArray) ? 'selected' : '' }}>Sexta</option>
                                        <option value="sabado" {{ in_array('sabado', $daysArray) ? 'selected' : '' }}>Sábado</option>
                                    </select>
                                    <small class="form-text text-muted">Segure Ctrl (ou Cmd no Mac) para selecionar múltiplos dias.</small>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de cards -->
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content">
                    <h1 class="fw-bold">Cards Cadastrados</h1>
                </div>
                <div class="card-body px-4 py-3">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Local</th>
                                <th scope="col">Img</th>
                                <th scope="col">Visibilidade</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cards as $card)
                                <tr>
                                    <td>{{ $card->name }}</td>
                                    <td>{{ $card->local }}</td>
                                    <td>{{ $card->img }}</td>
                                    <td>
                                        <!-- Formulário para atualizar a visibilidade -->
                                        <form action="/cards/{{ $card->id }}/visibility" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="visible" id="visible{{ $card->id }}" {{ $card->visible ? 'checked' : '' }}>
                                                <label class="form-check-label" for="visible{{ $card->id }}">Visível</label>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-2">Atualizar</button>
                                        </form>
                                    </td>
                                    <td>
                                        <!-- Botão de editar que abre o modal -->
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editCardModal{{ $card->id }}">
                                            Editar
                                        </button>

                                        <!-- Modal de edição para cada card -->
                                        <div class="modal fade" id="editCardModal{{ $card->id }}" tabindex="-1" aria-labelledby="editCardModalLabel{{ $card->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editCardModalLabel{{ $card->id }}">Editar Card</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="/cards/{{ $card->id }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-floating mb-3">
                                                                <input type="text" class="form-control" name="name" id="name{{ $card->id }}" placeholder="Nome" value="{{ $card->name }}">
                                                                <label for="floatingInput">Nome</label>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <input type="text" class="form-control" name="local" id="local{{ $card->id }}" placeholder="Local" value="{{ $card->local }}">
                                                                <label for="floatingInput">Local</label>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <input type="file" class="form-control" name="img" id="img{{ $card->id }}" placeholder="Imagem">
                                                                <label for="floatingInput">Imagem</label>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <input type="url" class="form-control" name="ticket_link" id="ticket_link" placeholder="Link para compra de ingressos" value="{{ $card->ticket_link }}">
                                                                <label for="floatingInput">Link para compra de ingressos</label>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <select class="form-select" name="classification" id="classification{{ $card->id }}">
                                                                    <option value="Livre" {{ $card->classification == 'Livre' ? 'selected' : '' }}>Livre</option>
                                                                    <option value="10" {{ $card->classification == '10' ? 'selected' : '' }}>10</option>
                                                                    <option value="12" {{ $card->classification == '12' ? 'selected' : '' }}>12</option>
                                                                    <option value="14" {{ $card->classification == '14' ? 'selected' : '' }}>14</option>
                                                                    <option value="16" {{ $card->classification == '16' ? 'selected' : '' }}>16</option>
                                                                    <option value="18" {{ $card->classification == '18' ? 'selected' : '' }}>18</option>
                                                                </select>
                                                                <label for="classification">Classificação</label>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <textarea class="form-control" name="description" id="description" placeholder="Descrição">{{ $card->description  }}</textarea>
                                                                <label for="description">Descrição</label>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <input type="text" class="form-control" name="duration" id="duration" placeholder="Duração" value="{{ $card->duration }}">
                                                                <label for="duration">Duração</label>
                                                            </div>
                                                            <div class="form-group">
    <label for="season">Temporada</label>
    <input type="text" name="season" id="season" class="form-control" value="{{ old('season', $card->season) }}" placeholder="Selecione o período">
</div>
                                                            <div class="form-group">
                        <label for="days">Dias da Semana</label>
                        <select name="days[]" id="days{{ $card->id }}" class="form-control" multiple>
                            @foreach(['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sabado'] as $day)
                            <option value="{{ $day }}" {{ is_array($card->days) && in_array($day, $card->days) ? 'selected' : '' }}>
                            {{ ucfirst($day) }}
            </option>
        @endforeach
                        </select>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Formulário de exclusão -->
                                        <form action="/cards/{{ $card->id }}" method="POST" onsubmit="return confirm('Você tem certeza de que deseja prosseguir com a exclusão do card?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger delete-button">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum card cadastrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Logout  -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<a href="{{ route('logout') }}"
    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    Logout
</a>

@stack('scripts')

@endsection

@section('scripts')
<script>
    flatpickr("#season", {
        mode: "range",
        dateFormat: "d/m/Y"
    });
</script>
@endsection
