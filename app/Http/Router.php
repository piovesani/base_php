<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router{
    /**
     * URL completa do projeto (raiz)
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de request
     * @var Request
     */
    private $request;

    /**
     * Método responsável por iniciar a classe
     * @param string $url
     */
    public function __construct($url){
        $this->request = new Request();
        $this->url     = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por definir o prefixo das rotas
     */
    private function setPrefix(){
        //informações da url atual
        $parseUrl = parse_url($this->url);

        //define o prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método resposável por dadicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    public function addRoute($method, $route, $params = []){
        //validação de parâmetros
        foreach($params as $key=>$value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //variaveis da rota
        $params['variables'] = [];

        //padrão de validação das variaveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        //padrão de vaidação da URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

        //adicionando a rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;

    }

    /**
     * Método resposável por definir uma rota tipo GET
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = []){
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Método resposável por definir uma rota tipo POST
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = []){
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Método resposável por definir uma rota tipo PUT
     * @param string $route
     * @param array $params
     */
    public function put($route, $params = []){
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método resposável por definir uma rota tipo DELETE
     * @param string $route
     * @param array $params
     */
    public function delete($route, $params = []){
        return $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Método responsável por retornar URI sem prefixo
     * @return string
     */
    private function getUri(){
        //uri da request
        $uri = $this->request->getUri();
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        return end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     * @return array
     */
    private function getRoute(){
        //URI
        $uri = $this->getUri();

        $httpMethod = $this->request->getHttpMethod();

        foreach($this->routes as $patternRoute=>$methods){

            //verifica se a uri bate com o padrão
            if(preg_match($patternRoute, $uri, $matches)){

                //verifica o metodo
                if(isset($methods[$httpMethod])){

                    //remove a primeira posição
                    unset($matches[0]);

                    //variaveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    //retorno dos parâmetros da rota
                    return $methods[$httpMethod];
                }

                throw new Exception('Método não permitido', 405);
            }
        }
        throw new Exception('Url não encontrada', 404);
    }

    /**
     * Método resposável por executar a rota atual
     * @return Response
     */
    public function run(){
        try{
            $route = $this->getRoute();


            if(!isset($route['controller'])){
                throw new Exception('Url não pode ser processada', 500);
            }

            $args = [];
            
            //reflection
            $reflection = new ReflectionFunction($route['controller']);
            
            foreach($reflection->getParameters() as $parameter){
               
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
                
            }

          
            //retorna a execução da função
            return call_user_func_array($route['controller'], $args);
        }
        catch(Exception $e){
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}

?>