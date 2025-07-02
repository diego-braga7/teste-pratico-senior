<?php

namespace Src\Controller;

use Src\Repository\AlternativeRepository;
use Src\Repository\QuestionRepository;
use Src\Repository\QuizRepository;
use Src\Request;
use Src\Response;
use Src\Service\AlternativeService;
use Src\Service\QuestionService;
use Src\Service\QuizService;
use Src\Validator\QuizValidator;

class QuizController extends BaseController
{
    private QuizService $service;

    public function __construct()
    {
        $alternativeService = new AlternativeService(new AlternativeRepository);
        $questionService = new QuestionService(new QuizValidator, new QuestionRepository, $alternativeService);
        $this->service = new QuizService(new QuizValidator, new QuizRepository, $questionService);
    }
    public function index(Request $req): Response
    {
        try {
            $quizzes = $this->service->getAll();
            return new Response($quizzes, 201);
        } catch (\Throwable $th) {
            return new Response([
                'error' => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function store(Request $req): Response
    {
        try {
            $data = $req->body;
            $quiz = $this->service->createQuiz($req->user,$data['title'], $data['questions']);
            return new Response([
                'message' => 'Quiz created',
                'id' => $quiz->getId()
            ], 201);
        } catch (\Throwable $th) {
            return new Response([
                'error' => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function show(Request $req): Response
    {
        $id = $req->params['id'];
        try {
            $quizzes = $this->service->getCompleteQuiz($id);
            return new Response($quizzes, 200);
        } catch (\Throwable $th) {
            return new Response([
                'error' => $th->getMessage()
            ], $th->getCode());
        }
    }

    
}
