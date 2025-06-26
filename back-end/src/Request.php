<?php
namespace Src;

class Request
{
    public string $method;
    public string $uri;
    public array $query = [];
    public array $body  = [];
    public array $params = [];
    public array $headers = [];

    private function __construct() {}

    /**
     * Captura a requisição HTTP de forma segura,
     * sanitizando inputs e incluindo todos os headers, mesmo Authorization.
     */
    public static function capture(): self
    {
        $req = new self();

        // Método HTTP
        $req->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // URI limpa (sem query string)
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($path, PHP_URL_PATH);
        $req->uri = rtrim($path, '/') ?: '/';

        // Captura todos os headers (inclui Authorization)
        if (function_exists('getallheaders')) {
            foreach (getallheaders() as $name => $value) {
                $req->headers[$name] = $value;
            }
        }

        // Fallback para servidores que não suportam getallheaders
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $req->headers[$name] = $value;
            } elseif ($key === 'CONTENT_TYPE') {
                $req->headers['Content-Type'] = $value;
            } elseif ($key === 'CONTENT_LENGTH') {
                $req->headers['Content-Length'] = $value;
            }
        }

        // Query params (GET) — sanitizados
        $rawGet = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: [];
        $req->query = self::sanitize($rawGet);

        // Body (POST, PUT, PATCH)
        if (in_array($req->method, ['POST', 'PUT', 'PATCH'], true)) {
            $contentType = $req->headers['Content-Type'] ?? '';
            if (stripos($contentType, 'application/json') === 0) {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true) ?: [];
                $req->body = self::sanitize($data);
            } else {
                $rawPost = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: [];
                $req->body = self::sanitize($rawPost);
            }
        }

        return $req;
    }

    /**
     * Verifica se a URI bate com a rota informada,
     * extraindo parâmetros nomeados ({id}, {slug}, etc).
     */
    public function matches(string $routePath): bool
    {
        $pattern = preg_replace_callback(
            '/\{(\w+)\}/',
            fn($m) => '(?P<' . $m[1] . '>[^\/]+)',
            $routePath
        );
        $pattern = '#^' . rtrim($pattern, '/') . '/?$#';

        if (preg_match($pattern, $this->uri, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $this->params[$key] = $value;
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Sanitiza recursivamente strings e arrays,
     * aplicando FILTER_SANITIZE_FULL_SPECIAL_CHARS.
     */
    private static function sanitize(array|string $data): array|string
    {
        if (is_array($data)) {
            $clean = [];
            foreach ($data as $key => $value) {
                $clean[$key] = self::sanitize($value);
            }
            return $clean;
        }

        return trim(filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }
}
