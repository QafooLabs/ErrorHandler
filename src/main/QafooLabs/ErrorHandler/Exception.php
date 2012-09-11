<?php
/**
 * This file is part of the QafooLabs ErrorHandler Component.
 *
 * @version $Revision$
 */

namespace QafooLabs\ErrorHandler;

/**
 * Exception thrown, when a PHP error occurred
 *
 * @version $Revision$
 */
class Exception extends \ErrorException
{
    /**
     * Array type to name mapping
     *
     * @var array
     */
    protected $errorType = array(
        E_STRICT            => 'Strict notice',
        E_NOTICE            => 'Notice',
        E_USER_NOTICE       => 'Notice',
        E_WARNING           => 'Warning',
        E_USER_WARNING      => 'Warning',
        E_RECOVERABLE_ERROR => 'Recoverable error',
        E_USER_ERROR        => 'Error',
        E_ERROR             => 'Error',
    );

    /**
     * Construct PHP error
     *
     * Construct PHP error from type, name, file, line and backtrace
     *
     * @param integer $type
     * @param string  $message
     * @param string  $file
     * @param integer $line
     */
    public function __construct($type, $message, $file, $line)
    {
        parent::__construct("A PHP error occurred: {$this->errorType[$type]}: {$message}");

        $this->file = $file;
        $this->line = $line;
    }
}

