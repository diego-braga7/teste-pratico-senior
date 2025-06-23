<?php
namespace Src\Service;

use Psr\Log\LoggerInterface;
use Src\LoggerFactory;

trait LogTrait{
    public function getLogger() : LoggerInterface{
        return LoggerFactory::getLogger();
    }
}