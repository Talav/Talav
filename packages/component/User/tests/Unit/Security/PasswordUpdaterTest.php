<?php

declare(strict_types=1);

namespace Talav\Component\User\Tests\Unit\Security;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Talav\Component\User\Security\PasswordUpdater;
use Talav\Component\User\Tests\Helper\User;

final class PasswordUpdaterTest extends TestCase
{
    /**
     * @var PasswordUpdater
     */
    private $updater;

    /**
     * @before
     */
    public function setup(): void
    {
        $factory = new EncoderFactory([User::class => new PlaintextPasswordEncoder()]);
        $this->updater = new PasswordUpdater($factory);
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
