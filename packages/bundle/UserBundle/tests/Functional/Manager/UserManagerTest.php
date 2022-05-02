<?php

declare(strict_types=1);

namespace Talav\UserBundle\Manager\Tests;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Talav\Component\User\Manager\UserManagerInterface;
use UserAppBundle\DataFixtures\UserFixtures;

final class UserManagerTest extends KernelTestCase
{
    private UserManagerInterface $userManager;

    public function setUp(): void
    {
        $this->userManager = static::getContainer()->get('app.manager.user');
        static::getContainer()->get(DatabaseToolCollection::class)->get()->loadFixtures([UserFixtures::class]);
    }

    /**
     * @test
     */
    public function it_updates_canonical()
    {
        $user = $this->userManager->create();
        $this->addUserData($user);
        $this->userManager->update($user, true);
        $this->assertEquals('test', $user->getUsernameCanonical());
        $this->assertEquals('test@test.com', $user->getEmailCanonical());
    }

    /**
     * @test
     */
    public function it_updates_username_if_its_not_provided()
    {
        $user = $this->userManager->create();
        $user->setEmail('Test@test.com');
        $user->setPassword('test');
        $this->userManager->update($user, true);
        $this->userManager->reload($user);
        $this->assertEquals('test@test.com', $user->getUsernameCanonical());
    }

    /**
     * @test
     */
    public function it_saves_user_and_allows_to_find_it_by_username()
    {
        $user = $this->userManager->create();
        $this->addUserData($user);
        $this->userManager->update($user, true);
        $foundUser = $this->userManager->findUserByUsername('test');
        $this->assertNotNull($foundUser);
    }

    /**
     * @test
     */
    public function it_saves_user_and_allows_to_find_it_by_email()
    {
        $user = $this->userManager->create();
        $this->addUserData($user);
        $this->userManager->update($user, true);
        $foundUser = $this->userManager->findUserByEmail('test@test.com');
        $this->assertNotNull($foundUser);
    }

    /**
     * @test
     */
    public function it_saves_user_and_allows_to_find_it_by_email_or_username()
    {
        $user = $this->userManager->create();
        $this->addUserData($user);
        $this->userManager->update($user, true);
        $foundUser = $this->userManager->findUserByUsernameOrEmail('test@test.com');
        $this->assertNotNull($foundUser);
        $foundUser = $this->userManager->findUserByUsernameOrEmail('test');
        $this->assertNotNull($foundUser);
    }

    /**
     * @test
     */
    public function it_reloads_user()
    {
        $user = $this->userManager->create();
        $this->addUserData($user);
        $this->userManager->update($user, true);
        $this->userManager->reload($user);
        $this->assertNotNull($user->getId());
    }

    /**
     * @test
     */
    public function it_deletes_user()
    {
        $user = $this->userManager->create();
        $this->addUserData($user);
        $this->userManager->update($user, true);
        $this->userManager->reload($user);
        $this->userManager->remove($user);
        $foundUser = $this->userManager->findUserByUsernameOrEmail('test');
        $this->assertNull($foundUser);
    }

    private function addUserData($user)
    {
        $user->setUsername('Test');
        $user->setEmail('Test@test.com');
        $user->setPassword('test');
        $user->setEnabled(true);
    }
}
