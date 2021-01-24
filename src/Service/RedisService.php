<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Redis;

class RedisService
{
    protected $redis;

    public function __construct($redisUrl, $redisPort, $redisPassword)
    {
        $redis = new Redis();
        $redis->connect($redisUrl, $redisPort);
        $redis->auth($redisPassword);
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value)
    {
        return $this->redis->set($key, $value);
    }
}
