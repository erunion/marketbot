<?php

namespace PastFuture\MarketBot;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass PastFuture\MarketBot\MarketBot
 */
class MarketBotTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MarketBot
     */
    protected $marketbot;

    protected function setUp()
    {
        $this->marketbot = new MarketBot;
    }

    /**
     * @covers ::setLanguage()
     * @covers ::getLanguage()
     */
    public function testLanguage()
    {
        $this->marketbot->setLanguage('en-US');
        $this->assertEquals('en-US', $this->marketbot->getLanguage());
    }

    /**
     * @covers ::initScraper()
     * @covers ::setDocument()
     * @covers ::getDocument()
     */
    public function testInitScraper()
    {
        $this->marketbot->initScraper('https://www.google.com/');
        $this->assertInstanceOf('\phpQueryObject', $this->marketbot->getDocument());
    }
}
