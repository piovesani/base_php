<?php

use \App\Http\Response;
use \App\Controllers\Pages;

//rota home
$obRouter->get('/', [
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);

//rota home
$obRouter->get('/about', [
    function(){
        return new Response(200, Pages\About::getAbout());
    }
]);

//rota dinâmica
$obRouter->get('/blog/{id}/{action}', [
    function($id, $action){
        return new Response(200, 'Page '.$id.' - '.$action);
    }
]);

?>