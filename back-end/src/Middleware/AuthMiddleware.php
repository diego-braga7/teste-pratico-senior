<?php
namespace Src\Middleware;

use Src\Request;
use Src\Response;
use Src\Service\LogTrait;

class AuthMiddleware
{
    use LogTrait;
    public function handle(Request $req)
    {
        $this->getLogger()->warning("passsou pelo authmiddler");
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
