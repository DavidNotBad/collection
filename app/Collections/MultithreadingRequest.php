<?php
namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Console\Command;

class MultithreadingRequest extends Command
{
    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 7;  // 同时并发抓取

    private $users = ['CycloneAxe', 'appleboy', 'Aufree', 'lifesign',
        'overtrue', 'zhengjinghua', 'NauxLiu'];

    protected $signature = 'test:multithreading-request';
    protected $description = '获取Github用户的id';


    public function handle()
    {
        $this->totalPageCount = count($this->users);

        $client = new Client();

        $requests = function ($total) use ($client) {
            foreach ($this->users as $key => $user) {

                $uri = 'https://api.github.com/users/' . $user;
                yield function() use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };

        $pool = new Pool($client, $requests($this->totalPageCount), [
            'concurrency' => $this->concurrency,
            'fulfilled'   => function (Response $response, $index){
                $res = json_decode($response->getBody()->getContents());
                dd($res);

                $this->info("请求第 $index 个请求，用户 " . $this->users[$index] . " 的 Github ID 为：" .$res->id);

                $this->countedAndCheckEnded();
            },
            'rejected' => function ($reason, $index){
                $this->error("rejected" );
                $this->error("rejected reason: " . $reason );
                $this->countedAndCheckEnded();
            },
        ]);

        // 开始发送请求
        $promise = $pool->promise();
        $promise->wait();
    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            $this->counter++;
            return;
        }
        $this->info("请求结束！");
    }

    public function test()
    {
        $client = new Client();
        $requests = function ($total) {
            $uri = 'http://baidu.com';
            for ($i = 0; $i < $total; $i++) {
                yield new Request('GET', $uri);
            }
        };
        $pool = new Pool($client, $requests(100), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                // this is delivered each successful response
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
            },
        ]);
        // Initiate the transfers and create a promise
        $promise = $pool->promise();
        // Force the pool of requests to complete.
        $promise->wait();
    }

}
