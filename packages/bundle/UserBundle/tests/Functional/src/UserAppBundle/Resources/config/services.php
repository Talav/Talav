<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\Mailer\EventListener\MessageLoggerListener;

/*
 * Adds mailer logger without turning profiler
 * caused by https://github.com/symfony/symfony/issues/39511
 */
return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('mailer.message_logger_listener', MessageLoggerListener::class)
            ->tag('kernel.event_subscriber')
            ->tag('kernel.reset', ['method' => 'reset'])
    ;
};
