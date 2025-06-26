<?php

namespace Src\Controller;

use Src\LoggerFactory;
use Src\Request;
use Src\Response;
use Src\Service\ActiveCampaignService;
use Src\Service\AuthService;
use Src\Validator\EmailValidator;

class AuthController extends BaseController
{
    private AuthService $service;
    public function __construct()
    {
        $this->service = new AuthService((new EmailValidator()));
    }
    public function login(Request $request)
    {
        try {
            $email = $request->body['email'] ?? '';
            $senha = $request->body['senha'] ?? '';
            
            return new Response([
                'token' => $this->service->login($email, $senha)
            ]);
        } catch (\Throwable $th) {
            return new Response([
                'error' => $th->getMessage()
            ], 401);
        }
    }
}
