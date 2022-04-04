<?php

declare(strict_types=1);

namespace Talav\Component\Media\Tests\Functional\Provider;

use Doctrine\ORM\EntityManager;
use League\Flysystem\Filesystem;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ValidatorBuilder;
use Talav\Component\Media\Cdn\Server;
use Talav\Component\Media\Context\ContextConfig;
use Talav\Component\Media\Generator\UuidGenerator;
use Talav\Component\Media\Provider\Constraints;
use Talav\Component\Media\Provider\FileProvider;
use Talav\Component\Media\Provider\ProviderPool;
use Talav\Component\Media\Subscriber\MediaEventSubscriber;
use Talav\Component\Media\Tests\Functional\Setup\Entity\MediaEntity;
use Webfactory\Doctrine\ORMTestInfrastructure\ORMInfrastructure;

final class FileProviderTest extends TestCase
{
    private const FILE_CONTENT = 'File content';
    private const FILE_NAME = 'test_file.txt';

    private ORMInfrastructure $infrastructure;

    private EntityManager $em;

    private ProviderPool $pool;

    private Filesystem $fs;

    protected function setUp(): void
    {
        $this->fs = new Filesystem(new InMemoryFilesystemAdapter());
        $cdn = new Server(sys_get_temp_dir());
        $generator = new UuidGenerator();
        $validator = (new ValidatorBuilder())->getValidator();
        $provider1 = new FileProvider('file1', $this->fs, $cdn, $generator, $validator, new Constraints(['txt'], ['mimeTypes' => [
            'text/plain',
        ]], []));
        $provider2 = new FileProvider('file2', $this->fs, $cdn, $generator, $validator, new Constraints(['jpeg'], [], []));
        $provider3 = new FileProvider('file3', $this->fs, $cdn, $generator, $validator, new Constraints([], ['mimeTypes' => [
            'image/jpeg',
        ]], []));
        $provider4 = new FileProvider('file4', $this->fs, $cdn, $generator, $validator, new Constraints([]));
        $this->pool = new ProviderPool();
        $this->pool->addContext(new ContextConfig('test1', [$provider1], []));
        $this->pool->addContext(new ContextConfig('test2', [$provider2], []));
        $this->pool->addContext(new ContextConfig('test3', [$provider3], []));
        $this->pool->addContext(new ContextConfig('test4', [$provider4], []));
        $subscriber = new MediaEventSubscriber($this->pool);
        $this->infrastructure = ORMInfrastructure::createWithDependenciesFor(MediaEntity::class);
        $this->em = $this->infrastructure->getEntityManager();
        $this->infrastructure->getEventManager()->addEventSubscriber($subscriber);
    }

    /**
     * @test
     */
    public function it_validates_extension_in_pre_persist_hook()
    {
        self::expectException("\Talav\Component\Media\Exception\InvalidMediaException");
        self::expectExceptionMessage('Invalid media file');
        $this->createMedia('file2');
    }

    /**
     * @test
     */
    public function it_validates_mime_type_in_pre_persist_hook()
    {
        self::expectException("\Talav\Component\Media\Exception\InvalidMediaException");
        self::expectExceptionMessage('Invalid media file');
        $this->createMedia('file3');
    }

    /**
     * @test
     */
    public function it_creates_file_from_post_persist_hook()
    {
        $media = $this->createMedia();
        self::assertEquals(self::FILE_CONTENT, $this->pool->getProvider('file1')->getMediaContent($media));
        self::assertEquals(self::FILE_NAME, $media->getFileName());
    }

    /**
     * @test
     */
    public function it_creates_file_without_any_extension_from_post_persist_hook()
    {
        $media = $this->createMedia('file4', 'test4');
        self::assertEquals(self::FILE_CONTENT, $this->pool->getProvider('file4')->getMediaContent($media));
        self::assertEquals(self::FILE_NAME, $media->getFileName());
    }

    /**
     * @test
     */
    public function it_removes_file_from_post_remove_hooks()
    {
        $media = $this->createMedia();
        $path = $this->pool->getProvider('file1')->getFilesystemReference($media);
        self::assertTrue($this->fs->fileExists($path));
        $this->em->remove($media);
        $this->em->flush();
        self::assertFalse($this->fs->fileExists($path));
    }

    /**
     * @test
     */
    public function it_removes_file_from_post_update_hook()
    {
        $media = $this->createMedia();
        $path = $this->pool->getProvider('file1')->getFilesystemReference($media);
        self::assertTrue($this->fs->fileExists($path));
        $media->setFile($this->createTempFile());
        $this->em->flush();
        self::assertFalse($this->fs->fileExists($path));
    }

    protected function createMedia(string $providerName = 'file1', string $context = 'test1'): MediaEntity
    {
        $media = new MediaEntity();
        $media->setFile($this->createTempFile());
        $media->setProviderName($providerName);
        $media->setContext($context);
        $this->em->persist($media);
        $this->em->flush();

        return $media;
    }

    protected function createTempFile(): UploadedFile
    {
        $tmpfname = tempnam(sys_get_temp_dir(), 'test').'.txt';
        file_put_contents($tmpfname, self::FILE_CONTENT);

        return new UploadedFile($tmpfname, self::FILE_NAME, null, null, true);
    }
}
