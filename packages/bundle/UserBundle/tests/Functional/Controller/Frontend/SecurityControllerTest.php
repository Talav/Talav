<?php

namespace Talav\UserBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Talav\UserBundle\Tests\Functional\Setup\Doctrine;
use Talav\UserBundle\Tests\Functional\Setup\SymfonyKernel;

class SecurityControllerTest extends KernelTestCase
{
    use SymfonyKernel;
    use Doctrine;

    /**
     * @test
     */
    public function it_correctly_shows_login_page()
    {
        $client = $this->getClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotNull($crawler->selectLink("Register")->getNode(0));
        $this->assertNotNull($crawler->selectLink("Forgot password?")->getNode(0));
    }

    /**
     * @test
     */
    public function it_shows_error_message_for_incorrect_credentials()
    {
        $client = $this->getClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('submit')->form();
        $form['_username'] = 'incorrect';
        $form['_password'] = 'incorrect';
        $client->followRedirects(true);
        $crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Username could not be found', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_logout_link_for_correct_credentials()
    {
        $client = $this->getClient();
        $crawler = $this->login($client);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('logout', $crawler->html());
    }

    /**
     * @test
     */
    public function it_supports_logout()
    {
        $client = $this->getClient();
        $crawler = $this->login($client);
        $link = $crawler->selectLink('Log out')->link();
        $crawler = $client->click($link);
        $this->assertStringNotContainsStringIgnoringCase('logout', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('login', $crawler->html());
    }

    private function login($client): Crawler
    {
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('submit')->form();
        $form['_username'] = 'tester';
        $form['_password'] = 'tester';
        $client->followRedirects(true);
        return $client->submit($form);
    }
}
