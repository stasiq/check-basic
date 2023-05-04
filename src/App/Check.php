<?php

namespace App;

class Check
{
    const URLS = [
        'http://arsenaltula.intensa-dev.ru/',
        'https://www.comagic.ru',
        'https://www.arsenaltula.ru',
        'http://comagic2.intensa-dev.ru',
    ];
    const FILES = [
        'basic' => 'without-basic.txt',
        'robots' => 'without-robots.txt',
        'meta' => 'without-meta.txt',

    ];
    static private $withoutBasic = [];
    static private $withoutRobots = [];
    static private $withoutMeta = [];

    function Check()
    {
        self::Basic(self::URLS);

        if (count(self::$withoutBasic) > 0) {
            file_put_contents(self::FILES['basic'], '');
            $current = file_get_contents(self::FILES['basic']);

            foreach (self::$withoutBasic as $url) {
                $current .= $url . "\n";
                file_put_contents(self::FILES['basic'], $current);
            }

            self::Robots(self::$withoutBasic);
        }

        if (count(self::$withoutRobots) > 0) {
            file_put_contents(self::FILES['robots'], '');

            foreach (self::$withoutBasic as $url) {
                $current = file_get_contents(self::FILES['robots']);
                $current .= $url . "\n";
                file_put_contents(self::FILES['robots'], $current);
            }

            self::Meta(self::$withoutBasic);
        }

        if (count(self::$withoutMeta) > 0) {
            file_put_contents(self::FILES['meta'], '');

            foreach (self::$withoutBasic as $url) {
                $current = file_get_contents(self::FILES['meta']);
                $current .= $url . "\n";
                file_put_contents(self::FILES['meta'], $current);
            }
        }
    }

    static function Basic($urls)
    {
        foreach ($urls as $url) {
            $headers = get_headers($url, 1);

            if (!isset($headers['WWW-Authenticate'])) {
                self::$withoutBasic[] = $url;
            }

        }
    }

    private function Robots($urls)
    {
        foreach ($urls as $url) {
            $robots_url = $url . '/robots.txt';
            $accepted = 0;
            $result = explode("\n", file_get_contents($robots_url));

            foreach ($result as $line) {
                if (trim($line) == 'User-agent: *' || trim($line) == 'Disallow: /') {
                    $accepted++;
                }
            }

            if ($accepted < 2) {
                self::$withoutRobots[] = $url;
            }
        }
    }

    private function Meta($urls)
    {
        foreach ($urls as $url) {
            $content = file_get_contents($url);
            if (!stripos($content, '<meta name="robots" content="noindex, nofollow" />')) {
                self::$withoutMeta[] = $url;
            }
        }
    }
}