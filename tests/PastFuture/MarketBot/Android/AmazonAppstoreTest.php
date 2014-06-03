<?php

namespace PastFuture\MarketBot\Android;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass PastFuture\MarketBot\Android\AmazonAppstore
 */
class AmazonAppstoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AmazonAppstore
     */
    protected $market;

    protected function setUp()
    {
        $this->market = new AmazonAppstore;
    }

    public function testGetInvalidApp()
    {
        $app = $this->market->get('BOOOOTHISMAN');
        $this->assertEquals(false, $app);
    }

    public function testGetFreeApp()
    {
        $app = $this->market->get('B0094BB4TW');

        $this->assertInstanceOf('PastFuture\MarketBot\App\Android\AmazonAppstoreApp', $app);

        $this->assertEquals('B0094BB4TW', $app->getMarketId());
        $this->assertEquals('http://www.amazon.com/dp/B0094BB4TW', $app->getUrl());
        $this->assertEquals('Facebook', $app->getName());
        $this->assertEquals('Facebook', $app->getDeveloper());
        $this->assertInternalType('string', $app->getDescription());

        $this->assertEquals(null, $app->getCategory());
        $this->assertEquals(null, $app->getReleaseNotes());
        $this->assertEquals(0, $app->getPrice());
        $this->assertEquals([], $app->getScreenshots());
        $this->assertInternalType('string', $app->getCurrentVersion());
        $this->assertEquals('31.5MB', $app->getSize());
        $this->assertEquals('Guidance Suggested', $app->getContentRating());
        $this->assertEquals(null, $app->getInstalls());
        $this->assertInternalType('float', $app->getRating());
        $this->assertInternalType('integer', $app->getVotes());
        $this->assertEquals(null, $app->getLastUpdated());

        $this->assertInternalType('array', $app->getRelated());
        $this->assertEquals(6, count($app->getRelated()));

        $this->assertInternalType('array', $app->getPermissions());
        $this->assertTrue(count($app->getPermissions()) > 0);

        $this->assertEquals('Android 2.2', $app->getRequires());

        $this->assertEquals('http://ecx.images-amazon.com/images/I/21-leKb-zsL._SL500_AA300_.png', $app->getImageThumbnail());
        $this->assertEquals('http://ecx.images-amazon.com/images/I/21-leKb-zsL._SL500_AA300_.png', $app->getImageIcon());
    }

    public function testGetPaidApp()
    {
        $app = $this->market->get('B00992CF6W');

        $this->assertInstanceOf('PastFuture\MarketBot\App\Android\AmazonAppstoreApp', $app);

        $this->assertEquals('B00992CF6W', $app->getMarketId());
        $this->assertEquals('http://www.amazon.com/dp/B00992CF6W', $app->getUrl());
        $this->assertEquals('Minecraft - Pocket Edition', $app->getName());
        $this->assertEquals('Mojang', $app->getDeveloper());
        $this->assertInternalType('string', $app->getDescription());

        $this->assertEquals(null, $app->getCategory());
        $this->assertEquals(null, $app->getReleaseNotes());
        $this->assertEquals(6.99, $app->getPrice());
        $this->assertEquals([], $app->getScreenshots());
        $this->assertInternalType('string', $app->getCurrentVersion());
        $this->assertEquals('Varies by device (10.3MB - 10.7MB)', $app->getSize());
        $this->assertEquals('Guidance Suggested', $app->getContentRating());
        $this->assertEquals(null, $app->getInstalls());
        $this->assertInternalType('float', $app->getRating());
        $this->assertInternalType('integer', $app->getVotes());
        $this->assertEquals(null, $app->getLastUpdated());

        $this->assertInternalType('array', $app->getRelated());
        $this->assertEquals(6, count($app->getRelated()));

        $this->assertInternalType('array', $app->getPermissions());
        $this->assertTrue(count($app->getPermissions()) > 0);

        $this->assertEquals('Android 2.3', $app->getRequires());

        $this->assertEquals('http://ecx.images-amazon.com/images/I/61psbVb0TAL._SL500_AA300_.png', $app->getImageThumbnail());
        $this->assertEquals('http://ecx.images-amazon.com/images/I/61psbVb0TAL._SL500_AA300_.png', $app->getImageIcon());
    }
}
