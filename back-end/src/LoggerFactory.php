<?php
namespace Src;

use Psr\Log\LoggerInterface;

class LoggerFactory
{
    /** @var LoggerInterface */
    private static $logger;

    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    public static function getLogger(): LoggerInterface
    {
        if (!self::$logger) {
            throw new \RuntimeException('Logger não inicializado.');
        }
        return self::$logger;
    }
}
