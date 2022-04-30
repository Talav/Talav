<?php

namespace Talav\UserBundle\Controller\Frontend;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use UserAppBundle\DataFixtures\UserFixtures;

class ChangePasswordControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->client->getContainer()->get(DatabaseToolCollection::class)->get()->loadFixtures([UserFixtures::class]);
    }

    /**
     * @test
     */
    public function it_redirects_to_login_form_for_non_authorized_user(): void
    {
        $this->client->request('GET', '/user/change-password');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirects(true);
        $crawler = $this->client->request('GET', '/user/change-password');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsStringIgnoringCase('Log in', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_change_password_form(): void
    {
        $this->login();
        $crawler = $this->client->request('GET', '/user/change-password');
        $this->assertStringContainsStringIgnoringCase('Change password', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_if_current_password_invalid(): void
    {
        $crawler = $this->submitChangePasswordForm([
            'talav_user_change_password[currentPassword]' => 'invalid current password',
        ]);
        $this->assertStringContainsStringIgnoringCase('The entered password is invalid.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_if_new_passwords_mismatch(): void
    {
        $crawler = $this->submitChangePasswordForm([
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
        $crawler = $this->submitChangePasswordForm([
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
        $crawler = $this->submitChangePasswordForm([
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
        $crawler = $this->submitChangePasswordForm([
            'talav_user_change_password[currentPassword]' => 'tester',
        ]);
        $this->assertStringContainsStringIgnoringCase('Please enter a password.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_change_password(): void
    {
        $crawler = $this->submitChangePasswordForm([
            'talav_user_change_password[currentPassword]' => 'tester',
            'talav_user_change_password[newPassword][first]' => 'tester1',
            'talav_user_change_password[newPassword][second]' => 'tester1',
        ]);
        $this->assertStringContainsStringIgnoringCase('The password has been changed.', $crawler->html());
        $link = $crawler->selectLink('Log out')->link();
        $this->client->click($link);
        $crawler = $this->login(['_password' => 'tester1']);
        $this->assertStringContainsStringIgnoringCase('Log out', $crawler->html());
    }

    /**
     * Login user using login form.
     *
     * @param $params
     */
    private function login($params = []): Crawler
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('submit')->form();
        $form['_username'] = 'tester';
        $form['_password'] = 'tester';
        foreach ($params as $key => $value) {
            $form[$key] = $value;
        }
        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $this->assertStringContainsStringIgnoringCase('Logged in as tester', $crawler->html());

        return $crawler;
    }

    /**
     * Submit change password form.
     *
     * @param array $data
     */
    private function submitChangePasswordForm($data = []): Crawler
    {
        $this->login();
        $crawler = $this->client->request('GET', '/user/change-password');
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
