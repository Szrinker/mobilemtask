<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AdvertisementControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Lista ogłoszeń');
    }

    public function testNewAdvertisementForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/advertisements/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="advertisement[name]"]');
        $this->assertSelectorExists('textarea[name="advertisement[description]"]');
        $this->assertSelectorExists('input[name="advertisement[price]"]');
        $this->assertSelectorExists('input[name="advertisement[images][]"]');
    }

    public function testSubmitNewAdvertisementWithoutFiles(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/advertisements/new');

        $form = $crawler->selectButton('Dodaj ogłoszenie')->form();

        $form['advertisement[name]'] = 'Test Advertisement';
        $form['advertisement[description]'] = 'Test description';
        $form['advertisement[price]'] = '123.45';

        $client->submit($form);

        $this->assertResponseRedirects('/');
    }
}
