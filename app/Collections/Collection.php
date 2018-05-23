<?php

namespace App\Collections;

use DavidNotBad\Http;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Symfony\Component\DomCrawler\Crawler;

abstract class Collection
{
    /**
     * @var \Illuminate\Console\Command
     */
    protected $command;
    protected $client;

    public function __construct(Command $command)
    {
        $this->command = $command;

        $this->handle();
    }

    protected function client($timeout = 5)
    {
        return new Client(['headers' => ['User-Agent' => Http::userAgent()], 'timeout' => $timeout]);
    }

    protected function get($url, array $config=[])
    {
        $timeout = isset($config['timeout']) ? $config['timeout'] : 5;
        $retry = isset($config['retry']) ? $config['retry'] : 3;
        $sleep = isset($config['sleep']) ? $config['sleep'] : 0;
        $retryFun = isset($config['retryFun']) ? $config['retryFun'] : null;
        $charset = isset($config['charset']) ? $config['charset'] : null;

        $html = $this->getHtml($url, $timeout, $retry, $sleep, $retryFun);
        return $this->crawler($html, $url, $charset);
    }

    protected function crawler($html, $url=null, $charset=null)
    {
        if(is_null($url)){
            if(is_null($charset)){
                return new Crawler($html);
            }

            return (new Crawler(null, $url))->addHtmlContent($html, $charset);
        }

        return new Crawler($html, $url);
    }


    protected function getHtml($url, $timeout = 5, $retry = 3, $sleep = 0, callable $retryFun=null)
    {
        $this->client = $this->client ? : $this->client($timeout);

        do{
            $html = $this->getHtmlContent($this->client, $url, $sleep);

            $retry--;
            if($retry == 0 && $html === false){
                if(is_callable($retryFun)) {
                    $retryFun($this, $this->client);
                }

                return false;
            }
        }while($html === false);

        return $html;
    }

    protected function getHtmlContent(Client $client, $url, $sleep = 0)
    {
        try {
            $res  = $client->request('GET', $url);
            $html =  (string)$res->getBody();
        } catch (RequestException $e) {
            // 抓取中会有404状态返回，再重新请求一次。
            $this->command->info(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                $this->command->info(Psr7\str($e->getResponse()));
            }

            $this->command->info("get timeout retry");

            //等待时间
            if($sleep){
                $this->command->info("sleep {$sleep}s");
                sleep($sleep);
            }

            return false;
        }

        return $html;
    }


    protected abstract function handle();
}