<?php

namespace Talav\UserBundle\Controller\Frontend;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use UserAppBundle\DataFixtures\UserFixtures;

class SecurityControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->databaseTool = $this->client->getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([UserFixtures::class]);
    }

    /**
     * @test
     */
    public function it_correctly_shows_login_page()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertNotNull($crawler->selectLink('Register')->getNode(0));
        $this->assertNotNull($crawler->selectLink('Forgot password?')->getNode(0));
    }

    /**
     * @test
     */
    public function it_shows_error_message_for_incorrect_credentials()
    {
        $crawler = $this->login('incorrect', 'incorrect');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Invalid credentials', $crawler->html());
    }

    /**
     * @test
     */
    public function it_redirects_to_home_page_and_shows_logout_link_for_correct_credentials()
    {
        $crawler = $this->login();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('logout', $crawler->html());
    }

    /**
     * @test
     */
    public function it_redirects_to_profile_page_after_login_if_user_tried_access_profile()
    {
        $this->client->request('GET', '/user/profile');
        $crawler = $this->login();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('profile', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('logout', $crawler->html());
    }

    /**
     * @test
     */
    public function it_supports_logout()
    {
        $crawler = $this->login();
        $link = $crawler->selectLink('Log out')->link();
        $crawler = $this->client->click($link);
        $this->assertStringNotContainsStringIgnoringCase('logout', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('login', $crawler->html());
    }

    private function login(string $username = 'tester', string $password = 'tester'): Crawler
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('submit')->form();
        $form['_username'] = $username;
        $form['_password'] = $password;
        $this->client->followRedirects(true);

        return $this->client->submit($form);
    }
}
