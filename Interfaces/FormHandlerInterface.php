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
 * Interface FormHandlerInterface
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 *
 * @package Mbx\ArchitectBundle\Interfaces
 */
interface FormHandlerInterface
{

    /**
     * Init logic
     */
    public function init();

    /**
     * Returns the route name of delete action
     *
     * @return string
     */
    public function initDeleteRouteName();

    /**
     * Returns the FormType's namespace of the managed entity
     *
     * @return string
     */
    public function initFormTypeNS();

    /**
     * @return FormType
     */
    public function getForm();

    /**
     * Creates form, handles request and saves entity if form is submitted and valid
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return bool
     */
    public function processForm(EntityInterface $entity);


    /**
     * Creates a form for a managed entity.
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     */
    public function createForm(EntityInterface $entity);

    /**
     * Code to execute before checking if form is submitted and valid
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return array
     */
    public function beforeCheckForm(EntityInterface $entity);

    /**
     * Code to execute after checking if form is submitted and valid
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @param array $extraVars
     * @return mixed|void
     */
    public function afterCheckForm(EntityInterface $entity, $extraVars = array());

    /**
     * Creates a form to delete a managed entity.
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return mixed
     */
    public function createDeleteForm(EntityInterface $entity);

    /**
     * Creates delete form, handles request and removes entity if form is submitted and valid
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return bool
     */
    public function processDeleteForm(EntityInterface $entity);

    /**
     * Code to execute before checking if delete form is submitted and valid
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @return array
     */
    public function beforeCheckDeleteForm(EntityInterface $entity);

    /**
     * Code to execute after checking if delete form is submitted and valid
     *
     * @param \Mbx\ArchitectBundle\Interfaces\EntityInterface $entity
     * @param array $extraVars
     * @return mixed
     */
    public function afterCheckDeleteForm(EntityInterface $entity, $extraVars = array());
}
