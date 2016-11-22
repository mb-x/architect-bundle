<?php
namespace Mbx\ArchitectBundle\Command;

use Mbx\ArchitectBundle\Generator\MbxGenerator;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 */
class GeneratorCommand extends ContainerAwareCommand
{

    const FormHandlerType = 'FormHandler';
    const ManagerType = 'Manager';
    const AllType = 'All';
    /**
     * @var MbxGenerator
     */
    private $generator;

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return (
            class_exists('Sensio\\Bundle\\GeneratorBundle\\Command\\GenerateDoctrineCommand')
        );
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('mbx:generate')
            ->setDescription('Generates a new Manager and FormHandler classes based on the given entity.')
            ->addArgument('entity', InputArgument::REQUIRED, 'The entity class name (shortcut notation).')
            ->addOption('classType', 'c', InputOption::VALUE_OPTIONAL,'The class type to be generated ('.self::ManagerType.'|'.self::FormHandlerType.'|'.self::AllType.')',self::AllType)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getArgument('entity'));
//        $output->writeln($entity);
        list($bundle, $entity) = $this->parseShortcutNotation($entity);
//        $output->writeln($entity);
//        $output->writeln($bundle);
        $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);
//        $output->writeln($bundle->getNamespace());
//        $output->writeln($bundle->getPath());
        $classType = $input->getOption('classType');
        $output->writeln($classType);
        $generator = $this->getGenerator($bundle);
        if($classType == self::ManagerType || $classType==self::AllType){ /*Manager block*/
            $output->writeln('---------------------------------------');
            $output->writeln('|     Generating Class Manager ...     |');
            $output->writeln('---------------------------------------');
            $generator->generateManager($bundle, $entity);
            $output->writeln(
                sprintf(
                    'The new Manager %s.php class file has been created under %s.',
                    $generator->getClassName(),
                    $generator->getClassPath()
                )
            );
            $output->writeln('---------------------------------------');
            $output->writeln('Add the following lines to your services.yml file:');
            $output->writeln($generator->getManagerServiceLines());
            $output->writeln('---------------------------------------');
        }
        if($classType == self::FormHandlerType || $classType==self::AllType) {/*FormHandler block*/
            $output->writeln('---------------------------------------');
            $output->writeln('|   Generating Class FormHandler ...   |');
            $output->writeln('---------------------------------------');
            $generator->generateFormHandler($bundle, $entity);
            $output->writeln(
                sprintf(
                    'The new FormHandler %s.php class file has been created under %s.',
                    $generator->getClassName(),
                    $generator->getClassPath()
                )
            );
            $output->writeln('---------------------------------------');
            $output->writeln('Add the following lines to your services.yml file:');
            $output->writeln($generator->getFormHandlerServiceLines());
            $output->writeln('---------------------------------------');
        }
    }
    /**
     * Parse shortcut notation.
     *
     * @param $shortcut
     *
     * @return array
     */
    protected function parseShortcutNotation($shortcut)
    {
        $entity = str_replace('/', '\\', $shortcut);
        if (false === $pos = strpos($entity, ':')) {
            throw new \InvalidArgumentException(sprintf('The entity name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Blog/Post)', $entity));
        }
        return array(substr($entity, 0, $pos), substr($entity, $pos + 1));
    }

    /**
     * Get skeleton dirs.
     *
     * @param BundleInterface $bundle
     *
     * @return array
     */
    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();
        if (isset($bundle) && is_dir($dir = $bundle->getPath().'/Resources/MbxArchitectBundle/views/Skeleton')) {
            $skeletonDirs[] = $dir;
        }
        if (is_dir($dir = $this->getContainer()->get('kernel')->getRootdir().'/Resources/MbxArchitectBundle/views/Skeleton')) {
            $skeletonDirs[] = $dir;
        }
        $reflClass = new \ReflectionClass(get_class($this));
        $skeletonDirs[] = dirname($reflClass->getFileName()) . '/../Resources/views/Skeleton';
        $skeletonDirs[] = dirname($reflClass->getFileName()) . '/../Resources';
        return $skeletonDirs;
    }
    /**
     * Create generator.
     *
     * @return MbxGenerator
     */
    protected function createGenerator()
    {
        return new MbxGenerator($this->getContainer()->get('filesystem'));
    }

    /**
     * Get generator.
     *
     * @param BundleInterface|null $bundle
     *
     * @return mixed|MbxGenerator
     */
    protected function getGenerator(BundleInterface $bundle = null)
    {
        if (null === $this->generator) {
            $this->generator = $this->createGenerator();
            $this->generator->setSkeletonDirs($this->getSkeletonDirs($bundle));
        }
        return $this->generator;
    }

}