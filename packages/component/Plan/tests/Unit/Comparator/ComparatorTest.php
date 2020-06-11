<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Tests\Unit\Comparator;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;
use Talav\Component\Plan\Comparator\Comparator;
use Talav\Component\Plan\Model\Feature;
use Talav\Component\Plan\Model\FeatureInterface;
use Talav\Component\Plan\Model\FeatureOverride;
use Talav\Component\Plan\Model\FeatureValue;
use Talav\Component\Plan\Tests\Helper\Plan;
use Talav\Component\Plan\Tests\Helper\User;
use Talav\Component\Plan\Tests\Helper\ValueProvider;

class ComparatorTest extends TestCase
{
    const KEY_CAN_POST = 'can_private_post';
    const KEY_MAX_POST = 'max_posts';

    const VALUE_CAN_POST = true;
    const VALUE_MAX_POST = 10;
    const VALUE_MAX_POST_OVERRIDE = 20;

    /**
     * @var Security
     */
    private $security;

    /**
     * @before
     */
    public function before()
    {
        $this->security = $this->getMockBuilder(Security::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->security->method("getUser")->willReturn($this->buildUser());
    }

    /**
     * @test
     */
    public function it_allows_features_and_overrides()
    {
        $provider = new ValueProvider(new ArrayCollection([self::KEY_MAX_POST => 15]));
        $comparator = new Comparator($this->security, $provider);
        $this->assertTrue($comparator->isAllowed(self::KEY_CAN_POST));
        $this->assertTrue($comparator->isAllowed(self::KEY_MAX_POST));
    }

    /**
     * @test
     */
    public function it_do_not_allow_if_current_equal_max()
    {
        $provider = new ValueProvider(new ArrayCollection([self::KEY_MAX_POST => 20]));
        $comparator = new Comparator($this->security, $provider);
        $this->assertFalse($comparator->isAllowed(self::KEY_MAX_POST));
    }

    /**
     * Builds user with plan and 1 override
     *
     * @return User
     */
    private function buildUser(): User
    {
        $user = new User();
        $plan = new Plan();
        $feature1 = Feature::construct("Can submit private post",self::KEY_CAN_POST, FeatureInterface::TYPE_BOOL);
        $feature2 = Feature::construct("Max posts",self::KEY_MAX_POST, FeatureInterface::TYPE_INT);
        FeatureValue::construct($feature1, $plan, self::VALUE_CAN_POST);
        FeatureValue::construct($feature2, $plan, self::VALUE_MAX_POST);
        $user->setPlan($plan);

        $feature = Feature::construct("Max posts",self::KEY_MAX_POST, FeatureInterface::TYPE_INT);
        $user->addFeatureOverride(FeatureOverride::construct($feature, $user, self::VALUE_MAX_POST_OVERRIDE));
        return $user;
    }
}
