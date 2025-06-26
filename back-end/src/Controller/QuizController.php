<?php

namespace Src\Controller;

use Src\Request;
use Src\Response;
use Src\Service\QuizService;
use Src\Validator\QuizValidator;

class QuizController extends BaseController
{
    private QuizService $service;

    public function __construct()
    {
        $this->service = new QuizService((new QuizValidator));
    }
    public function index(Request $req): Response
    {
        $quizzes = $this->service->getAll();
        if(empty($quiz)){
            return new Response(null,404);
        }
        return new Response($quizzes);
    }

    public function store(Request $req): Response
    {
        try {
            $data = $req->body;
            $quiz = $this->service->createQuiz($data['title'], $data['questions']);
            return new Response([
                'message' => 'Quiz created',
                'id' => $quiz['id']
            ], 201);
        } catch (\Throwable $th) {
            return new Response([
                'error' => $th->getMessage()
            ], 422);
        }
    }

    public function show(Request $req): Response
    {
        $id = $req->params['id'];
        $quiz = $this->service->getQuiz($id);
        if(empty($quiz)){
            return new Response(null,404);
        }
        return new Response($quiz);
    }

    
}
