<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';


use Src\Router;
use Src\Request;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Src\LoggerFactory;


$envPath = realpath(__DIR__ . '/..') . '/.env';

if (!file_exists($envPath) || !is_readable($envPath)) {
    throw new \RuntimeException(".env não encontrado ou sem permissão de leitura em: $envPath");
}

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$dotenv->required(['MYSQL_PASSWORD', 'MYSQL_USER', 'MYSQL_DATABASE'])
       ->notEmpty();


$monolog = new Logger('system');
$logPath = __DIR__ . '/../storage/logs/system.log';
$monolog->pushHandler(new StreamHandler($logPath, Logger::DEBUG));


LoggerFactory::setLogger($monolog);

LoggerFactory::getLogger()->info("inicializado o log");

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
