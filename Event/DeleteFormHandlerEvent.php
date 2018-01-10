<?php

namespace Mbx\ArchitectBundle\Event;

class DeleteFormHandlerEvent extends AbstractEvent
{
    const PRE_CREATE = 'deleteformhandler.precreate';
    const POST_CREATE = 'deleteformhandler.postcreate';
    const PRE_VALID = 'deleteformhandler.prevalid';
    const POST_VALID = 'deleteformhandler.postvalid';
}