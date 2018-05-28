<?php
namespace App\Collections;
use function foo\func;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;

class Test extends Collection
{
    protected $urls = [
        'https://api.github.com/users/CycloneAxe',
        'https://api.github.com/users/appleboy',
        'https://api.github.com/users/Aufree',
        'https://api.github.com/users/lifesign',
        'https://api.github.com/users/overtrue',
        'https://api.github.com/users/zhengjinghua',
        'https://api.github.com/users/NauxLiu',
    ];

    protected function handle()
    {
        $this->getList($this->urls);
    }

    public function getListSucc(Response $response, $index, Promise $promise)
    {
        $res = $response->getBody();
        dump($res);

        $this->info("请求第 $index 个请求，用户 " . $index . " 的 Github ID 为：" .$res->id);
    }

    public function getListError($reason, $index, Promise $promise)
    {
        $this->command->error("rejected" );
        $this->command->error("rejected reason: " . $reason );
    }

}