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

     public function initRepositoryNS();
     
     public function find($id);

     public function beforeSave(EntityInterface $entity);
     
     public function save(EntityInterface $entity);

     public function afterSave(EntityInterface $entity);
     
     public function remove(EntityInterface $entity);

     public function beforeRemove(EntityInterface $entity);

     public function afterRemove(EntityInterface $entity);
}
