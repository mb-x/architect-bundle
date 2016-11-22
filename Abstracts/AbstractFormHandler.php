<?php

namespace Mbx\ArchitectBundle\Abstracts;

use Mbx\ArchitectBundle\Exception\NotStringException;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Mbx\ArchitectBundle\Interfaces\FormHandlerInterface;
use Mbx\ArchitectBundle\Interfaces\EntityInterface;
use Mbx\ArchitectBundle\Abstracts\AbstractEntityManager;
/**
 * Abstract form handler
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 */
abstract class AbstractFormHandler implements FormHandlerInterface {

    protected $formFactory;
    protected $request;
    protected $form;
    protected $router;
    protected $manager;
    
    protected $formTypeNS;
    protected $deleteRouteName;

    public function init() {
        $this->deleteRouteName =  $this->initDeleteRouteName();
        $this->formTypeNS = $this->initFormTypeNS();
        if(!trim($this->deleteRouteName) || !is_string($this->initDeleteRouteName())){
            throw new NotStringException('Delete Route Name');
        }
        if(!trim($this->formTypeNS) || !is_string($this->initFormTypeNS())){
            throw new NotStringException('Form Type Namespace');
        }
    }
    
    public function __construct(FormFactory $formFactory, Request $request, Router $router, AbstractEntityManager $manager) {
        $this->formFactory = $formFactory;
        $this->request = $request;
        $this->router = $router;
        $this->manager = $manager;
        $this->init();
    }

    public function getForm() {
        return $this->form;
    }

    /**
     * Create Form, hanle Request and flush entity,
     * make sure you called $this->processEntity before calling this function,
     * otherwise, an exception will be thrown
     * @return boolean
     * @throws \Exception
     */
    public function processForm(EntityInterface $entity) {
        $this->createForm($entity);
        $this->form->handleRequest($this->request);
        $this->beforeCheckForm($entity);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->afterCheckForm($entity);
            $this->manager->save($entity);
            return true;
        }
        return FALSE;
    }

    /**
     * Create Delete Form, hanle Request and remove entity,
     * make sure you called $this->processEntity before calling this function,
     * otherwise, an exception will be thrown
     * @return boolean
     * @throws \Exception
     */
    public function processDeleteForm(EntityInterface $entity) {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($this->request);
        $this->beforeCheckDeleteForm($entity);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->afterCheckDeleteForm($entity);
            $this->manager->remove($entity);
            return true;
        }
        return FALSE;
    }

     public function createForm(EntityInterface $entity) {
        $this->form = $this->formFactory->create($this->formTypeNS, $entity);
    }

    /**
     * Creates a form to delete an entity.
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDeleteForm(EntityInterface $entity) {
        return $this->formFactory->createBuilder()
                        ->setAction($this->router->generate($this->deleteRouteName, array('id' => $entity->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
