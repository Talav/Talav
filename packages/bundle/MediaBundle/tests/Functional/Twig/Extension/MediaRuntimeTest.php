<?php

namespace Talav\MediaAppBundle\Twig\Extension;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use MediaAppBundle\DataFixtures\AuthorFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MediaRuntimeTest extends WebTestCase
{
    protected KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->client->getContainer()->get(DatabaseToolCollection::class)->get()->loadFixtures([AuthorFixtures::class]);
    }

    /**
     * @test
     */
    public function it_correctly_renders_media_path_for_txt_document(): void
    {
        self::assertMatchesRegularExpression("/\/uploads\/media\/doc\/0001\/00\/[0-9a-z]*\.txt/", $this->client->request('GET', '/view/test1')->html());
    }

    /**
     * @test
     */
    public function it_correctly_renders_media_path_for_media_document(): void
    {
        self::assertMatchesRegularExpression("/\/uploads\/media\/avatar\/0002\/00\/[0-9a-z]*\.jpeg/", $this->client->request('GET', '/view/test2')->html());
    }

    /**
     * @test
     */
    public function it_correctly_validates_that_thumb_paths_and_media_path_are_different(): void
    {
        $crawler = $this->client->request('GET', '/view/test3')->filter('div');
        // 3 divs
        self::assertCount(3, $crawler);
        // all divs point to the correct path
        self::assertMatchesRegularExpression("/\/uploads\/media\/avatar\/0002\/00\/[0-9a-z]*\.jpeg/", $crawler->getNode(0)->nodeValue);
        self::assertMatchesRegularExpression("/\/uploads\/media\/avatar\/0002\/00\/[0-9a-z]*\.jpeg/", $crawler->getNode(1)->nodeValue);
        self::assertMatchesRegularExpression("/\/uploads\/media\/avatar\/0002\/00\/[0-9a-z]*\.jpeg/", $crawler->getNode(2)->nodeValue);
        // but they are all different
        self::assertNotEquals($crawler->getNode(0)->nodeValue, $crawler->getNode(1)->nodeValue);
        self::assertNotEquals($crawler->getNode(1)->nodeValue, $crawler->getNode(2)->nodeValue);
    }

    /**
     * @test
     */
    public function it_correctly_generates_thumbnail_img_tags(): void
    {
        $crawler = $this->client->request('GET', '/view/test4')->filter('img');
        // 3 images
        self::assertCount(3, $crawler);
        $img0 = $crawler->getNode(0);
        $img1 = $crawler->getNode(1);
        $img2 = $crawler->getNode(2);
        self::assertNotNull($img0);
        self::assertNotNull($img1);
        self::assertNotNull($img2);

        // common attributes are the same
        self::assertEquals($img0->attributes->getNamedItem('src')->nodeValue, $img1->attributes->getNamedItem('src')->nodeValue);
        self::assertEquals($img0->attributes->getNamedItem('width')->nodeValue, $img1->attributes->getNamedItem('width')->nodeValue);
        self::assertEquals($img0->attributes->getNamedItem('height')->nodeValue, $img1->attributes->getNamedItem('height')->nodeValue);
        self::assertEquals($img0->attributes->getNamedItem('title')->nodeValue, $img1->attributes->getNamedItem('title')->nodeValue);

        // alt and class are different
        self::assertNotEquals($img0->attributes->getNamedItem('alt')->nodeValue, $img1->attributes->getNamedItem('alt')->nodeValue);
        self::assertNull($img0->attributes->getNamedItem('class'), 'Image 0 does not have class attribute set from the template');
        self::assertNotNull($img1->attributes->getNamedItem('class'), 'Image 1 has class attribute set from the template');

        // different path fo different thumbnail formats
        self::assertNotEquals($img0->attributes->getNamedItem('src')->nodeValue, $img2->attributes->getNamedItem('src')->nodeValue);

        // need to make sure dimensions are accurate
    }
}
