<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Tests\Unit\Comparator;

use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

use Talav\Component\Plan\Comparator\FeatureValueMap;
use Talav\Component\Plan\Model\Feature;
use Talav\Component\Plan\Model\FeatureInterface;
use Talav\Component\Plan\Model\FeatureOverride;
use Talav\Component\Plan\Model\FeatureValue;
use Talav\Component\Plan\Tests\Helper\Plan;
use Talav\Component\Plan\Tests\Helper\User;

class FeatureValueMapTest extends TestCase
{
    const KEY_CAN_POST = 'can_private_post';
    const KEY_MAX_POST = 'max_posts';

    const VALUE_CAN_POST = true;
    const VALUE_MAX_POST = 10;
    const VALUE_MAX_POST_OVERRIDE = 20;

    /**
     * @test
     */
    public function it_correctly_creates_object_from_plan()
    {
        $featureList = FeatureValueMap::fromPlan($this->buildPlan());
        $this->assertTrue($featureList->containsKey(self::KEY_CAN_POST));
        $this->assertTrue($featureList->containsKey(self::KEY_MAX_POST));
        $this->assertEquals(self::VALUE_CAN_POST, $featureList->get(self::KEY_CAN_POST));
        $this->assertEquals(self::VALUE_MAX_POST, $featureList->get(self::KEY_MAX_POST));
    }

    /**
     * @test
     */
    public function it_correctly_creates_object_from_plan_and_overrides()
    {
        $featureList = FeatureValueMap::fromPlanWithOverrides($this->buildPlan(), $this->buildOverrides());

        $this->assertTrue($featureList->containsKey(self::KEY_CAN_POST));
        $this->assertTrue($featureList->containsKey(self::KEY_MAX_POST));
        $this->assertEquals(self::VALUE_CAN_POST, $featureList->get(self::KEY_CAN_POST));
        // only 1 value should be changed
        $this->assertEquals(self::VALUE_MAX_POST_OVERRIDE, $featureList->get(self::KEY_MAX_POST));
    }

    /**
     * @return Plan
     */
    private function buildPlan(): Plan
    {
        $plan1 = new Plan();
        $feature1 = Feature::construct("Can submit private post",self::KEY_CAN_POST, FeatureInterface::TYPE_BOOL);
        $feature2 = Feature::construct("Max posts",self::KEY_MAX_POST, FeatureInterface::TYPE_INT);
        FeatureValue::construct($feature1, $plan1, self::VALUE_CAN_POST);
        FeatureValue::construct($feature2, $plan1, self::VALUE_MAX_POST);
        return $plan1;
    }

    /**
     * @return ArrayCollection
     */
    private function buildOverrides(): ArrayCollection
    {
        $user = new User();
        $feature = Feature::construct("Max posts",self::KEY_MAX_POST, FeatureInterface::TYPE_INT);
        return new ArrayCollection([FeatureOverride::construct($feature, $user, self::VALUE_MAX_POST_OVERRIDE)]);
    }
}