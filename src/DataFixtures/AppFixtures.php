<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        
        // utilisateur spécifique UserName : 'Anonyme'
        $user = UserFactory::createOne([
            'email' => 'anonym@.com',
            'password' => '$2y$13$2mdoEUC2eiMVAwrd.uks4uMGhu7Ug6MNSda2cj0ykd2JFVD4q4Aem',
            'roles' => ["ROLE_USER"],
            'username' => 'Anonyme',

        ]);



        // Nourrir avec des infos qui vont bien. Re Hash de password. 
        UserFactory::createMany(10);

        TaskFactory::createMany(50, function() {
            return [
                'author' => UserFactory::random()
            ];
        });

        
    }
}
