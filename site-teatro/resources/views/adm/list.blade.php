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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createCardModal">Adicionar Card</button>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Modal de criação de card -->
                <div class="modal fade" id="createCardModal" tabindex="-1" aria-labelledby="createCardModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createCardModalLabel">Novo Card</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="/cards/new" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="accordion" id="accordionExample">

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    Informações da Peça
                                                </button>
                                            </h2>

                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required name="name"
                                                            id="name" placeholder="Nome" value="{{ old('name') }}">
                                                        <label for="floatingInput">Nome</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="url" class="form-control" required
                                                            name="ticket_link" id="ticket_link"
                                                            placeholder="Link para compra de ingressos"
                                                            value="{{ old('ticket_link') }}">
                                                        <label for="floatingInput">Link para compra de ingressos</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <select class="form-select" name="classification"
                                                            id="classification">
                                                            <option value="Livre"
                                                                {{ old('classification') == 'Livre' ? 'selected' : '' }}>
                                                                Livre</option>
                                                            <option value="10"
                                                                {{ old('classification') == '10' ? 'selected' : '' }}>10
                                                            </option>
                                                            <option value="12"
                                                                {{ old('classification') == '12' ? 'selected' : '' }}>12
                                                            </option>
                                                            <option value="14"
                                                                {{ old('classification') == '14' ? 'selected' : '' }}>14
                                                            </option>
                                                            <option value="16"
                                                                {{ old('classification') == '16' ? 'selected' : '' }}>16
                                                            </option>
                                                            <option value="18"
                                                                {{ old('classification') == '18' ? 'selected' : '' }}>18
                                                            </option>
                                                        </select>
                                                        <label for="classification">Classificação</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <textarea class="form-control" required name="description" id="description" placeholder="Descrição">{{ old('description', $card->description ?? '') }}</textarea>
                                                        <label for="description">Sinopse</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="number" class="form-control" required step="5"
                                                            name="duration" id="duration"
                                                            placeholder="Duração (em minutos)"
                                                            value="{{ old('duration', $card->duration ?? '') }}">
                                                        <label for="duration">Duração (em minutos)</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required name="season"
                                                            id="season" placeholder="Temporada"
                                                            value="{{ old('season', $card->season ?? '') }}">
                                                        <label for="season">Temporada</label>
                                                        @error('season')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
    {{-- Dias da semana --}}
    @foreach(['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'] as $day)
    <div class="form-check ms-2">
        <input class="form-check-input checkbox-day" type="checkbox" value="{{ $day }}" id="check{{ $day }}" name="days[]" 
            {{ in_array($day, $card->days ?? []) ? 'checked' : '' }}> {{-- Mantém o valor selecionado --}}
        <label class="form-check-label" for="check{{ $day }}">{{ ucfirst($day) }}</label>
    </div>

    <div id="horarios-{{ $day }}" class="mt-2 ms-2 {{ in_array($day, $card->days ?? []) ? '' : 'd-none' }}">
        <div class="horario-wrapper mb-3">
            @if(!empty($card->horarios[$day]))
                @foreach($card->horarios[$day] as $horario)
                <div class="d-flex align-items-center mb-2">
                    <input type="time" class="form-control me-2" name="horarios[{{ $day }}][]" value="{{ $horario }}">
                    <button type="button" class="btn btn-danger btn-remover-horario">Remover</button>
                </div>
                @endforeach
            @else
                <div class="d-flex align-items-center mb-2">
                    <input type="time" class="form-control me-2" name="horarios[{{ $day }}][]">
                    <button type="button" class="btn btn-danger btn-remover-horario">Remover</button>
                </div>
            @endif
        </div>

        <button type="button" class="btn btn-danger btn-adicionar-horario d-flex align-items-center mb-3" data-dia="{{ $day }}">
            <span class="ic--baseline-plus"></span>
            <span class="roboto-regular">Adicionar horário</span>
        </button>
    </div>
    @endforeach
</div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">

                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="false" aria-controls="collapseTwo">
                                                    Imagens da Peça
                                                </button>
                                            </h2>

                                            <div id="collapseTwo" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    {{-- Input de Imagem do Card --}}

                                                    <div class="form-floating mb-3">
                                                        <input type="file" class="form-control" required
                                                            name="img" id="img" placeholder="Imagem">
                                                        <label for="floatingInput">Imagem do Cartão</label>
                                                    </div>

                                                    {{-- Inputs de Imagens do Banner --}}

                                                    <h2 class="text-center mb-3">Imagens do Banner</h2>

                                                    <div class="form-floating mb-3">
                                                        <input type="file" class="form-control" required
                                                            name="img1" id="img1" placeholder="Imagem">
                                                        <label for="floatingInput">Imagem do Banner 1</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="file" class="form-control" required
                                                            name="img2" id="img2" placeholder="Imagem">
                                                        <label for="floatingInput">Imagem do Banner 2</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="file" class="form-control" required
                                                            name="img3" id="img3" placeholder="Imagem">
                                                        <label for="floatingInput">Imagem do Banner 3</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="file" class="form-control" required
                                                            name="img4" id="img4" placeholder="Imagem">
                                                        <label for="floatingInput">Imagem do Banner 4</label>
                                                    </div>

                                                    <div class="form-floating mb-3">
                                                        <input type="file" class="form-control" required
                                                            name="img5" id="img5" placeholder="Imagem">
                                                        <label for="floatingInput">Imagem do Banner 5</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">

                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                    aria-expanded="false" aria-controls="collapseThree">
                                                    Ficha Técnica
                                                </button>
                                            </h2>

                                            <div id="collapseThree" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required
                                                            name="texto" id="texto" placeholder="Texto"
                                                            value="{{ old('texto', $card->texto ?? '') }}">
                                                        <label for="texto">Texto(Autoria)</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required
                                                            name="elenco" id="elenco" placeholder="Elenco"
                                                            value="{{ old('elenco', $card->elenco ?? '') }}">
                                                        <label for="elenco">Elenco</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required
                                                            name="direcao" id="direcao" placeholder="Direcao"
                                                            value="{{ old('direcao', $card->direcao ?? '') }}">
                                                        <label for="direcao">Direção</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required
                                                            name="figurino" id="figurino" placeholder="Figurino"
                                                            value="{{ old('figurino', $card->figurino ?? '') }}">
                                                        <label for="figurino">Figurino</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required
                                                            name="cenografia" id="cenografia" placeholder="Cenografia"
                                                            value="{{ old('cenografia', $card->cenografia ?? '') }}">
                                                        <label for="cenografia">Cenografia</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required
                                                            name="iluminacao" id="iluminacao" placeholder="Iluminação"
                                                            value="{{ old('iluminacao', $card->iluminacao ?? '') }}">
                                                        <label for="iliminacao">Iluminação</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required
                                                            name="sonorizacao" id="sonorizacao" placeholder="Sonoriação"
                                                            value="{{ old('sonorizacao', $card->sonorizacao ?? '') }}">
                                                        <label for="sonorizacao">Sonorização</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" required
                                                            name="producao" id="producao" placeholder="Produção"
                                                            value="{{ old('producao', $card->producao ?? '') }}">
                                                        <label for="producao">Produção</label>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                        <div class="accordion-item">

                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                    aria-expanded="false" aria-controls="collapseFour">
                                                    Opcionais (Ficha Técnica)
                                                </button>
                                            </h2>

                                            <div id="collapseFour" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="costureira"
                                                            id="costureira" placeholder="Costureira"
                                                            value="{{ old('costureira', $card->costureira ?? '') }}">
                                                        <label for="costureira">Costureira</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control"
                                                            name="assistente_cenografia" id="assistente_cenografia"
                                                            placeholder="Assistente_De_Cenografia"
                                                            value="{{ old('assistente_cenografia', $card->assistente_cenografia ?? '') }}">
                                                        <label for="assistente_cenografia">Assistente de Cenografía</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="cenotecnico"
                                                            id="cenotecnico" placeholder="Cenotécnico"
                                                            value="{{ old('cenotecnico', $card->cenotecnico ?? '') }}">
                                                        <label for="cenotecnico">Cenotécnico</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control"
                                                            name="consultoria_design" id="consultoria_design"
                                                            placeholder="Consultoria_Design"
                                                            value="{{ old('consultoria_design', $card->consultoria_design ?? '') }}">
                                                        <label for="consultoria_design">Consultoria De Design</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="co_producao"
                                                            id="co_producao" placeholder="Co_produção"
                                                            value="{{ old('co_producao', $card->co_producao ?? '') }}">
                                                        <label for="co_producao">Co-produção</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="agradecimentos"
                                                            id="agradecimentos" placeholder="Agradecimentos"
                                                            value="{{ old('agradecimentos', $card->agradecimentos ?? '') }}">
                                                        <label for="agradecimentos">Agradecimentos</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
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
                                    <th scope="col">Img</th>
                                    <th scope="col">Visibilidade</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cards as $card)
                                    <tr>
                                        <td>{{ $card->name }}</td>
                                        <td>{{ $card->img }}</td>
                                        <td>
                                            <!-- Formulário para atualizar a visibilidade -->
                                            <form action="/cards/{{ $card->id }}/visibility" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="visible"
                                                        id="visible{{ $card->id }}"
                                                        {{ $card->visible ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="visible{{ $card->id }}">Visível</label>
                                                </div>
                                                <button type="submit" class="btn btn-primary mt-2">Atualizar</button>
                                            </form>
                                        </td>
                                        <td>
                                            <!-- Botão de editar que abre o modal -->
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#editCardModal{{ $card->id }}">
                                                Editar
                                            </button>

                                            <!-- Modal de edição para cada card -->
                                            <div class="modal fade" id="editCardModal{{ $card->id }}" tabindex="-1"
                                                aria-labelledby="editCardModalLabel{{ $card->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editCardModalLabel{{ $card->id }}">Editar Card
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="/cards/{{ $card->id }}" method="POST"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="name" id="name{{ $card->id }}"
                                                                        placeholder="Nome" value="{{ $card->name }}">
                                                                    <label for="floatingInput">Nome</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="file" class="form-control"
                                                                        name="img" id="img{{ $card->id }}"
                                                                        placeholder="Imagem">
                                                                    <label for="floatingInput">Imagem</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="url" class="form-control"
                                                                        name="ticket_link" id="ticket_link"
                                                                        placeholder="Link para compra de ingressos"
                                                                        value="{{ $card->ticket_link }}">
                                                                    <label for="floatingInput">Link para compra de
                                                                        ingressos</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <select class="form-select" name="classification"
                                                                        id="classification{{ $card->id }}">
                                                                        <option value="Livre"
                                                                            {{ $card->classification == 'Livre' ? 'selected' : '' }}>
                                                                            Livre</option>
                                                                        <option value="10"
                                                                            {{ $card->classification == '10' ? 'selected' : '' }}>
                                                                            10</option>
                                                                        <option value="12"
                                                                            {{ $card->classification == '12' ? 'selected' : '' }}>
                                                                            12</option>
                                                                        <option value="14"
                                                                            {{ $card->classification == '14' ? 'selected' : '' }}>
                                                                            14</option>
                                                                        <option value="16"
                                                                            {{ $card->classification == '16' ? 'selected' : '' }}>
                                                                            16</option>
                                                                        <option value="18"
                                                                            {{ $card->classification == '18' ? 'selected' : '' }}>
                                                                            18</option>
                                                                    </select>
                                                                    <label for="classification">Classificação</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <textarea class="form-control" name="description" id="description" placeholder="Descrição">{{ $card->description }}</textarea>
                                                                    <label for="description">Descrição</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="duration" id="duration"
                                                                        placeholder="Duração"
                                                                        value="{{ $card->duration }}">
                                                                    <label for="duration">Duração</label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="season">Temporada</label>
                                                                    <input type="text" name="season" id="season"
                                                                        class="form-control"
                                                                        value="{{ old('season', $card->season) }}"
                                                                        placeholder="Selecione o período">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="days">Dias da Semana</label>
                                                                    <select name="days[]" id="days{{ $card->id }}"
                                                                        class="form-control" multiple>
                                                                        @foreach (['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sabado'] as $day)
                                                                            <option value="{{ $day }}"
                                                                                {{ is_array($card->days) && in_array($day, $card->days) ? 'selected' : '' }}>
                                                                                {{ ucfirst($day) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="texto" id="texto" placeholder="Texto"
                                                                        value="{{ $card->texto }}">
                                                                    <label for="texto">Texto(Autoria)</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="elenco" id="elenco"
                                                                        placeholder="Elenco" value="{{ $card->elenco }}">
                                                                    <label for="elenco">Elenco</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="direcao" id="direcao"
                                                                        placeholder="Direcao"
                                                                        value="{{ $card->direcao }}">
                                                                    <label for="direcao">Direção</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="figurino" id="figurino"
                                                                        placeholder="Figurino"
                                                                        value="{{ $card->figurino }}">
                                                                    <label for="figurino">Figurino</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="cenografia" id="cenografia"
                                                                        placeholder="Cenografia"
                                                                        value="{{ $card->cenografia }}">
                                                                    <label for="cenografia">Cenografia</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="iluminacao" id="iluminacao"
                                                                        placeholder="Iluminação"
                                                                        value="{{ $card->iluminacao }}">
                                                                    <label for="iliminacao">Iluminação</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="sonorizacao" id="sonorizacao"
                                                                        placeholder="Sonoriação"
                                                                        value="{{ $card->sonorizacao }}">
                                                                    <label for="sonorizacao">Sonorização</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="producao" id="producao"
                                                                        placeholder="Produção"
                                                                        value="{{ $card->producao }}">
                                                                    <label for="producao">Produção</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="costureira" id="costureira"
                                                                        placeholder="Costureira"
                                                                        value="{{ $card->costureira }}">
                                                                    <label for="costureira">Costureira</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="assistente_cenografia"
                                                                        id="assistente_cenografia"
                                                                        placeholder="Assistente_De_Cenografia"
                                                                        value="{{ $card->assistente_cenografia }}">
                                                                    <label for="assistente_cenografia">Assistente de
                                                                        Cenografía</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="cenotecnico" id="cenotecnico"
                                                                        placeholder="Cenotécnico"
                                                                        value="{{ $card->cenotecnico }}">
                                                                    <label for="cenotecnico">Cenotécnico</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="consultoria_design" id="consultoria_design"
                                                                        placeholder="Consultoria_Design"
                                                                        value="{{ $card->consultoria_design }}">
                                                                    <label for="consultoria_design">Consultoria De
                                                                        Design</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="co_producao" id="co_producao"
                                                                        placeholder="Co_produção"
                                                                        value="{{ $card->co_producao }}">
                                                                    <label for="co_producao">Co-produção</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control"
                                                                        name="agradecimentos" id="agradecimentos"
                                                                        placeholder="Agradecimentos"
                                                                        value="{{ $card->agradecimentos }}">
                                                                    <label for="agradecimentos">Agradecimentos</label>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Salvar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Formulário de exclusão -->
                                            <form action="/cards/{{ $card->id }}" method="POST"
                                                onsubmit="return confirm('Você tem certeza de que deseja prosseguir com a exclusão do card?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger delete-button">Excluir</button>
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

    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
    </a>

    @stack('scripts')

@endsection

<script>

// Script dos Inputs de Sessões de Apresentação
        
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar ou ocultar o campo de horário quando marcar o checkbox
    document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let dia = this.value;
            let horariosDiv = document.getElementById('horarios-' + dia);

            if (this.checked) {
                horariosDiv.classList.remove('d-none');
            } else {
                horariosDiv.classList.add('d-none');
                horariosDiv.querySelector('.horario-wrapper'); // .innerHTML = ''; // Remove os horários quando desmarcar o checkbox
            }
        });
    });

    // Função para adicionar eventos de remover horário
    function adicionarEventoRemoverHorario(botao) {
        botao.addEventListener('click', function() {
            this.parentElement.remove(); // Remove o div que contém o input de horário
        });
    }

    // Adicionar mais horários
    document.querySelectorAll('.btn-adicionar-horario').forEach(function(button) {
        button.addEventListener('click', function() {
            let dia = this.getAttribute('data-dia');
            let horariosWrapper = document.querySelector('#horarios-' + dia + ' .horario-wrapper');
            
            // Cria um novo div para o horário e o botão de remover
            let horarioDiv = document.createElement('div');
            horarioDiv.classList.add('d-flex', 'align-items-center', 'mb-2');

            // Cria o input de horário
            let novoHorario = document.createElement('input');
            novoHorario.type = 'time';
            novoHorario.name = 'horarios[' + dia + '][]';
            novoHorario.classList.add('form-control', 'me-2');

            // Cria o botão de remover
            let botaoRemover = document.createElement('button');
            botaoRemover.type = 'button';
            botaoRemover.classList.add('btn', 'btn-danger', 'btn-remover-horario');
            botaoRemover.textContent = 'Remover';

            // Adiciona o input e o botão de remover ao div
            horarioDiv.appendChild(novoHorario);
            horarioDiv.appendChild(botaoRemover);

            // Adiciona o novo div ao wrapper
            horariosWrapper.appendChild(horarioDiv);

            // Adiciona o evento ao botão de remover
            adicionarEventoRemoverHorario(botaoRemover);
        });
    });

    // Adiciona o evento de remover horário aos botões já existentes
    document.querySelectorAll('.btn-remover-horario').forEach(function(button) {
        adicionarEventoRemoverHorario(button);
    });
});


</script>

@section('scripts')
    <script>
        flatpickr("#season", {
            mode: "range",
            dateFormat: "d/m/Y",
            allowInput: true
        });

        $(document).ready(function() {
            $('#card-form').submit(function(e) {
                var days = $('#days').val();
                if (days.length === 0) {
                    alert('Você deve selecionar pelo menos um dia da semana.');
                    e.preventDefault();
                }
            });
        });

    </script>
@endsection
