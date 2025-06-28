<?php
namespace Src\Middleware;

use Src\Request;
use Src\Response;
use Src\LoggerFactory;
use Src\Repository\UserRepository;
use Src\Service\AuthService;
use Src\Validator\EmailValidator;

class AuthMiddleware
{
    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService(new EmailValidator, new UserRepository);
    }
    public function handle(Request $req)
    {
        $logger = LoggerFactory::getLogger();

        $logger->info('AuthMiddleware: checando Basic Auth', [
            'headers' => $req->headers
        ]);

        $authHeader = $req->headers['Authorization'] ?? '';
        if (!$this->checkToken($req)) {
            $logger->warning('AuthMiddleware: falha na autenticação Basic', [
                'received' => $authHeader
            ]);
            (new Response(['error' => 'Unauthorized'], 401))->send();
            exit;
        }

        $logger->info('AuthMiddleware: autenticação Basic bem-sucedida');
    }

    private function checkToken(Request $req): bool
    {
        $authHeader = $req->headers['Authorization'] ?? '';

        if (stripos($authHeader, 'Basic ') !== 0) {
            return false;
        }

        $encoded = substr($authHeader, 6);
        $decoded = base64_decode($encoded);
        if (!$decoded) {
            return false;
        }

        [$email, $pass] = array_pad(explode(':', $decoded, 2), 2, null);

        $user = $this->authService->getUSer($email);

        if(!$user){
            return false;
        }

        $req->user = $user;

        return hash_equals($user->getPasswordHash(), $pass);
    }
}
