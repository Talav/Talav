<?php

declare(strict_types=1);

namespace AvatarAppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Talav\Component\User\Message\Command\CreateUserCommand;
use Talav\Component\User\Message\Dto\CreateUserDto;

class UserFixtures extends Fixture
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create();
        $this->messageBus->dispatch(new CreateUserCommand(new CreateUserDto(
            'tester',
            'tester@test.com',
            'tester',
            true,
            $faker->firstName,
            $faker->lastName,
        )));
        $this->messageBus->dispatch(new CreateUserCommand(new CreateUserDto(
            $faker->userName,
            $faker->email,
            $faker->password,
            true,
            $faker->firstName,
            $faker->lastName,
        )));
    }
}
