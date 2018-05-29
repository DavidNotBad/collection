<?php
namespace App\Collections;
use function foo\func;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Page
 * @package App\Collections
 */
class Page extends Collection
{
    /**
     * @var string
     */
    protected $url = 'http://www.skieer.com/guide/demo/moviespage1.html';

    /**
     * @param null $url
     * @return mixed|void
     */
    protected function handle($url = null)
    {
        $crawler = $this->get(is_null($url) ? $this->url : $url);
        $crawler->filter('ul li a')->each(function (Crawler $crawler, $index){
            //点击电影链接
            $crawler = $this->click($crawler->link());
            //采集详情页的数据
            return $this->getDetailPage($crawler);
        });

        $link = $crawler->selectLink('下一页')->link();
        //点击下一页
        $this->click($link);
        //循环采集下一页
        $this->handle($link->getUri());
    }

    /**
     * @param Crawler $crawler
     */
    protected function getDetailPage(Crawler $crawler)
    {
        $title = $crawler->filter('h1')->text();
        $desc = $crawler->filter('.plotsummary span')->text();
        $time = trim($crawler->filter('.releaseyear')->text());
        $this->info('title-'.$title);
        $this->info('desc-'.$desc);
        $this->info('year-'.$time);
        $this->info('...................................');
    }


}