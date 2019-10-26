<?php

namespace Talav\UserBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Talav\UserBundle\Tests\Functional\Setup\Doctrine;
use Talav\UserBundle\Tests\Functional\Setup\SymfonyKernel;

class RegistrationControllerTest extends KernelTestCase
{
    use SymfonyKernel;
    use Doctrine;

    /**
     * @test
     */
    public function it_correctly_shows_registration_page()
    {
        $client = $this->getClient();
        $client->request('GET', '/register');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_empty_values()
    {
        $client = $this->getClient();
        $crawler = $this->submitForm($client);
        $this->assertStringContainsStringIgnoringCase('Please enter an email.', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('Please enter a username.', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('Please enter a password.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_incorrect_email()
    {
        $client = $this->getClient();
        $crawler = $this->submitForm($client, ['talav_user_registration[email]' => 'incorrectemail']);
        $this->assertStringContainsStringIgnoringCase('The email is not valid.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_existing_email()
    {
        $client = $this->getClient();
        $crawler = $this->submitForm($client, ['talav_user_registration[email]' => 'tester@test.com']);
        $this->assertStringContainsStringIgnoringCase('The email is already used.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_existing_username()
    {
        $client = $this->getClient();
        $crawler = $this->submitForm($client, ['talav_user_registration[username]' => 'tester']);
        $this->assertStringContainsStringIgnoringCase('The username is already used', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_password_mismatch()
    {
        $client = $this->getClient();
        $crawler = $this->submitForm($client, [
            'talav_user_registration[plainPassword][first]' => 'first',
            'talav_user_registration[plainPassword][second]' => 'second',
        ]);
        $this->assertStringContainsStringIgnoringCase("The entered passwords don't match.", $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_register_and_logs_user()
    {
        $client = $this->getClient();
        $crawler = $this->submitForm($client, [
            'talav_user_registration[username]' => 'tester1',
            'talav_user_registration[email]' => 'tester1@test.com',
            'talav_user_registration[plainPassword][first]' => 'tester1',
            'talav_user_registration[plainPassword][second]' => 'tester1',
        ]);
        $this->assertStringContainsStringIgnoringCase('Logged in as tester1', $crawler->html());
    }

    /**
     * @param $client
     * @param array $data
     *
     * @return Crawler
     */
    private function submitForm($client, $data = []): Crawler
    {
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Register')->form();
        foreach ($data as $key => $value) {
            $form[$key] = $value;
        }
        $client->followRedirects(true);
        $crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $crawler;
    }
}
