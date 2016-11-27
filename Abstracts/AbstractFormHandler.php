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

    /**
     * @var FormFactory
     */
    protected $formFactory;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var FormType The FormType of the managed entity
     */
    protected $form;
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var EntityManager child of \Mbx\ArchitectBundle\Abstracts\AbstractEntityManager
     */
    protected $manager;

    /**
     * @var string The FormType's namespace of the managed entity
     */
    protected $formTypeNS;
    /**
     * @var string The Route Name for delete Action of the managed entity
     */
    protected $deleteRouteName;


    /**
     * @throws NotStringException
     */
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


    /**
     * @param FormFactory $formFactory
     * @param Request $request
     * @param Router $router
     * @param \Mbx\ArchitectBundle\Abstracts\AbstractEntityManager $manager
     * @throws NotStringException
     */
    public function __construct(FormFactory $formFactory, Request $request, Router $router, AbstractEntityManager $manager) {
        $this->formFactory = $formFactory;
        $this->request = $request;
        $this->router = $router;
        $this->manager = $manager;
        $this->init();
    }


    /**
     * @return FormType
     */
    public function getForm() {
        return $this->form;
    }


    /**
     * Creates form, handles request and saves entity if form is submitted and valid
     * @param EntityInterface $entity
     * @return bool
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
     * Creates delete form, handles request and removes entity if form is submitted and valid
     *
     * @param EntityInterface $entity
     * @return bool
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


    /**
     * Creates a form for a managed entity.
     *
     * @param EntityInterface $entity
     */
    public function createForm(EntityInterface $entity) {
        $this->form = $this->formFactory->create($this->formTypeNS, $entity);
    }


    /**
     * Creates a form to delete a managed entity.
     * @param EntityInterface $entity
     * @return mixed
     */
    public function createDeleteForm(EntityInterface $entity) {
        return $this->formFactory->createBuilder()
                        ->setAction($this->router->generate($this->deleteRouteName, array('id' => $entity->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
