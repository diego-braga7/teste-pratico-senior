<?php
namespace Src\Controller;

use Src\Request;
use Src\Response;

class PublicQuizController extends BaseController
{
    public function show(Request $req): Response
    {
        $id = $req->params['id'];
        // carregar quiz com perguntas e alternativas...
        return new Response([
            'id'        => $id,
            'title'     => 'Quiz Público',
            'questions' => [/* ... */],
        ]);
    }
}
