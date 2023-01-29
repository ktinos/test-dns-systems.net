<?php

namespace App\Console\Commands;

use App\Scrapers\DnsSystemsScraper;
use Illuminate\Console\Command;

class DnsScrapeProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dns-systems:scrape-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape products from dns-systems.net';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scraper = new DnsSystemsScraper();
        $data = $scraper->retrieveProducts()
                        ->get();

        if (count($data) < 1) {
            $this->error(json_encode($data));

            return Command::FAILURE;
        }

        $this->info(json_encode($data));

        return Command::SUCCESS;
    }
}
