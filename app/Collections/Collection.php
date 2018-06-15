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
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\DomCrawler\Link;

/**
 * Class Collection
 * @package App\Collections
 */
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

    /**
     * @var
     */
    protected $goutteClient;

    /**
     * @var int
     */
    protected $concurrency = 25;

    /**
     * Collection constructor.
     * @param Command $command
     * @param Model|null $model
     */
    public function __construct(Command $command, Model $model=null)
    {
        $this->command = $command;
        $this->model = $model;

        $this->handle();
    }

    /**
     * @param int $timeout
     * @return Client
     */
    protected function client($timeout = 5)
    {
        return new Client(['headers' => ['User-Agent' => Http::userAgent()], 'timeout' => $timeout]);
    }

    /**
     * @param $url
     * @param array $config
     * @return Crawler|void
     */
    protected function get($url, array $config=[])
    {
        $config = $this->getConfig($config);

        $html = $this->getHtml($url, ...array_values($config));
        return $this->crawler($html, $url, $config['charset']);
    }

    /**
     * @param array $config
     * @return array
     */
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

    /**
     * @param array $urls
     * @param array $config
     */
    protected function getList(array $urls, array $config=[])
    {
        $config = $this->getConfig($config);
        $this->client = $this->client($config['timeout']);

        $pool = new Pool($this->client, $this->getPoolRequests($urls), $this->getPoolConfig());
        $pool->promise()->wait();
    }

    /**
     * @return array
     */
    protected function getPoolConfig()
    {
        return [
            'concurrency' => $this->concurrency,
            'fulfilled'   => [$this, 'getListSucc'],
            'rejected'    => [$this, 'getListError'],
        ];
    }


    /**
     * @param Response $response
     * @param $index
     * @param Promise $promise
     * @throws \Exception
     */
    public function getListSucc(Response $response, $index, Promise $promise)
    {
        throw new \Exception('你需要实现getListSucc方法');
    }


    /**
     * @param $reason
     * @param $index
     * @param Promise $promise
     * @throws \Exception
     */
    public function getListError($reason, $index, Promise $promise)
    {
        throw new \Exception('你需要实现getListError方法');
    }


    /**
     * @param $urls
     * @return mixed
     */
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


    /**
     * @param $html
     * @param null $url
     * @param null $charset
     * @return Crawler|void
     */
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


    /**
     * @param $url
     * @param int $timeout
     * @param int $retry
     * @param int $sleep
     * @param callable|null $retryFun
     * @return bool|string
     */
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

    /**
     * @param Client $client
     * @param $url
     * @param int $sleep
     * @return bool|string
     */
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


    protected function getGoutteClient(\Goutte\Client $client = null, $timeout = 5)
    {
        if(! $this->goutteClient){
            $this->goutteClient = ($client) ? : (new \Goutte\Client())->setClient($this->client($timeout));
        }

        return $this->goutteClient;
    }

    /**
     * @param callable $callBack
     * @param $url
     * @param int $timeout
     * @return Crawler
     */
    protected function submit(callable $callBack, $url, $timeout = 5)
    {
        $client = $this->getGoutteClient(null, $timeout);

        $form = call_user_func($callBack, $client->request('GET', $url));
        return $client->submit($form);
    }

    /**
     * @param Link $link
     * @return mixed
     */
    protected function click(Link $link)
    {
        return $this->getGoutteClient()->click($link);
    }

    /**
     * @param $message
     */
    protected function info($message)
    {
        echo $message . PHP_EOL;
    }

    protected function getListCrawler(Response $response)
    {
        return $this->crawler($response->getBody()->getContents());
    }


    protected function outerHtml(Crawler $crawler)
    {
        return \Closure::bind(function(){
            if (!$this->nodes) {
                throw new \InvalidArgumentException('The current node list is empty.');
            }

            $firstNode = $this->getNode(0);
            return $firstNode->ownerDocument->saveHTML($firstNode);
        },$crawler, Crawler::class)();
    }


    /**
     * @return mixed
     */
    protected abstract function handle();
}