<?php

namespace Talav\AvatarBundle\Controller\Frontend;

use AvatarAppBundle\DataFixtures\UserFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Talav\Component\User\Repository\UserRepositoryInterface;

class AvatarControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->client->followRedirects();
        $this->client->getContainer()->get(DatabaseToolCollection::class)->get()->loadFixtures([UserFixtures::class]);
        $this->userRepository = $this->client->getContainer()->get('app.repository.user');
        $this->client->loginUser($this->userRepository->findOneByEmail('tester@test.com'));
    }

    /**
     * @test
     */
    public function it_shows_update_avatar_page()
    {
        $this->client->request('GET', '/user/profile/avatar');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function it_allows_to_add_avatar()
    {
        $crawler = $this->client->request('GET', '/user/profile/avatar');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Submit')->form();
        $uploadField = $form->get('media[file]');
        $uploadField->upload(new UploadedFile(__DIR__.'/../../../var/fixtures/avatar.jpeg', 'avatar.jpeg', null, null, true));
        $this->client->submit($form);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        $user = $this->userRepository->findOneByEmail('tester@test.com');
        self::assertNotNull($user->getAvatar());
        self::assertNotNull($user->getAvatar()->getName());
        self::assertNotNull($user->getAvatar()->getDescription());
    }
}
