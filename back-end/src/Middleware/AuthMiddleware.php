<?php
namespace Src\Middleware;

use Src\Request;
use Src\Response;
use Src\LoggerFactory;

class AuthMiddleware
{
    public function handle(Request $req)
    {
        $logger = LoggerFactory::getLogger();

        $logger->info('AuthMiddleware: checando Basic Auth', [
            'headers' => $req->headers
        ]);

        $authHeader = $req->headers['Authorization'] ?? '';
        if (!$this->checkToken($authHeader)) {
            $logger->warning('AuthMiddleware: falha na autenticação Basic', [
                'received' => $authHeader
            ]);
            (new Response(['error' => 'Unauthorized'], 401))->send();
            exit;
        }

        $logger->info('AuthMiddleware: autenticação Basic bem-sucedida');
    }

    private function checkToken(string $authHeader): bool
    {
        // Espera header no formato 'Basic base64(user:pass)'
        if (stripos($authHeader, 'Basic ') !== 0) {
            return false;
        }

        $encoded = substr($authHeader, 6);
        $decoded = base64_decode($encoded);
        if (!$decoded) {
            return false;
        }

        [$user, $pass] = array_pad(explode(':', $decoded, 2), 2, null);

        // $envUser = getenv('BASIC_AUTH_USER') ?: '';
        // $envPass = getenv('BASIC_AUTH_PASS') ?: '';
        $envUser = 'diego@gmail.com.br';
        $envPass = 'asajisa';

        return hash_equals($envUser, $user) && hash_equals($envPass, $pass);
    }
}
