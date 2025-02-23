<?php

declare(strict_types=1);

namespace Siler\Monolog;

use Exception;
use InvalidArgumentException;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

const MONOLOG_DEFAULT_CHANNEL = 'log';

/**
 * Stores to any stream resource
 * Can be used to store into php://stderr, remote and local files, etc.
 *
 * @param resource|string $stream
 * @param int $level The minimum logging level at which this handler will be triggered
 * @param bool $bubble Whether the messages that are handled can bubble up the stack or not
 * @param int|null $filePermission Optional file permissions (default (0644) are only for owner read/write)
 * @param bool $useLocking Try to lock log file before doing any writes
 *
 * @return StreamHandler
 * @throws InvalidArgumentException If stream is not a resource or string
 *
 * @throws Exception                If a missing directory is not buildable
 */
function stream(
    $stream,
    int $level = Logger::DEBUG,
    bool $bubble = true,
    ?int $filePermission = null,
    bool $useLocking = false
): StreamHandler {
    return new StreamHandler($stream, $level, $bubble, $filePermission, $useLocking);
}

/**
 * Adds a log record at an arbitrary level.
 * This function allows for compatibility with common interfaces.
 *
 * @param int $level The log level.
 * @param string $message The log message.
 * @param array $context The log context.
 * @param string $channel The log channel.
 */
function log(int $level, string $message, array $context = [], string $channel = MONOLOG_DEFAULT_CHANNEL)
{
    $logger = Loggers::getLogger($channel);
    $logger->log($level, $message, $context);
}

/**
 * Pushes a handler on to the stack.
 *
 * @param HandlerInterface $handler The handler to be pushed.
 * @param string $channel The Logger channel.
 *
 * @return Logger Returns the Logger.
 */
function handler(HandlerInterface $handler, string $channel = MONOLOG_DEFAULT_CHANNEL): Logger
{
    $logger = Loggers::getLogger($channel);

    return $logger->pushHandler($handler);
}

/**
 * Detailed debug information.
 *
 * @param string $message The log message.
 * @param array $context The log context.
 * @param string $channel The log channel.
 */
function debug(string $message, array $context = [], string $channel = MONOLOG_DEFAULT_CHANNEL)
{
    log(Logger::DEBUG, $message, $context, $channel);
}

/**
 * Interesting events. Examples: User logs in, SQL logs.
 *
 * @param string $message The log message.
 * @param array $context The log context.
 * @param string $channel The log channel.
 */
function info(string $message, array $context = [], string $channel = MONOLOG_DEFAULT_CHANNEL)
{
    log(Logger::INFO, $message, $context, $channel);
}

/**
 * Normal but significant events.
 *
 * @param string $message The log message.
 * @param array $context The log context.
 * @param string $channel The log channel.
 */
function notice(string $message, array $context = [], string $channel = MONOLOG_DEFAULT_CHANNEL)
{
    log(Logger::NOTICE, $message, $context, $channel);
}

/**
 * Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
 *
 * @param string $message The log message.
 * @param array $context The log context.
 * @param string $channel The log channel.
 */
function warning(string $message, array $context = [], string $channel = MONOLOG_DEFAULT_CHANNEL)
{
    log(Logger::WARNING, $message, $context, $channel);
}

/**
 * Runtime errors that do not require immediate action but should typically be logged and monitored.
 *
 * @param string $message The log message.
 * @param array $context The log context.
 * @param string $channel The log channel.
 */
function error(string $message, array $context = [], string $channel = MONOLOG_DEFAULT_CHANNEL)
{
    log(Logger::ERROR, $message, $context, $channel);
}

/**
 * Critical conditions. Example: Application component unavailable, unexpected exception.
 *
 * @param string $message The log message.
 * @param array $context The log context.
 * @param string $channel The log channel.
 */
function critical(string $message, array $context = [], string $channel = MONOLOG_DEFAULT_CHANNEL)
{
    log(Logger::CRITICAL, $message, $context, $channel);
}

/**
 * Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
 *
 * @param string $message The log message.
 * @param array $context The log context.
 * @param string $channel The log channel.
 */
function alert(string $message, array $context = [], string $channel = MONOLOG_DEFAULT_CHANNEL)
{
    log(Logger::ALERT, $message, $context, $channel);
}

/**
 * Emergency: system is unusable.
 *
 * @param string $message The log message.
 * @param array $context The log context.
 * @param string $channel The log channel.
 */
function emergency(string $message, array $context = [], string $channel = MONOLOG_DEFAULT_CHANNEL)
{
    log(Logger::EMERGENCY, $message, $context, $channel);
}

/**
 * Internal DIC.
 *
 * @ignore Not part of the API.
 */
final class Loggers
{
    /**
     * @var Logger[]
     */
    public static $loggers = [];

    /**
     * Returns the Logger identified by the channel.
     *
     * @param string $channel The log channel.
     *
     * @return Logger
     */
    public static function getLogger(string $channel): Logger
    {
        if (empty(static::$loggers[$channel])) {
            static::$loggers[$channel] = new Logger($channel);
        }

        return static::$loggers[$channel];
    }
}
