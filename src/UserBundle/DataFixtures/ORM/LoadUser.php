<?php
/**
 * Created by PhpStorm.
 * User: Noobix
 * Date: 11/06/2016
 * Time: 14:59
 */

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername("Noobix");
        $user->setPassword('$2y$13$Ya2VYiP09pdEV/bvtbRilOhvfwH.wgk0y0vU3wOUWoKihJbGKzPRa');
        $user->setSalt('');
        $user->setRoles(array('ROLE_USER'));
        
        $manager->persist($user);
        $manager->flush();
    }
}