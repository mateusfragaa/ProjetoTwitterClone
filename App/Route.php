<?php
// Para controlar a rota dos usuários recuperando a url
namespace App;
//importando a classe contida no diretório MF
use MF\Init\Bootstrap;

class Route extends Bootstrap{

    // Possui as rotas da aplicação
    protected function initRoutes(){
        // Volta a aplicação para a rota raiz a index.php
        $routes['home'] = array(
            'route' => '/',
            'controller' => 'indexController',
            'action' => 'index'
        );

        $routes['inscreverse'] = array(
            'route' => '/inscreverse',
            'controller' => 'indexController',
            'action' => 'inscreverse'
        );

        $routes['registrar'] = array(
            'route' => '/registrar',
            'controller' => 'indexController',
            'action' => 'registrar'
        );

        $routes['autenticar'] = array(
            'route' => '/autenticar',
            'controller' => 'AuthController',
            'action' => 'autenticar'
        );

        $routes['timeline'] = array(
            'route' => '/timeline',
            'controller' => 'AppController',
            'action' => 'timeline'
        );

        $routes['sair'] = array(
            'route' => '/sair',
            'controller' => 'AuthController',
            'action' => 'sair'
        );

        $routes['tweet'] = array(
            'route' => '/tweet',
            'controller' => 'AppController',
            'action' => 'tweet'
        );

        $routes['quem_seguir'] = array(
            'route' => '/quem_seguir',
            'controller' => 'AppController',
            'action' => 'quemSeguir'
        );
        $routes['acao'] = array(
            'route' => '/acao',
            'controller' => 'AppController',
            'action' => 'acao'
        );
        $routes['deleteTweet'] = array(
            'route' => '/deleteTweet',
            'controller' => 'AppController',
            'action' => 'deleteTweet'
        );
       
        $this->setRoutes($routes);
    }
}