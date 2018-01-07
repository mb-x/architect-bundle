<?php
/**
 * This file is part of the ArchitectBundle package.
 *
 * (c) mb-x <https://github.com/mb-x/architect-bundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mbx\ArchitectBundle\FormHandler;

use Mbx\ArchitectBundle\EntityManager\AbstractEntityManager;
use Mbx\ArchitectBundle\Event\FormHandlerEvents;
use Mbx\ArchitectBundle\Event\FormHandlerPreCreate;
use Mbx\ArchitectBundle\Exception\NotStringException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use Mbx\ArchitectBundle\Interfaces\EntityInterface;

/**
 * Abstract form handler
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 */
abstract class AbstractFormHandler implements FormHandlerInterface
{

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var FormFactory
     */
    protected $formFactory;
    /**
     * @var RequestStack
     */
    protected $requestStack;
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
    public function init()
    {
        $this->deleteRouteName =  $this->initDeleteRouteName();
        $this->formTypeNS = $this->initFormTypeNS();
        if (!trim($this->deleteRouteName) || !is_string($this->initDeleteRouteName())) {
            throw new NotStringException('Delete Route Name');
        }
        if (!trim($this->formTypeNS) || !is_string($this->initFormTypeNS())) {
            throw new NotStringException('Form Type Namespace');
        }
    }


    /**
     * @param FormFactory $formFactory
     * @param RequestStack $requestStack
     * @param Router $router
     * @param \Mbx\ArchitectBundle\EntityManager\AbstractEntityManager $manager
     * @param EventDispatcherInterface $eventDispatcher
     * @throws NotStringException
     */
    public function __construct(
        FormFactory $formFactory,
        RequestStack $requestStack,
        Router $router,
        AbstractEntityManager $manager,
        EventDispatcher $eventDispatcher
    )
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
        $this->init();
    }


    /**
     * @return FormType
     */
    public function getForm()
    {
        return $this->form;
    }


    /**
     * Creates form, handles request and saves entity if form is submitted and valid
     * @param EntityInterface $entity
     * @return bool
     */
    public function processForm(EntityInterface $entity)
    {
        /* PreFormCreate */
        $formEvents = new FormHandlerEvents($entity);
        $this->eventDispatcher->dispatch(FormHandlerEvents::PRE_CREATE, $formEvents);

        $this->createForm($entity);
        $this->eventDispatcher->dispatch(FormHandlerEvents::POST_CREATE, $formEvents);

        $this->form->handleRequest($this->requestStack->getCurrentRequest());

        $this->eventDispatcher->dispatch(FormHandlerEvents::PRE_VALID, $formEvents);

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            $this->eventDispatcher->dispatch(FormHandlerEvents::POST_VALID, $formEvents);

            $this->manager->save($entity);
            return true;
        }
        return false;
    }


    /**
     * Creates delete form, handles request and removes entity if form is submitted and valid
     *
     * @param EntityInterface $entity
     * @return bool
     */
    public function processDeleteForm(EntityInterface $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        $extraVars = $this->beforeCheckDeleteForm($entity);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->afterCheckDeleteForm($entity, $extraVars);
            $this->manager->remove($entity);
            return true;
        }
        return false;
    }


    /**
     * Creates a form for a managed entity.
     *
     * @param EntityInterface $entity
     */
    public function createForm(EntityInterface $entity)
    {
        $this->form = $this->formFactory->create($this->formTypeNS, $entity);
    }


    /**
     * Creates a form to delete a managed entity.
     * @param EntityInterface $entity
     * @return mixed
     */
    public function createDeleteForm(EntityInterface $entity)
    {
        return $this->formFactory->createBuilder()
                        ->setAction($this->router->generate($this->deleteRouteName, array('id' => $entity->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }
}
