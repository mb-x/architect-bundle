<?php
/**
 * This file is part of the ArchitectBundle package.
 *
 * (c) mb-x <https://github.com/mb-x/architect-bundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mbx\ArchitectBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MbxTwigExtension
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 *
 * @package Mbx\ArchitectBundle\Twig
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