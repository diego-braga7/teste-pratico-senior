<?php
namespace Src;
class Request
{
    public $method;
    public $uri;
    public $body;
    public $params = [];

    public static function capture(): self { /* popula method, uri, body */ 
    return new self;
    }

    public function matches(string $routePath): bool {
    return true;    
    /* compara URI e extrai params */ }
}
