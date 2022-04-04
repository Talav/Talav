<?php

declare(strict_types=1);

namespace Talav\Component\Media\Tests\Functional\Provider;

use Doctrine\ORM\EntityManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use League\Flysystem\Filesystem;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use League\Glide\ServerFactory;
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
use Talav\Component\Media\Thumbnail\GlideServer;
use Webfactory\Doctrine\ORMTestInfrastructure\ORMInfrastructure;

class ImageProviderTest extends TestCase
{
    private ORMInfrastructure $infrastructure;

    private EntityManager $em;

    private ProviderPool $pool;

    private Filesystem $fs;

    private Generator $faker;

    private GlideServer $glideServer;

    protected function setUp(): void
    {
        $this->fs = new Filesystem(new InMemoryFilesystemAdapter());
        $cdn = new Server(sys_get_temp_dir());
        $generator = new UuidGenerator();
        $validator = (new ValidatorBuilder())->getValidator();
        $this->glideServer = new GlideServer(ServerFactory::create([
            'source' => sys_get_temp_dir(),
            'cache' => sys_get_temp_dir(),
        ]));
        $provider1 = new ImageProvider('image1', $this->fs, $cdn, $generator, $validator, $this->glideServer, new Constraints(['png']));
        $provider1->addFormat('format1', ['w' => 50, 'h' => 50]);
        $provider1->addFormat('format2', ['w' => 150, 'h' => 150]);
        $provider1->addFormat('format3', ['w' => 250, 'h' => 50]);
        $provider2 = new ImageProvider('image2', $this->fs, $cdn, $generator, $validator, $this->glideServer, new Constraints(['png']));
        $this->pool = new ProviderPool();
        $this->pool->addContext(new ContextConfig('test1', [$provider1], []));
        $this->pool->addContext(new ContextConfig('test2', [$provider2], []));
        $subscriber = new MediaEventSubscriber($this->pool);
        $this->infrastructure = ORMInfrastructure::createWithDependenciesFor(MediaEntity::class);
        $this->em = $this->infrastructure->getEntityManager();
        $this->infrastructure->getEventManager()->addEventSubscriber($subscriber);
        $this->faker = FakerFactory::create();
    }

    /**
     * @test
     */
    public function it_creates_thumbnails_from_post_persist_hook()
    {
        $media = $this->createMedia('image1');
        $provider = $this->pool->getProvider('image1');
        foreach ($provider->getFormats() as $format) {
            $this->assertTrue($this->glideServer->isThumbExists($provider, $media, $format));
        }
    }

    /**
     * @test
     */
    public function it_removes_file_from_post_remove_hooks()
    {
        $media = $this->createMedia('image1');
        $provider = $this->pool->getProvider('image1');
        $path = $provider->getFilesystemReference($media);
        self::assertTrue($this->fs->fileExists($path));
        $this->em->remove($media);
        $this->em->flush();
        self::assertFalse($this->fs->fileExists($path));
        foreach ($provider->getFormats() as $format) {
            self::assertFalse($this->glideServer->isThumbExists($provider, $media, $format));
        }
    }

    protected function createMedia(string $providerName = 'image1', string $context = 'test1'): Media
    {
        // creates a file and returns its path
        $path = $this->faker->image(sys_get_temp_dir(), 800, 600, 'dogs');
        $media = new MediaEntity();
        $media->setFile(new UploadedFile($path, 'test.png', null, null, true));
        $media->setProviderName($providerName);
        $media->setContext($context);
        $this->em->persist($media);
        $this->em->flush();

        return $media;
    }
}
