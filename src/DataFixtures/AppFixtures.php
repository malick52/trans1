<?php

namespace App\DataFixtures;


use App\Entity\Role;
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
        $role = new Role();
        $role->setLibelle("ROLE_SUPADMIN");
        $manager->persist($role);
         $user = new User();
         $user->setUsername("malick");
         $user->setNom("Aly");
         $user->setPassword($this->encoder->encodePassword($user, "supadmin"));
         $user->setRoles(array("$role"));
         $user->setIsActive(true);
         $user->setRole($role);

         $manager->persist($user);

        $manager->flush  ();
    }
}
