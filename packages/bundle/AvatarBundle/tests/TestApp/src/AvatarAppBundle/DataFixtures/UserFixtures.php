<?php

declare(strict_types=1);

namespace AvatarAppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Model\UserInterface;

class UserFixtures extends Fixture
{
    private UserManagerInterface $userManager;

    private Generator $faker;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->userManager->create();
        /* @var UserInterface $user */
        $user->setUsername('tester');
        $user->setEmail('tester@test.com');
        $user->setPlainPassword('tester');
        $user->setEnabled(true);
        $user->setFirstName($this->faker->firstName);
        $user->setLastName($this->faker->lastName);

        $this->userManager->update($user);

        $user = $this->userManager->create();
        $user->setUsername($this->faker->userName);
        $user->setEmail($this->faker->email);
        $user->setPlainPassword($this->faker->password);
        $user->setEnabled(true);

        $this->userManager->update($user, true);
    }
}
