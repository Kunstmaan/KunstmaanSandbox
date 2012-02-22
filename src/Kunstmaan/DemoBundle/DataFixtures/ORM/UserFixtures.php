<?php
// src/Kunstmaan/DemoBundle/DataFixtures/ORM/UserFixtures.php

namespace Kunstmaan\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Kunstmaan\AdminBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername("admin");
        $user1->setPlainPassword("admin");
        $user1->setRoles(array("ROLE_SUPER_ADMIN"));
        $user1->setEmail("test@example.be");
        $user1->setEnabled(true);
        $user1->addGroup($manager->merge($this->getReference('kunstmaan-group')));

        $manager->persist($user1);
        $manager->flush();
        $manager->refresh($user1);

        $this->addReference('adminuser', $user1);


        $user2 = new User();
        $user2->setUsername("kris");
        $user2->setPlainPassword("test");
        $user2->setRoles(array("ROLE_ADMIN"));
        $user2->setEmail("kris.pypen@kunstmaan.be");
        $user2->setEnabled(true);
        $user2->addGroup($manager->merge($this->getReference('kunstmaan-group')));

        $manager->persist($user2);
        $manager->flush();


        $user3 = new User();
        $user3->setUsername("kristof");
        $user3->setPlainPassword("test");
        $user3->setRoles(array("ROLE_ADMIN"));
        $user3->setEmail("kristof.van.cauwenbergh@kunstmaan.be");
        $user3->setEnabled(true);
        $user3->addGroup($manager->merge($this->getReference('kunstmaan-group')));

        $manager->persist($user3);
        $manager->flush();


        $user4 = new User();
        $user4->setUsername("kim");
        $user4->setPlainPassword("test");
        $user4->setRoles(array("ROLE_ADMIN"));
        $user4->setEmail("kim.ausloos@kunstmaan.be");
        $user4->setEnabled(true);
        $user4->addGroup($manager->merge($this->getReference('guest-group')));

        $manager->persist($user4);
        $manager->flush();


        $user5 = new User();
        $user5->setUsername("guest");
        $user5->setPlainPassword("guest");
        $user5->setRoles(array("ROLE_GUEST"));
        $user5->setEmail("guest@kunstmaan.be");
        $user5->setEnabled(false);
        $user5->addGroup($manager->merge($this->getReference('guest-group')));

        $manager->persist($user5);
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

}