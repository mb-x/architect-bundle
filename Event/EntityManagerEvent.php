<?php

namespace Mbx\ArchitectBundle\Event;


class EntityManagerEvent extends AbstractEvent
{
    const PRE_SAVE = 'entitymanager.presave';
    const POST_SAVE = 'entitymanager.postsave';
    const PRE_REMOVE = 'entitymanager.preremove';
    const POST_REMOVE = 'entitymanager.postremove';
}