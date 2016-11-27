<?php
/**
 * This file is part of the ArchitectBundle package.
 *
 * (c) mb-x <https://github.com/mb-x/architect-bundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mbx\ArchitectBundle\Exception;

/**
 * Class NullValueException
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 *
 * @package Mbx\ArchitectBundle\Exception
 */
class NullValueException extends \Exception
{

    /**
     * @param string $element
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($element, $code = 0, Exception $previous = null)
    {
        $message = $element. ' Could Not Be Null !';
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}