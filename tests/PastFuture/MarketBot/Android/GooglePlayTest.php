<?php

namespace PastFuture\MarketBot\Android;

use PHPUnit_Framework_TestCase;

class GooglePlayTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GooglePlay
     */
    protected $market;

    protected function setUp()
    {
        $this->market = new GooglePlay;
    }

    public function testGetInvalidStoreItem()
    {
        $this->assertFalse($this->market->get('com.mojang.minecraftpe', 'music'));
        $this->assertFalse($this->market->get('com.mojang.minecraftpe', 'books'));
        $this->assertFalse($this->market->get('com.mojang.minecraftpe', 'movies'));
        $this->assertFalse($this->market->get('com.mojang.minecraftpe', 'magazines'));
    }

    public function testGetInvalidApp()
    {
        $this->assertFalse($this->market->get('com.i.am.an.app.that.probably.doenst.exist', 'apps'));
    }

    public function testGetDeveloperEmail()
    {
        $app = $this->market->get('com.candyrufusgames.survivalcraft', 'apps');
        $this->assertEquals('candyrufusgames@gmail.com', $app->getDeveloperEmail());
    }

    /*public function testGetFreeApp()
    {
        $app = $this->market->get('com.humblebundle.library', 'apps');
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
        $this->assertEquals('28.9MB', $app->getSize());
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
    }*/

    public function testGetPaidApp()
    {
        $app = $this->market->get('com.chrislacy.actionlauncher.pro', 'apps');

//var_dump($app);exit;

        $this->assertInstanceOf('PastFuture\MarketBot\App\Android\GooglePlayApp', $app);

        $this->assertEquals('com.chrislacy.actionlauncher.pro', $app->getMarketId());
        $this->assertEquals('https://play.google.com/store/apps/details?id=com.chrislacy.actionlauncher.pro', $app->getUrl());
        $this->assertEquals('Action Launcher Pro', $app->getName());
        $this->assertEquals('Chris Lacy', $app->getDeveloper());
        $this->assertInternalType('string', $app->getDescription());
        $this->assertEquals('Personalization', $app->getCategory());
        $this->assertInternalType('string', $app->getReleaseNotes());
        $this->assertEquals(3.99, $app->getPrice());
        $this->assertEquals(21, count($app->getScreenshots()));
        $this->assertEquals('2.0.0', $app->getCurrentVersion());
        $this->assertEquals('111k', $app->getSize());
        $this->assertEquals('Everyone', $app->getContentRating());
        $this->assertEquals('50,000 - 100,000', $app->getInstalls());
        $this->assertInternalType('float', $app->getRating());
        $this->assertInternalType('integer', $app->getVotes());
        $this->assertEquals('December 27, 2013', $app->getLastUpdated());

        $this->assertEquals(16, count($app->getRelated()));

        $this->assertEquals([], $app->getPermissions());
        $this->assertEquals('4.0.3 and up', $app->getRequires());

        $this->assertEquals('https://www.google.com/url?q=http://www.actionlauncher.com&sa=D&usg=AFQjCNFdryFa1jn9qszJpPzAIK3jdKUD9Q', $app->getWebsiteUrl());
        $this->assertFalse($app->getDeveloperEmail());
        $this->assertEquals(4, count($app->getMoreFromDeveloper()));

        //$this->assertEquals([], $app->getImages());
        //$this->assertEquals('http://ecx.images-amazon.com/images/I/61psbVb0TAL._SL500_AA300_.png', $app->getImageThumbnail());
        //$this->assertEquals('http://ecx.images-amazon.com/images/I/61psbVb0TAL._SL500_AA300_.png', $app->getImageIcon());

        $this->assertEquals([], $app->getVideos());
    }
}
