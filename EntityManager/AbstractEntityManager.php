<?php
/**
 * This file is part of the ArchitectBundle package.
 *
 * (c) mb-x <https://github.com/mb-x/architect-bundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mbx\ArchitectBundle\EntityManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Mbx\ArchitectBundle\Event\EntityManagerEvent;
use Mbx\ArchitectBundle\Interfaces\EntityInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Abstract Entity Manager
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 */
abstract class AbstractEntityManager implements EntityManagerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    /**
     * @var EntityInterface Entity that implements EntityInterface
     */
    protected $entity;
    /**
     * @var Repository The repository of the managed entity
     */
    protected $repository;

    /**
     *
     */
    public function init()
    {
        $this->repository = $this->em->getRepository($this->initRepositoryNS());
    }


    /**
     * @param EntityManager $em
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EntityManager $em, EventDispatcher $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->init();
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @return EntityInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $id
     * @return EntityInterface
     * @throws EntityNotFoundException
     */
    public function find($id)
    {
        $this->entity = $this->repository->find($id);
        if (!$this->entity) {
            throw new EntityNotFoundException();
        }
        return $this->entity;
    }

    /**
     * @param EntityInterface $entity
     * @return mixed|void
     */
    public function save(EntityInterface $entity)
    {
        $entityManagerEvent = new EntityManagerEvent($entity);
        $this->eventDispatcher->dispatch(EntityManagerEvent::PRE_SAVE, $entityManagerEvent);
        $this->em->persist($entity);
        $this->em->flush();
        $this->eventDispatcher->dispatch(EntityManagerEvent::POST_SAVE, $entityManagerEvent);
    }

    /**
     * @param EntityInterface $entity
     * @return mixed|void
     */
    public function remove(EntityInterface $entity)
    {
        $entityManagerEvent = new EntityManagerEvent($entity);
        $this->eventDispatcher->dispatch(EntityManagerEvent::PRE_REMOVE, $entityManagerEvent);
        $this->em->remove($entity);
        $this->em->flush();
        $this->eventDispatcher->dispatch(EntityManagerEvent::POST_REMOVE, $entityManagerEvent);
    }
}
