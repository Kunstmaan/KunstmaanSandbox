<?php
// src/Blogger/BlogBundle/DataFixtures/ORM/BlogFixtures.php

namespace Kunstmaan\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Kunstmaan\DemoBundle\Entity\ExamplePage;

class ExamplePageFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $page1 = new ExamplePage();
        $page1->setTitle('8 weeks with Symfony2');
        $page1->setTranslatableLocale('en');
        $manager->persist($page1);
        $manager->flush();
        
        $page1->setTranslatableLocale('nl');
        $manager->refresh($page1);
        $page1->setTitle("8 weken met Symfony2");
        $manager->persist($page1);
        $manager->flush();
        
        $page1->setTranslatableLocale('fr');
        $manager->refresh($page1);
        $page1->setTitle("8 semaines avec Symfony2");
        $manager->persist($page1);

        $manager->flush();
        
        $nodeparent = $manager->getRepository('KunstmaanAdminNodeBundle:Node')->getNodeFor($page1);

        $page2 = new ExamplePage();
        $page2->setTitle('2 weeks with Symfony2 sub 1');
        $manager->persist($page2);

        $page3 = new ExamplePage();
        $page3->setTitle('2 weeks with Symfony2 sub 2');
        $manager->persist($page3);

        $manager->flush();
        
        $node2 = $manager->getRepository('KunstmaanAdminNodeBundle:Node')->getNodeFor($page2);
        $node2->setParent($nodeparent);
        $manager->persist($node2);
        
        $node3 = $manager->getRepository('KunstmaanAdminNodeBundle:Node')->getNodeFor($page3);
        $node3->setParent($nodeparent);
        $manager->persist($node3);
        
        $manager->flush();

        $this->addReference('page1', $page1);
        $this->addReference('page2', $page2);
        $this->addReference('page3', $page3);
    }

    public function getOrder()
    {
        return 100;
    }

}