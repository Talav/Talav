<?php

declare(strict_types=1);

namespace Talav\Component\Registry\Registry;

use InvalidArgumentException;
use Talav\Component\Registry\Exception\ExistingServiceException;
use Talav\Component\Registry\Exception\NonExistingServiceException;

interface PrioritizedServiceRegistryInterface
{
    public function all(): iterable;

    /**
     * @param object $service
     *
     * @throws ExistingServiceException
     * @throws InvalidArgumentException
     */
    public function register($service, int $priority = 0): void;

    /**
     * @param object $service
     *
     * @throws NonExistingServiceException
     */
    public function unregister($service): void;

    /**
     * @param $service
     */
    public function has($service): bool;
}
