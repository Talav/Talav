<?php

declare(strict_types=1);

namespace Talav\Component\Registry\Registry;

use InvalidArgumentException;
use Talav\Component\Registry\Exception\ExistingServiceException;
use Talav\Component\Registry\Exception\NonExistingServiceException;

interface ServiceRegistryInterface
{
    /**
     * Returns list of all services
     */
    public function all(): iterable;

    /**
     * @param object $service
     *
     * @throws ExistingServiceException
     * @throws InvalidArgumentException
     */
    public function register(string $identifier, $service): void;

    /**
     * @throws NonExistingServiceException
     */
    public function unregister(string $identifier): void;

    public function has(string $identifier): bool;

    /**
     * @return object
     */
    public function get(string $identifier);
}
