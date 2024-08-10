<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Chamando o Model Card
use App\Models\Card;

class CardController extends Controller
{
    public function showHomePage()
    {
        $cards = Card::where('visible', true)->get(); // Seleciona apenas os cards visíveis
    
        return view('dashboard/home', compact('cards'));
    }
    //Renderiza a lista de cards com um método que tráz os valores da tabela através do index
    public function index(){

        //Seleciona todos os "cards" e armazena no array cards
        $cards = Card::all();

        // Retorna a view 'adm/list' com o array 'cards'
        return view('adm/list',['cards' => $cards]);

    }//fim do index

    public function store(Request $request){

        // Cria uma nova instância de Card
        $card = new Card();

        // Define os atributos do card com os valores da requisição
        $card->name = $request->name;
        $card->date = $request->date;
        $card->local = $request->local;
        $card->ticket_link = $request->ticket_link; // Adiciona o link

        //Image Upload
        // Verifica se o request contém um arquivo 'img' e se ele é válido
        if($request->hasFile('img') && $request->file('img')->isValid()) {

            // Armazena o arquivo da imagem na variável $requestImg
            $requestImg = $request->img;

            // Obtém a extensão do arquivo da imagem
            $extension = $requestImg->extension();

            // Gera um nome único para a imagem utilizando a função md5 e a timestamp atual
            $imgName = md5($requestImg->getClientOriginalName() . strtotime("now")) . "." . $extension;

            // Move a imagem para a pasta pública 'img/cards' com o novo nome gerado
            $request->img->move(public_path('img/cards'), $imgName);

            // Atribui o novo nome da imagem à propriedade 'img' do modelo Card
            $card->img = $imgName;
        }
        
        // Salva o card no banco de dados
        $card->save();

        // Retorna a view 'adm/list'
        return redirect('/cards')->with('success', 'Card Cadastrado com sucesso');

    }//Fim da store

    public function destroy($id) {
        // Encontra o card no banco de dados ou falha se não encontrar
        $card = Card::findOrFail($id);
    
        // Exclui a imagem associada ao card se ela existir
        if ($card->img) {
            // Monta o caminho completo para o arquivo de imagem
            $imagePath = public_path('img/cards/' . $card->img);
    
            // Verifica se o arquivo existe e então exclui
            if (file_exists($imagePath)) {
                unlink($imagePath); // Exclui o arquivo
            }
        }
    
        // Exclui o card do banco de dados
        $card->delete();
    
        // Redireciona para a página dos cards
        return redirect('/cards');
    }//Fim do destroy
    

    
    //Função show - Mostra a tela para atualizar os dados do card já cadastrado
    public function show($id) {

        // findOrFail - Procure esses dados no Banco de Dados, se não achar, de erro/falha 
        // Retornar o tipo do Banco de Dados
        $card = Card::findOrFail($id);

        // Retorna a view form com os dados do card encontrado no Banco de Dados
        return view('adm/form', ["card"=>$card]);

    }//Fim do show

    
    //Função edit - Edita os dados que já vieram cadastrados nas inputs pelo método show
    public function edit($id) {
        $card = Card::findOrFail($id);
        return view('adm/edit', compact('card'));
    }
    
    public function update(Request $request, $id) {
        $card = Card::findOrFail($id);
        $card->name = $request->name;
        $card->date = $request->date;
        $card->local = $request->local;
        $card->ticket_link = $request->ticket_link; // Atualiza o link
        
        // Verifica se foi enviado um novo arquivo de imagem
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            // Processo de upload de imagem (mesmo processo que você já tem)
        }
    
        $card->save();
    
        return redirect('/cards')->with('success', 'Card atualizado com sucesso');
    }//Fim do Edit

    public function updateVisibility(Request $request, $id) {
        // Busca o card pelo ID
        $card = Card::findOrFail($id);
        
        // Define o valor do campo 'visible' baseado no valor do checkbox
        $card->visible = $request->has('visible');
        
        // Salva as mudanças no banco de dados
        $card->save();
        
        // Redireciona de volta para a listagem de cards
        return redirect('/cards')->with('success', 'Visibilidade do card atualizada com sucesso');
    }

}//Fim da classe
