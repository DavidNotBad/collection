<?php
namespace App\Collections;

use App\Models\BaiduCities;
use App\Models\BaiduCity;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Response;
use App\Models\BaiduCityDetail as BaiduCityDetailDB;
use Symfony\Component\DomCrawler\Crawler;

class BaiduCityDetail extends Collection
{
    protected $catalog;
    protected $concurrency = 10;

    protected function handle()
    {
        $has = BaiduCity::pluck('name')->toArray();
        $urls = BaiduCities::whereNotIn('name', $has)->pluck('url')->toArray();

        $this->getList($urls,[
            'timeout'   =>  60,
        ]);
    }

    public function getListSucc(Response $response, $index, Promise $promise)
    {
        $crawler = $this->getListCrawler($response);
        $this->catalog = $this->getTitleList($crawler);

        $id = BaiduCity::insertGetId($this->transform($crawler));

        //存储列表
        $this->transformTitle($crawler, $id);
    }

    protected function transform(Crawler $crawler)
    {
        $cityName = str_replace('市', '', $crawler->filter('h1')->html()) . '市';
        $h2 = $crawler->filter('h1+h2');

        $this->info($cityName);
        return [
            //市名称
            'name'  =>  $cityName,
            //市等级
            'level' =>  $h2->count() && preg_replace(['/\（.*?\）/','/\s+/'],'',$h2->html()),
            //简介
            'summary'   =>  $this->outerHtml($crawler->filter('.lemma-summary')),
            //基本信息()
            'basicinfo' => $this->outerHtml($crawler->filter('.basic-info')),
            //目录
            'catalog_bak' => $this->outerHtml($crawler->filter('.lemma-catalog')),
            'catalog'     => json_encode($this->catalog),
        ];
    }

    protected function transformTitle(Crawler $crawler, $id)
    {
        $catalogTmp = $this->catalog;
        $lastCata = end($catalogTmp);
        foreach ($this->catalog as $catalog)
        {
            $this->info('|____'.$catalog);

            $main = "//div[@class='main-content']//div[@class='para-title level-2']//h2[contains(text(),'{$catalog}')]";
            $crawlerNext = $crawler->filterXPath($main)->parents();
            $html = '';


            while (true){
                $crawlerNext = $crawlerNext->nextAll();
                $outerHtml = $this->outerHtml($crawlerNext);

                $nextAll = $crawlerNext->nextAll();
                if((!$nextAll->count()) || strpos( $this->outerHtml($nextAll), 'para-title level-2')){
                    break;
                }

                $html .= $outerHtml;
            }

            //最后一条的溢出处理
            if($catalog == $lastCata){
                $html = $this->outerHtml((new Crawler($html))->filter('.anchor-list')->previousAll());
            }

            BaiduCityDetailDB::insert([
               'pid'    =>  $id,
               'title'  =>  $catalog,
               'content'=>  $html,
            ]);
            sleep(0.01);
        }

    }



    protected function getTitleList(Crawler $crawler)
    {
        return $crawler->filter('.lemma-catalog .level1 a')->each(function(Crawler $crawler){
            return $crawler->text();
        });
    }




    public function getListError($reason, $index, Promise $promise)
    {
        dd($reason);
    }


}