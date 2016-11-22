<?php
namespace Mbx\ArchitectBundle\Generator;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 */
class MbxGenerator extends Generator
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $classPath;

    private $entityName;
    private $bundle_namespace;

    public function __construct()
    {
        $this->className = '';
        $this->classPath = '';
        $this->bundle_namespace = '';
        $this->entityName = '';
    }
    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
    /**
     * @return string
     */
    public function getClassPath()
    {
        return $this->classPath;
    }


    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }


    /**
     * @return string
     */
    public function getBundleNamespace()
    {
        return $this->bundle_namespace;
    }

    private function getManagerServiceName(){
        return strtolower($this->getBundleNamespace().'.'.$this->getEntityName()).'_manager';
    }
    private function getFormHandlerServiceName(){
        return strtolower($this->getBundleNamespace().'.'.$this->getEntityName()).'_form_handler';
    }
    public function getManagerServiceLines(){
        $lines =  sprintf(
            "%s:
                class: %s
                parent: mbx.abstract_entity_manager",
            $this->getManagerServiceName(),
            $this->getBundleNamespace().'\\'.'Manager'.'\\'.$this->getClassName()
            );
        return $lines;
    }
    public function getFormHandlerServiceLines(){
        $lines =  sprintf(
                "%s:
                class: %s
                parent: mbx.abstract_form_handler
                arguments: ['@%s']
                scope: request"
                ,
                $this->getFormHandlerServiceName(),
                $this->getBundleNamespace().'\\'.'FormHandler'.'\\'.$this->getClassName(),
                $this->getManagerServiceName()
            );
        return $lines;
    }

    /**
     * Generates the Manager class if it does not exist.
     *
     * @param BundleInterface $bundle     The bundle in which to create the class
     * @param string          $entity     The entity relative class name
     */
    public function generateManager(BundleInterface $bundle, $entity)
    {
        $parts = explode("\\", $entity);
        $entityClass = array_pop($parts);
        $this->entityName = $entity;
        $this->className = $entityClass . 'Manager';
        $this->bundle_namespace = $bundle->getNamespace();
        $dirPath = $bundle->getPath() . '/Manager';
        $this->classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Manager.php';
        if (file_exists($this->classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s Manager class as it already exists under the %s file', $this->className, $this->classPath));
        }
        $parts = explode('\\', $entity);
        array_pop($parts);
        $this->renderFile('manager.php.twig', $this->classPath, array(
            'namespace' => $bundle->getNamespace(),
//            'entity_namespace' => implode('\\', $parts),
            'entity_class' => $entityClass,
        ));
    }

    public function generateFormHandler(BundleInterface $bundle, $entity)
    {
        $parts = explode("\\", $entity);
        $entityClass = array_pop($parts);
        $this->className = $entityClass . 'FormHandler';
        $this->bundle_namespace = $bundle->getNamespace();
        $dirPath = $bundle->getPath() . '/FormHandler';
        $this->classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'FormHandler.php';
        if (file_exists($this->classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s FormHandler class as it already exists under the %s file', $this->className, $this->classPath));
        }
        $parts = explode('\\', $entity);
        array_pop($parts);
        $this->renderFile('form_handler.php.twig', $this->classPath, array(
            'namespace' => $bundle->getNamespace(),
//            'entity_namespace' => implode('\\', $parts),
            'entity_class' => $entityClass,
        ));
    }

}