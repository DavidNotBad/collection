<?php
namespace App\Collections;
use function foo\func;
use Symfony\Component\DomCrawler\Crawler;

class Ajax extends Collection
{
    protected $url = 'http://www.gaokaopai.com/daxue-zhaosheng-477.html';

    protected function handle()
    {
//        $crawer = $this->get($this->url);
//        $this->selectSubject($crawer, '文科');
        $crawler = $this->submit(function(Crawler $crawler){
            return $crawler->selectButton('搜索');
        }, $this->url);
        dd($crawler->html());
    }

    protected function selectSubject(Crawler $crawler, $name)
    {
        $crawler->filter('#schoolPage .i1 .sub li')->each(function (Crawler $crawler)use($name){
            if($crawler->html() == $name){
                $this->click($crawler->link());
            }
        });
    }




}