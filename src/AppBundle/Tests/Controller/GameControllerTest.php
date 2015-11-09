<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testGuessMysteriousWord()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/game/');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains(
            'Tranquille, il reste 11 essais !',
            $crawler->filter('#content .attempts')->text()
        );
        $this->assertCount(8, $crawler->filter('.word_letters > li.hidden'));

        $link = $crawler->selectLink('E')->link();
        $crawler = $client->click($link);

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains(
            'Tranquille, il reste 10 essais !',
            $crawler->filter('#content .attempts')->text()
        );
        $this->assertCount(8, $crawler->filter('.word_letters > li.hidden'));
        $this->assertCount(25, $crawler->filter('#letters > li'));

        $link = $crawler->selectLink('P')->link();
        $crawler = $client->click($link);

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains(
            'Tranquille, il reste 10 essais !',
            $crawler->filter('#content .attempts')->text()
        );
        $this->assertCount(6, $crawler->filter('.word_letters > li.hidden'));
        $this->assertCount(2, $crawler->filter('.word_letters > li.guessed'));
        $this->assertCount(24, $crawler->filter('#letters > li'));

        $link = $crawler->selectLink('L')->link();
        $crawler = $client->click($link);

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains(
            'Tranquille, il reste 10 essais !',
            $crawler->filter('#content .attempts')->text()
        );
        $this->assertCount(4, $crawler->filter('.word_letters > li.hidden'));
        $this->assertCount(4, $crawler->filter('.word_letters > li.guessed'));
        $this->assertCount(23, $crawler->filter('#letters > li'));

        $form = $crawler->selectButton('Envoyer')->form();
        $form['word'] = 'papillon';
        $client->submit($form);

        $this->assertContains('Bravo', (string) $client->getResponse());
    }
}
