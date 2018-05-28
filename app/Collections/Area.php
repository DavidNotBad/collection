<?php
namespace App\Collections;
use function foo\func;
use Symfony\Component\DomCrawler\Crawler;

class Area extends Collection
{
    protected $url = 'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2016/';

    protected function handle()
    {
        $crawler = $this->get($this->url);

        try {
            $this->getArea($crawler);
        }catch (\Exception $e){
            $this->info($e->getMessage());
            $this->info($e->getTraceAsString());
        }
    }


    protected function getArea(Crawler $crawler, $level = 1, $parent='')
    {
        if($level == 5){
            return $this->getAreaByNoLink($crawler, $parent);
        }

        $crawler = ($level == 1) ? $crawler->filter('.provincetr a') : $crawler->filterXPath('//tr/td[2]/a');

        return $crawler->each(function (Crawler $item)use($level, $parent){
            $name = str_replace('<br>','',$item->html());
            ($name == '市辖区') && $name = $parent;

            //过滤已经采集的省份
            if($this->isFilter($name)){
                return;
            }


            $url = $item->link()->getUri();
            preg_match_all('#\d+#', parse_url($url, PHP_URL_PATH), $code);
            $code = str_pad(end($code[0]), 12, '0');

            $this->info('|'.str_repeat('____', $level)."{$level} {$name} {$code} {$url}");

            $this->save($level, $code, $url, $name, $parent);
            $this->getArea($this->get($url), ++$level, $name);
        });
    }

    protected function getAreaByNoLink(Crawler $crawler, $parent)
    {
        return $crawler->filter('.villagetr')->each(function(Crawler $crawler)use($parent){
            $td = $crawler->filter('td');
            $name = $td->eq(2)->html();
            $code = $td->eq(0)->html();

            $this->info('|'.str_repeat('____', 5)."5 {$name} {$code}");
            return $this->save(5, $code, '', $name, $parent);
        });
    }

    protected function save($level, $code, $url, $name, $parent)
    {
        $data = compact([
            'level', 'code', 'url', 'name', 'parent'
        ]);
        return $this->model->insertTransform($data);
    }

    //过滤已经采集的省份
    protected function isFilter($name)
    {
        return in_array($name,[
//            '北京市','天津市','河北省','山西省','内蒙古自治区','辽宁省',
        ]);
    }

    //1-133163

}