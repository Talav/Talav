<?php

namespace Talav\UserBundle\Controller\Frontend;

use DateTime;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Talav\Component\User\Model\UserInterface;
use UserAppBundle\DataFixtures\UserFixtures;

class ResettingControllerTest extends WebTestCase
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
    public function it_correctly_shows_reset_page()
    {
        $crawler = $this->client->request('GET', '/reset');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertNotNull($crawler->selectLink('Log in')->getNode(0));
    }

    /**
     * @test
     */
    public function it_shows_error_message_for_empty_values()
    {
        $crawler = $this->submitForm();
        $this->assertStringContainsStringIgnoringCase('The username is not found.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_request_password_reset_for_valid_user()
    {
        $crawler = $this->submitForm([
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        $this->assertStringContainsStringIgnoringCase(
            'Password reset confirmation email was sent to your email',
            $crawler->html()
        );
    }

    /**
     * @test
     */
    public function it_redirects_to_login_page_if_reset_token_not_found()
    {
        $this->client->followRedirects(true);
        $crawler = $this->client->request('GET', '/reset/invalid_token');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Incorrect password reset token', $crawler->html());
    }

    /**
     * @test
     */
    public function it_redirects_to_password_reset_page_for_expired_token()
    {
        $this->submitForm([
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        /** @var UserInterface $user */
        $user = self::$kernel->getContainer()->get('app.manager.user')->findUserByEmail('tester@test.com');
        $this->assertNotNull($user);
        // emulate that password request has been sent long time ago
        $user->setPasswordRequestedAt(new DateTime('2018-12-12'));
        self::$kernel->getContainer()->get('app.manager.user')->update($user, true);
        $crawler = $this->client->request('GET', '/reset/'.$user->getPasswordResetToken());
        $this->assertStringContainsStringIgnoringCase(
            'Password reset token expired. Please try again',
            $crawler->html()
        );
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_generate_tokens_too_often()
    {
        $this->submitForm([
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        $crawler = $this->submitForm([
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        $this->assertStringContainsStringIgnoringCase(
            'Password request has been sent too many times. Please wait and try again later',
            $crawler->html()
        );
    }

    /**
     * @test
     */
    public function it_allows_to_open_reset_password()
    {
        $this->openResetPasswordPage();
    }

    /**
     * @test
     */
    public function it_shows_error_for_mismatched_passwords()
    {
        $crawler = $this->submitResetPasswordForm([
            'talav_user_reset_password[password][first]' => 'first',
            'talav_user_reset_password[password][second]' => 'second',
        ]);
        $this->assertStringContainsStringIgnoringCase("The entered passwords don't match.", $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_change_password_and_login_with_new_password()
    {
        $crawler = $this->submitResetPasswordForm([
            'talav_user_reset_password[password][first]' => 'pass',
            'talav_user_reset_password[password][second]' => 'pass',
        ]);
        $this->assertStringContainsStringIgnoringCase('The password has been reset successfully.', $crawler->html());
        $form = $crawler->selectButton('submit')->form();
        $form['_username'] = 'tester';
        $form['_password'] = 'pass';
        $crawler = $this->client->submit($form);
        $this->assertStringContainsStringIgnoringCase('logout', $crawler->html());
        /** @var UserInterface $user */
        $user = self::$kernel->getContainer()->get('app.manager.user')->findUserByEmail('tester@test.com');
        $this->assertNotNull($user);
        $this->assertNull($user->getPasswordResetToken());
        $this->assertNull($user->getPasswordRequestedAt());
    }

    /**
     * @param array $data
     */
    private function submitForm($data = []): Crawler
    {
        $crawler = $this->client->request('GET', '/reset');
        $form = $crawler->selectButton('Reset password')->form();
        foreach ($data as $key => $value) {
            $form[$key] = $value;
        }
        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $crawler;
    }

    private function openResetPasswordPage(): Crawler
    {
        $this->submitForm([
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        /** @var UserInterface $user */
        $user = self::$kernel->getContainer()->get('app.manager.user')->findUserByEmail('tester@test.com');
        $this->assertNotNull($user);
        $crawler = $this->client->request('GET', '/reset/'.$user->getPasswordResetToken());
        $this->assertStringContainsStringIgnoringCase('Change password', $crawler->html());

        return $crawler;
    }

    /**
     * @param array $data
     */
    private function submitResetPasswordForm($data = []): Crawler
    {
        $crawler = $this->openResetPasswordPage();
        $form = $crawler->selectButton('Change password')->form();
        foreach ($data as $key => $value) {
            $form[$key] = $value;
        }
        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $crawler;
    }
}
