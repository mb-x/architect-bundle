<?php
/**
 * Created by PhpStorm.
 * User: PC-MA13
 * Date: 17/11/2016
 * Time: 18:32
 */

namespace Mbx\SymfonyBootstrapBundle\Exception;


class NotStringException extends \Exception
{
    public function __construct($element, $code = 0, Exception $previous = null)
    {
        $message = $element. ' Should be of String Type !';
        parent::__construct($message, $code, $previous);
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}