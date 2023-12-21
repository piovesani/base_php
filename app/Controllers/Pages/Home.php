<?php

namespace App\Controllers\Pages;

use \App\Utils\View;
use \App\Models\Entity\Organization;

class Home extends Page{
    /**
     * Método responsável por retornar o conteúdo (view) da nossa home
     * @return string
     */

     public static function getHome(){
        //Dados da organização
        $obOrganization = new Organization();

        $content = View::render('pages/home', [
            'name' => $obOrganization->name
        ]);

        return parent::getPage('Meu e home', $content);
     }
}

?>