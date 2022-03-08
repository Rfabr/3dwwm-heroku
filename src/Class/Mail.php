<?php

declare(strict_types=1);

namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = '79f069ee65cc72d4c6e8798278300f93';

    private $api_key_secret = 'b25b581015fb66fda5c55eb63aa42fc6';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "fabrice.reaume@gmail.com",
                        'Name' => "3DWWM"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3429509,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
