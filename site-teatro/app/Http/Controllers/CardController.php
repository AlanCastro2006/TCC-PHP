<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card; // Importa o model Card
use Carbon\Carbon;

class CardController extends Controller
{
    // Exibe a página inicial com todos os cards visíveis
    public function showHomePage()
    {
        $cards = Card::where('visible', true)->get(); // Seleciona apenas os cards visíveis
        return view('dashboard/home', compact('cards')); // Retorna a view com os cards
    }

    // Renderiza a lista de cards para a área administrativa
    public function index()
    {
        $cards = Card::all(); // Seleciona todos os cards
        return view('adm/list', ['cards' => $cards, 'daysArray' => $card->days ?? [],]); // Retorna a view com os cards
    }

// Armazena um novo card no banco de dados
public function store(Request $request)
{
    // Validação dos dados
    $request->validate([
        'name' => 'required|string|max:255',
        'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'ticket_link' => 'nullable|url',
        'classification' => 'required|string',
        'description' => 'nullable|string',
        'duration' => 'nullable|string',
        'season' => 'required|string',
        'days' => 'required|array|min:1|in:domingo,segunda,terça,quarta,quinta,sexta,sabado',
        // Novos campos obrigatórios
        'texto' => 'required|string|max:255',
        'elenco' => 'required|string|max:255',
        'direcao' => 'required|string|max:255',
        'figurino' => 'required|string|max:255',
        'cenografia' => 'required|string|max:255',
        'iluminacao' => 'required|string|max:255',
        'sonorizacao' => 'required|string|max:255',
        'producao' => 'required|string|max:255',
        // Novos campos opcionais
        'costureira' => 'nullable|string|max:255',
        'assistente_cenografia' => 'nullable|string|max:255',
        'cenotecnico' => 'nullable|string|max:255',
        'consultoria_design' => 'nullable|string|max:255',
        'co_producao' => 'nullable|string|max:255',
        'agradecimentos' => 'nullable|string',
    ],[
        'days.required' => 'Você deve selecionar pelo menos um dia da semana.',
        'days.min' => 'Você deve selecionar pelo menos um dia da semana.',
    ]);

    // Separar o intervalo de datas
    $season = explode(' to ', $request->input('season'));
    $season_start = isset($season[0]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $season[0])->format('Y-m-d') : null;
    $season_end = isset($season[1]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $season[1])->format('Y-m-d') : null;

    $card = new Card();

    // Preparação dos dados
    $data = $request->all();
    $data['days'] = $request->input('days', []);

    // Atribuição dos dados ao objeto Card
    $card->name = $request->name;
    $card->season_start = $season_start;
    $card->season_end = $season_end;
    $card->days = implode(',', $request->days ?? []);
    $card->ticket_link = $request->ticket_link;
    $card->classification = $request->classification;
    $card->description = $request->description;
    $card->duration = $request->duration;
    // Atribuição dos novos campos
    $card->texto = $request->texto;
    $card->elenco = $request->elenco;
    $card->direcao = $request->direcao;
    $card->figurino = $request->figurino;
    $card->cenografia = $request->cenografia;
    $card->iluminacao = $request->iluminacao;
    $card->sonorizacao = $request->sonorizacao;
    $card->producao = $request->producao;
    // Atribuição dos campos opcionais
    $card->costureira = $request->costureira;
    $card->assistente_cenografia = $request->assistente_cenografia;
    $card->cenotecnico = $request->cenotecnico;
    $card->consultoria_design = $request->consultoria_design;
    $card->co_producao = $request->co_producao;
    $card->agradecimentos = $request->agradecimentos;

    // Upload de imagem
    if ($request->hasFile('img') && $request->file('img')->isValid()) {
        $requestImg = $request->img;
        $extension = $requestImg->extension();
        $imgName = md5($requestImg->getClientOriginalName() . strtotime("now")) . "." . $extension;
        $request->img->move(public_path('img/cards'), $imgName);
        $card->img = $imgName;
    }

    $card->save(); // Salva o card no banco de dados

    return redirect('/cards')->with('success', 'Card cadastrado com sucesso');
}

    // Exclui um card do banco de dados
    public function destroy($id)
    {
        $card = Card::findOrFail($id); // Encontra o card pelo ID

        // Verifica se o card tem uma imagem associada e a exclui
        if ($card->img) {
            $imagePath = public_path('img/cards/' . $card->img); // Caminho completo da imagem
            if (file_exists($imagePath)) {
                unlink($imagePath); // Exclui o arquivo de imagem
            }
        }

        $card->delete(); // Exclui o card do banco de dados

        return redirect('/cards'); // Redireciona para a página dos cards
    }

    //Mostra a tela específica do card selecionado
    public function show($id)
    {
        $card = Card::findOrFail($id); // Encontra o card ou lança um erro 404 se não for encontrado

        // Converte a string de dias em um array para exibição
        $daysArray = explode(',', $card->days);

        return view('dashboard.details', compact('card', 'daysArray'));
    }
    

    // Edita os dados de um card existente
    public function edit($id)
    {
        $card = Card::findOrFail($id); // Encontra o card pelo ID

        // Certifique-se de que 'days' é um array
        $card->days = is_string($card->days) ? explode(',', $card->days) : $card->days;

        return view('adm/edit', ['card' => $card]); // Retorna a view de edição com o card
    }

// Atualiza os dados de um card existente no banco de dados
public function update(Request $request, $id)
{
    // Separar o intervalo de datas
    $season = explode(' to ', $request->input('season'));
    $season_start = isset($season[0]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $season[0])->format('Y-m-d') : null;
    $season_end = isset($season[1]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $season[1])->format('Y-m-d') : null;

    // Validação dos dados
    $request->validate([
        'name' => 'required|string|max:255',
        'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'ticket_link' => 'nullable|url',
        'classification' => 'required|string',
        'description' => 'nullable|string',
        'duration' => 'nullable|string',
        'season' => 'required|string',
        'days' => 'array|in:domingo,segunda,terça,quarta,quinta,sexta,sabado',
        // Novos campos obrigatórios
        'texto' => 'required|string|max:255',
        'elenco' => 'required|string|max:255',
        'direcao' => 'required|string|max:255',
        'figurino' => 'required|string|max:255',
        'cenografia' => 'required|string|max:255',
        'iluminacao' => 'required|string|max:255',
        'sonorizacao' => 'required|string|max:255',
        'producao' => 'required|string|max:255',
        // Novos campos opcionais
        'costureira' => 'nullable|string|max:255',
        'assistente_cenografia' => 'nullable|string|max:255',
        'cenotecnico' => 'nullable|string|max:255',
        'consultoria_design' => 'nullable|string|max:255',
        'co_producao' => 'nullable|string|max:255',
        'agradecimentos' => 'nullable|string'
    ]);

    $card = Card::findOrFail($id); // Encontra o card pelo ID

    // Atualiza os atributos do card com os novos valores do formulário
    $card->name = $request->name;
    $card->season_start = $season_start;
    $card->season_end = $season_end;
    $card->days = implode(',', $request->days ?? []);
    $card->ticket_link = $request->ticket_link;
    $card->classification = $request->classification;
    $card->description = $request->description;
    $card->duration = $request->duration;
    // Atualiza os novos campos
    $card->texto = $request->texto;
    $card->elenco = $request->elenco;
    $card->direcao = $request->direcao;
    $card->figurino = $request->figurino;
    $card->cenografia = $request->cenografia;
    $card->iluminacao = $request->iluminacao;
    $card->sonorizacao = $request->sonorizacao;
    $card->producao = $request->producao;
    // Atualiza os campos opcionais
    $card->costureira = $request->costureira;
    $card->assistente_cenografia = $request->assistente_cenografia;
    $card->cenotecnico = $request->cenotecnico;
    $card->consultoria_design = $request->consultoria_design;
    $card->co_producao = $request->co_producao;
    $card->agradecimentos = $request->agradecimentos;

    // Verifica se foi enviada uma nova imagem e a processa
    if ($request->hasFile('img') && $request->file('img')->isValid()) {
        // Exclui a imagem antiga, se existir
        if ($card->img) {
            $imagePath = public_path('img/cards/' . $card->img);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Exclui o arquivo de imagem antigo
            }
        }

        // Processo de upload de imagem
        $requestImg = $request->img;
        $extension = $requestImg->extension();
        $imgName = md5($requestImg->getClientOriginalName() . strtotime("now")) . "." . $extension;
        $request->img->move(public_path('img/cards'), $imgName);
        $card->img = $imgName; // Atualiza o nome da imagem no card
    }

    $card->save(); // Salva as alterações no banco de dados

    return redirect('/cards')->with('success', 'Card atualizado com sucesso');
}


    // Atualiza a visibilidade de um card
    public function updateVisibility(Request $request, $id)
    {
        $card = Card::findOrFail($id); // Encontra o card pelo ID
        $card->visible = $request->has('visible'); // Atualiza a visibilidade do card
        $card->save(); // Salva as mudanças no banco de dados

        return redirect('/cards')->with('success', 'Visibilidade do card atualizada com sucesso'); // Redireciona com uma mensagem de sucesso
    }
}
