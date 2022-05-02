<?php

declare(strict_types=1);

namespace UserAppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Symfony\Component\Messenger\MessageBusInterface;
use Talav\Component\User\Message\Command\CreateUserCommand;
use Talav\Component\User\Message\Dto\CreateUserDto;

class UserFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private MessageBusInterface $messageBus
    ) {
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->messageBus->dispatch(new CreateUserCommand(new CreateUserDto(
            'tester',
            'tester@test.com',
            'tester',
            true
        )));
        $this->messageBus->dispatch(new CreateUserCommand(new CreateUserDto(
            $this->faker->userName,
            $this->faker->email,
            $this->faker->password,
            true
        )));
    }
}
