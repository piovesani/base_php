<?php

namespace App\Controllers\Pages;

use \App\Utils\View;

class Page{
    /**
     * Método responsável por reinderizar o topo da página
     * @return string
     */
    private static function getHeader(){
        return View::render('pages/components/header');
    }

    /**
     * Método responsável por reinderizar o footer da página
     * @return string
     */
    private static function getFooter(){
        return View::render('pages/components/footer');
    }

    /**
     * Método responsável por retornar o conteúdo (view) da nossa page
     * @return string
     */
     public static function getPage($title, $content){
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
     }
}

?>