<?php
namespace Mbx\SymfonyBootstrapBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Created by PhpStorm.
 * User: PC-MA13
 * Date: 09/11/2016
 * Time: 12:20
 */
class MbxTwigExtension extends \Twig_Extension
{

    protected $twig;
    protected $container;

    public function __construct(ContainerInterface $container) {

        $this->container = $container;

    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('renderBsDeleteModal', array($this, 'renderBsDeleteModal'), array('is_safe' => array('html'))),
        );
    }

    public function renderBsDeleteModal($delete_form, $options = array()) {
        $this->twig = $this->container->get('templating');
        return $this->twig->render('@MbxSymfonyBootstrap/Bootstrap/modalBsDeleteForm.html.twig', array(
            'delete_form'=> $delete_form,
            'options' => $options
        ));
    }

    public function getName() {
        return 'mbx_twig_extension';
    }


}