<?php
namespace App\Collections;


use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Response;

class GitHubUserInfo extends Collection
{
    protected $concurrency    = 7;  // 同时并发抓取

    protected $userList = [
        'https://api.github.com/users/CycloneAxe',
        'https://api.github.com/users/appleboy',
        'https://api.github.com/users/Aufree',
        'https://api.github.com/users/lifesign',
        'https://api.github.com/users/overtrue',
        'https://api.github.com/users/zhengjinghua',
        'https://api.github.com/users/NauxLiu'
    ];

    protected function handle()
    {
        $this->getList($this->userList);
    }

    public function getListSucc(Response $response, $index, Promise $promise)
    {
        $res = json_decode($response->getBody()->getContents());

        $this->info("请求第 $index 个请求，用户 " . $index . " 的 Github ID 为：" .$res->id);
    }

    public function getListError($reason, $index, Promise $promise)
    {
        $this->command->error("rejected" );
        $this->command->error("rejected reason: " . $reason );
    }




}