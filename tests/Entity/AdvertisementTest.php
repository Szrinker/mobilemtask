<?php

namespace App\Tests\Entity;

use App\Entity\Advertisement;
use PHPUnit\Framework\TestCase;

class AdvertisementTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $advertisement = new Advertisement();

        $advertisement->setName('Test Name');
        $this->assertSame('Test Name', $advertisement->getName());

        $advertisement->setDescription('Test Description');
        $this->assertSame('Test Description', $advertisement->getDescription());

        $advertisement->setPrice(12345);
        $this->assertSame(12345, $advertisement->getPrice());
    }
}
