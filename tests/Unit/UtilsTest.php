<?php

namespace Tests\Unit;

use App\Helpers\Utils;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class UtilsTest extends TestCase
{
    public string $html1 = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <p class="message">Hello World!</p>
        <div>Hello Crawler!</div>
        <div class="price1">£304.12 with additional stuff</div>
        <div class="price2">showing my £102.99 wholesome</div>
        <div class="price3">showing my wholesome £8654.54 </div>
        <div class="price4">showing some $405.33 bucks</div>
        <div class="price-string">showing my £fake wholesome</div>
    </body>
</html>
HTML;

    public array $inputArray1 = [
        [
            'description' => 'my description first',
            'cost' => '9.99',
            'notes' => 'some random notes',
        ],
        [
            'description' => 'my description first',
            'cost' => '18.11',
            'notes' => 'some random notes',
        ],
        [
            'description' => 'my description first',
            'cost' => '2.56',
            'notes' => 'some random notes',
        ],
    ];

    public array $inputArray2 = [
        [
            'description' => 'my description first',
            'cost' => '2.56',
            'notes' => 'some random notes',
        ],
        [
            'description' => 'my description first',
            'cost' => '9.99',
            'notes' => 'some random notes',
        ],
        [
            'description' => 'my description first',
            'cost' => '18.11',
            'notes' => 'some random notes',
        ],
    ];

    public array $inputArray3 = [
        [
            'description' => 'my description first',
            'cost' => '18.11',
            'notes' => 'some random notes',
        ],
        [
            'description' => 'my description first',
            'cost' => '9.99',
            'notes' => 'some random notes',
        ],
        [
            'description' => 'my description first',
            'cost' => '2.56',
            'notes' => 'some random notes',
        ],
    ];

    public function test_sorts_array_by_provided_key()
    {
        $expected = [
            [
                'description' => 'my description first',
                'cost' => '18.11',
                'notes' => 'some random notes',
            ],
            [
                'description' => 'my description first',
                'cost' => '9.99',
                'notes' => 'some random notes',
            ],
            [
                'description' => 'my description first',
                'cost' => '2.56',
                'notes' => 'some random notes',
            ],
        ];

        $key = 'cost';

        $result = Utils::sortArrayByNumericValueAccordingToProvidedKey($key, $this->inputArray1);
        $this->assertEquals($expected, $result);
        $result = Utils::sortArrayByNumericValueAccordingToProvidedKey($key, $this->inputArray2);
        $this->assertEquals($expected, $result);
        $result = Utils::sortArrayByNumericValueAccordingToProvidedKey($key, $this->inputArray3);
        $this->assertEquals($expected, $result);
    }

    public function test_return_array_as_is_if_provided_key_is_not_numeric()
    {
        $key = 'description';
        $result = Utils::sortArrayByNumericValueAccordingToProvidedKey($key, $this->inputArray1);
        $this->assertEquals($this->inputArray1, $result);
        $result = Utils::sortArrayByNumericValueAccordingToProvidedKey($key, $this->inputArray2);
        $this->assertEquals($this->inputArray2, $result);
        $key = 'notes';
        $result = Utils::sortArrayByNumericValueAccordingToProvidedKey($key, $this->inputArray3);
        $this->assertEquals($this->inputArray3, $result);
    }

    public function test_retrieve_text_from_specified_node()
    {
        $crawler = new Crawler($this->html1);

        $result = Utils::getTextFromNode($crawler, '.message');
        $this->assertEquals('Hello World!', $result);
        $result = Utils::getTextFromNode($crawler, 'div');
        $this->assertEquals('Hello Crawler!', $result);
    }

    public function test_get_empty_string_if_node_does_not_exist()
    {
        $crawler = new Crawler($this->html1);

        $result = Utils::getTextFromNode($crawler, 'li');
        $this->assertEquals('', $result);
        $result = Utils::getTextFromNode($crawler, 'form');
        $this->assertEquals('', $result);
    }

    public function test_get_only_numeric_value_if_currency_detected()
    {
        $crawler = new Crawler($this->html1);

        $result = Utils::getPriceFromNode($crawler, '.price1', '£');
        $this->assertEquals('304.12', $result);
        $this->assertNotEquals('£304.12', $result);
        $result = Utils::getPriceFromNode($crawler, '.price2');
        $this->assertEquals('102.99', $result);
        $this->assertNotEquals('£102.99', $result);
        $result = Utils::getPriceFromNode($crawler, '.price3');
        $this->assertEquals('8654.54', $result);
        $this->assertNotEquals('£8654.54', $result);
    }

    public function test_correctly_detect_currency_if_identifier_is_specified()
    {
        $crawler = new Crawler($this->html1);

        $result = Utils::getPriceFromNode($crawler, '.price4', '$');
        $this->assertEquals('405.33', $result);
        $this->assertNotEquals('$405.33', $result);
        $result = Utils::getPriceFromNode($crawler, '.price2', '^');
        $this->assertEquals('', $result);
        $this->assertNotEquals('^', $result);
    }

    public function test_get_empty_string_if_retrieved_currency_but_it_is_not_numeric()
    {
        $crawler = new Crawler($this->html1);

        $result = Utils::getPriceFromNode($crawler, '.price-string', '£');
        $this->assertEquals('', $result);
        $this->assertNotEquals('£', $result);
    }
}
