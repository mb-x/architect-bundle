<?php
namespace Mbx\SymfonyBootstrapBundle\Generator;
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

    public function __construct()
    {
        $this->className = '';
        $this->classPath = '';
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
     * Generates the Manager class if it does not exist.
     *
     * @param BundleInterface $bundle     The bundle in which to create the class
     * @param string          $entity     The entity relative class name
     */
    public function generateManager(BundleInterface $bundle, $entity)
    {
        $parts = explode("\\", $entity);
        $entityClass = array_pop($parts);
//        $entityClassLowerCase = strtolower($entityClass);
        $this->className = $entityClass . 'Manager';
        $dirPath = $bundle->getPath() . '/Manager';
        $this->classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Manager.php';
//        if (file_exists($this->classPath)) {
//            throw new \RuntimeException(sprintf('Unable to generate the %s Manager class as it already exists under the %s file', $this->className, $this->classPath));
//        }
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
//        $entityClassLowerCase = strtolower($entityClass);
        $this->className = $entityClass . 'FormHandler';
        $dirPath = $bundle->getPath() . '/FormHandler';
        $this->classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'FormHandler.php';
//        if (file_exists($this->classPath)) {
//            throw new \RuntimeException(sprintf('Unable to generate the %s FormHandler class as it already exists under the %s file', $this->className, $this->classPath));
//        }
        $parts = explode('\\', $entity);
        array_pop($parts);
        $this->renderFile('form_handler.php.twig', $this->classPath, array(
            'namespace' => $bundle->getNamespace(),
//            'entity_namespace' => implode('\\', $parts),
            'entity_class' => $entityClass,
        ));
    }

}