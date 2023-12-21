<?php

namespace App\Utils;

class View{

    /**
     * Variaveis padrões das views
     */
    private static $vars = [];
    /**
     * Método responsável por definir os dados iniciais da classe
     * @param array $vars
     */
    public static function init($vars = []){
        self::$vars = $vars;
    }

    /**
     * Método responsável por retornar o conteúdo de uma view
     * @param string $view
     * @return string
     */
    private static function getContentView($view){
        $file = __DIR__.'/../../resources/views/'.$view.'.html';

        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável por retornar o conteúdo reinderizado de uma view
     * @param string $view
     * @param array $vars (string/numeric)
     * @return string
     */
    public static function render($view, $vars = []){
        //conteudo da view
        $contentView = self::getContentView($view);

        //merge de variaveis da view
        $vars = array_merge(self::$vars, $vars);

        //chaves do array
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);

        return str_replace($keys, array_values($vars), $contentView);
    }
}

?>