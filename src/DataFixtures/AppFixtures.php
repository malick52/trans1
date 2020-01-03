<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
         $user = new User("supadmin");
         $user->setUsername("malick");
         $user->setNom("Aly");
         $user->setPassword($this->encoder->encodePassword($user, "supadmin"));
         $user->setRoles(array("ROLE_SUPADMIN"));
         $user->setIsActive(true);

         $manager->persist($user);

        $manager->flush  ();
    }
}
