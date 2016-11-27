<?php

namespace Mbx\ArchitectBundle\Interfaces;

use Mbx\ArchitectBundle\Interfaces\EntityInterface;

/**
 * Interface EntityManagerInterface
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 *
 * @package Mbx\ArchitectBundle\Interfaces
 */
interface EntityManagerInterface
{

    /**
     * @return mixed
     */
    public function init();

    /**
     * Retuns the repository of the managed entity
     * @return Repository
     */
    public function getRepository();

    /**
     * Returns the repository namespace
     *
     * @return string
     */
    public function initRepositoryNS();

    /**
     * Shortcut to find method of the repository
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Code to execute before saving the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return mixed
     */
    public function beforeSave(EntityInterface $entity);

    /**
     * Saves the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return mixed
     */
    public function save(EntityInterface $entity);

    /**
     * Code to execute after saving the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return mixed
     */
    public function afterSave(EntityInterface $entity);

    /**
     * Removes the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return mixed
     */
    public function remove(EntityInterface $entity);

    /**
     * Code to execute before removing the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return mixed
     */
    public function beforeRemove(EntityInterface $entity);

    /**
     * Code to execute after removing the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return mixed
     */
    public function afterRemove(EntityInterface $entity);
}
