<?php

namespace App\Models;
use \Exception;

class Area extends Common
{
    protected $fillable = [
        'name','pid','level'
    ];

    public function insertTransform(array $data)
    {
        return $this->insert([
            'name'  =>  $data['name'],
            'pid'   =>  $this->byNameToId($data['parent'], $data['level']-1),
            'level' =>  $data['level'],
        ]);
    }

    protected function byNameToId($name, $level=null)
    {
        if(empty($name)){
            return 0;
        }

        $where = is_null($level) ? compact('name') : compact('name', 'level');
        return $this->where($where)->orderByDesc('id')->take(1)->value('id');
    }
}
