<?php
namespace Src;

use Src\Service\LogTrait;

class Router
{
    use LogTrait;

    private $routes = [];
    private $middlewareMap = [];

    public function get($path, $handler)  { $this->addRoute('GET',  $path, $handler); }
    public function post($path, $handler) { $this->addRoute('POST', $path, $handler); }
    public function put($path, $handler)  { $this->addRoute('PUT',  $path, $handler); }
    public function delete($path, $handler){ $this->addRoute('DELETE',$path, $handler); }

    public function group(array $options, callable $callback)
    {
        if (!empty($options['middleware'])) {
            $this->middlewareMap[] = $options['middleware'];
            $callback($this);
            array_pop($this->middlewareMap);
        }
    }

    private function addRoute($method, $path, $handler)
    {
        
        $this->routes[] = [
            'method'     => $method,
            'path'       => $path,
            'handler'    => $handler,
            'middleware' => $this->middlewareMap,
        ];
    }

    public function dispatch(Request $req): Response
    {
        foreach ($this->routes as $route) {
            $this->getLogger()->info( $req->matches($route['path']));
            if ($req->method === $route['method'] && $req->matches($route['path'])) {
                foreach ($route['middleware'] as $mwClass) {
                    $mw = new $mwClass;
                    $mw->handle($req);
                }
                list($class, $method) = explode('@', $route['handler']);
                $controller = new $class;
                return $controller->$method($req);
            }
        }
        
        return new Response('Not Found', 404);
    }
}
