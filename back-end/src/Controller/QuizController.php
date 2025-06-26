<?php
namespace Src\Controller;

use Src\Request;
use Src\Response;
use Src\Service\QuizService;
use Src\Validator\QuizValidator;

class QuizController extends BaseController
{
    private QuizService $service;

    public function __construct() {
       $this->service = new QuizService((new QuizValidator));
    }
    public function index(Request $req): Response
    {
        // listar quizzes (mock)
        $quizzes = [
            ['id'=>1, 'title'=>'Quiz 1'],
            ['id'=>2, 'title'=>'Quiz 2'],
        ];
        return new Response($quizzes);
    }

    public function store(Request $req): Response
    {
        $data = $req->body; // título, perguntas...
        // validar e criar quiz...
        $this->service->createQuiz($data['title'], $data['question']);
        return new Response(['message'=>'Quiz created'], 201);
    }

    public function show(Request $req): Response
    {
        $id = $req->params['id'];
        // buscar quiz...
        return new Response(['id'=>$id,'title'=>'Quiz Exemplo']);
    }

    public function update(Request $req): Response
    {
        $id   = $req->params['id'];
        $data = $req->body;
        // atualizar quiz...
        return new Response(['message'=>"Quiz {$id} updated"]);
    }

    public function destroy(Request $req): Response
    {
        $id = $req->params['id'];
        // deletar quiz...
        return new Response(null, 204);
    }
}
