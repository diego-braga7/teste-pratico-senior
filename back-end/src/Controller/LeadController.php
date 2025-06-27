<?php

namespace Src\Controller;

use Src\Request;
use Src\Response;
use Src\Service\ActiveCampaignObserver;
use Src\Service\ActiveCampaignService;
use Src\Service\LeadService;
use Src\Service\QuizService;
use Src\Validator\LeadValidator;
use Src\Validator\QuizValidator;

class LeadController extends BaseController
{
    private LeadService $service;

    public function __construct()
    {
        $this->service = new LeadService(new LeadValidator(new QuizService(new QuizValidator())));
        $this->service->attach(new ActiveCampaignObserver());
    }
    public function submit(Request $req): Response
    {
        try {
            $body   = $req->body;
        $this->service->submitLead($body['quizId'], $body['name'], $body['email'], $body['answers']);

        return new Response(['message' => 'Lead enviado'], 201);
        } catch (\Throwable $th) {
            return new Response(['error' => $th->getMessage()], 500);
        }
    }
}
