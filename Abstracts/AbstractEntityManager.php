<?php

namespace Mbx\ArchitectBundle\Abstracts;

use Doctrine\ORM\EntityManager;
use Mbx\ArchitectBundle\Interfaces\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Mbx\ArchitectBundle\Interfaces\EntityInterface;

/**
 * Abstract Entity Manager
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 */
abstract class AbstractEntityManager implements EntityManagerInterface {

    protected $em;
    protected $entity;
    protected $repository;

    public function init() {
        $this->repository = $this->em->getRepository($this->initRepositoryNS());
    }

    /**
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->init();
    }

    public function getRepository() {
        return $this->repository;
    }

    public function getEm() {
        return $this->em;
    }

    public function getEntity() {
        return $this->entity;
    }

    public function find($id) {
        $this->entity = $this->repository->find($id);
        if (!$this->entity) {
            throw new EntityNotFoundException();
        }
        return $this->entity;
    }

    public function save(EntityInterface $entity) {
        $this->beforeSave($entity);
        $this->em->persist($entity);
        $this->em->flush();
        $this->afterSave($entity);
    }

    public function remove(EntityInterface $entity) {
        $this->beforeRemove($entity);
        $this->em->remove($entity);
        $this->em->flush();
        $this->afterRemove($entity);
    }

}
