<?php
namespace Src;
class Response
{
    private $body;
    private $status;
    private $headers = ['Content-Type' => 'application/json'];

    public function __construct($body = '', int $status = 200)
    {
        $this->body   = is_array($body) ? json_encode($body) : $body;
        $this->status = $status;
    }

    public function send()
    {
        http_response_code($this->status);
        foreach ($this->headers as $k => $v) {
            header("$k: $v");
        }
        echo $this->body;
    }
}
