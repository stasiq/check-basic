<?php

namespace App;

use App\SendMail as Mail;

class Check
{
    private static array $urls = [];
    private static array $withoutBasic = [];
    private static array $withoutRobots = [];
    private static array $withoutMeta = [];

    // Основной метод, для проверки и реализации результата.
    public function check(): void
    {
        self::$urls = file('urls.txt', FILE_IGNORE_NEW_LINES);
        self::basic(self::$urls);

        if (count(self::$withoutBasic) > 0) {
            self::robots(self::$withoutBasic);
        }

        if (count(self::$withoutRobots) > 0) {
            self::meta(self::$withoutRobots);
        }

        Mail::mail(self::$withoutBasic, self::$withoutRobots, self::$withoutMeta);
    }

    // Метод, проверяющий присутствие basic авторизации
    private static function basic($urls): void
    {
        foreach ($urls as $url) {
            $headers = get_headers($url, 1);

            if (!isset($headers['WWW-Authenticate'])) {
                self::$withoutBasic[] = $url;
            }
        }
    }

    // Метод, проверяющий robots.txt файл на закрытие индексации
    private static function robots($urls): void
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

    // Метод, проверяющий на наличие meta тега, закрывающего индексацию
    private static function meta($urls): void
    {
        foreach ($urls as $url) {
            $content = file_get_contents($url);
            if (!stripos($content, '<meta name="robots" content="noindex, nofollow" />')) {
                self::$withoutMeta[] = $url;
            }
        }
    }
}
