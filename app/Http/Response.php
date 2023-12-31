<?php

namespace App\Http;

class Response
{

    /**
     * Código do status HTTP
     */
    private $httpCode = 200;

    /**
     * Cabeçalho do response
     *@var array
     */
    private $headers = [];

    /**
     * tipo de conteúdo que está sendo retornado
     *@var string
     */
    private $contentType = 'text/html';

    /**
     * Conteúdo do response
     *@var mixed
     */
    private $content;

    /** Método responsável por iniciar a classe e definir os valores 
     * @var integer $httpCode
     * @var mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Método responsável por alterar o contentType do response
     */
    public function setContentType($contentType){
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Método responsável por adicionar um registro no cabeçalho do response
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por enviar os headers para o navegador
     */
    private function sendHeaders(){
        //status
        http_response_code($this->httpCode);

        //enviar headers
        foreach($this->headers as $key=>$value){
            header($key.': '.$value);
        }
    }

    /**
     * Método responsável por enviar a resposta para o usuário
     */
    public function sendResponse(){
        //envia os headers
        $this->sendHeaders();

        //imprime o conteúdo
        switch($this->contentType){
            case 'text/html';
            echo $this->content;
            exit;
        }
    }
}
