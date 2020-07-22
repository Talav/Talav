<?php

declare(strict_types=1);

namespace Talav\Component\Registry\Registry;

use Talav\Component\Registry\Exception\NonExistingServiceException;
use Webmozart\Assert\Assert;
use Zend\Stdlib\PriorityQueue;

final class PrioritizedServiceRegistry implements PrioritizedServiceRegistryInterface
{
    private PriorityQueue $services;

    /**
     * Interface which is required by all services.
     */
    private string $interface;

    /**
     * Human readable context for these services, e.g. "tax calculation"
     */
    private string $context;

    public function __construct(string $interface, string $context = 'service')
    {
        $this->interface = $interface;
        $this->services = new PriorityQueue();
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): iterable
    {
        foreach ($this->services as $service) {
            yield $service;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register($service, int $priority = 0): void
    {
        $this->assertServiceHaveType($service);
        $this->services->insert($service, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function unregister($service): void
    {
        if (!$this->has($service)) {
            throw new NonExistingServiceException($this->context, gettype($service), array_keys($this->services->toArray()));
        }
        $this->services->remove($service);
    }

    /**
     * {@inheritdoc}
     */
    public function has($service): bool
    {
        $this->assertServiceHaveType($service);

        return $this->services->contains($service);
    }

    /**
     * @param object $service
     */
    private function assertServiceHaveType($service): void
    {
        Assert::isInstanceOf(
            $service,
            $this->interface,
            $this->context . ' needs to implement "%2$s", "%s" given.'
        );
    }
}
