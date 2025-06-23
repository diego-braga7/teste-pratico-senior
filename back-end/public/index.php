<?php
// Habilita exibição de erros (apenas dev)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Autoloader (PSR-4 ou require manual)
require __DIR__ . '/../vendor/autoload.php';

use Src\Router;
use Src\Request;
use Src\Response;

// Cria router e registra rotas
$router = new Router();

// Rotas de autenticação
$router->post('/login', 'Src\Controller\AuthController@login');

// Rotas protegidas (CRUD Quiz)
$router->group(['middleware' => 'Src\Middleware\AuthMiddleware'], function($r) {
    $r->get('/quizzes', 'Src\Controller\QuizController@index');
    $r->post('/quizzes', 'Src\Controller\QuizController@store');
    $r->get('/quizzes/{id}', 'Src\Controller\QuizController@show');
    $r->put('/quizzes/{id}', 'Src\Controller\QuizController@update');
    $r->delete('/quizzes/{id}', 'Src\Controller\QuizController@destroy');
});

// Rotas públicas
$router->get('/quiz/{id}', 'Src\Controller\PublicQuizController@show');
$router->post('/quiz/{id}/submit', 'Src\Controller\LeadController@submit');

// Dispara o roteamento
$request  = Request::capture();
$response = $router->dispatch($request);
$response->send();
