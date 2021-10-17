<?php

namespace Talav\Media\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Talav\Component\Media\Model\Media;

class MediaTest extends TestCase
{
    private const FILE_CONTENT = 'File content';
    private const FILE_NAME = 'test_file.txt';

    /**
     * @test
     */
    public function it_creates_provider_reference_if_not_provided(): void
    {
        $file = $this->createTempFile();
        $media = new Media();
        $media->setFile($file);
        self::assertNotNull($media->getProviderReference());
    }

    /**
     * @test
     */
    public function it_copies_corresponding_values_from_file(): void
    {
        $file = $this->createTempFile();
        $media = new Media();
        $media->setFile($file);
        self::assertEquals(self::FILE_NAME, $media->getName());
        self::assertEquals($file->getSize(), $media->getSize());
        self::assertEquals($file->getMimeType(), $media->getMimeType());
        self::assertEquals($file->getExtension(), $media->getFileExtension());
        self::assertEquals($file->getClientOriginalName(), $media->getFileName());
    }

    /**
     * @test
     */
    public function it_correctly_replaces_provider_reference(): void
    {
        $file = $this->createTempFile();
        $media = new Media();
        $media->setFile($file);
        $previous = $media->getProviderReference();
        $media->setFile($file);
        self::assertNotNull($media->getPreviousProviderReference());
        self::assertNotEquals($previous, $media->getProviderReference());
    }

    protected function createTempFile(): UploadedFile
    {
        $tmpfname = tempnam(sys_get_temp_dir(), 'test').'.txt';
        file_put_contents($tmpfname, self::FILE_CONTENT);

        return new UploadedFile($tmpfname, self::FILE_NAME, null, null, true);
    }
}
