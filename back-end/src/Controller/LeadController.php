<?php
namespace Src\Controller;

use Src\Request;
use Src\Response;
use Src\Service\ActiveCampaignService;

class LeadController extends BaseController
{
    public function submit(Request $req): Response
    {
        $quizId = $req->params['id'];
        $body   = $req->body; 
        // validar body: respostas + nome/email lead...
        // salvar lead e respostas (omitido)
        $ac = new ActiveCampaignService;
        $result = $ac->addContact($body['name'], $body['email']);
        if ($result === true) {
            return new Response(['message'=>'Lead enviado'], 201);
        } else {
            return new Response(['error'=>$result], 422);
        }
    }
}
