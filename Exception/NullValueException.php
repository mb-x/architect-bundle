<?php
namespace Mbx\SymfonyBootstrapBundle\Exception;
/**
 * Author Mohamed Bengrich <mbengrich.dev@gmail.com>
 */
class NullValueException extends \Exception
{


    /**
     * NullValueException constructor.
     */
    public function __construct($element, $code = 0, Exception $previous = null)
    {
        $message = $element. ' Could Not Be Null !';
        parent::__construct($message, $code, $previous);
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}