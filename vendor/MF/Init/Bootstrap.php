<?php
namespace MF\Init;
abstract class Bootstrap{
    private $routes;

    public function __construct(){
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    public function getRoutes(){
        return $this->routes;
    }

    public function setRoutes(array $routes){
        $this->routes = $routes;
    }

    abstract protected function initRoutes();

    protected function run($url){
        foreach($this->getRoutes() as $path => $route){
            if ($url == $route['route']) {
                $class = "App\\Controllers\\".ucfirst($route['controller']);
                $controller = new $class;

                $action = $route['action'];
                
                $controller->$action();
            }
        }
    }

    // Recupera todos os dados do meu servidor, aqui especifícamente a página requisitada pelo cliente
    protected function getUrl(){
        // Transforma o caminho da pagina requisitada /page em um [path] => / e com a constante PHP_URL_PATH => /page
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}