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

class PublicQuizController extends BaseController
{
    private QuizService $service;
    
    public function __construct() {
        $alternativeService = new AlternativeService(new AlternativeRepository);
        $questionService = new QuestionService(new QuizValidator, new QuestionRepository, $alternativeService);
        $this->service = new QuizService(new QuizValidator, new QuizRepository, $questionService);
    }
    public function show(Request $req): Response
    {
       try {
        $quizId = (int) current($req->params);
        $quiz = $this->service->getCompleteQuiz($quizId);
        return new Response($quiz);
       } catch (\Throwable $th) {
        return new Response(null, $th->getCode());
       }
    }
}
