<?php
namespace App\Controllers;
//recursos do framework
use MF\Controller\Action;
use MF\Model\Container;



class IndexController extends Action{
    // Esse métodos representam as actions de Route
    // Todos os requires partem do index.php
	public function index(){
       
        $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
        $this->render('index');
    }

    public function inscreverse(){
        // Para quando o inscreverse.phtml for resderizado de primeira não gerar erro de chave de array indefinida
        $this->view->usuario = array(
                'nome' => "",
                'email' => "",
                'senha' => ""
            );

        $this->view->erroCadastro = false;
        $this->render('inscreverse');
    }

    public function registrar(){
        // Receber dados do formulário
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = md5($_POST['senha']);

        $usuario = Container::getModel('Usuario');
        $usuario->__set('nome',$nome);
        $usuario->__set('email', $email);
        $usuario->__set('senha', $senha);

        if ( $usuario->validarCadastro() && count($usuario->getUsuarioEmail()) == 0) {
                $usuario->salvar();
                $this->render('cadastro');
        }else{

            // Recarrega o formulário com as informações enviadas para não precisar ser repreenchido
            $this->view->usuario = array(
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'senha' => $_POST['senha']
            );

            $this->view->erroCadastro = true;
            $this->render('inscreverse');
        }
    }       
}


   

