<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kris
 * Date: 14/11/11
 * Time: 15:48
 * To change this template use File | Settings | File Templates.
 */

namespace Kunstmaan\AdminNodeBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Kunstmaan\AdminNodeBundle\Entity\Node;
use Kunstmaan\AdminNodeBundle\Entity\HasNode;
// see http://inchoo.net/tools-frameworks/symfony2-event-listeners/

class NodeGenerator {

    public function postUpdate(LifecycleEventArgs $args) {
        $this->updateOrCreateNode($args);
    }

    public function postPersist(LifecycleEventArgs $args) {
        $this->updateOrCreateNode($args);
    }

    public function updateOrCreateNode(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $classname = get_class($entity);
        if($entity instanceof HasNode){
            $entityrepo = $em->getRepository($classname);
            $node = $this->getNode($em, $entity->getId(), $classname);
            if($node==null){
                $node = new Node();
                $node->setRefId($entity->getId());
                $node->setRefEntityname($classname);
                $node->setSequencenumber(1);
            }
            $node->setTitle($entity->__toString());
            $node->setOnline($entity->isOnline());
            $em->persist($node);
            $em->flush();
        }
    }

    public function prePersist(LifecycleEventArgs $args) {

    }

    public function getNode($em, $id, $entityName){
        return $em->getRepository('KunstmaanAdminNodeBundle:Node')
            ->findOneBy(array('refId' => $id, 'refEntityname' => $entityName));
    }
}