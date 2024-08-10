<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card; // Importa o model Card

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
        return view('adm/list', ['cards' => $cards]); // Retorna a view com os cards
    }

    // Armazena um novo card no banco de dados
    public function store(Request $request)
    {
        $card = new Card(); // Cria uma nova instância de Card

        // Define os atributos do card com os valores do formulário
        $card->name = $request->name;
        $card->date = $request->date;
        $card->local = $request->local;
        $card->ticket_link = $request->ticket_link; // Adiciona o link de ingressos

        // Upload de imagem
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $requestImg = $request->img; // Armazena o arquivo da imagem
            $extension = $requestImg->extension(); // Obtém a extensão do arquivo
            $imgName = md5($requestImg->getClientOriginalName() . strtotime("now")) . "." . $extension; // Gera um nome único
            $request->img->move(public_path('img/cards'), $imgName); // Move a imagem para a pasta pública
            $card->img = $imgName; // Define o nome da imagem no card
        }

        $card->save(); // Salva o card no banco de dados

        return redirect('/cards')->with('success', 'Card cadastrado com sucesso'); // Redireciona com uma mensagem de sucesso
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

    // Mostra a tela de edição para um card específico
    public function show($id)
    {
        $card = Card::findOrFail($id); // Encontra o card pelo ID
        return view('adm/form', ["card" => $card]); // Retorna a view com o card encontrado
    }

    // Edita os dados de um card existente
    public function edit($id)
    {
        $card = Card::findOrFail($id); // Encontra o card pelo ID
        return view('adm/edit', compact('card')); // Retorna a view de edição com o card
    }

    // Atualiza os dados de um card existente no banco de dados
    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id); // Encontra o card pelo ID

        // Atualiza os atributos do card com os novos valores do formulário
        $card->name = $request->name;
        $card->date = $request->date;
        $card->local = $request->local;
        $card->ticket_link = $request->ticket_link; // Atualiza o link de ingressos

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
