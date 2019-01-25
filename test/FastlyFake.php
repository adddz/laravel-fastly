<?php

namespace Erashdan\LaravelFastly\Test;

use Erashdan\LaravelFastly\FastlyInterface;
use Fastly\FastlyFake as FastlyFaker;
use PHPUnit\Framework\Assert as PHPUnit;

class FastlyFake implements FastlyInterface
{

    protected $calls = 0;
    protected $urls = [];

    public function __construct()
    {
        $this->faker = new FastlyFaker();
    }

    public function purgeUrl($url, $options = [])
    {
        $this->urls = (!is_array($url)) ? [$url] : $url;

        foreach ($this->urls as $signleUrl) {
            $this->faker->purge($signleUrl, $options);
        }

        return true;
    }

    public function assertCall()
    {
        PHPUnit::assertEquals(count($this->urls), $this->numberOfCalls());
    }

    public function purgeService($service_name)
    {
        $service_id = $this->getService($service_name);

        $this->faker->purgeAll($service_id);
    }


    public function getService($service_name)
    {
        return str_random(22);
    }

    public function assertPurgeService()
    {
        PHPUnit::assertEquals(1, $this->numberOfCalls());
    }

    private function numberOfCalls()
    {
        $number = -1;

        do {
            $number++;
            $calls = $this->faker->getCall($number);
        } while (!empty($calls));

        return $number;
    }

}