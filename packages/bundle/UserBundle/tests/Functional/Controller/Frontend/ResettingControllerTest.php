<?php

namespace Talav\UserBundle\Controller\Frontend;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\Component\User\Model\UserInterface;
use Talav\UserBundle\Tests\Functional\Setup\Doctrine;
use Talav\UserBundle\Tests\Functional\Setup\SymfonyKernel;

class ResettingControllerTest extends KernelTestCase
{
    use SymfonyKernel;
    use Doctrine;

    /**
     * @test
     */
    public function it_correctly_shows_reset_page()
    {
        $client = $this->getClient();
        $crawler = $client->request('GET', '/reset');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotNull($crawler->selectLink('Log in')->getNode(0));
    }

    /**
     * @test
     */
    public function it_shows_error_message_for_empty_values()
    {
        $client = $this->getClient();
        $crawler = $this->submitForm($client);
        $this->assertStringContainsStringIgnoringCase('The username is not found.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_request_password_reset_for_valid_user()
    {
        $client = $this->getClient();
        $crawler = $this->submitForm($client, [
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        $this->assertStringContainsStringIgnoringCase('Password reset confirmation email was sent to your email', $crawler->html());
    }

    /**
     * @test
     */
    public function it_redirects_to_login_page_if_reset_token_not_found()
    {
        $client = $this->getClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/reset/invalid_token');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Incorrect password reset token', $crawler->html());
    }

    /**
     * @test
     */
    public function it_redirects_to_password_reset_page_for_expired_token()
    {
        $client = $this->getClient();
        $this->submitForm($client, [
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        /** @var UserInterface $user */
        $user = self::$kernel->getContainer()->get('app.manager.user')->findUserByEmail('tester@test.com');
        $this->assertNotNull($user);
        $user->setPasswordRequestedAt(new DateTime('2018-12-12'));
        self::$kernel->getContainer()->get('app.manager.user')->update($user, true);
        $crawler = $client->request('GET', '/reset/'.$user->getPasswordResetToken());
        $this->assertStringContainsStringIgnoringCase('Password reset token expired. Please try again', $crawler->html());
    }

    /**
     * @test
     */
    public function it_does_not_allow_to_generate_tokens_too_often()
    {
        $client = $this->getClient();
        $this->submitForm($client, [
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        $crawler = $this->submitForm($client, [
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        $this->assertStringContainsStringIgnoringCase('Password request has been sent too many times. Please wait and try again later', $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_open_reset_password()
    {
        $this->openResetPasswordPage($this->getClient());
    }

    /**
     * @test
     */
    public function it_shows_error_for_mismatched_passwords()
    {
        $client = $this->getClient();
        $crawler = $this->submitResetPasswordForm($client, [
            'talav_user_reset_password[plainPassword][first]' => 'first',
            'talav_user_reset_password[plainPassword][second]' => 'second',
        ]);
        $this->assertStringContainsStringIgnoringCase("The entered passwords don't match.", $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_change_password_and_login_with_new_password()
    {
        $client = $this->getClient();
        $crawler = $this->submitResetPasswordForm($client, [
            'talav_user_reset_password[plainPassword][first]' => 'pass',
            'talav_user_reset_password[plainPassword][second]' => 'pass',
        ]);
        $this->assertStringContainsStringIgnoringCase('The password has been reset successfully.', $crawler->html());
        $form = $crawler->selectButton('submit')->form();
        $form['_username'] = 'tester';
        $form['_password'] = 'pass';
        $crawler = $client->submit($form);
        $this->assertStringContainsStringIgnoringCase('logout', $crawler->html());
        /** @var UserInterface $user */
        $user = self::$kernel->getContainer()->get('app.manager.user')->findUserByEmail('tester@test.com');
        $this->assertNotNull($user);
        $this->assertNull($user->getPasswordResetToken());
        $this->assertNull($user->getPasswordRequestedAt());
    }

    /**
     * @param $client
     * @param array $data
     *
     * @return Crawler
     */
    private function submitForm($client, $data = []): Crawler
    {
        $crawler = $client->request('GET', '/reset');
        $form = $crawler->selectButton('Reset password')->form();
        foreach ($data as $key => $value) {
            $form[$key] = $value;
        }
        $client->followRedirects(true);
        $crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $crawler;
    }

    /**
     * @param $client
     *
     * @return Crawler
     */
    private function openResetPasswordPage($client): Crawler
    {
        $this->submitForm($client, [
            'talav_user_reset_password_request[user]' => 'tester@test.com',
        ]);
        /** @var UserInterface $user */
        $user = self::$kernel->getContainer()->get('app.manager.user')->findUserByEmail('tester@test.com');
        $this->assertNotNull($user);
        $crawler = $client->request('GET', '/reset/'.$user->getPasswordResetToken());
        $this->assertStringContainsStringIgnoringCase('Change password', $crawler->html());
        return $crawler;
    }

    /**
     * @param $client
     * @param array $data
     *
     * @return Crawler
     */
    private function submitResetPasswordForm($client, $data = []): Crawler
    {
        $crawler = $this->openResetPasswordPage($client);
        $form = $crawler->selectButton('Change password')->form();
        foreach ($data as $key => $value) {
            $form[$key] = $value;
        }
        $client->followRedirects(true);
        $crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $crawler;
    }
}
