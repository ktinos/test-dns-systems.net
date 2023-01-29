<?php

namespace App\Helpers;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Helper class with static methods to organise commonly used tasks.
 *
 * @author Christos Panagiotopoulos
 */
class Utils
{

    /**
     * Get the text from the selected DOM node.
     *
     * If no result exists, an empty string will be returned.
     *
     * @param Crawler $node
     * @param string $key
     *
     * @return string
     */
    public static function getTextFromNode(Crawler $node, string $key): string
    {

        return $node->filter($key)->count() > 0 ?
            $node->filter($key)->text() :
            '';
    }

    /**
     * Get the price from the selected DOM node.
     *
     * The price will be identified using a provided identifier, which by default is '£'.
     * If no result exists, an empty string will be returned.
     *
     * @param Crawler $node
     * @param string $key
     * @param string $priceIdentifier
     *
     * @return string
     */
    public static function getPriceFromNode(Crawler $node, string $key, string $priceIdentifier = '£'): string
    {
        $price = '';

        $priceText = $node->filter($key)->count() > 0 ?
            $node->filter($key)->text() :
            '';

        if (!empty($priceText)) {
            $priceTextExploded = explode(' ', $priceText);

            foreach($priceTextExploded as $word) {
                if (str_starts_with($word, $priceIdentifier)) {
                    $price = ltrim($word, $priceIdentifier);
                    if (!is_numeric($price)) {
                        return '';
                    }
                    break;
                }
            }
        }

        return $price;
    }

    /**
     * Returns the provided array sorted by provided key.
     *
     * If the key does not contain numeric values, the array will be returned as is.
     *
     * @param string $key
     * @param $array
     *
     * @return array
     */
    public static function sortArrayByNumericValueAccordingToProvidedKey(string $key, $array): array
    {

        usort($array, function($a, $b) use ($key) {
            if (is_numeric($b[$key]) && is_numeric($a[$key])) {
                return $b[$key] - $a[$key];
            }
        });

        return $array;
    }
}
