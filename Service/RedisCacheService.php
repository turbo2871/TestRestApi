<?php

class RedisCacheService
{
    protected $redis = null;

    public function __construct()
    {
        try {
            $this->redis = new Redis();
            $this->redis->connect(REDIS_HOST, REDIS_PORT);
            $this->redis->auth(REDIS_PASSWORD);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function set($key, $value)
    {
        return $this->redis->set($key, $value);
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }
}