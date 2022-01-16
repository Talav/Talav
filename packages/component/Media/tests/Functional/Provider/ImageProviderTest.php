<?php

declare(strict_types=1);

namespace Talav\Component\Media\Tests\Functional\Provider;

use Doctrine\ORM\EntityManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use League\Flysystem\Filesystem;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ValidatorBuilder;
use Talav\Component\Media\Cdn\Server;
use Talav\Component\Media\Context\ContextConfig;
use Talav\Component\Media\Generator\UuidGenerator;
use Talav\Component\Media\Model\Media;
use Talav\Component\Media\Provider\Constraints;
use Talav\Component\Media\Provider\ImageProvider;
use Talav\Component\Media\Provider\ProviderPool;
use Talav\Component\Media\Subscriber\MediaEventSubscriber;
use Talav\Component\Media\Tests\Functional\Setup\Entity\MediaEntity;
use Webfactory\Doctrine\ORMTestInfrastructure\ORMInfrastructure;

class ImageProviderTest extends TestCase
{
    private ORMInfrastructure $infrastructure;

    private EntityManager $em;

    private ProviderPool $pool;

    private Filesystem $fs;

    private Generator $faker;

    protected function setUp(): void
    {
        $this->fs = new Filesystem(new InMemoryFilesystemAdapter());
        $cdn = new Server(sys_get_temp_dir());
        $generator = new UuidGenerator();
        $validator = (new ValidatorBuilder())->getValidator();
        $provider1 = new ImageProvider('file1', $this->fs, $cdn, $generator, $validator, new Constraints([]));
        $provider2 = new ImageProvider('file2', $this->fs, $cdn, $generator, $validator, new Constraints([]));
        $this->pool = new ProviderPool();
        $this->pool->addContext(new ContextConfig('test1', $provider1, []));
        $this->pool->addContext(new ContextConfig('test2', $provider2, []));
        $subscriber = new MediaEventSubscriber($this->pool);
        $this->infrastructure = ORMInfrastructure::createWithDependenciesFor(MediaEntity::class);
        $this->em = $this->infrastructure->getEntityManager();
        $this->infrastructure->getEventManager()->addEventSubscriber($subscriber);
        $this->faker = FakerFactory::create();
    }

    /**
     * @test
     */
    public function it_validates_extension_in_pre_persist_hook()
    {
        // creates a file and returns its path
        $path = $this->faker->image(sys_get_temp_dir(), 800, 600, 'cats');
        $media = new Media();
        $media->setFile(new UploadedFile($path, 'test.png'));
        dd($media);
    }
}
