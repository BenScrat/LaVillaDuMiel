<?php

namespace App\Classe;



use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = 'c3e1728b3e2b6acfb3871cc6d470f0e4';
    private $api_key_secret = 'e1983febb05af83f1ed075e3f6332a56';

    public function send($to_mail, $to_name, $subject, $content): void
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "lavilladumiel@gmail.com",
                        'Name' => "La Villa Du Miel"
                    ],
                    'To' => [
                        [
                            'Email' => $to_mail,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3844643,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

}