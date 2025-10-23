<?php

namespace App\DataFixtures;

use App\Factory\AuthorFactory;
use App\Factory\BookFactory;
use App\Factory\EditorFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'ginalinetti@nypd.com',
            'firstname' => 'Gina',
            'lastname' => 'Linetti',
            'roles' => ['ROLE_ADMIN'],
            ]
        );

        EditorFactory::createMany(20);
        AuthorFactory::createMany(50);
        UserFactory::createMany(10);
        BookFactory::createMany(100);
    }
}
