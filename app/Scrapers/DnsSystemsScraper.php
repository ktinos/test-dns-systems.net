<?php

namespace App\Scrapers;

use App\Helpers\Utils;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * A simple class to scrape products from DNS Systems.
 *
 * @author Christos Panagiotopoulos
 */
class DnsSystemsScraper
{
    /**
     * The Dom Crawler Client to be used throughout the scraper.
     *
     * @var Client
     */
    public Client $client;

    /**
     * The products url to be used throughout the scraper.
     *
     * @var string
     */
    public string $productsUrl;

    /**
     * The product package key for css selector container.
     *
     * @var string
     */
    public string $packageKey;

    /**
     * The product title key for css selector.
     *
     * @var string
     */
    public string $titleKey;

    /**
     * The product description key for css selector.
     *
     * @var string
     */
    public string $descriptionKey;

    /**
     * The product price key for css selector.
     *
     * @var string
     */
    public string $priceKey;

    /**
     * The product discount key for css selector.
     *
     * @var string
     */
    public string $discountKey;

    /**
     * The product price character to identify if the field is a price.
     *
     * @var string
     */
    public string $priceIdentifier;

    /**
     * The array result.
     *
     * @var array
     */
    public array $result;

    /**
     * Create a new Scraper instance for DNS System.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->productsUrl = config('scraper.products_url');
        $this->packageKey = config('scraper.products_package_key');
        $this->titleKey = config('scraper.products_title_key');
        $this->descriptionKey = config('scraper.products_description_key');
        $this->priceKey = config('scraper.products_price_key');
        $this->discountKey = config('scraper.products_discount_key');
        $this->priceIdentifier = config('scraper.products_price_identifier');
        $this->result = [];
    }

    /**
     * Get an array with product information.
     *
     * The array returned will be ordered by highest price and the fields are:
     * title, description, price, discount
     * If any of the above fields are not found in the html, they will be empty strings.
     *
     * @param Crawler|null $html
     *
     * @return DnsSystemsScraper
     */
    public function retrieveProducts(Crawler|null $html = null): DnsSystemsScraper
    {
        if (empty($html)) {
            // retrieve all the html from the page.
            $html = $this->client->request(
                'GET',
                $this->productsUrl,
            );
        }

        $data = [];

        // Attempt to retrieve all products.
        $packages = $html->filter($this->packageKey);

        // if no products detected, return empty array.
        if ($packages->count() < 1) {
            $this->result = [];
            return $this;
        }

        // populate array with each product and its required fields.
        $packages->each(function ($node) use (&$data) {
            $title = Utils::getTextFromNode($node, $this->titleKey);

            // quick and "dirty" way to determine if a package is paid annually or monthly
            if (str_contains(strtolower($title), 'year')) {
                // price is per year, assign the value as is
                $price = Utils::getPriceFromNode($node, $this->priceKey, $this->priceIdentifier);
            } else {
                // by default, assume the price is per month, multiply the value by 12 to get the annual price
                $pricePerMonth = Utils::getPriceFromNode($node, $this->priceKey, $this->priceIdentifier);
                $price = $pricePerMonth * 12;
            }

            $data[] = [
                'title' => $title,
                'description' => Utils::getTextFromNode($node, $this->descriptionKey),
                'price' => (float)$price,
                'discount' => Utils::getPriceFromNode($node, $this->discountKey, $this->priceIdentifier),
            ];

        });

        // Sort array and assign to result.
        $this->result = Utils::sortArrayByNumericValueAccordingToProvidedKey(config('scraper.products_sort_by_key'), $data);

        return $this;
    }

    /**
     * Get the result array.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->result;
    }

    /**
     * Get the result as a json string.
     *
     * @return string
     */
    public function getJson(): string
    {
        return json_encode($this->result);
    }
}
