<?php

declare(strict_types=1);

namespace Talav\UserBundle\Tests\Functional\Setup;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait SymfonyKernel
{
    /**
     * @beforeClass
     */
    public static function boot(): void
    {
        self::$kernel = self::bootKernel(['environment' => 'test', 'debug' => true]);
    }

    /**
     * @afterClass
     */
    public static function shutdown(): void
    {
        static::ensureKernelShutdown();
    }

    /**
     * Gets client based on the provided kernel.
     *
     * @return KernelBrowser
     */
    public function getClient(): KernelBrowser
    {
        $client = new KernelBrowser(static::$kernel);
        $client->disableReboot();
        return $client;
    }

    protected function tearDown(): void
    {
        // do not allow trait to reboot kernel
    }
}
