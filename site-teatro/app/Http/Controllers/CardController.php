<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\CardImage;
use App\Models\CardHorario;
use Carbon\Carbon;

class CardController extends Controller
{
    // Exibe a lista de cards
    public function index()
    {
        $cards = Card::all();
        return view('adm.list', compact('cards'));
    }

    // Exibe o formulário de criação de novo card
    public function create()
    {
        return view('adm.create');
    }

    // Armazena um novo card no banco de dados
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'img1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',          
            'img2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'img3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'img4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'img5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ticket_link' => 'nullable|url',
            'classification' => 'required|string',
            'description' => 'nullable|string',
            'duration' => 'nullable|string',
            'season' => 'required|string',
            'days' => 'required|array|min:1|in:domingo,segunda,terça,quarta,quinta,sexta,sábado',
            'horarios' => 'required|array', 
            'texto' => 'required|string|max:255',
            'elenco' => 'required|string|max:255',
            'direcao' => 'required|string|max:255',
            'figurino' => 'required|string|max:255',
            'cenografia' => 'required|string|max:255',
            'iluminacao' => 'required|string|max:255',
            'sonorizacao' => 'required|string|max:255',
            'producao' => 'required|string|max:255',
            'costureira' => 'nullable|string|max:255',
            'assistente_cenografia' => 'nullable|string|max:255',
            'cenotecnico' => 'nullable|string|max:255',
            'consultoria_design' => 'nullable|string|max:255',
            'co_producao' => 'nullable|string|max:255',
            'agradecimentos' => 'nullable|string',
        ]);

        // Separar o intervalo de datas
        $season = explode(' to ', $request->input('season'));
        $season_start = isset($season[0]) ? Carbon::createFromFormat('d/m/Y', $season[0])->format('Y-m-d') : null;
        $season_end = isset($season[1]) ? Carbon::createFromFormat('d/m/Y', $season[1])->format('Y-m-d') : null;

        // Criação do card
        $card = new Card();
        $card->name = $request->name;
        $card->season_start = $season_start;
        $card->season_end = $season_end;
        $card->days = implode(',', $request->days ?? []);
        $card->ticket_link = $request->ticket_link;
        $card->classification = $request->classification;
        $card->description = $request->description;
        $card->duration = $request->duration;
        $card->texto = $request->texto;
        $card->elenco = $request->elenco;
        $card->direcao = $request->direcao;
        $card->figurino = $request->figurino;
        $card->cenografia = $request->cenografia;
        $card->iluminacao = $request->iluminacao;
        $card->sonorizacao = $request->sonorizacao;
        $card->producao = $request->producao;
        $card->costureira = $request->costureira;
        $card->assistente_cenografia = $request->assistente_cenografia;
        $card->cenotecnico = $request->cenotecnico;
        $card->consultoria_design = $request->consultoria_design;
        $card->co_producao = $request->co_producao;
        $card->agradecimentos = $request->agradecimentos;

        // Upload da imagem de capa
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $requestImg = $request->file('img');
            $extension = $requestImg->extension();
            $imgName = md5($requestImg->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImg->move(public_path('img/cards'), $imgName);
            $card->img = $imgName;
        }

        $card->save(); // Salva o card no banco de dados

        // Upload de imagens adicionais
        $images = ['img1', 'img2', 'img3', 'img4', 'img5'];
        foreach ($images as $image) {
            if ($request->hasFile($image) && $request->file($image)->isValid()) {
                $requestImg = $request->file($image);
                $extension = $requestImg->extension();
                $imgName = md5($requestImg->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestImg->move(public_path('img/cards'), $imgName);

                // Salvar a imagem na tabela card_images
                $cardImage = new CardImage();
                $cardImage->card_id = $card->id;
                $cardImage->image_path = $imgName;
                $cardImage->save();
            }
        }

        // Salvar dias e horários na tabela card_horarios
        if ($request->has('horarios')) {
            foreach ($request->horarios as $dia => $horarios) {
                foreach ($horarios as $horario) {
                    if (!empty($horario)) {
                        // Criar novo registro de horário
                        $cardHorario = new CardHorario();
                        $cardHorario->card_id = $card->id;
                        $cardHorario->dia = $dia;
                        $cardHorario->horario = $horario;
                        $cardHorario->save();
                    }
                }
            }
        }

        return redirect('/cards')->with('success', 'Card cadastrado com sucesso');
    }

    // Atualiza a visibilidade de um card
    public function updateVisibility(Request $request, $id)
    {
        $card = Card::findOrFail($id); // Encontra o card pelo ID
        $card->visible = $request->has('visible'); // Atualiza a visibilidade do card
        $card->save(); // Salva as mudanças no banco de dados

        return redirect('/cards')->with('success', 'Visibilidade do card atualizada com sucesso'); // Redireciona com uma mensagem de sucesso
    }

    public function showHomepage()
{
    // Obtém todos os cards disponíveis
    $cards = Card::all();
    
    // Retorna a view da página inicial com os cards
    return view('dashboard.home', compact('cards'));
}


public function show($id)
{
    // Carrega o card junto com as imagens associadas
    $card = Card::with('images')->findOrFail($id);

    // Converte a string de dias em um array, se a coluna days existir e não estiver vazia
    $daysArray = [];
    if ($card->days) {
        $daysArray = explode(',', $card->days);
    }

    // Carrega os horários associados ao card e agrupa por dia
    $horarios = CardHorario::where('card_id', $card->id)
                ->orderBy('dia') // Ordena por dia da semana
                ->get()
                ->groupBy('dia'); // Agrupa por dia

    // Retorna a view com os dados do card, dias e horários
    return view('dashboard.details', compact('card', 'daysArray', 'horarios'));
}

    // Exibe o formulário de edição de um card
    public function edit($id)
    {
        $card = Card::findOrFail($id);

            // Definindo os dias da semana
    $days = ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'];

        return view('adm.edit', compact('card'));
    }

    // Atualiza os dados de um card no banco de dados
    public function update(Request $request, $id)
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
        'days' => 'required|array|min:1|in:domingo,segunda,terça,quarta,quinta,sexta,sábado',
        'horarios' => 'required|array',
    ]);

    // Encontra o card existente
    $card = Card::findOrFail($id);



    // Atualiza a temporada
// Divide a string de temporada em datas
    $seasonDates = explode(' a ', $request->season);
    
    // Formata as datas
    $seasonStart = \Carbon\Carbon::createFromFormat('d/m/Y', trim($seasonDates[0]))->toDateString();
    $seasonEnd = \Carbon\Carbon::createFromFormat('d/m/Y', trim($seasonDates[1]))->toDateString();
    
        // Atualiza os campos
        $card->name = $request->name;
        $card->ticket_link = $request->ticket_link;
        $card->classification = $request->classification;
        $card->description = $request->description;
        $card->duration = $request->duration;
        $card->season_start = $seasonStart;
        $card->season_end = $seasonEnd;


    // Atualiza os dias
    $card->days = implode(',', $request->days);

    // Verifica se uma nova imagem de capa foi enviada
    if ($request->hasFile('img') && $request->file('img')->isValid()) {
        $imgName = md5($request->file('img')->getClientOriginalName() . strtotime("now")) . '.' . $request->img->extension();
        $request->file('img')->move(public_path('img/cards'), $imgName);
        $card->img = $imgName;
    }

    $card->save(); // Salva as atualizações

    // Atualiza os horários
    CardHorario::where('card_id', $card->id)->delete(); // Remove os horários antigos
    foreach ($request->horarios as $dia => $horarios) {
        foreach ($horarios as $horario) {
            if (!empty($horario)) {
                $cardHorario = new CardHorario();
                $cardHorario->card_id = $card->id;
                $cardHorario->dia = $dia;
                $cardHorario->horario = $horario;
                $cardHorario->save();
            }
        }
    }

    return redirect()->route('cards.show', $card->id)->with('success', 'Card atualizado com sucesso!');
}

    // Exclui um card
    public function destroy($id)
    {
        $card = Card::findOrFail($id);

        // Excluir imagens associadas
        if ($card->img) {
            $imagePath = public_path('img/cards/' . $card->img);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Excluir imagens adicionais
        foreach ($card->images as $image) {
            $imagePath = public_path('img/cards/' . $image->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $image->delete();
        }

        // Excluir horários associados
        $card->horarios()->delete();

        // Excluir o card
        $card->delete();

        return redirect('/cards')->with('success', 'Card excluído com sucesso');
    }
}
