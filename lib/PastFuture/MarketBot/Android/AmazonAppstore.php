<?php

/**
 * MarketBot
 *
 * @author Jon Ursenbach <jon@ursenba.ch>
 * @link http://github.com/pastfuture/MarketBot
 * @license Modified BSD
 * @version 0.1
 *
 * Copyright (c) 2012, PastFuture, Inc.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  * Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *  * Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *  * Neither the name of PastFuture, Inc., gdgt, nor the names of its
 *    contributors may be used to endorse or promote products derived from this
 *    software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace PastFuture\MarketBot\Android;

use PastFuture\MarketBot;
use PastFuture\MarketBot\App;
use pq;

/**
 * Amazon Appstore
 *
 * @todo Screenshots
 * @todo Strip images from the product description so it's just text
 *
 * @package MarketBot
 * @author Jon Ursenbach <jon@ursenba.ch>
 * @since 0.1
 */
class AmazonAppstore extends MarketBot\Android
{
    /**
     * Page
     *
     * @var integer
     */
    private $page = 1;

    /**
     * Details URL
     *
     * @var string
     */
    protected $details_url = 'http://www.amazon.com/dp/%s';

    /**
     * Search URL
     *
     * @var string
     */
    protected $search_url = 'http://www.amazon.com/s/';

    /**
     * Scrape the dedicated details page for a specific application and return an
     * array containing its data.
     *
     * @param string $market_id
     *
     * @return array|false Array if the item and data were found, false otherwise.
     */
    public function get($market_id)
    {
        $url = $this->getDetailsUrl($market_id);
        $this->initScraper($url);

        $page = $this->document['body'];

        $name = $page->find('div.buying h1.parseasinTitle span')->html();
        if (empty($name)) {
            return false;
        }

        $metadata = $page->find('td.bucket div.content');

        $app = new App\Android\AmazonAppstoreApp(
            array(
                'market_id' => $market_id,
                'url' => $url,
                'name' => $name,
                'price' => $page->find('#actualPriceValue b.priceLarge')->html(),
                'developer' => $page->find('div.buying span a')->html(),
                'description' => $page->find('div.content div.aplus')->html(),
                'rating' => $page->find('span.asinReviewsSummary a span')->attr('title'),
                'content_rating' => $page->find('#mas-show-ratings')->html(),

                'size' => str_replace('<strong>Size:</strong>', '', $metadata->find('li:contains("Size:")')->html()),
                'current_version' => str_replace('<strong>Version:</strong>', '', $metadata->find('li:contains("Version:")')->html()),
                'requires' => str_replace('<strong>Minimum Operating System:</strong>', '', $metadata->find('li:contains("Minimum Operating System:")')->html())
            )
        );

        $votes = $page->find('div.jumpBar span.crAvgStars a:last')->html();
        $votes = str_replace(' customer reviews', '', $votes);
        $app->setVotes($votes);

        $features = $page->find('#feature-bullets_feature_div ul li');
        if ($features->length()) {
            foreach ($features as $feature) {
                $feature = pq($feature);

                $app->addProductFeature($feature->text());
            }
        }

        $related_apps = $page->find('#purchaseButtonWrapper ul li');
        if ($related_apps->length()) {
            foreach ($related_apps as $related) {
                $related = pq($related);

                $related = str_replace('purchase_', '', $related->find('div:first')->attr('id'));
                $app->addRelated($related);
            }
        }

        $image = $page->find('#prodImage')->attr('src');
        $app->setImageThumbnail($image);
        $app->setImageIcon($image);

        // @todo screenshots

        $permissions = $page->find('ul.mas-app-permissions-list li');
        if ($permissions->length()) {
            foreach ($permissions as $permission) {
                $permission = pq($permission);

                $text = $permission->text();

                $app->addPermission($text, $text);
            }
        }

        return $app;
    }

    /**
     * With a search term, execute a search on Google Play.
     *
     * @param string $term
     *
     * @return array|false If results are found, an array is returned, otherwise false.
     */
    public function search($term)
    {
        $url = $this->search_url . '?';
        $url .= http_build_query(
            array(
                'url' => 'search-alias=mobile-apps',
                'field-keywords' => $term,
                'page' => $this->getPage()
            )
        );

        $apps = array();
        $this->initScraper($url);

        $items = $this->document['#atfResults li'];
        if (!$items->length()) {
            return false;
        }

        foreach ($items as $item) {
            $item = pq($item);

            $market_id = $item->attr('name');

            $app = new App\Android\AmazonAppstoreApp(
                array(
                    'market_id' => $market_id,
                    'url' => $this->getDetailsUrl($market_id),
                    'name' => $item->find('a.title')->html(),
                    'price' => $item->find('div.newPrice span.price')->html(),
                    'rating' => $item->find('div.asinReviewsSummary a:first')->attr('alt'),
                    'votes' => $item->find('div.reviewsCount a')->html()
                )
            );

            $developer = $item->find('h3.title span.ptBrand')->html();
            $developer = substr($developer, 3);
            $app->setDeveloper($developer);

            $image = $item->find('div.image img')->attr('src');
            $app->setImageThumbnail($image);
            $app->setImageIcon($image);

            $apps[$market_id] = $app;
        }

        return (!empty($apps)) ? $apps : false;
    }

    /**
     * Sets the page we wish to pulling results from.
     *
     * @param integer $number
     *
     * @return void
     */
    public function setPage($page)
    {
        $this->page = (int)$page;
    }

    /**
     * Gets the page we wish to pull results from.
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get the dedicated details market URL for a specific market ID.
     *
     * @param string $market_id
     *
     * @return string
     */
    private function getDetailsUrl($market_id)
    {
        return sprintf($this->details_url, $market_id);
    }
}
