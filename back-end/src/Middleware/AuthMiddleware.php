<?php
namespace Src\Middleware;

use Src\Request;
use Src\Response;

class AuthMiddleware
{
    public function handle(Request $req)
    {
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!$this->checkToken($auth)) {
            (new Response(['error'=>'Unauthorized'], 401))->send();
            exit;
        }
    }

    private function checkToken(string $header): bool
    {
        // validar JWT ou token simples...
        return $header === 'Bearer seu_token_valido';
    }
}
