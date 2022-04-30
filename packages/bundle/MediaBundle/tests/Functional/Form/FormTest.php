<?php

namespace Talav\MediaAppBundle\Form;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use MediaAppBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Talav\Component\Media\Provider\ProviderPool;
use Talav\Component\Resource\Manager\ManagerInterface;

class FormTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected ManagerInterface $authorManager;

    protected ManagerInterface $mediaManager;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->client->getContainer()->get(DatabaseToolCollection::class)->get()->loadFixtures();
        $this->authorManager = self::$kernel->getContainer()->get('app.manager.author');
        $this->mediaManager = self::$kernel->getContainer()->get('app.manager.media');
    }

    /**
     * @test
     */
    public function it_allows_to_submit_form_without_file(): void
    {
        $crawler = $this->submitMediaTypeForm('/test1', 'Test name');
        self::assertStringContainsStringIgnoringCase('Test passed', $crawler->html());
        $author = $this->authorManager->getRepository()->findBy(['name' => 'Test name']);
        self::assertNotNull($author);
    }

    /**
     * @test
     */
    public function it_allows_to_submit_form_with_file(): void
    {
        $filename = tempnam(sys_get_temp_dir(), 'test').'.txt';
        $this->createTxtFile($filename);
        $crawler = $this->submitMediaTypeForm('/test1', 'Test name', $filename);
        self::assertStringContainsStringIgnoringCase('Test passed', $crawler->html());
        $author = $this->authorManager->getRepository()->findOneBy(['name' => 'Test name']);
        self::assertNotNull($author);
        self::assertNotNull($author->getMedia());
        self::assertNotNull($author->getMedia()->getCreatedAt());
        self::assertNotNull($author->getMedia()->getUpdatedAt());
        unlink($filename);
    }

    /**
     * @test
     */
    public function it_correctly_stores_file(): void
    {
        $filename = tempnam(sys_get_temp_dir(), 'test').'.txt';
        $this->createTxtFile($filename);
        $crawler = $this->submitMediaTypeForm('/test1', 'Test name', $filename);
        self::assertStringContainsStringIgnoringCase('Test passed', $crawler->html());
        $author = $this->authorManager->getRepository()->findOneBy(['name' => 'Test name']);
        $media = $author->getMedia();

        /** @var ProviderPool $pool */
        $pool = self::$kernel->getContainer()->get('talav.media.provider.pool');
        $provider = $pool->getProvider($media->getProviderName());
        self::assertEquals('Test file', $provider->getMediaContent($media));
        unlink($filename);
    }

    /**
     * @test
     */
    public function it_errors_if_media_required_not_provided(): void
    {
        $crawler = $this->submitMediaTypeForm('/test2', 'Test name');
        self::assertStringNotContainsString('Test passed', $crawler->html());
        self::assertStringContainsStringIgnoringCase('This value should not be blank.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_errors_if_file_is_too_large(): void
    {
        $filename = tempnam(sys_get_temp_dir(), 'test').'.txt';
        $this->generateRandFile($filename, 5100000);
        $crawler = $this->submitMediaTypeForm('/test1', 'Test name', $filename);
        self::assertStringContainsStringIgnoringCase('The file is too large (5.1 MB). Allowed maximum size is 5 MB', $crawler->html());
        unlink($filename);
    }

    /**
     * @test
     */
    public function it_errors_if_file_has_incorrect_mime_type(): void
    {
        $filename = tempnam(sys_get_temp_dir(), 'test').'.php';
        $this->createPhpFile($filename);
        $crawler = $this->submitMediaTypeForm('/test1', 'Test name', $filename);
        self::assertStringContainsStringIgnoringCase('The mime type of the file is invalid', $crawler->html());
        unlink($filename);
    }

    /**
     * @test
     */
    public function it_errors_if_file_has_incorrect_extension(): void
    {
        $filename = tempnam(sys_get_temp_dir(), 'test').'.bla';
        $this->createTxtFile($filename);
        $crawler = $this->submitMediaTypeForm('/test1', 'Test name', $filename);
        self::assertStringContainsStringIgnoringCase('It\'s not allowed to upload a file with extension', $crawler->html());
        unlink($filename);
    }

    /**
     * @test
     */
    public function it_does_not_modify_media_if_file_is_not_selected(): void
    {
        $author = $this->createAuthorWithMedia();
        $previousMedia = $author->getMedia();
        // initialize doctrine proxy, field does not really matter
        $previousMedia->getProviderReference();

        // Submit form without file
        $crawler = $this->client->request('GET', '/form/test3/'.$author->getId().'/edit');
        $form = $crawler->selectButton('Submit')->form();
        $form->get('talav_media_app_entity[name]')->setValue('Updated author name');
        $this->client->followRedirects(true);
        $this->client->submit($form);

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        $author = $this->authorManager->reload($author);

        self::assertEquals('Updated author name', $author->getName());
        self::assertNotNull($author->getMedia());
        self::assertEquals($previousMedia->getProviderReference(), $author->getMedia()->getProviderReference());
        self::assertEquals($previousMedia->getName(), $author->getMedia()->getName());
    }

    /**
     * @test
     */
    public function it_modifies_media_if_file_is_selected(): void
    {
        $author = $this->createAuthorWithMedia();
        $previousMedia = $author->getMedia();
        // initialize doctrine proxy, field does not really matter
        $previousMedia->getProviderReference();

        // Submit form with file
        $crawler = $this->client->request('GET', '/form/test3/'.$author->getId().'/edit');

        $form = $crawler->selectButton('Submit')->form();
        $filename = tempnam(sys_get_temp_dir(), 'test').'.txt';
        $this->createTxtFile($filename);
        $uploadField = $form->get('talav_media_app_entity[media][file]');
        $uploadField->upload($filename);
        $this->client->followRedirects(true);
        $this->client->submit($form);

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        $author = $this->authorManager->reload($author);

        // relation still exists
        self::assertNotNull($author->getMedia());
        // the same id but different provider reference because files are different
        self::assertNotEquals($previousMedia->getProviderReference(), $author->getMedia()->getProviderReference());
        self::assertNotEquals($previousMedia->getFileInfo()->getName(), $author->getMedia()->getFileInfo()->getName());
        self::assertEquals($previousMedia->getId(), $author->getMedia()->getId());
    }

    /**
     * @test
     */
    public function it_unlinks_and_deletes_media(): void
    {
        $author = $this->createAuthorWithMedia();
        $previousMedia = $author->getMedia();
        $id = $previousMedia->getId();

        $crawler = $this->client->request('GET', '/form/test3/'.$author->getId().'/edit');
        $form = $crawler->selectButton('Submit')->form();
        $form->get('talav_media_app_entity[media][unlink]')->setValue(true);
        $this->client->submit($form);

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        $author = $this->authorManager->reload($author);

        // relation does not exist
        self::assertNull($author->getMedia());
        // and media does not exist
        self::assertNull($this->mediaManager->getRepository()->find($id));
    }

    /**
     * Submit form.
     */
    private function submitMediaTypeForm(string $uri, string $name, ?string $file = null): Crawler
    {
        $crawler = $this->client->request('GET', $uri);
        $form = $crawler->selectButton('Submit')->form();
        $form->get('talav_media_app_entity[name]')->setValue($name);
        if (!is_null($file)) {
            /** @var FileFormField $uploadField */
            $uploadField = $form->get('talav_media_app_entity[media][file]');
            $uploadField->upload($file);
        }
        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $crawler;
    }

    private function generateRandFile($filename, $filesize)
    {
        if ($h = fopen($filename, 'w')) {
            if ($filesize > 1024) {
                for ($i = 0; $i < floor($filesize / 1024); ++$i) {
                    fwrite($h, bin2hex(openssl_random_pseudo_bytes(511)).PHP_EOL);
                }
                $filesize = $filesize - (1024 * $i);
            }
            $mod = $filesize % 2;
            fwrite($h, bin2hex(openssl_random_pseudo_bytes(($filesize - $mod) / 2)));
            if ($mod) {
                fwrite($h, substr(uniqid(), 0, 1));
            }
            fclose($h);
            umask(0000);
            chmod($filename, 0644);
        }
    }

    private function createPhpFile($path)
    {
        $content = "<?php 
echo '1'; ";
        file_put_contents($path, $content);
    }

    private function createTxtFile($path)
    {
        file_put_contents($path, 'Test file');
    }

    private function createAuthorWithMedia(): Author
    {
        // create a new author using form
        $filename = tempnam(sys_get_temp_dir(), 'test').'.txt';
        $this->createTxtFile($filename);
        $crawler = $this->submitMediaTypeForm('/test1', 'Test name', $filename);
        self::assertStringContainsStringIgnoringCase('Test passed', $crawler->html());

        return $this->authorManager->getRepository()->findOneBy(['name' => 'Test name']);
    }
}
