<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Currencies;
use App\Repository\CurrencyRepository;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PageService
{
    private HttpClientInterface $client;
    private CurrencyRepository $currencyRepository;

    public function __construct(HttpClientInterface $client, CurrencyRepository $currencyRepository)
    {
        $this->client = $client;
        $this->currencyRepository = $currencyRepository;
    }

    public function getPageContent(string $url): string
    {
        $response = $this->client->request('GET', $url);
        return $response->getContent();
    }

    public function getTableData(string $url): void
    {
        if($this->currencyRepository->hasData()===true){
            $this->currencyRepository->removeAll();
        }
        $content = $this->getPageContent($url);
        $crawler = new Crawler($content);

        $crawler->filter(selector: 'body table tr')->each(function (Crawler $row) {
            $columns = $row->filter('td');

            if ($columns->count() >= 4) {
                $currencyCode = substr(trim($columns->eq(1)->text()),-3);
                $buyRate = (float)str_replace(',', '.', trim($columns->eq(2)->text()));
                $sellRate = (float)str_replace(',', '.', trim($columns->eq(3)->text()));
                $spread = round(($sellRate - $buyRate), 3);

                $currency = new Currencies();
                $currency->setName($currencyCode);
                $currency->setBuyRate($buyRate);
                $currency->setSellRate($sellRate);
                $currency->setSpread($spread);
                $currency->setCreatedAt(new \DateTime());
                $this->currencyRepository->save($currency);
            }
        });
    }
}