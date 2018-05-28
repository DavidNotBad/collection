<?php

namespace App\Collections;

use DavidNotBad\Http;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;
use \GuzzleHttp\Promise\Promise;

abstract class Collection
{
    /**
     * @var \Illuminate\Console\Command
     */
    protected $command;
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;
    /**
     * @var Model
     */
    protected $model;

    public function __construct(Command $command, Model $model=null)
    {
        $this->command = $command;
        $this->model = $model;

        $this->handle();
    }

    protected function client($timeout = 5)
    {
        return new Client(['headers' => ['User-Agent' => Http::userAgent()], 'timeout' => $timeout]);
    }

    protected function get($url, array $config=[])
    {
        $config = $this->getConfig($config);

        $html = $this->getHtml($url, ...$config);
        return $this->crawler($html, $url, $config['charset']);
    }

    protected function getConfig(array $config)
    {
        $timeout = isset($config['timeout']) ? $config['timeout'] : 5;
        $retry = isset($config['retry']) ? $config['retry'] : 3;
        $sleep = isset($config['sleep']) ? $config['sleep'] : 0;
        $retryFun = isset($config['retryFun']) ? $config['retryFun'] : null;
        $charset = isset($config['charset']) ? $config['charset'] : null;

        return compact([
            'timeout', 'retry', 'sleep', 'retryFun', 'charset'
        ]);
    }


    protected function getList(array $urls, array $config=[])
    {

        $config = $this->getConfig($config);
        $this->client = $this->client($config['timeout']);

        $pool = new Pool($this->client, $this->getPoolRequests($urls), $this->getPoolConfig());

        $pool->promise()->wait();
    }

    protected function getPoolConfig($concurrency = 1)
    {
        return [
            'concurrency' => $concurrency,
            'fulfilled'   => [$this, 'getListSucc'],
            'rejected'    => [$this, 'getListError'],
        ];
    }

    public function getListSucc(Response $response, $index, Promise $promise)
    {
        throw new \Exception('你需要实现getListSucc方法');
//        $res = json_decode($response->getBody()->getContents());
//        dd($res);
//
//        $this->info("请求第 $index 个请求，用户 " . $index . " 的 Github ID 为：" .$res->id);
    }

    public function getListError($reason, $index, Promise $promise)
    {
        throw new \Exception('你需要实现getListError方法');
//        $this->command->error("rejected" );
//        $this->command->error("rejected reason: " . $reason );
    }


    protected function getPoolRequests($urls)
    {
        return (function ()use($urls){
            foreach ($urls as $url) {
                yield function() use ($url) {
                    return $this->client->getAsync($url);
                };
            }
        })();
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
            $this->command->info($e->getMessage());
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

    protected function info($message)
    {
        echo $message . PHP_EOL;
    }

    protected abstract function handle();
}