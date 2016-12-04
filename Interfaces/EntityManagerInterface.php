<?php
/**
 * This file is part of the ArchitectBundle package.
 *
 * (c) mb-x <https://github.com/mb-x/architect-bundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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
     * @return array
     */
    public function beforeSave(EntityInterface $entity);

    /**
     * Saves the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return mixed|void
     */
    public function save(EntityInterface $entity);

    /**
     * Code to execute after saving the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @param array $extraVars
     * @return mixed|void
     */
    public function afterSave(EntityInterface $entity, $extraVars = array());

    /**
     * Removes the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return mixed|void
     */
    public function remove(EntityInterface $entity);

    /**
     * Code to execute before removing the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return array
     */
    public function beforeRemove(EntityInterface $entity);

    /**
     * Code to execute after removing the managed entity
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @param array $extraVars
     * @return mixed|void
     */
    public function afterRemove(EntityInterface $entity, $extraVars = array());
}
