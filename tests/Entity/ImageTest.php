<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use App\Entity\Advertisement;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $image = new Image();
        $advertisement = new Advertisement();

        $image->setFilename('test_image.jpg');
        $this->assertSame('test_image.jpg', $image->getFilename());

        $image->setAdvertisement($advertisement);
        $this->assertSame($advertisement, $image->getAdvertisement());
    }
}
