<?php
namespace App\Collections;

use DavidNotBad\Http;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CriminalCase extends Collection
{
    protected $url = 'http://wenshu.court.gov.cn/List/List?sorttype=1&conditions=searchWord+1+AJLX++%E6%A1%88%E4%BB%B6%E7%B1%BB%E5%9E%8B:%E5%88%91%E4%BA%8B%E6%A1%88%E4%BB%B6';
    protected $config = [
        'timeout'   =>  60,
        'retry'     =>  10,
    ];

    protected function client($timeout = 5)
    {
        return new Client([
            'headers' => ['User-Agent' => Http::userAgent()],
            'timeout' => $timeout,
            'base_uri'=> 'http://wenshu.court.gov.cn',
        ]);
    }

    protected function handle()
    {
        $html = $this->get($this->url, $this->config);

        file_put_contents('./test.html',$this->outerHtml($html->filter('html')));
//            ->each(function(Crawler $crawler){
//            var_dump($crawler->html());
//            exit;
//        });
    }




}