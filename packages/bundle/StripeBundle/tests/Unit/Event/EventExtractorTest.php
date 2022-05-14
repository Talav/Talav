<?php

namespace Talav\StripeBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use Talav\StripeBundle\Event\EventExtractor;
use Talav\StripeBundle\Tests\Helper\FileContentRequest;

class EventExtractorTest extends TestCase
{
    use FileContentRequest;

    public function testExtract()
    {
        $extractor = new EventExtractor(null);
        $event = $extractor->extract($this->getEventFromFile('product.created'));

        $this->assertEquals('evt_1Kxf0WBckVgjYj7Sj0BCDc0r', $event->id);
        $this->assertEquals('prod_Leyyy3KhUWphiQ', $event->data->object->id);
    }
}
