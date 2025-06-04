<?php

namespace core\library;

class Logger
{
  public const DEBUG = 'DEBUG';
  public const INFO = 'INFO';
  public const WARNING = 'WARNING';
  public const ERROR = 'ERROR';
  public const CRITICAL = 'CRITICAL';

  public function __construct(private string $logFilePath)
  {
    $logDir = dirname($logFilePath);

    if (! is_dir($logDir)) {
      mkdir($logDir, 0777, true);
    }
  }

  private function write(string $level, string $message, array $context = []): void
  {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = sprintf("[%s] [%s] %s", $timestamp, $level, $message);

    if ($context) {
      $logEntry .= ' ' . json_encode($context);
    }

    $logEntry .= PHP_EOL;

    file_put_contents($this->logFilePath, $logEntry, FILE_APPEND | LOCK_EX);
  }

  public function debug(string $message, array $context = []): void
  {
    $this->write(self::DEBUG, $message, $context);
  }

  public function info(string $message, array $context = []): void
  {
    $this->write(self::INFO, $message, $context);
  }

  public function warning(string $message, array $context = []): void
  {
    $this->write(self::WARNING, $message, $context);
  }

  public function error(string $message, array $context = []): void
  {
    $this->write(self::ERROR, $message, $context);
  }

  public function critical(string $message, array $context = []): void
  {
    $this->write(self::CRITICAL, $message, $context);
  }
}
