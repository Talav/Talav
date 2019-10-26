<?php

namespace Talav\UserBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Talav\UserBundle\Tests\Functional\Setup\Doctrine;
use Talav\UserBundle\Tests\Functional\Setup\SymfonyKernel;

class ChangePasswordControllerTest extends KernelTestCase
{
    use SymfonyKernel;
    use Doctrine;

    /**
     * @test
     */
    public function it_redirects_to_login_form_for_non_authorized_user(): void
    {
        $client = $this->getClient();
        $client->request('GET', '/user/change-password');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client = $this->getClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/user/change-password');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Log in', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_change_password_form(): void
    {
        $client = $this->getClient();
        $this->login($client);
        $crawler = $client->request('GET', '/user/change-password');
        $this->assertStringContainsStringIgnoringCase('Change password', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_if_current_password_invalid(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitChangePasswordForm($client, [
            'talav_user_change_password[currentPassword]' => 'invalid current password',
        ]);
        $this->assertStringContainsStringIgnoringCase('The entered password is invalid.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_if_new_passwords_mismatch(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitChangePasswordForm($client, [
            'talav_user_change_password[currentPassword]' => 'tester',
            'talav_user_change_password[newPassword][first]' => 'first',
            'talav_user_change_password[newPassword][second]' => 'second',
        ]);
        $this->assertStringContainsStringIgnoringCase("The entered passwords don't match", $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_if_new_password_is_too_short(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitChangePasswordForm($client, [
            'talav_user_change_password[currentPassword]' => 'tester',
            'talav_user_change_password[newPassword][first]' => '1',
            'talav_user_change_password[newPassword][second]' => '1',
        ]);
        $this->assertStringContainsStringIgnoringCase('The password is too short.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_if_new_password_is_too_long(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitChangePasswordForm($client, [
            'talav_user_change_password[currentPassword]' => 'tester',
            'talav_user_change_password[newPassword][first]' => str_repeat('1', 255),
            'talav_user_change_password[newPassword][second]' => str_repeat('1', 255),
        ]);
        $this->assertStringContainsStringIgnoringCase('The password is too long.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_if_new_password_is_not_provided(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitChangePasswordForm($client, [
            'talav_user_change_password[currentPassword]' => 'tester',
        ]);
        $this->assertStringContainsStringIgnoringCase('Please enter a password.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_change_password(): void
    {
        $client = $this->getClient();
        $crawler = $this->submitChangePasswordForm($client, [
            'talav_user_change_password[currentPassword]' => 'tester',
            'talav_user_change_password[newPassword][first]' => 'tester1',
            'talav_user_change_password[newPassword][second]' => 'tester1',
        ]);
        $this->assertStringContainsStringIgnoringCase('The password has been changed.', $crawler->html());
        $link = $crawler->selectLink('Log out')->link();
        $client->click($link);
        $crawler = $this->login($client, ['_password' => 'tester1']);
        $this->assertStringContainsStringIgnoringCase('Log out', $crawler->html());
    }

    /**
     * Logic user.
     *
     * @param $client
     * @param $params
     *
     * @return Crawler
     */
    private function login($client, $params = []): Crawler
    {
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('submit')->form();
        $form['_username'] = 'tester';
        $form['_password'] = 'tester';
        foreach ($params as $key => $value) {
            $form[$key] = $value;
        }
        $client->followRedirects(true);
        $crawler = $client->submit($form);
        $this->assertStringContainsStringIgnoringCase('Logged in as tester', $crawler->html());
        return $crawler;
    }

    /**
     * Submit change password form.
     *
     * @param $client
     * @param array $data
     *
     * @return Crawler
     */
    private function submitChangePasswordForm($client, $data = []): Crawler
    {
        $this->login($client);
        $crawler = $client->request('GET', '/user/change-password');
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