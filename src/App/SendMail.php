<?php

namespace App;

class SendMail
{
    private static string $subject = 'Проверка закрытости тестовых доменов';

    //Метод, отправляющий сообщение на почту с выявленными проблемами.
    public static function mail(array $basic, array $robots, array $meta): void
    {

        $to = getenv('EMAIL');
        $message = '';
        if (count($basic)) {
            $message .= "Домены без basic авторизации:<br><br> \r\n";
            foreach ($basic as $url) {
                $message .= $url . "<br>\n";
            }
        }

        if (count($robots)) {
            $message .= "<br><br>Домены без закрытия индексации в robots.txt:<br><br> \r\n";
            foreach ($robots as $url) {
                $message .= $url . "<br> \n";
            }
        }

        if (count($meta)) {
            $message .= "<br><br>Домены без закрытия индексации в meta:<br><br> \r\n";
            foreach ($meta as $url) {
                $message .= $url . "<br>\n";
            }
        }

        $headers = 'From: test@intensa.ru' . "\r\n" .
            'Reply-To: test@intensa.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        if (strlen($message) > 1) {
            mail($to, self::$subject, $message, $headers);
        }
    }
}
