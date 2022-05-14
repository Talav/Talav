<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Tests\Helper;

use Symfony\Component\HttpFoundation\Request;

trait FileContentRequest
{
    public function getResponseFromFile(string $event): Request
    {
        return new Request([], [], [], [], [], [], $this->loadJsonFile('response', $event));
    }

    public function getEventFromFile(string $event): Request
    {
        return new Request([], [], [], [], [], [], $this->loadJsonFile('event', $event));
    }

    public function loadJsonFile(string $type, string $event): string
    {
        return file_get_contents(dirname(__DIR__).'/var/'.$type.'/'.$event.'.json');
    }
}
