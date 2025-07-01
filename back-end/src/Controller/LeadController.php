<?php

namespace Src\Controller;

use Src\Repository\AlternativeRepository;
use Src\Repository\LeadRepository;
use Src\Repository\LeadResponseRepository;
use Src\Repository\QuestionRepository;
use Src\Repository\QuizRepository;
use Src\Request;
use Src\Response;
use Src\Service\ActiveCampaignObserver;
use Src\Service\ActiveCampaignService;
use Src\Service\AlternativeService;
use Src\Service\LeadResponseService;
use Src\Service\LeadService;
use Src\Service\QuestionService;
use Src\Service\QuizService;
use Src\Validator\LeadValidator;
use Src\Validator\QuizValidator;

class LeadController extends BaseController
{
    private LeadService $service;

    public function __construct()
    {
        $alternativeService = new AlternativeService(new AlternativeRepository);
        $quizRepository = new QuizRepository;
        $questionService = new QuestionService(new QuizValidator, new QuestionRepository, $alternativeService);
        $leadResponse = new LeadResponseService(new LeadResponseRepository());
        $leadValidator = new LeadValidator(new QuizService(new QuizValidator(), $quizRepository,  $questionService));
        $leadRepository = new LeadRepository;
        $this->service = new LeadService($leadValidator, $leadRepository, $leadResponse);
        $this->service->attach(new ActiveCampaignObserver());
    }
    public function submit(Request $req): Response
    {
        // try {
            $body   = $req->body;
            $this->service->submitLead($body['quizId'], $body['name'], $body['email'], $body['responses']);

            return new Response(['message' => 'Lead enviado'], 201);
        // } catch (\Throwable $th) {
        //     return new Response(['error' => $th->getMessage()], 500);
        // }
    }
}
