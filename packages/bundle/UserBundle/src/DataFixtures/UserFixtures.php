<?php

declare(strict_types=1);

namespace Talav\UserBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Talav\Component\User\Manager\UserManagerInterface;

class UserFixtures extends Fixture
{
    /** @var UserManagerInterface */
    private $userManager;

    /** @var Generator */
    private $faker;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->userManager->create();
        $user->setUsername('tester');
        $user->setEmail('tester@test.com');
        $user->setPlainPassword('tester');
        $user->setEnabled(true);

        $this->userManager->update($user);

        $user = $this->userManager->create();
        $user->setUsername($this->faker->userName);
        $user->setEmail($this->faker->email);
        $user->setPlainPassword($this->faker->password);
        $user->setEnabled(true);

        $this->userManager->update($user, true);
    }
}
