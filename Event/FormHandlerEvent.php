<?php

namespace Mbx\ArchitectBundle\Event;


class FormHandlerEvent extends AbstractEvent
{
    const PRE_CREATE = 'formhandler.precreate';
    const POST_CREATE = 'formhandler.postcreate';
    const PRE_VALID = 'formhandler.prevalid';
    const POST_VALID = 'formhandler.postvalid';
}