<?php

declare(strict_types=1);

namespace Talav\Component\User\Tests\Unit\Security;

use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\PlaintextPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Talav\Component\User\Security\PasswordUpdater;
use Talav\Component\User\Tests\Helper\User;

final class PasswordUpdaterTest extends TestCase
{
    private PasswordUpdater $updater;

    public function setUp(): void
    {
        $this->updater = new PasswordUpdater(new UserPasswordHasher(new PasswordHasherFactory(
            [User::class => new PlaintextPasswordHasher()]
        )));
    }

    /**
     * @test
     */
    public function it_replaces_password_if_new_provided()
    {
        $password = 'test';
        $user = new User();
        $user->setPlainPassword('test');
        $this->updater->updatePassword($user);
        $this->assertNull($user->getPlainPassword());
        $this->assertEquals($password, $user->getPassword());
    }

    /**
     * @test
     */
    public function it_does_not_change_existing_password_if_new_not_provided()
    {
        $password = 'test';
        $user = new User();
        $user->setPassword($password);
        $this->updater->updatePassword($user);
        $this->assertNull($user->getPlainPassword());
        $this->assertEquals($password, $user->getPassword());
    }
}
