<?php

namespace Tests\Feature;

use App\Scrapers\DnsSystemsScraper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class ScraperTest extends TestCase
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

    public string $html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <div class="package featured-right" style="margin-top:0px; margin-right:0px; margin-bottom:0px; margin-left:25px">
                <div class="header dark-bg">
                    <h3>Basic: 500MB Data - 12 Months</h3>
                </div>
                <div class="package-features">
                    <ul>
                        <li>
                            <div class="package-name">The basic starter subscription providing you with all you need to get your device up and running with inclusive Data and SMS services.</div>
                        </li>
                        <li>
                            <div class="package-description">Up to 500MB of data per month<br>including 20 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                        </li>
                        <li>
                            <div class="package-price"><span class="price-big">£5.99</span><br>(inc. VAT)<br>Per Month</div>
                        </li>
                        <li>
                            <div class="package-data">12 Months - Data &amp; SMS Service Only</div>
                        </li>
                    </ul>
                    <div class="bottom-row">
                        <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/" role="button">Choose</a>
                    </div>
                </div>
            </div>
        </div> <!-- /END PACKAGE -->

        <div class="package featured center" style="margin-left:0px;">
            <div class="header dark-bg">
                <h3>Standard: 1GB Data - 12 Months</h3>
            </div>
            <div class="package-features">
                <ul>
                    <li>
                        <div class="package-name"></div>
                    </li>
                    <li>
                        <div class="package-description">Up to 1 GB data per month<br>including 35 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                    </li>
                    <li>
                        <div class="package-price"><span class="price-big">£9.99</span><br>(inc. VAT)<br>Per Month</div>
                    </li>
                    <li>
                        <div class="package-data">12 Months - Data &amp; SMS Service Only</div>
                    </li>
                </ul>
                <div class="bottom-row">
                    <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/" role="button">Choose</a>
                </div>
            </div>
        </div>

        <div class="package featured-right" style="margin-top:0px; margin-left:0px; margin-bottom:0px">
            <div class="header dark-bg">
                <h3>Optimum: 2 GB Data - 12 Months</h3>
            </div>
            <div class="package-features">
                <ul>
                    <li>
                        <div class="package-name">The optimum subscription providing you with enough service time to support the above-average user to enable your device to be up and running with inclusive Data and SMS services</div>
                    </li>
                    <li>
                        <div class="package-description">2GB data per month<br>including 40 SMS<br>(5p / minute and 4p / SMS thereafter)</div>
                    </li>
                    <li>
                        <div class="package-price"><span class="price-big">£15.99</span><br>(inc. VAT)<br>Per Month</div>
                    </li>
                    <li>
                        <div class="package-data">12 Months - Data &amp; SMS Service Only</div>
                    </li>
                </ul>
                <div class="bottom-row">
                    <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/#" role="button">Choose</a>
                </div>
            </div>
        </div>

    </body>
</html>
HTML;
public string $html2 = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <div class="package featured-right" style="margin-top:0px; margin-right:0px; margin-bottom:0px; margin-left:25px">
                <div class="header dark-bg">
                    <h3>Basic: 500MB Data - 1 Year</h3>
                </div>
                <div class="package-features">
                    <ul>
                        <li>
                            <div class="package-name">The basic starter subscription providing you with all you need to get your device up and running with inclusive Data and SMS services.</div>
                        </li>
                        <li>
                            <div class="package-description">Up to 500MB of data per month<br>including 20 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                        </li>
                        <li>
                            <div class="package-price"><span class="price-big">£877.23</span><br>(inc. VAT)<br>Per Year
                                <p style="color: red">Save £5.86 on the monthly price</p>
                            </div>
                        </li>
                        <li>
                            <div class="package-data">12 Months - Data &amp; SMS Service Only</div>
                        </li>
                    </ul>
                    <div class="bottom-row">
                        <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/" role="button">Choose</a>
                    </div>
                </div>
            </div>
        </div> <!-- /END PACKAGE -->

        <div class="package featured center" style="margin-left:0px;">
            <div class="header dark-bg">
                <h3>Standard: 1GB Data - 12 Months</h3>
            </div>
            <div class="package-features">
                <ul>
                    <li>
                        <div class="package-name"></div>
                    </li>
                    <li>
                        <div class="package-description">Up to 1 GB data per month<br>including 35 SMS<br>(5p / MB data and 4p / SMS thereafter)</div>
                    </li>
                    <li>
                        <div class="package-price"><span class="price-big">£9.99</span><br>(inc. VAT)<br>Per Month</div>
                    </li>
                    <li>
                        <div class="package-data">12 Months - Data &amp; SMS Service Only</div>
                    </li>
                </ul>
                <div class="bottom-row">
                    <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/" role="button">Choose</a>
                </div>
            </div>
        </div>

        <div class="package featured-right" style="margin-top:0px; margin-left:0px; margin-bottom:0px">
            <div class="header dark-bg">
                <h3>Optimum: 2 GB Data - 12 Months</h3>
            </div>
            <div class="package-features">
                <ul>
                    <li>
                        <div class="package-name">The optimum subscription providing you with enough service time to support the above-average user to enable your device to be up and running with inclusive Data and SMS services</div>
                    </li>
                    <li>
                        <div class="package-description">2GB data per month<br>including 40 SMS<br>(5p / minute and 4p / SMS thereafter)</div>
                    </li>
                    <li>
                        <div class="package-price"><span class="price-big">£15.99</span><br>(inc. VAT)<br>Per Month</div>
                    </li>
                    <li>
                        <div class="package-data">12 Months - Data &amp; SMS Service Only</div>
                    </li>
                </ul>
                <div class="bottom-row">
                    <a class="btn btn-primary main-action-button" href="https://wltest.dns-systems.net/#" role="button">Choose</a>
                </div>
            </div>
        </div>

    </body>
</html>
HTML;


    public function test_return_empty_array_if_no_products_are_found()
    {
        $scraper = new DnsSystemsScraper();
        $scraper->packageKey = 'somethingthatdoesntexist';
        $crawler = new Crawler($this->html);
        $result = $scraper->retrieveProducts($crawler)->get();

        $this->assertEmpty($result);
    }

    public function test_get_correct_amount_of_products()
    {
        $scraper = new DnsSystemsScraper();
        $crawler = new Crawler($this->html);
        $result = $scraper->retrieveProducts($crawler)->get();

        $this->assertCount(3, $result);
    }

    public function test_can_get_result_in_json()
    {
        $scraper = new DnsSystemsScraper();
        $crawler = new Crawler($this->html);
        $json = $scraper->retrieveProducts($crawler)->getJson();

        $this->assertIsString($json);
        $this->assertStringContainsString('title', $json);
        $data = json_decode($json);
        $this->assertIsArray($data);
    }

    public function test_result_has_correct_fields()
    {
        $scraper = new DnsSystemsScraper();
        $crawler = new Crawler($this->html);
        $json = $scraper->retrieveProducts($crawler)->getJson();

        $this->assertIsString($json);
        $this->assertStringContainsString('title', $json);
        $this->assertStringContainsString('description', $json);
        $this->assertStringContainsString('price', $json);
        $this->assertStringContainsString('discount', $json);
    }

    public function test_result_has_correct_title()
    {
        $scraper = new DnsSystemsScraper();
        $crawler = new Crawler($this->html);
        $json = $scraper->retrieveProducts($crawler)->get();

        $this->assertEquals('Optimum: 2 GB Data - 12 Months', $json[0]['title']);
        $this->assertEquals('Standard: 1GB Data - 12 Months', $json[1]['title']);
        $this->assertEquals('Basic: 500MB Data - 12 Months', $json[2]['title']);
    }

    public function test_result_has_correct_description()
    {
        $scraper = new DnsSystemsScraper();
        $crawler = new Crawler($this->html);
        $json = $scraper->retrieveProducts($crawler)->get();

        $this->assertEquals('2GB data per monthincluding 40 SMS(5p / minute and 4p / SMS thereafter)', $json[0]['description']);
        $this->assertEquals('Up to 1 GB data per monthincluding 35 SMS(5p / MB data and 4p / SMS thereafter)', $json[1]['description']);
        $this->assertEquals('Up to 500MB of data per monthincluding 20 SMS(5p / MB data and 4p / SMS thereafter)', $json[2]['description']);
    }

    public function test_result_has_correct_annual_price_for_monthly_listing()
    {
        $scraper = new DnsSystemsScraper();
        $crawler = new Crawler($this->html);
        $json = $scraper->retrieveProducts($crawler)->get();

        $this->assertEquals('191.88', $json[0]['price']);
        $this->assertEquals('119.88', $json[1]['price']);
        $this->assertEquals('71.88', $json[2]['price']);
    }

    public function test_result_has_correct_annual_price_for_annual_listing()
    {
        $scraper = new DnsSystemsScraper();
        $crawler = new Crawler($this->html2);
        $json = $scraper->retrieveProducts($crawler)->get();

        $this->assertEquals('877.23', $json[0]['price']);
    }

    public function test_result_has_correct_discount()
    {
        $scraper = new DnsSystemsScraper();
        $crawler = new Crawler($this->html2);
        $json = $scraper->retrieveProducts($crawler)->get();

        $this->assertEquals('5.86', $json[0]['discount']);
        $this->assertEquals('', $json[1]['discount']);
        $this->assertEquals('', $json[2]['discount']);

    }

}
