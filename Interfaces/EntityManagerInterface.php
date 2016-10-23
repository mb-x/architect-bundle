<?php

namespace Mbx\SymfonyBootstrapBundle\Interfaces;

use Mbx\SymfonyBootstrapBundle\Interfaces\EntityInterface;
/**
 * Description of EntityManagerInterface
 *
 * @author Mohamed Bengrich <mbengrich.dev@gmail.com>
 */
interface EntityManagerInterface {
    /**
     *
     */
     public function init();

     public function getRepository();

     public function initRepository();
     
     public function find($id);
     
     public function save(EntityInterface $entity);
     
     public function remove(EntityInterface $entity);
}
