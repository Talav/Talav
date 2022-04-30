<?php

declare(strict_types=1);

namespace MediaAppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Talav\Component\Media\Message\Command\Media\CreateMediaCommand;
use Talav\Component\Media\Message\Dto\Media\CreateMediaDto;
use Talav\Component\Resource\Manager\ManagerInterface;

class AuthorFixtures extends Fixture
{
    private ManagerInterface $authorManager;

    private Generator $faker;

    private MessageBusInterface $messageBus;

    public function __construct(ManagerInterface $authorManager, MessageBusInterface $messageBus)
    {
        $this->authorManager = $authorManager;
        $this->messageBus = $messageBus;
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager)
    {
        $author = $this->authorManager->create();
        $author->setName($this->faker->name);

        $dto = new CreateMediaDto();
        $dto->provider = 'file';
        $dto->context = 'doc';
        $dto->file = new UploadedFile(__DIR__.'/../../../../var/fixtures/test1.txt', 'test1.txt', null, null, true);
        $media = $this->messageBus->dispatch(new CreateMediaCommand($dto))->last(HandledStamp::class)->getResult();

        $author->setMedia($media);
        $this->authorManager->update($author, true);

        $author = $this->authorManager->create();
        $author->setName($this->faker->name);

        $dto = new CreateMediaDto();
        $dto->provider = 'image';
        $dto->context = 'avatar';
        $dto->name = 'Avatar name';
        $dto->description = 'Avatar description';
        $dto->file = new UploadedFile(__DIR__.'/../../../../var/fixtures/avatar.jpeg', 'avatar.jpeg', null, null, true);
        $media = $this->messageBus->dispatch(new CreateMediaCommand($dto))->last(HandledStamp::class)->getResult();
        $author->setMedia($media);

        $this->authorManager->update($author, true);
    }
}
