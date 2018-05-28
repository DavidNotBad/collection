<?php
namespace App\Collections;

use Symfony\Component\DomCrawler\Crawler;

class GithubRepositories extends Collection
{
    protected $url = 'https://github.com/login';
    protected $userName = 'davidnotbad@gmail.com';
    protected $pwd = 'your password';

    protected function handle()
    {
        $res = $this->submit(function(Crawler $crawler){
            return $crawler->selectButton('Sign in')->form([
                'login' => $this->userName,
                'password' => $this->pwd
            ]);
        }, $this->url)
            ->filterXPath('//*[@id="dashboard"]/div[1]/div[2]/div[2]/ul//li')
            ->each(function(Crawler $node, $index){
                return trim($node->filter('a span')->text());
            });

        dd($res);
    }




}