<?php

namespace Talav\AvatarBundle\Controller\Frontend;

use AvatarAppBundle\DataFixtures\UserFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Talav\Component\User\Repository\UserRepositoryInterface;

class AvatarControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->client->getContainer()->get(DatabaseToolCollection::class)->get()->loadFixtures([UserFixtures::class]);
        $this->userRepository = $this->client->getContainer()->get('app.repository.user');
        $this->client->loginUser($this->userRepository->findOneByEmail('tester@test.com'));
    }

    /**
     * Submit form.
     */
    private function submitMediaTypeForm(string $uri, ?string $file = null): Crawler
    {
        $crawler = $this->client->request('GET', $uri);
        $form = $crawler->selectButton('Submit')->form();
        if (!is_null($file)) {
            /** @var FileFormField $uploadField */
            $uploadField = $form->get('talav_avatar_type[avatar][file]');
            $uploadField->upload($file);
        }
        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $crawler;
    }
}
