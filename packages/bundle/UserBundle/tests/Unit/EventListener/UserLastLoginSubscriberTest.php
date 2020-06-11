<?php

declare(strict_types=1);

namespace Talav\UserBundle\EventListener\Tests;

use AppBundle\Entity\Media;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Talav\Component\User\Manager\UserManagerInterface;
use Talav\UserBundle\Event\UserEvent;
use Talav\UserBundle\EventSubscriber\UserLastLoginSubscriber;

final class UserLastLoginSubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function it_updates_last_login_on_user_event()
    {
        $subscriber = new UserLastLoginSubscriber($this->getManagerMock());
        $user = new Media();
        $event = new UserEvent($user);
        $subscriber->onImplicitLogin($event);
        $this->assertNotNull($user->getLastLogin());
    }

    /**
     * @test
     */
    public function it_updates_last_login_on_interactive_login()
    {
        $subscriber = new UserLastLoginSubscriber($this->getManagerMock());
        $user = new Media();
        $event = new InteractiveLoginEvent(new Request(), new UsernamePasswordToken($user, '', 'test'));
        $subscriber->onSecurityInteractiveLogin($event);
        $this->assertNotNull($user->getLastLogin());
    }

    private function getManagerMock()
    {
        $mock = $this->createMock(UserManagerInterface::class);
        $mock->expects($this->once())->method('getClassName')->willReturn(Media::class);
        $mock->expects($this->once())->method('update')->withAnyParameters();
        return $mock;
    }
}
