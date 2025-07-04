<?php 
namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action{

	public function autenticar(){
		$usuario = Container::getModel('Usuario');
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));

		$usuario->autenticar();
		
		//Controla a autenticação, se o id e o nome está vazio e porque não existe ainda no banco e deve ser cadastrado
		if (!empty($usuario->__get('id')) && !empty($usuario->__get('nome'))) { // Autenticado
			
			session_start();
			$_SESSION['id'] = $usuario->__get('id');
			$_SESSION['nome'] = $usuario->__get('nome');

			header("Location: /timeline");
		}else{ // Erro
			header("Location: /?login=erro");
			exit();
		}
	}

	public function sair(){
		session_start();
		session_destroy();
		header("Location: /");
	}
}