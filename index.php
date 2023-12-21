<?php
require __DIR__.'/vendor/autoload.php';

use \App\Http\Router;
use \App\Utils\View;

//rota completa
define('URL', 'http://localhost/php/project_pro');

View::init([
    'URL' => URL,
    'language' => 'pt-br'
]);

$obRouter = new Router(URL);

//incluindo as rotas
include __DIR__.'/routes/pages.php';

//imprimi o response da página
$obRouter->run()->sendResponse();

?>