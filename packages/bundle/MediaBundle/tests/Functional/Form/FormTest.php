<?php

namespace Talav\MediaAppBundle\Form;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Talav\Component\Media\Provider\ProviderPool;
use Talav\MediaBundle\Tests\Functional\Setup\Doctrine;
use Talav\MediaBundle\Tests\Functional\Setup\SymfonyKernel;

class FormTest extends KernelTestCase
{
    use SymfonyKernel;
    use Doctrine;

    /**
     * @test
     */
    public function it_allows_to_submit_form_without_file(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitMediaTypeForm($client, '/test1', 'Test name');
        self::assertStringContainsStringIgnoringCase('Test passed', $crawler->html());
        $author = self::$kernel->getContainer()->get('app.manager.author')->getRepository()->findBy(['name' => 'Test name']);
        self::assertNotNull($author);
    }

    /**
     * @test
     */
    public function it_allows_to_submit_form_with_file(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitMediaTypeForm($client, '/test1', 'Test name', '1.txt');
        self::assertStringContainsStringIgnoringCase('Test passed', $crawler->html());
        $author = self::$kernel->getContainer()->get('app.manager.author')->getRepository()->findOneBy(['name' => 'Test name']);
        self::assertNotNull($author);
        self::assertNotNull($author->getMedia());
        self::assertNotNull($author->getMedia()->getCreatedAt());
        self::assertNotNull($author->getMedia()->getUpdatedAt());
    }

    /**
     * @test
     */
    public function it_correctly_stores_file(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitMediaTypeForm($client, '/test1','Test name', '1.txt');
        self::assertStringContainsStringIgnoringCase('Test passed', $crawler->html());
        $author = self::$kernel->getContainer()->get('app.manager.author')->getRepository()->findOneBy(['name' => 'Test name']);
        $media = $author->getMedia();

        /** @var ProviderPool $pool */
        $pool = self::$kernel->getContainer()->get('talav.media.provider.pool');
        $provider = $pool->getProvider($media->getProviderName());
        self::assertEquals("Test file", $provider->getMediaContent($media));
    }

    /**
     * @test
     */
    public function it_errors_if_media_required_not_provided(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitMediaTypeForm($client, '/test2', 'Test name');
        self::assertStringNotContainsString('Test passed', $crawler->html());
        self::assertStringContainsStringIgnoringCase('This value should not be blank.', $crawler->html());
    }

    /**
     * Submit form.
     */
    private function submitMediaTypeForm(KernelBrowser $client, string $uri, string $name, ?string $file = null): Crawler
    {
        $crawler = $client->request('GET', $uri);
        $form = $crawler->selectButton('Submit')->form();
        $form->get('talav_media_app_entity[name]')->setValue($name);
        if (!is_null($file)) {
            $path = self::$kernel->locateResource('@MediaAppBundle/Resources/files/' . $file);
            /** @var FileFormField $uploadField */
            $uploadField = $form->get('talav_media_app_entity[media][file]');
            $uploadField->upload($path);
        }
        $client->followRedirects(true);
        $crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $crawler;
    }
}