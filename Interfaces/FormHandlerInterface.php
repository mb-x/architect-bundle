<?php

namespace Mbx\SymfonyBootstrapBundle\Interfaces;

use Mbx\SymfonyBootstrapBundle\Interfaces\EntityInterface;
/**
 * Description of EntityManagerInterface
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 */
interface FormHandlerInterface {
    /**
     *
     */
     public function init();
     
     public function initDeleteRouteName();
     
     public function initFormTypeNS();

     public function getForm();
     /**
     * Create Form, hanle Request and flush entity,
     * make sure you called $this->processEntity before calling this function,
     * otherwise, an exception will be thrown
     * @return boolean
     * @throws \Exception
     */
     public function processForm(EntityInterface $entity);

     public function createForm(EntityInterface $entity);
     /**
     * Creates a form to delete an entity.
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDeleteForm(EntityInterface $entity);
    
    public function processDeleteForm(EntityInterface $entity);
}
