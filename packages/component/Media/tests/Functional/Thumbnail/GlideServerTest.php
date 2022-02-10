<?php

declare(strict_types=1);

namespace Talav\Component\Media\Tests\Functional\Thumbnail;

use Faker\Factory as FakerFactory;
use Faker\Generator;
use League\Flysystem\Filesystem;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use League\Glide\Server as LeagueServer;
use League\Glide\ServerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ValidatorBuilder;
use Talav\Component\Media\Cdn\Server;
use Talav\Component\Media\Generator\UuidGenerator;
use Talav\Component\Media\Model\Media;
use Talav\Component\Media\Provider\Constraints;
use Talav\Component\Media\Provider\ImageProvider;
use Talav\Component\Media\Thumbnail\GlideServer;

class GlideServerTest extends TestCase
{
    protected ImageProvider $provider;

    protected GlideServer $glideServer;

    protected LeagueServer $leagueServer;

    protected Generator $faker;

    protected function setUp(): void
    {
        $this->faker = FakerFactory::create();
        $this->leagueServer = ServerFactory::create([
            'source' => sys_get_temp_dir(),
            'cache' => sys_get_temp_dir(),
        ]);
        $this->glideServer = new GlideServer($this->leagueServer);
        $this->fs = new Filesystem(new InMemoryFilesystemAdapter());
        $cdn = new Server(sys_get_temp_dir());
        $generator = new UuidGenerator();
        $validator = (new ValidatorBuilder())->getValidator();
        $this->provider = new ImageProvider('image1', $this->fs, $cdn, $generator, $validator, $this->glideServer, new Constraints(['png'], [], []));
        $this->provider->addFormat('format1', ['w' => 50, 'h' => 50]);
        $this->provider->addFormat('format2', ['w' => 150, 'h' => 150]);
        $this->provider->addFormat('format3', ['w' => 250, 'h' => 50]);
    }

    /**
     * @test
     */
    public function it_generates_all_thumbnails()
    {
        $media = $this->createMedia();
        $this->provider->postPersist($media);
        $this->glideServer->generate($this->provider, $media);
        foreach ($this->provider->getFormats() as $format) {
            self::assertTrue($this->glideServer->isThumbExists($this->provider, $media, $format));
        }
    }

    /**
     * @test
     */
    public function it_deletes_all_thumbnails()
    {
        $media = $this->createMedia();
        $this->provider->postPersist($media);
        $this->glideServer->generate($this->provider, $media);
        $this->provider->postRemove($media);
        foreach ($this->provider->getFormats() as $format) {
            self::assertFalse($this->glideServer->isThumbExists($this->provider, $media, $format));
        }
    }

    protected function createMedia(string $providerName = 'image1', string $context = 'test1'): Media
    {
        // creates a file and returns its path
        $path = $this->faker->image(sys_get_temp_dir(), 800, 600, 'cats');
        $media = new Media();
        $media->setFile(new UploadedFile($path, 'test.png'));
        $media->setProviderName($providerName);
        $media->setContext($context);

        return $media;
    }
}
