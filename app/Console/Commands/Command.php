<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/5/28
 * Time: 12:06
 */

namespace App\Console\Commands;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Exception\CommandNotFoundException;

/**
 * Class Command
 * @package App\Console\Commands
 */
class Command extends BaseCommand
{
    /**
     * @param array ...$args
     * @return mixed
     */
    protected function collection(...$args)
    {
        $args = $this->parseCollectionConfig($args);

        $className = str_replace('App\Console\Commands', 'App\Collections', get_class($this));
        if(class_exists($className)){
            return new $className(...array_values(array_merge([$this], $args)));
        }

        throw new CommandNotFoundException(sprintf('collection command %s not found', $className));
    }

    /**
     * @param $conf
     * @return array
     */
    protected function parseCollectionConfig($conf)
    {
        return collect($conf)->map(function($item){
            return (is_string($item) && class_exists($item)) ? app($item) : $item;
        })->all();
    }


}