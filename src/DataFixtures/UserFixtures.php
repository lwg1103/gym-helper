<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createDummyUser("user@ex.com"));
        $manager->persist($this->createDummyUser("user2@ex.com"));
        $manager->flush();
    }
    
    private function createDummyUser($email)
    {
        $user = new User();

        $user->setEmail($email)
                ->setPassword($this->passwordEncoder->encodePassword($user, "pass"))
                ->generateApiKey();
        
        return $user;
    }

}
