<?php

namespace Mbx\ArchitectBundle\Event;


use Mbx\ArchitectBundle\Interfaces\EntityInterface;
use Symfony\Component\EventDispatcher\Event;

class FormHandlerEvents extends Event
{
    const PRE_CREATE = 'formhandler.precreate';
    const POST_CREATE = 'formhandler.postcreate';
    const PRE_VALID = 'formhandler.prevalid';
    const POST_VALID = 'formhandler.postvalid';

    protected $entity;

    protected $args = [];

    public function __construct(EntityInterface $entity, array $args= array()) {
        $this->entity = $entity;
        $this->args = $args;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity(EntityInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function getArgs()
    {
        return $this->args;
    }
    public function getArg($key)
    {
        return isset($this->args[$key]) ? $this->args[$key] : '';
    }

    public function setArgs(array $args)
    {
        $this->args = $args;
        return $this;
    }

    public function addArg($key, $arg)
    {
        $this->args[$key] = $arg;
    }


}