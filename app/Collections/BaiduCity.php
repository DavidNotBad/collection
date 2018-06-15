<?php
namespace App\Collections;
use App\Models\BaiduCities;
use Symfony\Component\DomCrawler\Crawler;

class BaiduCity extends Collection
{
    protected $url = 'https://baike.baidu.com/item/%E5%B8%82%E8%BE%96%E5%8C%BA';

    protected function handle()
    {
        $crawler = $this->get($this->url);

        $city = $crawler->filterXPath('//table//tr')->each(function(Crawler $crawler){
            //筛选出含有区的城市
            if($crawler->filterXPath('//td[2]')->count()){
                return $this->toCity($crawler->filterXPath('//td[1]/a'));
            }else{
                return $this->toSpecialCity($crawler);
            }
        });

        $city = collect($city)->map(function($item){
            return collect(['name', 'url'])->combine(collect($item)->filter()->flatten()->toArray());
        })->toArray();

        BaiduCities::insert($city);
    }


    protected function toCity(Crawler $crawler)
    {
        return $crawler->each(function(Crawler $crawler){
            if(trim($crawler->text()) == '市'){
                return null;
            }
            $name = $crawler->text();
            return [
                'name'  =>  strpos($name, '市') ? $name : $name . '市',
                'url'   =>  $crawler->link()->getUri(),
            ];
        });
    }

    protected function toSpecialCity(Crawler $crawler)
    {
        $name = $crawler->parents()->filter('caption')->html();
        $name = preg_replace('/\（.*?\）/', '', $name);
        return [
            'name'  =>  $name,
            'url'   =>  'https://baike.baidu.com/item/'.urlencode($name),
        ];
    }

}