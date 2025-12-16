<?php

namespace yoanbmps\batchChecker\Services;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class Logger
{
    private static ?LoggerInterface $instance = null;

    public static function getInstance(): LoggerInterface
    {
        if (self::$instance === null) {
            $logger = new MonologLogger('batch-checker');
            $logFile = __DIR__ . '/../../logs/app.log';
            
            // Ensure logs directory exists
            $logDir = dirname($logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            
            $logger->pushHandler(new StreamHandler($logFile, MonologLogger::INFO));
            self::$instance = $logger;
        }

        return self::$instance;
    }
}
