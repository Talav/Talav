<?php

namespace Talav\UserBundle\Controller\Frontend;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use UserAppBundle\DataFixtures\UserFixtures;

class RegistrationControllerTest extends WebTestCase
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
    public function it_correctly_shows_registration_page()
    {
        $crawler = $this->client->request('GET', '/register');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertNotNull($crawler->selectLink('Log in')->getNode(0));
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_empty_values()
    {
        $crawler = $this->submitForm();
        $this->assertStringContainsStringIgnoringCase('Please enter an email.', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('Please enter a username.', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('Please enter a password.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_incorrect_email()
    {
        $crawler = $this->submitForm(['talav_user_registration[email]' => 'incorrectemail']);
        $this->assertStringContainsStringIgnoringCase('The email is not valid.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_existing_email()
    {
        $crawler = $this->submitForm(['talav_user_registration[email]' => 'tester@test.com']);
        $this->assertStringContainsStringIgnoringCase('The email is already used.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_existing_username()
    {
        $crawler = $this->submitForm(['talav_user_registration[username]' => 'tester']);
        $this->assertStringContainsStringIgnoringCase('The username is already used', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_password_mismatch()
    {
        $crawler = $this->submitForm([
            'talav_user_registration[plainPassword][first]' => 'first',
            'talav_user_registration[plainPassword][second]' => 'second',
        ]);
        $this->assertStringContainsStringIgnoringCase("The entered passwords don't match.", $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_register_and_authenticate_user_redirect_to_success_page()
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $this->submitForm([
            'talav_user_registration[username]' => 'tester1',
            'talav_user_registration[email]' => 'tester1@test.com',
            'talav_user_registration[plainPassword][first]' => 'tester1',
            'talav_user_registration[plainPassword][second]' => 'tester1',
        ]);
        $this->assertEmailCount(1);
        $email = $this->getMailerMessage(0);
        $this->assertEmailHeaderSame($email, 'To', 'tester1@test.com');
        $this->assertEmailHeaderSame($email, 'Subject', 'Welcome email');
        $crawler = $this->client->followRedirect();
        $this->assertStringContainsStringIgnoringCase('Logged in as tester1', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('Page after registration', $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_register_and_sends_welcome_email()
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $this->submitForm([
            'talav_user_registration[username]' => 'tester1',
            'talav_user_registration[email]' => 'tester1@test.com',
            'talav_user_registration[plainPassword][first]' => 'tester1',
            'talav_user_registration[plainPassword][second]' => 'tester1',
        ]);
        $this->assertEmailCount(1);
        $email = $this->getMailerMessage(0);
        $this->assertEmailHeaderSame($email, 'To', 'tester1@test.com');
        $this->assertEmailHeaderSame($email, 'Subject', 'Welcome email');
    }

    /**
     * @param array $data
     */
    private function submitForm($data = []): Crawler
    {
        $crawler = $this->client->request('GET', '/register');
        $form = $crawler->selectButton('Register')->form();
        foreach ($data as $key => $value) {
            $form[$key] = $value;
        }

        return $this->client->submit($form);
    }
}
