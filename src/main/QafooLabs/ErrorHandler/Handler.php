<?php
/**
 * This file is part of the QafooLabs ErrorHandler Component.
 *
 * @version $Revision$
 */

namespace QafooLabs\ErrorHandler;

/**
 * Handler that translates native PHP errors into exceptions.
 *
 * @version $Revision$
 */
final class Handler
{
    /**
     * Optional log file to log all error including backtrace for easier
     * debugging.
     *
     * @var string
     */
    protected static $logFile;

    /**
     * Registers this class as error and exception handler.
     *
     * @param string $logFile
     *
     * @return void
     */
    public static function register($logFile = null)
    {
        self::setLogFile($logFile);

        set_error_handler(array(__CLASS__, 'errorHandler'));
        set_exception_handler(array(__CLASS__, 'exceptionHandler'));
    }

    /**
     * Sets a log file that will be used to log all error including backtrace
     * for easier debugging.
     *
     * @param string $logFile A log file.
     *
     * @return void
     */
    public static function setLogFile($logFile)
    {
        self::$logFile = $logFile;
    }

    /**
     * Convert PHP errors to exceptions
     *
     * @param integer $type
     * @param string  $message
     * @param string  $file
     * @param integer $line
     *
     * @return void
     */
    public static function errorHandler($type, $message, $file, $line)
    {
        // Skip intentionally silenced error messages with @.
        //
        // This may give false positives for setups where the error reporting is
        // set to 0 in the php configuration, but people who do that are irrelevant
        // by design.
        if (error_reporting() === 0) {
            return false;
        }

        // Display all errors in debug mode, but only errors and warnings in
        // production environments.
        $exception = new Exception($type, $message, $file, $line);
        self::exceptionHandler($exception);
    }

    /**
     * Logs the exception to a log file when it wasn't handled before.
     *
     * @param \Exception $exception
     *
     * @return void
     */
    public static function exceptionHandler(\Exception $exception)
    {
        if (null !== self::$logFile) {
            file_put_contents(
                self::$logFile,
                '[' . date(\DateTime::RFC2822) . '] ' . $exception . PHP_EOL,
                FILE_APPEND
            );
        }

        throw $exception;
    }
}
