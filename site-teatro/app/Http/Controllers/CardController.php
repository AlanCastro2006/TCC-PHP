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
        // Recuperar o card e seus horários relacionados
        $card = Card::with('horarios')->findOrFail($id);

        // Formatar os horários em um array associativo
        $horarios = [];
        foreach ($card->horarios as $horario) {
            $dia = $horario->dia;
            if (!isset($horarios[$dia])) {
                $horarios[$dia] = [];
            }
            $horarios[$dia][] = $horario->horario;
        }

        // Converte a string de dias em um array, se a coluna days existir e não estiver vazia
        $daysArray = [];
        if ($card->days) {
            $daysArray = explode(',', $card->days);
        }

        // Definindo os dias da semana
        $days = ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'];

        // Retornar a view de edição com os dados do card, horários e dias
        return view('adm.list', compact('card', 'horarios', 'days', 'daysArray'));
    }

    // Atualiza os dados de um card no banco de dados
    public function update(Request $request, $id)
    {
        // Encontrar o card pelo ID
        $card = Card::findOrFail($id);

        // Validação dos campos 'days' e 'horarios'
        $validatedData = $request->validate([
            'days_editar' => 'required|array',
            'days_editar.*' => 'string',
            'horarios_editar' => 'required|array',
            'horarios_editar.*' => 'array',
            'horarios_editar.*.*' => 'date_format:H:i',
        ]);

        // Atualizar os dias no card
        $card->days = implode(',', $validatedData['days_editar']);
        $card->save();

        // Remover os horários atuais
        $card->horarios()->delete();

        // Adicionar os novos dias e horários
        foreach ($validatedData['days_editar'] as $day) {
            if (isset($validatedData['horarios_editar'][$day])) {
                foreach ($validatedData['horarios_editar'][$day] as $horario) {
                    $card->horarios()->create([
                        'dia' => $day,
                        'horario' => $horario,
                    ]);
                }
            }
        }

        // Atualizar a imagem de capa
        if ($request->hasFile('img')) {
            // Excluir a imagem de capa antiga
            $oldCoverImagePath = public_path('img/cards/' . $card->img);
            if (file_exists($oldCoverImagePath)) {
                unlink($oldCoverImagePath); // Exclui a imagem de capa antiga do servidor
            }

            // Salvar a nova imagem de capa
            $coverFile = $request->file('img');
            $coverFilename = time() . '_cover_' . $coverFile->getClientOriginalName();
            $coverFile->move(public_path('img/cards'), $coverFilename); // Move a nova imagem de capa para a pasta correta
            $card->img = $coverFilename; // Atualiza o campo `img` no banco de dados
        }

        // Obter as imagens antigas do banner
        $oldBannerImages = $card->images()->get();

        // Excluir as imagens antigas do banner
        foreach ($oldBannerImages as $image) {
            $imagePath = public_path('img/cards/' . $image->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Exclui a imagem do servidor
            }
            $image->delete(); // Remove a entrada do banco de dados
        }

        // Salvar as novas imagens do banner
        $newImages = [];
        for ($i = 1; $i <= 5; $i++) {
            if ($request->hasFile('img' . $i)) {
                $file = $request->file('img' . $i);
                $filename = time() . '_banner_' . $i . '_' . $file->getClientOriginalName();
                $file->move(public_path('img/cards'), $filename); // Move a imagem do banner para a pasta correta
                $newImages[] = ['card_id' => $id, 'image_path' => $filename];
            }
        }

        // Adiciona as novas imagens do banner ao banco de dados
        if (!empty($newImages)) {
            $card->images()->insert($newImages);
        }

        // Salva as alterações no card
        $card->save();

        return redirect('/cards')->with('success', 'Card atualizado com sucesso'); // Redireciona com uma mensagem de sucesso
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

    // Atualiza a visibilidade de um card
    public function updateVisibility(Request $request, $id)
    {
        $card = Card::findOrFail($id); // Encontra o card pelo ID
        $card->visible = $request->has('visible'); // Atualiza a visibilidade do card
        $card->save(); // Salva as mudanças no banco de dados
        return redirect('/cards')->with('success', 'Visibilidade do card atualizada com sucesso'); // Redireciona com uma mensagem de sucesso
    }
}
