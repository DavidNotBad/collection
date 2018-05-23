<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/5/23
 * Time: 下午8:54
 */

namespace App\Collections;


use Symfony\Component\DomCrawler\Crawler;

class Test extends Collection
{

    protected function handle()
    {
        $url = 'https://baike.baidu.com/item/%E5%8C%97%E4%BA%AC/128981';

        $html = $this->get($url);
        dd($html->filter('.para-title .title-text'));
    }







}