<?php

namespace App\Controllers\Pages;

use \App\Utils\View;
use \App\Models\Entity\Organization;

class About extends Page{
    /**
     * Método responsável por retornar o conteúdo (view) da nossa About
     * @return string
     */

     public static function getAbout(){
        //Dados da organização
        $obOrganization = new Organization();

        $content = View::render('pages/about', [
            'name' => $obOrganization->name,
            'description' => $obOrganization->description
        ]);

        return parent::getPage('Meu sobre', $content);
     }
}

?>