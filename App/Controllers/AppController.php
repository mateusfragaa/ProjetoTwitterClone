<?php 
namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{

	public function timeline(){

			$this->validaAutenticacao();		
			//Recuperar todos os tweets
			$tweet = Container::getModel("Tweet");
			$tweet->__set('id_usuario', $_SESSION['id']);

			$tweets = $tweet->getAll();

    		$this->view->tweets = $tweets;

    		// Preenchemento do perfiel de usuário dinâmico
    		$usuario = Container::getModel('Usuario');
    		$usuario->__set('id', $_SESSION['id']);
    		$this->view->getNome = $usuario->getNome();
    		$this->view->totalTweets = $usuario->totalTweets();
    		$this->view->totalseguidores = $usuario->totalSeguidores();
    		$this->view->totalSeguindo = $usuario->totalSeguindo();

			$this->render("timeline");

	}

	public function tweet(){

		$this->validaAutenticacao();

			$tweet = Container::getModel('Tweet');
			$tweet->__set('tweet', $_POST['tweet']);
			$tweet->__set('id_usuario', $_SESSION['id']);
			$tweet->salvar();

			header("Location: /timeline");
		
	}

	public function deleteTweet(){

		$this->validaAutenticacao();
		$_GET['id_tweet'] = isset($_GET['id_tweet']) ? htmlspecialchars($_GET['id_tweet']) : '';
		$tweet = Container::getModel('Tweet');
		$tweet->__set('id', $_GET['id_tweet']);
		$tweet->excluirTweet();
		header('Location: /timeline');
	}

	public function validaAutenticacao(){
		// Valida se um usuário está logado
		session_start();

		if (!isset($_SESSION['id']) || empty($_SESSION['id']) && !isset($_SESSION['nome']) || empty($_SESSION['nome'])) {
			header("Location: /?login=erro");
			exit();
		}
	}

	public function quemSeguir(){
		$this->validaAutenticacao();

		$pesquisaPor = isset($_GET['pesquisaPor']) ? $_GET['pesquisaPor'] : 'all';

		$this->view->usuarios = [];

		$usuario = Container::getModel('Usuario');
		if(!empty($pesquisaPor) && $pesquisaPor != 'all'){
			$usuario->__set('nome',$pesquisaPor);
			$usuario->__set('id', $_SESSION['id']);
			$this->view->usuarios = $usuario->getAll();
		}else{ // Para começar com todos os usuários da rede
			$usuario->__set('nome', $pesquisaPor);
			$usuario->__set('id', $_SESSION['id']);
			$this->view->usuarios = $usuario->getAll();
		}
		
		$this->view->usuarios;

		// Preenchemento do perfiel de usuário dinâmico
    		$usuario = Container::getModel('Usuario');
    		$usuario->__set('id', $_SESSION['id']);
    		
    		$this->view->getNome = $usuario->getNome();
    		$this->view->totalTweets = $usuario->totalTweets();
    		$this->view->totalSeguindo = $usuario->totalSeguindo();
    		$this->view->totalseguidores = $usuario->totalSeguidores();

		$this->render('quemSeguir');
	}

	public function acao(){
		$this->validaAutenticacao();

		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
		$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);

		if ($acao == 'seguir') {
			$usuario->seguirUsuario($id_usuario_seguindo);
		}else if ( $acao == 'deixar_de_seguir' ){
			$usuario->deixarSeguirUsuario($id_usuario_seguindo);
		}

		header('Location: quem_seguir');
	}

}